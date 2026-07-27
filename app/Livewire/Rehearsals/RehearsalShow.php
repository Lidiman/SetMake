<?php

namespace App\Livewire\Rehearsals;

use App\Models\Rehearsal;
use App\Models\RehearsalChecklist;
use Livewire\Component;

class RehearsalShow extends Component
{
    public Rehearsal $rehearsal;
    public string $newTask = '';

    public function mount(Rehearsal $rehearsal)
    {
        $this->rehearsal = $rehearsal->load(['setlist.songs', 'creator', 'members', 'checklists']);
    }

    public function toggleMemberStatus($userId)
    {
        $member = $this->rehearsal->members->find($userId);
        if (!$member) {
            $this->rehearsal->members()->attach($userId, ['status' => 'available']);
        } else {
            $newStatus = match ($member->pivot->status) {
                'available' => 'busy',
                'busy' => 'maybe',
                default => 'available',
            };
            $this->rehearsal->members()->updateExistingPivot($userId, ['status' => $newStatus]);
        }
        $this->rehearsal->refresh();
    }

    public function addTask()
    {
        $this->validate(['newTask' => 'required|string|max:255']);
        $this->rehearsal->checklists()->create(['task' => $this->newTask]);
        $this->newTask = '';
        $this->rehearsal->refresh();
    }

    public function toggleTask($taskId)
    {
        $task = $this->rehearsal->checklists()->find($taskId);
        if ($task) {
            $task->update(['is_completed' => !$task->is_completed]);
            $this->rehearsal->refresh();
        }
    }

    public function deleteTask($taskId)
    {
        $this->rehearsal->checklists()->where('id', $taskId)->delete();
        $this->rehearsal->refresh();
    }

    public function delete()
    {
        $this->rehearsal->delete();
        $this->redirect(route('rehearsals.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.rehearsals.rehearsal-show')
            ->layout('components.layouts.app')
            ->title($this->rehearsal->title);
    }
}
