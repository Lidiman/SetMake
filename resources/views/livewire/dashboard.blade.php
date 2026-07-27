<div class="space-y-8 animate-fade-in">
    <div>
        <h1 class="text-3xl font-bold text-white">Dashboard</h1>
        <p class="text-surface-400 mt-1">Welcome back! Here's what's happening with your band.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="stat-card">
            <div class="stat-icon bg-primary-500/15">
                <svg class="w-6 h-6 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
            </div>
            <div>
                <div class="stat-value">{{ $totalSongs }}</div>
                <div class="stat-label">Total Songs</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-amber-500/15">
                <svg class="w-6 h-6 text-amber-400" fill="currentColor" viewBox="0 0 24 24"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            </div>
            <div>
                <div class="stat-value">{{ $favoriteSongs }}</div>
                <div class="stat-label">Favorites</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-purple-500/15">
                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <div class="stat-value">{{ $totalGigs }}</div>
                <div class="stat-label">Total Gigs</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-emerald-500/15">
                <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div class="stat-value">Rp{{ number_format($monthlyIncome, 0) }}</div>
                <div class="stat-label">Income This Month</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-2 h-2 rounded-full bg-blue-400 animate-pulse-glow"></div>
                <h2 class="text-lg font-semibold text-white">Upcoming Rehearsals</h2>
            </div>
            @if($upcomingRehearsals->count() > 0)
                <div class="space-y-3">
                    @foreach($upcomingRehearsals as $rehearsal)
                        <a href="{{ route('schedules.show', $rehearsal) }}" class="flex items-center justify-between p-3 rounded-xl hover:bg-surface-800/30 transition-colors" wire:navigate>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-500/20 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <p class="font-medium text-white truncate">{{ $rehearsal->title }}</p>
                                    <p class="text-xs text-surface-400">{{ $rehearsal->date->format('M d, Y') }} @if($rehearsal->start_time)· {{ $rehearsal->start_time->format('g:i A') }}@endif</p>
                                </div>
                            </div>
                            <span class="badge-surface text-xs">{{ $rehearsal->members->count() }} going</span>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-surface-500 text-sm">No upcoming rehearsals.</p>
            @endif
        </div>

        <div class="card">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-2 h-2 rounded-full bg-purple-400 animate-pulse-glow"></div>
                <h2 class="text-lg font-semibold text-white">Upcoming Gigs</h2>
            </div>
            @if($upcomingGigs->count() > 0)
                <div class="space-y-3">
                    @foreach($upcomingGigs as $gig)
                        <a href="{{ route('schedules.show', $gig) }}" class="flex items-center justify-between p-3 rounded-xl hover:bg-surface-800/30 transition-colors" wire:navigate>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-purple-500/20 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <p class="font-medium text-white truncate">{{ $gig->title }}</p>
                                    <p class="text-xs text-surface-400">{{ $gig->venue }} · {{ $gig->date->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <span class="badge badge-{{ $gig->status->color() }}">{{ $gig->status->label() }}</span>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-surface-500 text-sm">No upcoming gigs.</p>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="card">
            <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>
                Song Readiness
            </h2>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-emerald-400">Ready</span>
                        <span class="text-emerald-400 font-medium">{{ $readySongs }}</span>
                    </div>
                    <div class="h-2 bg-surface-800 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $totalSongs > 0 ? ($readySongs / $totalSongs) * 100 : 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-amber-400">Needs Practice</span>
                        <span class="text-amber-400 font-medium">{{ $songsNeedingPractice }}</span>
                    </div>
                    <div class="h-2 bg-surface-800 rounded-full overflow-hidden">
                        <div class="h-full bg-amber-500 rounded-full" style="width: {{ $totalSongs > 0 ? ($songsNeedingPractice / $totalSongs) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                Most Played
            </h2>
            @if($mostPlayedSongs->count() > 0)
                <div class="space-y-3">
                    @foreach($mostPlayedSongs as $index => $song)
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
                <p class="text-surface-500 text-sm">No performances logged yet.</p>
            @endif
        </div>

        <div class="card">
            <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 24 24"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                Favorites
            </h2>
            @if($favoriteSongsList->count() > 0)
                <div class="space-y-3">
                    @foreach($favoriteSongsList as $song)
                        <a href="{{ route('songs.show', $song) }}" class="flex items-center gap-4 p-2 rounded-lg hover:bg-surface-800/30 transition-colors group" wire:navigate>
                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-amber-500/20 to-orange-500/20 flex items-center justify-center">
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55C7.79 13 6 14.79 6 17s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-white group-hover:text-primary-400 truncate transition-colors">{{ $song->title }}</p>
                                <p class="text-xs text-surface-500">{{ $song->artist }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-surface-500 text-sm">No favorite songs yet.</p>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card">
            <h2 class="text-lg font-semibold text-white mb-4">Recently Added Songs</h2>
            @if($recentSongs->count() > 0)
                <div class="space-y-2">
                    @foreach($recentSongs as $song)
                        <a href="{{ route('songs.show', $song) }}" class="flex items-center gap-3 p-2 rounded-lg hover:bg-surface-800/30 transition-colors" wire:navigate>
                            <div class="w-8 h-8 rounded-lg bg-surface-800 flex items-center justify-center">
                                <svg class="w-4 h-4 text-surface-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2z"/></svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-white">{{ $song->title }}</p>
                                <p class="text-xs text-surface-500">{{ $song->artist }} · {{ $song->created_at->diffForHumans() }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-surface-500 text-sm">No songs added yet.</p>
            @endif
        </div>

        <div class="card">
            <h2 class="text-lg font-semibold text-white mb-4">Recent Activity</h2>
            @if($recentPerformances->count() > 0)
                <div class="space-y-3">
                    @foreach($recentPerformances as $performance)
                        <div class="flex items-center gap-4 p-2 rounded-lg hover:bg-surface-800/30 transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-surface-800 flex items-center justify-center text-xs text-surface-400 font-medium">
                                {{ $performance->performed_at->format('d') }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-white truncate">{{ $performance->song?->title ?? 'Unknown' }}</p>
                                <p class="text-xs text-surface-500">{{ $performance->performed_at->format('M d, Y') }} @if($performance->venue)· {{ $performance->venue }}@endif</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-surface-500 text-sm">No recent activity.</p>
            @endif
        </div>
    </div>

    @if($incomeByMonth->count() > 0)
    <div class="card">
        <h2 class="text-lg font-semibold text-white mb-4">Monthly Income</h2>
        <div class="space-y-2">
            @foreach($incomeByMonth as $item)
                <div class="flex items-center gap-4 p-2">
                    <span class="text-sm text-surface-400 w-24">{{ $item->month }}</span>
                    <div class="flex-1 h-4 bg-surface-800 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $incomeByMonth->max('net_income') > 0 ? ($item->net_income / $incomeByMonth->max('net_income')) * 100 : 0 }}%"></div>
                    </div>
                    <span class="text-sm font-medium text-emerald-400 w-24 text-right">Rp{{ number_format($item->net_income, 0) }}</span>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
