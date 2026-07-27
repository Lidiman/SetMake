<?php

namespace Database\Factories;

use App\Models\Gig;
use Illuminate\Database\Eloquent\Factories\Factory;

class SongRequestFactory extends Factory
{
    public function definition(): array
    {
        return [
            'song_name' => fake()->words(rand(2, 4), true),
            'requested_by' => fake()->optional(0.5)->name(),
            'quantity' => fake()->numberBetween(1, 5),
            'is_performed' => fake()->boolean(40),
            'gig_id' => Gig::factory(),
        ];
    }
}
