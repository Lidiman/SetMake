<?php

namespace App\Livewire\Search;

use App\Models\Gig;
use App\Models\Rehearsal;
use App\Models\Setlist;
use App\Models\Song;
use App\Models\User;
use Livewire\Component;

class GlobalSearch extends Component
{
    public string $query = '';
    public bool $show = false;

    protected $listeners = ['toggleSearch' => 'toggle'];

    public function toggle()
    {
        $this->show = !$this->show;
        if ($this->show) {
            $this->query = '';
        }
    }

    public function close()
    {
        $this->show = false;
        $this->query = '';
    }

    public function render()
    {
        $results = [];
        if (strlen($this->query) >= 2) {
            $q = $this->query;

            $songs = Song::where('title', 'like', "%{$q}%")
                ->orWhere('artist', 'like', "%{$q}%")
                ->limit(5)->get();

            $setlists = Setlist::where('title', 'like', "%{$q}%")
                ->limit(5)->get();

            $gigs = Gig::where('title', 'like', "%{$q}%")
                ->orWhere('venue', 'like', "%{$q}%")
                ->limit(5)->get();

            $rehearsals = Rehearsal::where('title', 'like', "%{$q}%")
                ->orWhere('location', 'like', "%{$q}%")
                ->limit(5)->get();

            $members = User::where('name', 'like', "%{$q}%")
                ->limit(5)->get();

            $results = [
                'songs' => $songs,
                'setlists' => $setlists,
                'gigs' => $gigs,
                'rehearsals' => $rehearsals,
                'members' => $members,
            ];
        }

        return view('livewire.search.global-search', [
            'results' => $results,
        ]);
    }
}
