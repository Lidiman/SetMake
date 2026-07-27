<?php

namespace Database\Factories;

use App\Models\Song;
use Illuminate\Database\Eloquent\Factories\Factory;

class SongChecklistFactory extends Factory
{
    public function definition(): array
    {
        return [
            'song_id' => Song::factory(),
            'task' => fake()->randomElement(['Intro', 'Solo', 'Verse', 'Chorus', 'Bridge', 'Outro', 'Vocal Harmony', 'Drum Fill', 'Bass Line', 'Guitar Riff']),
            'is_completed' => fake()->boolean(50),
        ];
    }
}
