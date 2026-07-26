<?php

namespace Database\Factories;

use App\Enums\Difficulty;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SongFactory extends Factory
{
    private array $songs = [
        ['title' => 'Hotel California', 'artist' => 'Eagles', 'genre' => 'Rock', 'key' => 'Bm', 'bpm' => 74, 'duration' => 391, 'tuning' => 'Standard'],
        ['title' => 'Sweet Child O\' Mine', 'artist' => 'Guns N\' Roses', 'genre' => 'Rock', 'key' => 'D', 'bpm' => 122, 'duration' => 356, 'tuning' => 'Eb Standard'],
        ['title' => 'Wonderwall', 'artist' => 'Oasis', 'genre' => 'Britpop', 'key' => 'Em', 'bpm' => 87, 'duration' => 258, 'tuning' => 'Standard', 'capo' => 2],
        ['title' => 'Smells Like Teen Spirit', 'artist' => 'Nirvana', 'genre' => 'Grunge', 'key' => 'Fm', 'bpm' => 117, 'duration' => 301, 'tuning' => 'Standard'],
        ['title' => 'Bohemian Rhapsody', 'artist' => 'Queen', 'genre' => 'Rock', 'key' => 'Bb', 'bpm' => 72, 'duration' => 355, 'tuning' => 'Standard'],
        ['title' => 'Stairway to Heaven', 'artist' => 'Led Zeppelin', 'genre' => 'Rock', 'key' => 'Am', 'bpm' => 63, 'duration' => 482, 'tuning' => 'Standard'],
        ['title' => 'Come As You Are', 'artist' => 'Nirvana', 'genre' => 'Grunge', 'key' => 'Em', 'bpm' => 120, 'duration' => 219, 'tuning' => 'Drop D'],
        ['title' => 'Nothing Else Matters', 'artist' => 'Metallica', 'genre' => 'Metal', 'key' => 'Em', 'bpm' => 69, 'duration' => 388, 'tuning' => 'Standard'],
        ['title' => 'Hallelujah', 'artist' => 'Leonard Cohen', 'genre' => 'Folk', 'key' => 'C', 'bpm' => 56, 'duration' => 282, 'tuning' => 'Standard', 'capo' => 5],
        ['title' => 'Let It Be', 'artist' => 'The Beatles', 'genre' => 'Rock', 'key' => 'C', 'bpm' => 68, 'duration' => 243, 'tuning' => 'Standard'],
        ['title' => 'Wish You Were Here', 'artist' => 'Pink Floyd', 'genre' => 'Progressive Rock', 'key' => 'G', 'bpm' => 60, 'duration' => 334, 'tuning' => 'Standard'],
        ['title' => 'Blackbird', 'artist' => 'The Beatles', 'genre' => 'Folk Rock', 'key' => 'G', 'bpm' => 94, 'duration' => 138, 'tuning' => 'Standard'],
        ['title' => 'Creep', 'artist' => 'Radiohead', 'genre' => 'Alternative', 'key' => 'G', 'bpm' => 92, 'duration' => 236, 'tuning' => 'Standard'],
        ['title' => 'Under the Bridge', 'artist' => 'Red Hot Chili Peppers', 'genre' => 'Alternative Rock', 'key' => 'E', 'bpm' => 68, 'duration' => 264, 'tuning' => 'Standard'],
        ['title' => 'Every Breath You Take', 'artist' => 'The Police', 'genre' => 'Pop Rock', 'key' => 'Ab', 'bpm' => 117, 'duration' => 253, 'tuning' => 'Standard'],
        ['title' => 'Tears in Heaven', 'artist' => 'Eric Clapton', 'genre' => 'Acoustic', 'key' => 'A', 'bpm' => 80, 'duration' => 272, 'tuning' => 'Standard'],
        ['title' => 'Imagine', 'artist' => 'John Lennon', 'genre' => 'Pop', 'key' => 'C', 'bpm' => 75, 'duration' => 187, 'tuning' => 'Standard'],
        ['title' => 'Hey Jude', 'artist' => 'The Beatles', 'genre' => 'Rock', 'key' => 'F', 'bpm' => 74, 'duration' => 431, 'tuning' => 'Standard'],
        ['title' => 'Californication', 'artist' => 'Red Hot Chili Peppers', 'genre' => 'Alternative Rock', 'key' => 'Am', 'bpm' => 96, 'duration' => 330, 'tuning' => 'Standard'],
        ['title' => 'Zombie', 'artist' => 'The Cranberries', 'genre' => 'Alternative Rock', 'key' => 'Em', 'bpm' => 82, 'duration' => 306, 'tuning' => 'Drop D'],
        ['title' => 'Don\'t Stop Believin\'', 'artist' => 'Journey', 'genre' => 'Rock', 'key' => 'E', 'bpm' => 119, 'duration' => 251, 'tuning' => 'Standard'],
        ['title' => 'Sultans of Swing', 'artist' => 'Dire Straits', 'genre' => 'Rock', 'key' => 'Dm', 'bpm' => 149, 'duration' => 344, 'tuning' => 'Standard'],
        ['title' => 'Knockin\' on Heaven\'s Door', 'artist' => 'Bob Dylan', 'genre' => 'Folk Rock', 'key' => 'G', 'bpm' => 69, 'duration' => 151, 'tuning' => 'Standard'],
        ['title' => 'Losing My Religion', 'artist' => 'R.E.M.', 'genre' => 'Alternative', 'key' => 'Am', 'bpm' => 126, 'duration' => 270, 'tuning' => 'Standard'],
        ['title' => 'While My Guitar Gently Weeps', 'artist' => 'The Beatles', 'genre' => 'Rock', 'key' => 'Am', 'bpm' => 115, 'duration' => 285, 'tuning' => 'Standard'],
        ['title' => 'Back in Black', 'artist' => 'AC/DC', 'genre' => 'Hard Rock', 'key' => 'E', 'bpm' => 92, 'duration' => 255, 'tuning' => 'Standard'],
        ['title' => 'Free Fallin\'', 'artist' => 'Tom Petty', 'genre' => 'Heartland Rock', 'key' => 'E', 'bpm' => 85, 'duration' => 258, 'tuning' => 'Standard', 'capo' => 1],
        ['title' => 'Dust in the Wind', 'artist' => 'Kansas', 'genre' => 'Progressive Rock', 'key' => 'C', 'bpm' => 94, 'duration' => 206, 'tuning' => 'Standard'],
        ['title' => 'Yellow', 'artist' => 'Coldplay', 'genre' => 'Alternative Rock', 'key' => 'B', 'bpm' => 86, 'duration' => 269, 'tuning' => 'Standard'],
        ['title' => 'American Pie', 'artist' => 'Don McLean', 'genre' => 'Folk Rock', 'key' => 'G', 'bpm' => 100, 'duration' => 516, 'tuning' => 'Standard', 'capo' => 2],
    ];

    public function definition(): array
    {
        $song = $this->faker->randomElement($this->songs);

        return [
            'title' => $song['title'],
            'artist' => $song['artist'],
            'genre' => $song['genre'],
            'key' => $song['key'],
            'bpm' => $song['bpm'],
            'duration' => $song['duration'],
            'difficulty' => $this->faker->randomElement(Difficulty::cases()),
            'tuning' => $song['tuning'] ?? 'Standard',
            'capo' => $song['capo'] ?? null,
            'notes' => $this->faker->optional(0.3)->sentence(),
            'is_favorite' => $this->faker->boolean(20),
            'audio_path' => null,
            'created_by' => User::factory(),
        ];
    }

    public function favorite(): static
    {
        return $this->state(fn () => ['is_favorite' => true]);
    }

    /**
     * Create with specific song data to avoid duplicates.
     */
    public function fromList(int $index): static
    {
        $song = $this->songs[$index % count($this->songs)];

        return $this->state(fn () => [
            'title' => $song['title'],
            'artist' => $song['artist'],
            'genre' => $song['genre'],
            'key' => $song['key'],
            'bpm' => $song['bpm'],
            'duration' => $song['duration'],
            'tuning' => $song['tuning'] ?? 'Standard',
            'capo' => $song['capo'] ?? null,
        ]);
    }
}
