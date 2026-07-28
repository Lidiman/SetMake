<?php

namespace App\Livewire;

use App\Enums\ScheduleStatus;
use App\Models\Performance;
use App\Models\Schedule;
use App\Models\Setlist;
use App\Models\Song;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $totalSongs = Cache::remember('dashboard.total_songs', 600, function () {
            return Song::count();
        });
        $favoriteSongs = Cache::remember('dashboard.favorite_songs_count', 600, function () {
            return Song::where('is_favorite', true)->count();
        });
        $totalGigs = Cache::remember('dashboard.total_gigs', 600, function () {
            return Schedule::gig()->count();
        });
        $totalPerformances = Cache::remember('dashboard.total_performances', 600, function () {
            return Performance::count();
        });

        $upcomingRehearsals = Cache::remember('dashboard.upcoming_rehearsals', 300, function () {
            return Schedule::rehearsal()
                ->with(['setlist', 'members'])
                ->upcoming()
                ->limit(5)
                ->get();
        });

        $upcomingGigs = Cache::remember('dashboard.upcoming_gigs', 300, function () {
            return Schedule::gig()
                ->with(['setlist', 'members'])
                ->upcoming()
                ->limit(5)
                ->get();
        });

        $recentPerformances = Cache::remember('dashboard.recent_performances', 300, function () {
            return Performance::with('song')
                ->orderByDesc('performed_at')
                ->limit(8)
                ->get();
        });

        $mostPlayedSongs = Cache::remember('dashboard.most_played_songs', 600, function () {
            return Song::withCount('performances')
                ->has('performances')
                ->orderByDesc('performances_count')
                ->limit(5)
                ->get();
        });

        $favoriteSongsList = Cache::remember('dashboard.favorite_songs_list', 600, function () {
            return Song::where('is_favorite', true)
                ->with('tags')
                ->limit(6)
                ->get();
        });

        $songsNeedingPractice = Cache::remember('dashboard.songs_needing_practice', 600, function () {
            return Song::whereHas('checklists', function ($q) {
                $q->where('is_completed', false);
            })->orWhereDoesntHave('checklists')->count();
        });

        $readySongs = Cache::remember('dashboard.ready_songs', 600, function () {
            return Song::whereDoesntHave('checklists', function ($q) {
                $q->where('is_completed', false);
            })->count();
        });

        $recentSongs = Cache::remember('dashboard.recent_songs', 600, function () {
            return Song::latest()->limit(5)->get();
        });

        $weeklyRehearsals = Cache::remember('dashboard.weekly_rehearsals', 300, function () {
            return Schedule::rehearsal()
                ->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])
                ->get();
        });

        $monthlyIncome = Cache::remember('dashboard.monthly_income', 900, function () {
            return Schedule::gig()
                ->where('status', ScheduleStatus::Completed->value)
                ->byMonth(now()->year, now()->month)
                ->selectRaw('coalesce(sum(payment + tips - transport - parking - food - equipment_rental - other_expenses), 0) as net_income')
                ->value('net_income');
        });

        $incomeByMonth = Cache::remember('dashboard.income_by_month', 900, function () {
            return Schedule::gig()
                ->where('status', ScheduleStatus::Completed->value)
                ->selectRaw("DATE_FORMAT(date, '%Y-%m') as month, sum(payment + tips - transport - parking - food - equipment_rental - other_expenses) as net_income")
                ->groupBy('month')
                ->orderBy('month')
                ->limit(6)
                ->get();
        });

        $rehearsalActivities = Cache::remember('dashboard.rehearsal_activities', 300, function () {
            return Schedule::rehearsal()
                ->with('creator')
                ->latest()
                ->limit(5)
                ->get();
        });

        $recentSetlists = Cache::remember('dashboard.recent_setlists', 600, function () {
            return Setlist::withCount('songs')
                ->latest()
                ->limit(5)
                ->get();
        });

        $audienceFavorites = Cache::remember('dashboard.audience_favorites', 600, function () {
            return \App\Models\SongRequest::selectRaw('song_name, sum(quantity) as total')
                ->groupBy('song_name')
                ->orderByDesc('total')
                ->limit(5)
                ->get();
        });

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
