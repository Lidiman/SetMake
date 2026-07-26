<?php

namespace App\Livewire\Setlists;

use App\Enums\SetlistType;
use App\Models\Setlist;
use App\Models\Song;
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
    public array $setlistSongs = []; // Array of ['id', 'title', 'artist', 'duration', 'notes']
    
    // Search State
    public string $searchQuery = '';
    
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

    public function addSong($songId)
    {
        // Don't add if already in setlist
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
        
        // Convert empty strings to null
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

        // Sync songs with order and notes
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
        $searchResults = [];
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
