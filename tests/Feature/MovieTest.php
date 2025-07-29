<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Director;
use App\Models\Movie;

class MovieTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * A basic feature test example.
     */
    public function test_it_returns_paginated_movies_response()
    {
        $director = Director::factory()
            ->create(['name' => 'Christopher Nolan']);

        $movie = Movie::factory()->create([
            'title' => 'Inception',
            'year' => 2010,
        ]);

        $movie->directors()->attach($director->id);

        $response = $this->getJson('/api/movies?title=Inception&director=Nolan');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'imdb_id',
                        'title',
                        'year',
                        'poster',
                        'released',
                        'plot',
                        'type',
                        'runtime',
                        'directors'
                    ],
                ],
                'links',
                'meta',
            ]);

        $this->assertEquals('Inception', $response->json('data.0.title'));
        $this->assertContains('Christopher Nolan', $response->json('data.0.directors'));
    }
}
