<?php

namespace App\Livewire\Schedules;

use App\Enums\ScheduleStatus;
use App\Enums\ScheduleType;
use App\Models\Schedule;
use Livewire\Component;
use Livewire\WithPagination;

class ScheduleIndex extends Component
{
    use WithPagination;

    public string $filter = 'upcoming'; // upcoming, past
    public ?string $typeFilter = null; // rehearsal, gig
    public ?string $statusFilter = null;

    protected $queryString = [
        'filter' => ['except' => 'upcoming'],
        'typeFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function updatingFilter()
    {
        $this->resetPage();
    }
    
    public function updatingTypeFilter()
    {
        $this->resetPage();
    }
    
    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function delete(Schedule $schedule)
    {
        $schedule->delete();
        $this->dispatch('toast', message: 'Schedule deleted successfully', type: 'success');
    }

    public function render()
    {
        $query = Schedule::with(['creator', 'setlist', 'members']);

        match ($this->filter) {
            'past' => $query->past(),
            default => $query->upcoming(),
        };

        if ($this->typeFilter) {
            $query->where('type', $this->typeFilter);
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $schedules = $query->paginate(12);

        return view('livewire.schedules.schedule-index', [
            'schedules' => $schedules,
            'types' => ScheduleType::cases(),
            'statuses' => ScheduleStatus::cases(),
        ])->layout('components.layouts.app')->title('Schedule');
    }
}
