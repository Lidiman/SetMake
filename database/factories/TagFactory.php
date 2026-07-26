<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TagFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Rock', 'Pop', 'Blues', 'Jazz', 'Metal', 'Folk',
                'Country', 'Punk', 'Grunge', 'Alternative', 'Acoustic',
                'Classic Rock', 'Indie', 'Soul', 'Funk', 'Reggae',
                'Ballad', 'Power Ballad', 'Instrumental', 'Covers',
                'Originals', 'Opener', 'Closer', 'Encore', 'Crowd Favorite',
            ]),
        ];
    }
}
