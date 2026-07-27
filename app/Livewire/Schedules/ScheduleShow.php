<?php

namespace App\Livewire\Schedules;

use App\Models\Schedule;
use Livewire\Component;

class ScheduleShow extends Component
{
    public Schedule $schedule;

    public function mount(Schedule $schedule)
    {
        $this->schedule = $schedule->load(['creator', 'members', 'setlist.songs']);
    }

    public function render()
    {
        return view('livewire.schedules.schedule-show')
            ->layout('components.layouts.app')
            ->title($this->schedule->title);
    }
}
