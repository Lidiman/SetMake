<?php

namespace App\Livewire\Gigs;

use App\Models\Gig;
use App\Models\SongRequest;
use Livewire\Component;

class GigShow extends Component
{
    public Gig $gig;
    public string $requestSong = '';
    public string $requestedBy = '';
    public int $requestQuantity = 1;

    public function mount(Gig $gig)
    {
        $this->gig = $gig->load(['setlist.songs', 'creator', 'members', 'requests', 'expenses']);
    }

    public function addRequest()
    {
        $this->validate([
            'requestSong' => 'required|string|max:255',
            'requestedBy' => 'nullable|string|max:255',
            'requestQuantity' => 'integer|min:1|max:100',
        ]);

        $this->gig->requests()->create([
            'song_name' => $this->requestSong,
            'requested_by' => $this->requestedBy ?: null,
            'quantity' => $this->requestQuantity,
        ]);

        $this->requestSong = '';
        $this->requestedBy = '';
        $this->requestQuantity = 1;
        $this->gig->refresh();
    }

    public function togglePerformed($requestId)
    {
        $request = $this->gig->requests()->find($requestId);
        if ($request) {
            $request->update(['is_performed' => !$request->is_performed]);
            $this->gig->refresh();
        }
    }

    public function deleteRequest($requestId)
    {
        $this->gig->requests()->where('id', $requestId)->delete();
        $this->gig->refresh();
    }

    public function updateStatus($status)
    {
        $this->gig->update(['status' => $status]);
        $this->gig->refresh();
        $this->dispatch('toast', message: 'Gig status updated', type: 'success');
    }

    public function delete()
    {
        $this->gig->delete();
        $this->redirect(route('gigs.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.gigs.gig-show')
            ->layout('components.layouts.app')
            ->title($this->gig->title);
    }
}
