<?php

namespace Database\Factories;

use App\Models\Song;
use Illuminate\Database\Eloquent\Factories\Factory;

class PerformanceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'song_id' => Song::factory(),
            'setlist_id' => null,
            'gig_id' => null,
            'performed_at' => fake()->dateTimeBetween('-6 months', 'now'),
            'venue' => fake()->optional(0.7)->randomElement([
                'The Blue Note', 'Rock Bottom Bar', 'Harmony Hall',
                'The Garage', 'Studio 54', 'Moonlight Lounge',
            ]),
            'status' => fake()->randomElement(['completed', 'skipped', 'encore']),
            'notes' => fake()->optional(0.3)->sentence(),
        ];
    }
}
