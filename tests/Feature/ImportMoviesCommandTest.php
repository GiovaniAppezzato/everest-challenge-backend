<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\MovieService;

class ImportMoviesCommandTest extends TestCase
{
    public function test_it_imports_movies_successfully()
    {
        $mock = \Mockery::mock(MovieService::class);
        $mock->shouldReceive('importFromExternalApi')
            ->once()
            ->with('Batman');

        $this->app->instance(MovieService::class, $mock);

        $this->artisan('app:import-movies')
            ->expectsQuestion('What search term would you like to use to import the movies?', 'Batman')
            ->expectsOutput('Importing movies with the term: Batman')
            ->expectsOutput('Movies imported successfully.')
            ->assertExitCode(0);
    }

    public function test_it_shows_error_if_term_is_empty()
    {
        $this->artisan('app:import-movies')
            ->expectsQuestion('What search term would you like to use to import the movies?', '')
            ->expectsOutput('Search term cannot be empty.')
            ->assertExitCode(0);
    }
}
