<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'imdb_id' => $this->faker->unique()->uuid,
            'title' => $this->faker->sentence(3),
            'year' => $this->faker->year,
            'poster' => $this->faker->imageUrl(),
            'plot' => $this->faker->paragraph(),
            'type' => 'movie',
            'runtime' => $this->faker->numberBetween(80, 180) . ' min',
            'released' => $this->faker->date(),
        ];
    }
}
