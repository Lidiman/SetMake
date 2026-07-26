<?php

namespace App\Livewire\Setlists;

use App\Models\Setlist;
use Livewire\Component;
use Livewire\WithPagination;

class SetlistIndex extends Component
{
    use WithPagination;

    public string $filter = 'upcoming'; // all, upcoming, past, rehearsal, performance

    protected $queryString = [
        'filter' => ['except' => 'upcoming'],
    ];

    public function updatingFilter()
    {
        $this->resetPage();
    }

    public function delete(Setlist $setlist)
    {
        $this->authorize('delete', $setlist);
        $setlist->delete();
        $this->dispatch('toast', message: 'Setlist deleted successfully', type: 'success');
    }

    public function render()
    {
        $query = Setlist::query()
            ->with(['creator'])
            ->withCount('songs')
            ->withSum('songs', 'duration');

        match ($this->filter) {
            'upcoming' => $query->upcoming(),
            'past' => $query->past(),
            'rehearsal' => $query->rehearsals()->orderByDesc('scheduled_at'),
            'performance' => $query->performances()->orderByDesc('scheduled_at'),
            default => $query->orderByDesc('scheduled_at'),
        };

        $setlists = $query->paginate(12);

        return view('livewire.setlists.setlist-index', [
            'setlists' => $setlists,
        ])->layout('components.layouts.app')->title('Setlists');
    }
}
