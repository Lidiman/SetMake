<?php

namespace App\Livewire\Songs;

use App\Models\Song;
use Livewire\Component;

class SongShow extends Component
{
    public Song $song;

    public function mount(Song $song)
    {
        $this->song = $song->load(['tags', 'links', 'creator', 'performances.setlist' => function ($q) {
            $q->orderByDesc('scheduled_at');
        }]);
    }

    public function delete()
    {
        $this->authorize('delete', $this->song);
        $this->song->delete();
        $this->redirect(route('songs.index'), navigate: true);
    }
    
    public function toggleFavorite()
    {
        $this->song->update(['is_favorite' => !$this->song->is_favorite]);
    }

    public function render()
    {
        return view('livewire.songs.song-show')
            ->layout('components.layouts.app')
            ->title($this->song->title);
    }
}
