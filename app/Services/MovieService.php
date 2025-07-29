<?php

namespace App\Services;

use Carbon\Carbon;
use App\Repositories\MovieRepository;
use App\Models\Director;
use App\Models\Movie;

class MovieService
{
    public function __construct(
        protected MovieRepository $movieRepository,
        protected OmdbService $omdbService,
    ) {}

    public function getMovies(array $params)
    {
        return $this->movieRepository->getMovies($params);
    }

    public function importFromExternalApi(string $term): void
    {
        $response = $this->omdbService->searchMovies($term);

        if($response) {
            $movies = data_get($response, 'Search', []);

            foreach ($movies as $movie) {
                $imdbId = data_get($movie, 'imdbID', null);

                if ($imdbId) {
                    $this->createMovie($imdbId);
                }
            }
        }
    }

    private function createMovie(string $imdbId): void
    {
        if (!$this->checkIfMovieExists($imdbId)) {
            $data = $this->omdbService->getMovieDetails($imdbId);

            if ($data) {
                $movie = Movie::create(
                    [
                        'imdb_id'  => $imdbId,
                        'title'    => data_get($data, 'Title', null),
                        'year'     => data_get($data, 'Year', null),
                        'poster'   => data_get($data, 'Poster', null),
                        'plot'     => data_get($data, 'Plot', null),
                        'runtime'  => data_get($data, 'Runtime', null),
                        'type'     => data_get($data, 'Type', 'movie'),
                        'released' => $this->resolveReleaseDate(data_get($data, 'Released', null)),
                    ]
                );

                $directors = data_get($data, 'Director', '');
                
                $this->attachDirectors($movie, explode(', ', $directors));
            }
        }
    }

    private function attachDirectors(Movie $movie, array $directors): void
    {
        foreach ($directors as $directorName) {
            $director = Director::query()->firstOrCreate(['name' => $directorName]);

            $movie->directors()->syncWithoutDetaching($director->id);
        }
    }
    
    private function checkIfMovieExists(string $imdbId): bool
    {
        return Movie::query()->where('imdb_id', $imdbId)->exists();
    }

    private function resolveReleaseDate(?string $date): ?string
    {
        if (!$date || empty($date) || $date === 'N/A') {
            return null;
        }

        $carbonDate = Carbon::parse($date);

        return $carbonDate->format('Y-m-d');
    }
}
