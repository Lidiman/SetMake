<?php

namespace Database\Factories;

use App\Enums\LinkType;
use App\Models\Song;
use Illuminate\Database\Eloquent\Factories\Factory;

class SongLinkFactory extends Factory
{
    public function definition(): array
    {
        $type = $this->faker->randomElement(LinkType::cases());

        return [
            'song_id' => Song::factory(),
            'type' => $type,
            'url' => $this->generateUrl($type),
            'label' => null,
        ];
    }

    private function generateUrl(LinkType $type): string
    {
        $id = $this->faker->regexify('[a-zA-Z0-9]{11}');

        return match ($type) {
            LinkType::Spotify => "https://open.spotify.com/track/{$id}",
            LinkType::YouTube => "https://www.youtube.com/watch?v={$id}",
            LinkType::UltimateGuitar => "https://tabs.ultimate-guitar.com/tab/{$id}",
            LinkType::Chordify => "https://chordify.net/chords/{$id}",
            LinkType::Songsterr => "https://www.songsterr.com/a/wsa/{$id}",
            LinkType::Other => $this->faker->url(),
        };
    }
}
