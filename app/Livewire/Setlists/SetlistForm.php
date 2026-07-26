<?php

namespace App\Livewire\Setlists;

use App\Enums\LinkType;
use App\Enums\SetlistType;
use App\Models\Setlist;
use App\Models\Song;
use App\Services\YouTubeMusicService;
use Livewire\Component;

class SetlistForm extends Component
{
    public ?Setlist $setlist = null;
    public bool $isEditing = false;

    // Form fields
    public string $title = '';
    public string $description = '';
    public string $type = '';
    public ?string $scheduled_at = null;
    public string $venue = '';
    
    // Setlist Songs State
    public array $setlistSongs = [];
    
    // Search State
    public string $searchQuery = '';
    public bool $ytmSearching = false;
    public array $ytmResults = [];
    public ?string $ytmError = null;

    public function updatedSearchQuery()
    {
        $this->ytmResults = [];
        $this->ytmError = null;

        if (strlen($this->searchQuery) >= 2) {
            $this->searchYtm();
        }
    }
    
    public array $types = [];

    public function mount(?Setlist $setlist = null)
    {
        $this->types = SetlistType::cases();
        
        if ($setlist && $setlist->exists) {
            $this->authorize('update', $setlist);
            $this->setlist = $setlist;
            $this->isEditing = true;
            $this->fillForm();
        } else {
            $this->type = SetlistType::Rehearsal->value;
        }
    }

    private function fillForm()
    {
        $this->title = $this->setlist->title;
        $this->description = $this->setlist->description ?? '';
        $this->type = $this->setlist->type->value;
        $this->scheduled_at = $this->setlist->scheduled_at ? $this->setlist->scheduled_at->format('Y-m-d\TH:i') : null;
        $this->venue = $this->setlist->venue ?? '';
        
        $this->setlist->load(['songs' => function ($q) {
            $q->orderBy('setlist_song.position');
        }]);
        
        foreach ($this->setlist->songs as $song) {
            $this->setlistSongs[] = [
                'id' => $song->id,
                'title' => $song->title,
                'artist' => $song->artist,
                'duration' => $song->formatted_duration,
                'duration_seconds' => $song->duration ?? 0,
                'notes' => $song->pivot->notes ?? '',
            ];
        }
    }

    public function searchYtm()
    {
        if (strlen($this->searchQuery) < 2) return;

        $this->ytmSearching = true;
        $this->ytmError = null;
        $this->ytmResults = [];

        try {
            $service = app(YouTubeMusicService::class);
            $result = $service->search($this->searchQuery);

            if (isset($result['error'])) {
                $this->ytmError = $result['error'];
            } else {
                $this->ytmResults = array_slice($result['results'] ?? [], 0, 5);
            }
        } catch (\Exception $e) {
            $this->ytmError = $e->getMessage();
        }

        $this->ytmSearching = false;
    }

    public function addSong($songId)
    {
        if (collect($this->setlistSongs)->contains('id', $songId)) {
            $this->dispatch('toast', message: 'Song is already in the setlist.', type: 'error');
            return;
        }
        
        $song = Song::find($songId);
        if (!$song) return;
        
        $this->setlistSongs[] = [
            'id' => $song->id,
            'title' => $song->title,
            'artist' => $song->artist,
            'duration' => $song->formatted_duration,
            'duration_seconds' => $song->duration ?? 0,
            'notes' => '',
        ];
        
        $this->searchQuery = '';
        $this->ytmResults = [];
    }

    public function addSongFromYtm(int $index)
    {
        $data = $this->ytmResults[$index] ?? null;
        if (!$data) return;

        $title = $data['title'];
        $artist = implode(', ', $data['artists']);
        $duration = $data['duration_seconds'];
        $videoId = $data['videoId'];

        // Check if song already exists in library
        $existing = Song::where('title', $title)
            ->where('artist', $artist)
            ->first();

        if ($existing) {
            $song = $existing;
        } else {
            // Create new song in library
            $song = Song::create([
                'title' => $title,
                'artist' => $artist ?: null,
                'duration' => $duration ?: null,
                'created_by' => auth()->id(),
            ]);

            // Add YouTube Music link
            if ($videoId) {
                $song->links()->create([
                    'type' => LinkType::YouTube,
                    'url' => "https://music.youtube.com/watch?v={$videoId}",
                    'label' => 'YouTube Music',
                ]);
            }
        }

        // Add to setlist
        if (collect($this->setlistSongs)->contains('id', $song->id)) {
            $this->dispatch('toast', message: 'Song is already in the setlist.', type: 'error');
            return;
        }

        $this->setlistSongs[] = [
            'id' => $song->id,
            'title' => $song->title,
            'artist' => $song->artist,
            'duration' => $song->formatted_duration,
            'duration_seconds' => $song->duration ?? 0,
            'notes' => '',
        ];

        $this->searchQuery = '';
        $this->ytmResults = [];
    }

    public function removeSong($index)
    {
        unset($this->setlistSongs[$index]);
        $this->setlistSongs = array_values($this->setlistSongs);
    }

    public function updateSongOrder($list)
    {
        $newOrder = [];
        foreach ($list as $item) {
            $newOrder[] = $this->setlistSongs[(int) $item['value']];
        }
        $this->setlistSongs = $newOrder;
    }

    public function getTotalDurationProperty()
    {
        $totalSeconds = collect($this->setlistSongs)->sum('duration_seconds');
        
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;

        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%d:%02d', $minutes, $seconds);
    }

    protected function rules()
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'type' => ['required', 'string'],
            'scheduled_at' => ['nullable', 'date'],
            'venue' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function save()
    {
        $validatedData = $this->validate();
        
        foreach (['description', 'scheduled_at', 'venue'] as $field) {
            if (empty($validatedData[$field])) {
                $validatedData[$field] = null;
            }
        }

        if ($this->isEditing) {
            $this->setlist->update($validatedData);
        } else {
            $validatedData['created_by'] = auth()->id();
            $this->setlist = Setlist::create($validatedData);
        }

        $syncData = [];
        foreach ($this->setlistSongs as $index => $song) {
            $syncData[$song['id']] = [
                'position' => $index + 1,
                'notes' => $song['notes'] ?? null,
            ];
        }
        
        $this->setlist->songs()->sync($syncData);

        return redirect()->route('setlists.show', $this->setlist);
    }

    public function render()
    {
        $searchResults = collect();
        if (strlen($this->searchQuery) >= 2) {
            $searchResults = Song::search($this->searchQuery)
                ->limit(5)
                ->get();
        }

        return view('livewire.setlists.setlist-form', [
            'searchResults' => $searchResults
        ])
            ->layout('components.layouts.app')
            ->title($this->isEditing ? 'Edit Setlist' : 'Create Setlist');
    }
}
