<?php

namespace App\Livewire\Rehearsals;

use App\Models\Rehearsal;
use Livewire\Component;
use Livewire\WithPagination;

class RehearsalIndex extends Component
{
    use WithPagination;

    public string $filter = 'upcoming';

    protected $queryString = ['filter' => ['except' => 'upcoming']];

    public function updatingFilter()
    {
        $this->resetPage();
    }

    public function delete(Rehearsal $rehearsal)
    {
        $rehearsal->delete();
        $this->dispatch('toast', message: 'Rehearsal deleted successfully', type: 'success');
    }

    public function render()
    {
        $query = Rehearsal::with(['creator', 'setlist', 'members'])->withCount('checklists');

        match ($this->filter) {
            'past' => $query->past(),
            'this_week' => $query->thisWeek(),
            default => $query->upcoming(),
        };

        $rehearsals = $query->paginate(12);

        return view('livewire.rehearsals.rehearsal-index', [
            'rehearsals' => $rehearsals,
        ])->layout('components.layouts.app')->title('Rehearsals');
    }
}
