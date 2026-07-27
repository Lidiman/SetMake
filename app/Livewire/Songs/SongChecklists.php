<?php

namespace App\Livewire\Songs;

use App\Models\Song;
use Livewire\Component;

class SongChecklists extends Component
{
    public Song $song;
    public string $newTask = '';

    public function mount(Song $song)
    {
        $this->song = $song->load('checklists');
    }

    public function addTask()
    {
        $this->validate(['newTask' => 'required|string|max:255']);
        $this->song->checklists()->create(['task' => $this->newTask]);
        $this->newTask = '';
        $this->song->refresh();
    }

    public function toggleTask($taskId)
    {
        $task = $this->song->checklists()->find($taskId);
        if ($task) {
            $task->update(['is_completed' => !$task->is_completed]);
            $this->song->refresh();
        }
    }

    public function deleteTask($taskId)
    {
        $this->song->checklists()->where('id', $taskId)->delete();
        $this->song->refresh();
    }

    public function render()
    {
        return view('livewire.songs.song-checklists', [
            'checklists' => $this->song->checklists,
        ]);
    }
}
