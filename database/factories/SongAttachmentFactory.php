<?php

namespace Database\Factories;

use App\Models\Song;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SongAttachmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'song_id' => Song::factory(),
            'name' => fake()->words(2, true),
            'type' => fake()->randomElement(['pdf', 'image', 'mp3', 'lyrics', 'chord_sheet', 'backing_track']),
            'file_path' => 'song-attachments/' . fake()->uuid() . '.' . fake()->fileExtension(),
            'uploaded_by' => User::factory(),
        ];
    }
}
