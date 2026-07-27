<?php

namespace App\Livewire\Analytics;

use App\Models\Gig;
use App\Models\Performance;
use App\Models\Song;
use App\Models\SongRequest;
use Livewire\Component;

class AnalyticsDashboard extends Component
{
    public string $period = 'all_time';

    public function render()
    {
        $songQuery = Song::withCount('performances');
        $gigQuery = Gig::query();
        $performanceQuery = Performance::query();

        if ($this->period === 'this_year') {
            $songQuery->whereHas('performances', fn($q) => $q->whereYear('performed_at', now()->year));
            $gigQuery->whereYear('date', now()->year);
            $performanceQuery->whereYear('performed_at', now()->year);
        } elseif ($this->period === 'this_month') {
            $songQuery->whereHas('performances', fn($q) => $q->whereMonth('performed_at', now()->month)->whereYear('performed_at', now()->year));
            $gigQuery->whereMonth('date', now()->month)->whereYear('date', now()->year);
            $performanceQuery->whereMonth('performed_at', now()->month)->whereYear('performed_at', now()->year);
        }

        $mostPlayed = (clone $songQuery)->orderByDesc('performances_count')->limit(10)->get();
        $leastPlayed = (clone $songQuery)->orderBy('performances_count')->limit(10)->get();

        $completedGigs = Gig::completed();
        $avgIncome = (clone $completedGigs)->avg('payment') ?? 0;
        $avgGigLength = Gig::whereNotNull('start_time')->whereNotNull('end_time')->get()->avg(fn($g) => $g->end_time && $g->start_time ? now()->parse($g->start_time)->diffInMinutes(now()->parse($g->end_time)) : 0);

        $incomeByVenue = Gig::completed()->selectRaw('venue, sum(payment + tips - transport - parking - food - equipment_rental - other_expenses) as net_income')
            ->groupBy('venue')
            ->orderByDesc('net_income')
            ->limit(10)
            ->get();

        $monthlyGigs = Gig::selectRaw("strftime('%Y-%m', date) as month, count(*) as count")
            ->groupBy('month')
            ->orderBy('month')
            ->limit(12)
            ->get();

        $monthlyIncome = Gig::completed()->selectRaw("strftime('%Y-%m', date) as month, sum(payment + tips - transport - parking - food - equipment_rental - other_expenses) as net_income")
            ->groupBy('month')
            ->orderBy('month')
            ->limit(12)
            ->get();

        $mostRequestedSongs = SongRequest::selectRaw('song_name, sum(quantity) as total_requests')
            ->groupBy('song_name')
            ->orderByDesc('total_requests')
            ->limit(10)
            ->get();

        $totalSongs = Song::count();
        $songsWithChecklists = Song::whereHas('checklists')->count();
        $songsReady = Song::whereHas('checklists', fn($q) => $q->where('is_completed', true))
            ->orWhereDoesntHave('checklists')
            ->count();

        return view('livewire.analytics.analytics-dashboard', [
            'mostPlayed' => $mostPlayed,
            'leastPlayed' => $leastPlayed,
            'avgIncome' => $avgIncome,
            'avgGigLength' => round($avgGigLength),
            'incomeByVenue' => $incomeByVenue,
            'monthlyGigs' => $monthlyGigs,
            'monthlyIncome' => $monthlyIncome,
            'mostRequestedSongs' => $mostRequestedSongs,
            'totalSongs' => $totalSongs,
            'songsReady' => $songsReady,
            'totalPerformances' => Performance::count(),
            'totalGigs' => Gig::count(),
        ])->layout('components.layouts.app')->title('Analytics');
    }
}
