<?php

namespace App\Livewire\Setlists;

use App\Models\Setlist;
use Livewire\Component;

class SetlistShow extends Component
{
    public Setlist $setlist;

    public function mount(Setlist $setlist)
    {
        $this->setlist = $setlist->load(['songs.tags', 'creator', 'performances']);
    }

    public function delete()
    {
        $this->authorize('delete', $this->setlist);
        $this->setlist->delete();
        $this->redirect(route('setlists.index'), navigate: true);
    }
    
    public function logPerformance()
    {
        if (!$this->setlist->scheduled_at || $this->setlist->scheduled_at->isFuture()) {
            $this->dispatch('toast', message: 'Cannot log a performance for a future date.', type: 'error');
            return;
        }
        
        $this->authorize('update', $this->setlist);
        
        foreach ($this->setlist->songs as $song) {
            \App\Models\Performance::firstOrCreate([
                'song_id' => $song->id,
                'setlist_id' => $this->setlist->id,
                'performed_at' => $this->setlist->scheduled_at->toDateString(),
                'venue' => $this->setlist->venue,
            ]);
        }
        
        $this->dispatch('toast', message: 'Performance logged for all songs in the setlist!', type: 'success');
        $this->setlist->refresh();
    }

    public function render()
    {
        return view('livewire.setlists.setlist-show')
            ->layout('components.layouts.app')
            ->title($this->setlist->title);
    }
}
