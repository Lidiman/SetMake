<?php

namespace App\Livewire\GigMode;

use App\Models\Schedule;
use App\Models\Performance;
use Livewire\Component;

class GigMode extends Component
{
    public Schedule $gig;
    public array $songs = [];
    public int $currentIndex = 0;
    public string $startedAt = '';
    public array $completedSongs = [];

    public function mount(Schedule $gig)
    {
        $this->gig = $gig->load(['setlist.songs.links', 'setlist.songs.attachments']);
        $this->songs = $this->gig->setlist?->songs->toArray() ?? [];
        $this->startedAt = now()->toIso8601String();
    }

    public function getCurrentSongProperty(): ?array
    {
        return $this->songs[$this->currentIndex] ?? null;
    }

    public function getNextSongProperty(): ?array
    {
        return $this->songs[$this->currentIndex + 1] ?? null;
    }

    public function getPreviousSongProperty(): ?array
    {
        return $this->songs[$this->currentIndex - 1] ?? null;
    }

    public function getProgressProperty(): float
    {
        if (empty($this->songs)) {
            return 0;
        }
        return (($this->currentIndex + 1) / count($this->songs)) * 100;
    }

    public function getElapsedTimeProperty(): string
    {
        $start = \Carbon\Carbon::parse($this->startedAt);
        return $start->diffForHumans(now(), true);
    }

    public function nextSong()
    {
        if ($this->currentIndex < count($this->songs) - 1) {
            $this->currentIndex++;
        }
    }

    public function previousSong()
    {
        if ($this->currentIndex > 0) {
            $this->currentIndex--;
        }
    }

    public function completeSong()
    {
        $currentSong = $this->currentSong;
        if (!$currentSong) return;

        Performance::create([
            'song_id' => $currentSong['id'],
            'setlist_id' => $this->gig->setlist_id,
            'schedule_id' => $this->gig->id,
            'performed_at' => now(),
            'venue' => $this->gig->venue ?? $this->gig->location,
            'status' => 'completed',
        ]);

        $this->completedSongs[] = $currentSong['id'];

        if ($this->currentIndex < count($this->songs) - 1) {
            $this->currentIndex++;
        }
    }

    public function skipSong()
    {
        $currentSong = $this->currentSong;
        if (!$currentSong) return;

        Performance::create([
            'song_id' => $currentSong['id'],
            'setlist_id' => $this->gig->setlist_id,
            'schedule_id' => $this->gig->id,
            'performed_at' => now(),
            'venue' => $this->gig->venue ?? $this->gig->location,
            'status' => 'skipped',
        ]);

        if ($this->currentIndex < count($this->songs) - 1) {
            $this->currentIndex++;
        }
    }

    public function markEncore()
    {
        $currentSong = $this->currentSong;
        if (!$currentSong) return;

        Performance::create([
            'song_id' => $currentSong['id'],
            'setlist_id' => $this->gig->setlist_id,
            'schedule_id' => $this->gig->id,
            'performed_at' => now(),
            'venue' => $this->gig->venue ?? $this->gig->location,
            'status' => 'encore',
        ]);

        $this->completedSongs[] = $currentSong['id'];

        if ($this->currentIndex < count($this->songs) - 1) {
            $this->currentIndex++;
        }
    }

    public function exitGigMode()
    {
        return redirect()->route('schedules.show', $this->gig);
    }

    public function render()
    {
        return view('livewire.gig-mode.gig-mode')
            ->layout('components.layouts.gig-mode');
    }
}
