<?php

namespace App\Livewire\Schedules;

use App\Enums\ScheduleStatus;
use App\Enums\ScheduleType;
use App\Models\Schedule;
use Livewire\Component;
use Livewire\WithPagination;

class ScheduleHistory extends Component
{
    use WithPagination;

    public string $typeFilter = '';
    public string $statusFilter = '';
    public string $search = '';

    protected $queryString = [
        'typeFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'search' => ['except' => ''],
    ];

    public function updatingSearch()
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

    public function restore($scheduleId)
    {
        $schedule = Schedule::withTrashed()->find($scheduleId);
        if ($schedule) {
            $schedule->restore();
            $this->dispatch('toast', message: 'Schedule restored successfully', type: 'success');
        }
    }

    public function delete($scheduleId)
    {
        $schedule = Schedule::withTrashed()->find($scheduleId);
        if ($schedule) {
            $schedule->forceDelete();
            $this->dispatch('toast', message: 'Schedule permanently deleted from history', type: 'success');
        }
    }

    public function render()
    {
        $query = Schedule::withTrashed()
            ->with(['creator', 'setlist'])
            ->whereIn('status', ['completed', 'cancelled']);

        if ($this->typeFilter) {
            $query->where('type', $this->typeFilter);
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('venue', 'like', '%' . $this->search . '%')
                    ->orWhere('location', 'like', '%' . $this->search . '%');
            });
        }

        $schedules = $query->orderByDesc('date')->paginate(15);

        return view('livewire.schedules.schedule-history', [
            'schedules' => $schedules,
            'types' => ScheduleType::cases(),
            'statuses' => ScheduleStatus::cases(),
        ])->layout('components.layouts.app')->title('History');
    }
}
