<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RehearsalFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->randomElement(['Weekly Practice', 'Pre-Gig Rehearsal', 'Song Run-Through', 'Section Practice']),
            'date' => fake()->dateTimeBetween('-1 week', '+2 weeks')->format('Y-m-d'),
            'start_time' => fake()->randomElement(['18:00', '19:00', '20:00']),
            'end_time' => fake()->randomElement(['20:00', '21:00', '22:00']),
            'location' => fake()->randomElement(['The Garage', 'Studio B', 'Band Room', 'Rehearsal Space']),
            'description' => fake()->optional()->sentence(),
            'setlist_id' => null,
            'created_by' => User::factory(),
        ];
    }
}
