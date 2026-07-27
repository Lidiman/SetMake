<?php

namespace App\Livewire\Gigs;

use App\Enums\GigStatus;
use App\Models\Gig;
use Livewire\Component;
use Livewire\WithPagination;

class GigIndex extends Component
{
    use WithPagination;

    public string $filter = 'upcoming';
    public ?string $statusFilter = null;

    protected $queryString = [
        'filter' => ['except' => 'upcoming'],
        'statusFilter' => ['except' => ''],
    ];

    public function updatingFilter()
    {
        $this->resetPage();
    }

    public function delete(Gig $gig)
    {
        $gig->delete();
        $this->dispatch('toast', message: 'Gig deleted successfully', type: 'success');
    }

    public function render()
    {
        $query = Gig::with(['creator', 'setlist', 'members']);

        match ($this->filter) {
            'past' => $query->past(),
            'completed' => $query->completed(),
            default => $query->upcoming(),
        };

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $gigs = $query->paginate(12);

        return view('livewire.gigs.gig-index', [
            'gigs' => $gigs,
            'statuses' => GigStatus::cases(),
        ])->layout('components.layouts.app')->title('Gigs');
    }
}
