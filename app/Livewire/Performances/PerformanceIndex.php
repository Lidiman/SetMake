<?php

namespace App\Livewire\Performances;

use App\Models\Performance;
use Livewire\Component;
use Livewire\WithPagination;

class PerformanceIndex extends Component
{
    use WithPagination;

    public string $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete(Performance $performance)
    {
        $this->authorize('delete', $performance);
        $performance->delete();
        $this->dispatch('toast', message: 'Performance record deleted.', type: 'success');
    }

    public function render()
    {
        $query = Performance::query()
            ->with(['song', 'setlist'])
            ->orderByDesc('performed_at');
            
        if ($this->search) {
            $query->whereHas('song', function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('artist', 'like', '%' . $this->search . '%');
            })->orWhere('venue', 'like', '%' . $this->search . '%')
              ->orWhereHas('setlist', function($q) {
                  $q->where('title', 'like', '%' . $this->search . '%');
              });
        }

        return view('livewire.performances.performance-index', [
            'performances' => $query->paginate(20),
        ])->layout('components.layouts.app')->title('Performance History');
    }
}
