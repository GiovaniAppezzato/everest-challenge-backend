<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MovieService;

class ImportMovies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-movies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import movies from a external API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $term = $this->ask('What search term would you like to use to import the movies?');

        if (empty($term)) {
            $this->error('Search term cannot be empty.');
            return;
        }

        $this->info("Importing movies with the term: {$term}");

        app(MovieService::class)->importFromExternalApi($term);

        $this->info('Movies imported successfully.');
    }
}
