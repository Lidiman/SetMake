<div class="space-y-8 animate-fade-in">
    {{-- Page header --}}
    <div>
        <h1 class="text-3xl font-bold text-white">Dashboard</h1>
        <p class="text-surface-400 mt-1">Welcome back! Here's what's happening with your band.</p>
    </div>

    {{-- Stats grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Songs --}}
        <div class="stat-card">
            <div class="stat-icon bg-primary-500/15">
                <svg class="w-6 h-6 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
            </div>
            <div>
                <div class="stat-value">{{ $totalSongs }}</div>
                <div class="stat-label">Total Songs</div>
            </div>
        </div>

        {{-- Favorites --}}
        <div class="stat-card">
            <div class="stat-icon bg-amber-500/15">
                <svg class="w-6 h-6 text-amber-400" fill="currentColor" viewBox="0 0 24 24"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            </div>
            <div>
                <div class="stat-value">{{ $favoriteSongs }}</div>
                <div class="stat-label">Favorites</div>
            </div>
        </div>

        {{-- Performances --}}
        <div class="stat-card">
            <div class="stat-icon bg-purple-500/15">
                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div class="stat-value">{{ $totalPerformances }}</div>
                <div class="stat-label">Performances</div>
            </div>
        </div>

        {{-- Rehearsal Time --}}
        <div class="stat-card">
            <div class="stat-icon bg-blue-500/15">
                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <div>
                <div class="stat-value">{{ $totalRehearsalMinutes }}<span class="text-sm text-surface-400 font-normal ml-1">min</span></div>
                <div class="stat-label">Rehearsal This Month</div>
            </div>
        </div>
    </div>

    {{-- Upcoming events --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Upcoming Rehearsal --}}
        <div class="card">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-2 h-2 rounded-full bg-blue-400 animate-pulse-glow"></div>
                <h2 class="text-lg font-semibold text-white">Next Rehearsal</h2>
            </div>
            @if($upcomingRehearsal)
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="font-medium text-white">{{ $upcomingRehearsal->title }}</h3>
                        <span class="badge-blue">Rehearsal</span>
                    </div>
                    <div class="flex items-center gap-4 text-sm text-surface-400">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ $upcomingRehearsal->scheduled_at->format('M d, Y · g:i A') }}
                        </span>
                        @if($upcomingRehearsal->venue)
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $upcomingRehearsal->venue }}
                        </span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 text-sm text-surface-500">
                        <span>{{ $upcomingRehearsal->songs->count() }} songs</span>
                        <span>·</span>
                        <span>{{ $upcomingRehearsal->formatted_total_duration }}</span>
                    </div>
                    <a href="{{ route('setlists.show', $upcomingRehearsal) }}" class="btn-secondary btn-sm mt-2" wire:navigate>View Setlist →</a>
                </div>
            @else
                <p class="text-surface-500 text-sm">No upcoming rehearsal scheduled.</p>
            @endif
        </div>

        {{-- Upcoming Gig --}}
        <div class="card">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-2 h-2 rounded-full bg-purple-400 animate-pulse-glow"></div>
                <h2 class="text-lg font-semibold text-white">Next Gig</h2>
            </div>
            @if($upcomingGig)
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="font-medium text-white">{{ $upcomingGig->title }}</h3>
                        <span class="badge-purple">Performance</span>
                    </div>
                    <div class="flex items-center gap-4 text-sm text-surface-400">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ $upcomingGig->scheduled_at->format('M d, Y · g:i A') }}
                        </span>
                        @if($upcomingGig->venue)
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $upcomingGig->venue }}
                        </span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 text-sm text-surface-500">
                        <span>{{ $upcomingGig->songs->count() }} songs</span>
                        <span>·</span>
                        <span>{{ $upcomingGig->formatted_total_duration }}</span>
                    </div>
                    <a href="{{ route('setlists.show', $upcomingGig) }}" class="btn-secondary btn-sm mt-2" wire:navigate>View Setlist →</a>
                </div>
            @else
                <p class="text-surface-500 text-sm">No upcoming gig scheduled.</p>
            @endif
        </div>
    </div>

    {{-- Most played & favorites --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Most Played --}}
        <div class="card">
            <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                Most Played
            </h2>
            @if($mostPlayedSongs->count())
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

        {{-- Favorite Songs --}}
        <div class="card">
            <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 24 24"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                Favorites
            </h2>
            @if($favoriteSongsList->count())
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
                            @if($song->tags->count())
                                <span class="badge-surface text-xs hidden sm:inline">{{ $song->tags->first()->name }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-surface-500 text-sm">No favorite songs yet. Star some songs!</p>
            @endif
        </div>
    </div>

    {{-- Recent activity --}}
    <div class="card">
        <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Recent Activity
        </h2>
        @if($recentPerformances->count())
            <div class="space-y-3">
                @foreach($recentPerformances as $performance)
                    <div class="flex items-center gap-4 p-2 rounded-lg hover:bg-surface-800/30 transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-surface-800 flex items-center justify-center text-xs text-surface-400 font-medium">
                            {{ $performance->performed_at->format('d') }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-white truncate">{{ $performance->song->title ?? 'Unknown' }}</p>
                            <p class="text-xs text-surface-500">
                                {{ $performance->performed_at->format('M d') }}
                                @if($performance->venue) · {{ $performance->venue }} @endif
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-surface-500 text-sm">No recent activity.</p>
        @endif
    </div>
</div>
