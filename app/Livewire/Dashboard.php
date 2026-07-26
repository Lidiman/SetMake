<?php

namespace App\Livewire;

use App\Models\Performance;
use App\Models\Setlist;
use App\Models\Song;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $totalSongs = Song::count();
        $favoriteSongs = Song::where('is_favorite', true)->count();

        $upcomingRehearsal = Setlist::with('songs')
            ->where('type', 'rehearsal')
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at')
            ->first();

        $upcomingGig = Setlist::with('songs')
            ->where('type', 'performance')
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at')
            ->first();

        $recentPerformances = Performance::with('song')
            ->orderByDesc('performed_at')
            ->limit(8)
            ->get();

        $totalRehearsalMinutes = Setlist::where('type', 'rehearsal')
            ->where('scheduled_at', '>=', now()->startOfMonth())
            ->where('scheduled_at', '<=', now()->endOfMonth())
            ->withSum('songs', 'duration')
            ->get()
            ->sum('songs_sum_duration');

        $mostPlayedSongs = Song::withCount('performances')
            ->has('performances')
            ->orderByDesc('performances_count')
            ->limit(5)
            ->get();

        $favoriteSongsList = Song::where('is_favorite', true)
            ->with('tags')
            ->limit(6)
            ->get();

        $totalPerformances = Performance::count();

        return view('livewire.dashboard', [
            'totalSongs' => $totalSongs,
            'favoriteSongs' => $favoriteSongs,
            'upcomingRehearsal' => $upcomingRehearsal,
            'upcomingGig' => $upcomingGig,
            'recentPerformances' => $recentPerformances,
            'totalRehearsalMinutes' => intval($totalRehearsalMinutes / 60),
            'mostPlayedSongs' => $mostPlayedSongs,
            'favoriteSongsList' => $favoriteSongsList,
            'totalPerformances' => $totalPerformances,
        ])->layout('components.layouts.app')->title('Dashboard');
    }
}
