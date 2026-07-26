<?php

namespace Database\Seeders;

use App\Enums\Difficulty;
use App\Enums\LinkType;
use App\Enums\SetlistType;
use App\Enums\UserRole;
use App\Models\Performance;
use App\Models\Setlist;
use App\Models\Song;
use App\Models\SongLink;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin and member users
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@bandset.com',
            'password' => bcrypt('password'),
            'role' => UserRole::Admin,
        ]);

        $member = User::factory()->create([
            'name' => 'Band Member',
            'email' => 'member@bandset.com',
            'password' => bcrypt('password'),
            'role' => UserRole::Member,
        ]);

        // Create tags
        $tags = collect([
            'Rock', 'Pop', 'Blues', 'Jazz', 'Metal', 'Folk',
            'Alternative', 'Acoustic', 'Classic Rock', 'Grunge',
            'Crowd Favorite', 'Opener', 'Closer', 'Encore', 'Ballad',
        ])->map(fn ($name) => Tag::create(['name' => $name]));

        // Create 30 unique songs
        $songs = collect();
        for ($i = 0; $i < 30; $i++) {
            $song = Song::factory()->fromList($i)->create([
                'created_by' => $i % 3 === 0 ? $member->id : $admin->id,
                'difficulty' => fake()->randomElement(Difficulty::cases()),
                'is_favorite' => $i < 8, // First 8 are favorites
            ]);

            // Attach 1-3 random tags
            $song->tags()->attach(
                $tags->random(rand(1, 3))->pluck('id')
            );

            // Add 1-3 links per song
            $linkTypes = fake()->randomElements(LinkType::cases(), rand(1, 3));
            foreach ($linkTypes as $type) {
                SongLink::factory()->create([
                    'song_id' => $song->id,
                    'type' => $type,
                ]);
            }

            $songs->push($song);
        }

        // Create setlists
        $upcomingRehearsal = Setlist::factory()->create([
            'title' => 'Friday Night Rehearsal',
            'type' => SetlistType::Rehearsal,
            'scheduled_at' => now()->addDays(2)->setHour(19),
            'venue' => 'The Garage',
            'created_by' => $admin->id,
        ]);

        $upcomingGig = Setlist::factory()->create([
            'title' => 'Saturday Night at The Blue Note',
            'type' => SetlistType::Performance,
            'scheduled_at' => now()->addDays(5)->setHour(21),
            'venue' => 'The Blue Note',
            'created_by' => $admin->id,
        ]);

        $pastRehearsal = Setlist::factory()->create([
            'title' => 'Last Week Practice',
            'type' => SetlistType::Rehearsal,
            'scheduled_at' => now()->subDays(5)->setHour(19),
            'venue' => 'Studio 54',
            'created_by' => $admin->id,
        ]);

        $pastGig = Setlist::factory()->create([
            'title' => 'Rock Bottom Bar Gig',
            'type' => SetlistType::Performance,
            'scheduled_at' => now()->subWeeks(2)->setHour(20),
            'venue' => 'Rock Bottom Bar',
            'created_by' => $admin->id,
        ]);

        // Additional past setlists for history
        for ($i = 0; $i < 4; $i++) {
            Setlist::factory()->create([
                'scheduled_at' => now()->subWeeks(rand(3, 12)),
                'created_by' => $admin->id,
            ]);
        }

        // Attach songs to setlists
        $this->attachSongsToSetlist($upcomingRehearsal, $songs->random(8));
        $this->attachSongsToSetlist($upcomingGig, $songs->random(12));
        $this->attachSongsToSetlist($pastRehearsal, $songs->random(6));
        $this->attachSongsToSetlist($pastGig, $songs->random(10));

        // Create performance history for past setlists
        foreach ([$pastRehearsal, $pastGig] as $setlist) {
            foreach ($setlist->songs as $song) {
                Performance::create([
                    'song_id' => $song->id,
                    'setlist_id' => $setlist->id,
                    'performed_at' => $setlist->scheduled_at->toDateString(),
                    'venue' => $setlist->venue,
                ]);
            }
        }

        // Additional random performances
        for ($i = 0; $i < 30; $i++) {
            Performance::factory()->create([
                'song_id' => $songs->random()->id,
                'performed_at' => fake()->dateTimeBetween('-6 months', 'now'),
            ]);
        }
    }

    private function attachSongsToSetlist(Setlist $setlist, $songs): void
    {
        $position = 1;
        foreach ($songs as $song) {
            $setlist->songs()->attach($song->id, [
                'position' => $position++,
                'notes' => fake()->optional(0.2)->sentence(),
            ]);
        }
    }
}
