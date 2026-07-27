<div class="space-y-6 animate-fade-in">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white">Analytics</h1>
            <p class="text-surface-400 mt-1">Insights into your band's performance.</p>
        </div>
        <select wire:model.live="period" class="input w-40">
            <option value="all_time">All Time</option>
            <option value="this_year">This Year</option>
            <option value="this_month">This Month</option>
        </select>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="stat-card"><div class="stat-icon bg-primary-500/15"><svg class="w-6 h-6 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg></div><div><div class="stat-value">{{ $totalSongs }}</div><div class="stat-label">Total Songs</div></div></div>
        <div class="stat-card"><div class="stat-icon bg-emerald-500/15"><svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg></div><div><div class="stat-value">{{ $songsReady }}</div><div class="stat-label">Songs Ready</div></div></div>
        <div class="stat-card"><div class="stat-icon bg-purple-500/15"><svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div><div><div class="stat-value">{{ $totalPerformances }}</div><div class="stat-label">Total Performances</div></div></div>
        <div class="stat-card"><div class="stat-icon bg-amber-500/15"><svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div><div><div class="stat-value">{{ $totalGigs }}</div><div class="stat-label">Total Gigs</div></div></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Most Played --}}
        <div class="card">
            <h2 class="text-lg font-semibold text-white mb-4">Most Played Songs</h2>
            @if($mostPlayed->count() > 0)
                <div class="space-y-2">
                    @foreach($mostPlayed as $index => $song)
                        <div class="flex items-center gap-4 p-2 rounded-lg hover:bg-surface-800/30 transition-colors">
                            <span class="text-lg font-bold text-surface-600 w-6 text-center">{{ $index + 1 }}</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-white truncate">{{ $song->title }}</p>
                                <p class="text-xs text-surface-500">{{ $song->artist }}</p>
                            </div>
                            <span class="badge-primary">{{ $song->performances_count }}×</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-surface-500 text-sm">No data yet.</p>
            @endif
        </div>

        {{-- Least Played --}}
        <div class="card">
            <h2 class="text-lg font-semibold text-white mb-4">Least Played Songs</h2>
            @if($leastPlayed->count() > 0)
                <div class="space-y-2">
                    @foreach($leastPlayed as $index => $song)
                        <div class="flex items-center gap-4 p-2 rounded-lg hover:bg-surface-800/30 transition-colors">
                            <span class="text-lg font-bold text-surface-600 w-6 text-center">{{ $index + 1 }}</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-white truncate">{{ $song->title }}</p>
                                <p class="text-xs text-surface-500">{{ $song->artist }}</p>
                            </div>
                            <span class="badge-surface">{{ $song->performances_count }}×</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-surface-500 text-sm">No data yet.</p>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Average Metrics --}}
        <div class="card">
            <h2 class="text-lg font-semibold text-white mb-4">Averages</h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 rounded-xl bg-surface-800/30">
                    <span class="text-surface-400">Avg Gig Length</span>
                    <span class="font-bold text-white">{{ $avgGigLength }} min</span>
                </div>
                <div class="flex justify-between items-center p-3 rounded-xl bg-surface-800/30">
                    <span class="text-surface-400">Avg Income</span>
                    <span class="font-bold text-primary-400">Rp{{ number_format($avgIncome, 0) }}</span>
                </div>
            </div>
        </div>

        {{-- Income by Venue --}}
        <div class="card">
            <h2 class="text-lg font-semibold text-white mb-4">Income by Venue</h2>
            @if($incomeByVenue->count() > 0)
                <div class="space-y-2">
                    @foreach($incomeByVenue as $item)
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-surface-800/30">
                            <span class="text-sm text-white truncate mr-2">{{ $item->venue }}</span>
                            <span class="text-sm font-medium text-primary-400 shrink-0">Rp{{ number_format($item->net_income, 0) }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-surface-500 text-sm">No data yet.</p>
            @endif
        </div>

        {{-- Most Requested Songs --}}
        <div class="card">
            <h2 class="text-lg font-semibold text-white mb-4">Most Requested Songs</h2>
            @if($mostRequestedSongs->count() > 0)
                <div class="space-y-2">
                    @foreach($mostRequestedSongs as $item)
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-surface-800/30">
                            <span class="text-sm text-white truncate mr-2">{{ $item->song_name }}</span>
                            <span class="badge-amber shrink-0">{{ $item->total_requests }}×</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-surface-500 text-sm">No requests yet.</p>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Monthly Gigs --}}
        <div class="card">
            <h2 class="text-lg font-semibold text-white mb-4">Monthly Gigs</h2>
            @if($monthlyGigs->count() > 0)
                <div class="space-y-2">
                    @foreach($monthlyGigs as $item)
                        <div class="flex items-center gap-4 p-2">
                            <span class="text-sm text-surface-400 w-20">{{ $item->month }}</span>
                            <div class="flex-1 h-3 bg-surface-800 rounded-full overflow-hidden">
                                <div class="h-full bg-primary-500 rounded-full" style="width: {{ ($item->count / $monthlyGigs->max('count')) * 100 }}%"></div>
                            </div>
                            <span class="text-sm font-medium text-white w-8 text-right">{{ $item->count }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-surface-500 text-sm">No data yet.</p>
            @endif
        </div>

        {{-- Monthly Income --}}
        <div class="card">
            <h2 class="text-lg font-semibold text-white mb-4">Monthly Income</h2>
            @if($monthlyIncome->count() > 0)
                <div class="space-y-2">
                    @foreach($monthlyIncome as $item)
                        <div class="flex items-center gap-4 p-2">
                            <span class="text-sm text-surface-400 w-20">{{ $item->month }}</span>
                            <div class="flex-1 h-3 bg-surface-800 rounded-full overflow-hidden">
                                <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $monthlyIncome->max('net_income') > 0 ? ($item->net_income / $monthlyIncome->max('net_income')) * 100 : 0 }}%"></div>
                            </div>
                            <span class="text-sm font-medium text-emerald-400 w-24 text-right">Rp{{ number_format($item->net_income, 0) }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-surface-500 text-sm">No data yet.</p>
            @endif
        </div>
    </div>
</div>
