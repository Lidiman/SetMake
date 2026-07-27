<?php

namespace App\Livewire;

use App\Models\Gig;
use App\Models\Performance;
use App\Models\Rehearsal;
use App\Models\Setlist;
use App\Models\Song;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $totalSongs = Song::count();
        $favoriteSongs = Song::where('is_favorite', true)->count();
        $totalGigs = Gig::count();
        $totalPerformances = Performance::count();

        $upcomingRehearsals = Rehearsal::with(['setlist', 'members'])
            ->upcoming()
            ->limit(5)
            ->get();

        $upcomingGigs = Gig::with(['setlist', 'members'])
            ->upcoming()
            ->limit(5)
            ->get();

        $recentPerformances = Performance::with('song')
            ->orderByDesc('performed_at')
            ->limit(8)
            ->get();

        $mostPlayedSongs = Song::withCount('performances')
            ->has('performances')
            ->orderByDesc('performances_count')
            ->limit(5)
            ->get();

        $favoriteSongsList = Song::where('is_favorite', true)
            ->with('tags')
            ->limit(6)
            ->get();

        $songsNeedingPractice = Song::whereHas('checklists', function ($q) {
            $q->where('is_completed', false);
        })->orWhereDoesntHave('checklists')->count();

        $readySongs = Song::whereDoesntHave('checklists', function ($q) {
            $q->where('is_completed', false);
        })->count();

        $recentSongs = Song::latest()->limit(5)->get();

        $weeklyRehearsals = Rehearsal::thisWeek()->get();

        $monthlyIncome = Gig::completed()
            ->whereYear('date', now()->year)
            ->whereMonth('date', now()->month)
            ->selectRaw('coalesce(sum(payment + tips - transport - parking - food - equipment_rental - other_expenses), 0) as net_income')
            ->value('net_income');

        $incomeByMonth = Gig::completed()
            ->selectRaw("DATE_FORMAT(date, '%Y-%m') as month, sum(payment + tips - transport - parking - food - equipment_rental - other_expenses) as net_income")
            ->groupBy('month')
            ->orderBy('month')
            ->limit(6)
            ->get();

        $rehearsalActivities = Rehearsal::with('creator')
            ->latest()
            ->limit(5)
            ->get();

        $recentSetlists = Setlist::withCount('songs')
            ->latest()
            ->limit(5)
            ->get();

        $audienceFavorites = \App\Models\SongRequest::selectRaw('song_name, sum(quantity) as total')
            ->groupBy('song_name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return view('livewire.dashboard', [
            'totalSongs' => $totalSongs,
            'favoriteSongs' => $favoriteSongs,
            'totalGigs' => $totalGigs,
            'totalPerformances' => $totalPerformances,
            'upcomingRehearsals' => $upcomingRehearsals,
            'upcomingGigs' => $upcomingGigs,
            'recentPerformances' => $recentPerformances,
            'mostPlayedSongs' => $mostPlayedSongs,
            'favoriteSongsList' => $favoriteSongsList,
            'songsNeedingPractice' => $songsNeedingPractice,
            'readySongs' => $readySongs,
            'recentSongs' => $recentSongs,
            'weeklyRehearsals' => $weeklyRehearsals,
            'monthlyIncome' => $monthlyIncome,
            'incomeByMonth' => $incomeByMonth,
            'rehearsalActivities' => $rehearsalActivities,
            'recentSetlists' => $recentSetlists,
            'audienceFavorites' => $audienceFavorites,
        ])->layout('components.layouts.app')->title('Dashboard');
    }
}
