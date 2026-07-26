<?php

namespace Database\Factories;

use App\Models\Song;
use App\Models\Setlist;
use Illuminate\Database\Eloquent\Factories\Factory;

class PerformanceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'song_id' => Song::factory(),
            'setlist_id' => null,
            'performed_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'venue' => $this->faker->optional(0.7)->randomElement([
                'The Blue Note', 'Rock Bottom Bar', 'Harmony Hall',
                'The Garage', 'Studio 54', 'Moonlight Lounge',
            ]),
            'notes' => $this->faker->optional(0.3)->sentence(),
        ];
    }
}
