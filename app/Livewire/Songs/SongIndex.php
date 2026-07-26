<?php

namespace App\Livewire\Songs;

use App\Models\Song;
use Livewire\Component;
use Livewire\WithPagination;

class SongIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filter = 'all'; // all, favorites
    public string $sort = 'title'; // title, newest, oldest
    public array $selectedTags = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'filter' => ['except' => 'all'],
        'sort' => ['except' => 'title'],
        'selectedTags' => ['except' => []],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function toggleFavorite(Song $song)
    {
        $song->update(['is_favorite' => !$song->is_favorite]);
    }

    public function delete(Song $song)
    {
        $this->authorize('delete', $song);
        $song->delete();
        $this->dispatch('toast', message: 'Song deleted successfully', type: 'success');
    }

    public function render()
    {
        $query = Song::query()
            ->with(['tags', 'creator'])
            ->withCount('performances')
            ->search($this->search);

        if ($this->filter === 'favorites') {
            $query->favorites();
        }

        if (!empty($this->selectedTags)) {
            $query->whereHas('tags', function ($q) {
                $q->whereIn('tags.id', $this->selectedTags);
            });
        }

        match ($this->sort) {
            'newest' => $query->orderByDesc('created_at'),
            'oldest' => $query->orderBy('created_at'),
            default => $query->orderBy('title'),
        };

        $songs = $query->paginate(12);
        
        $allTags = \App\Models\Tag::orderBy('name')->get();

        return view('livewire.songs.song-index', [
            'songs' => $songs,
            'allTags' => $allTags,
        ])->layout('components.layouts.app')->title('Songs Library');
    }
}
