<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OmdbService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $apiKey = env('OMDB_API_KEY');

        if (!$apiKey) {
            throw new \Exception('OMDB API key is not set in the environment variables.');
        }

        $this->apiKey = $apiKey;
        $this->client = Http::baseUrl(env('OMDB_API_URL'));
    }

    public function searchMovies(string $title, int $page = 1): ?array
    {
        $response = $this->client->get('/', [
            's' => $title,
            'page' => $page,
            'apikey' => $this->apiKey,
        ]);

        if ($response->successful()) {
            return $response->json();
        }
        
        return null;
    }

    public function getMovieDetails(string $imdbId): ?array
    {
        $response = $this->client->get('/', [
            'i' => $imdbId,
            'plot' => 'full',
            'apikey' => $this->apiKey,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }
}
