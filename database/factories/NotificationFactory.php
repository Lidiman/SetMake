<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'type' => fake()->randomElement(['upcoming_rehearsal', 'upcoming_gig', 'song_not_ready', 'gig_tomorrow']),
            'title' => fake()->sentence(),
            'body' => fake()->optional()->sentence(),
            'link' => fake()->optional()->url(),
            'is_read' => fake()->boolean(30),
        ];
    }
}
