<?php

namespace Database\Factories;

use App\Enums\SetlistType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SetlistFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->randomElement([
                'Friday Night Rehearsal', 'Saturday Gig', 'Open Mic Set',
                'Battle of the Bands', 'Acoustic Session', 'Full Band Practice',
                'Bar Gig Set A', 'Bar Gig Set B', 'Wedding Reception',
                'Birthday Party Set', 'Festival Slot', 'Sunday Jam',
            ]),
            'description' => $this->faker->optional(0.5)->sentence(),
            'type' => $this->faker->randomElement(SetlistType::cases()),
            'scheduled_at' => $this->faker->dateTimeBetween('-2 months', '+2 months'),
            'venue' => $this->faker->optional(0.7)->randomElement([
                'The Blue Note', 'Rock Bottom Bar', 'Harmony Hall',
                'The Garage', 'Studio 54', 'Moonlight Lounge',
                'The Jazz Corner', 'Central Park Stage', 'Backyard BBQ',
            ]),
            'created_by' => User::factory(),
        ];
    }

    public function rehearsal(): static
    {
        return $this->state(fn () => ['type' => SetlistType::Rehearsal]);
    }

    public function performance(): static
    {
        return $this->state(fn () => ['type' => SetlistType::Performance]);
    }

    public function upcoming(): static
    {
        return $this->state(fn () => [
            'scheduled_at' => $this->faker->dateTimeBetween('now', '+2 months'),
        ]);
    }
}
