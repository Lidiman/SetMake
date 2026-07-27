<?php

namespace Database\Factories;

use App\Enums\Difficulty;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SongFactory extends Factory
{
    public function definition(): array
    {
        $keys = ['C', 'G', 'D', 'A', 'E', 'B', 'F', 'Am', 'Em', 'Bm', 'F#m', 'C#m', 'Dm', 'Gm'];
        $genres = ['Rock', 'Pop', 'Jazz', 'Blues', 'Metal', 'Funk', 'Soul', 'Country', 'Reggae', 'Electronic'];
        $tunings = ['Standard', 'Drop D', 'Open G', 'DADGAD', 'Half Step Down', 'Drop C'];

        return [
            'title' => fake()->words(rand(2, 5), true),
            'artist' => fake()->name(),
            'genre' => fake()->randomElement($genres),
            'key' => fake()->randomElement($keys),
            'bpm' => fake()->numberBetween(60, 200),
            'duration' => fake()->numberBetween(120, 600),
            'difficulty' => fake()->randomElement(Difficulty::cases()),
            'tuning' => fake()->randomElement($tunings),
            'capo' => fake()->optional(0.3)->numberBetween(1, 7),
            'notes' => fake()->optional()->paragraph(),
            'is_favorite' => fake()->boolean(20),
            'created_by' => User::factory(),
        ];
    }
}
