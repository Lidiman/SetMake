<?php

namespace Database\Factories;

use App\Enums\GigStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class GigFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->randomElement(['Live at The Roxy', 'Summer Festival', 'Club Night', 'Private Event']) . ' ' . fake()->year(),
            'venue' => fake()->company(),
            'address' => fake()->address(),
            'date' => fake()->dateTimeBetween('-1 month', '+3 months')->format('Y-m-d'),
            'start_time' => fake()->randomElement(['20:00', '21:00', '22:00']),
            'end_time' => fake()->randomElement(['23:00', '00:00', '01:00']),
            'contact_person' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'description' => fake()->sentence(),
            'payment' => fake()->randomFloat(2, 100, 5000),
            'tips' => fake()->randomFloat(2, 0, 500),
            'transport' => fake()->randomFloat(2, 0, 200),
            'parking' => fake()->randomFloat(2, 0, 100),
            'food' => fake()->randomFloat(2, 0, 150),
            'equipment_rental' => fake()->randomFloat(2, 0, 300),
            'other_expenses' => fake()->randomFloat(2, 0, 100),
            'status' => fake()->randomElement(GigStatus::cases()),
            'setlist_id' => null,
            'created_by' => User::factory(),
        ];
    }

    public function completed(): static
    {
        return $this->state(fn() => [
            'status' => GigStatus::Completed,
            'date' => fake()->dateTimeBetween('-6 months', '-1 day')->format('Y-m-d'),
        ]);
    }

    public function upcoming(): static
    {
        return $this->state(fn() => [
            'status' => fake()->randomElement([GigStatus::Planned, GigStatus::Confirmed]),
            'date' => fake()->dateTimeBetween('now', '+3 months')->format('Y-m-d'),
        ]);
    }
}
