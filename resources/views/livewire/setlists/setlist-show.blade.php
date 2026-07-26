<div class="max-w-4xl mx-auto space-y-6 animate-fade-in">
    {{-- Navigation & Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <a href="{{ route('setlists.index') }}" class="text-surface-400 hover:text-white flex items-center gap-2 transition-colors" wire:navigate>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Setlists
        </a>

        <div class="flex items-center gap-3">
            @if($setlist->scheduled_at && $setlist->scheduled_at->isPast() && $setlist->performances->isEmpty())
                <button wire:click="logPerformance" class="btn-primary">Log Performance</button>
            @endif
            @can('update', $setlist)
                <a href="{{ route('setlists.edit', $setlist) }}" class="btn-secondary" wire:navigate>Edit</a>
            @endcan
            @can('delete', $setlist)
                <button wire:confirm="Are you sure you want to delete this setlist?" wire:click="delete" class="btn-danger">Delete</button>
            @endcan
        </div>
    </div>

    {{-- Main Header --}}
    <div class="card relative overflow-hidden">
        {{-- Background blur depending on type --}}
        <div class="absolute -top-24 -right-24 w-64 h-64 rounded-full blur-3xl opacity-20 pointer-events-none bg-{{ $setlist->type->color() }}-500"></div>

        <div class="relative z-10 flex flex-col md:flex-row gap-6 justify-between">
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <span class="badge badge-{{ $setlist->type->color() }}">{{ $setlist->type->label() }}</span>
                    @if($setlist->scheduled_at && $setlist->scheduled_at->isFuture())
                        <span class="badge badge-emerald">Upcoming</span>
                    @elseif($setlist->scheduled_at && $setlist->scheduled_at->isPast())
                        <span class="badge badge-surface">Past</span>
                    @endif
                </div>

                <div>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">{{ $setlist->title }}</h1>
                    @if($setlist->description)
                        <p class="text-surface-300 mt-2 max-w-2xl">{{ $setlist->description }}</p>
                    @endif
                </div>

                <div class="flex flex-wrap items-center gap-6 pt-2">
                    @if($setlist->scheduled_at)
                        <div class="flex items-center gap-2 text-surface-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="font-medium text-white">{{ $setlist->scheduled_at->format('l, F j, Y') }} at {{ $setlist->scheduled_at->format('g:i A') }}</span>
                        </div>
                    @endif

                    @if($setlist->venue)
                        <div class="flex items-center gap-2 text-surface-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="font-medium text-white">{{ $setlist->venue }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Summary Stats Box --}}
            <div class="shrink-0 bg-surface-950/50 rounded-xl p-5 border border-surface-700/50 flex flex-col justify-center min-w-[200px]">
                <div class="flex justify-between items-end mb-4 border-b border-surface-700 pb-2">
                    <span class="text-surface-400 text-sm">Total Songs</span>
                    <span class="text-2xl font-bold text-white">{{ $setlist->songs_count }}</span>
                </div>
                <div class="flex justify-between items-end">
                    <span class="text-surface-400 text-sm">Duration</span>
                    <span class="text-2xl font-bold text-white">{{ $setlist->formatted_total_duration }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Song List --}}
    <div class="card">
        <h2 class="text-xl font-bold text-white mb-6 border-b border-surface-800 pb-3">Setlist Order</h2>
        
        @if($setlist->songs->isEmpty())
            <div class="text-center py-8">
                <p class="text-surface-400">No songs added to this setlist yet.</p>
                @can('update', $setlist)
                    <a href="{{ route('setlists.edit', $setlist) }}" class="btn-primary mt-4" wire:navigate>Add Songs</a>
                @endcan
            </div>
        @else
            <div class="space-y-3">
                @foreach($setlist->songs as $index => $song)
                    <div class="flex flex-col sm:flex-row gap-4 p-4 rounded-xl bg-surface-800/30 hover:bg-surface-800/50 border border-surface-800 transition-colors">
                        {{-- Position & Duration --}}
                        <div class="flex sm:flex-col items-center sm:items-end justify-between sm:justify-start gap-4 sm:w-20 shrink-0">
                            <span class="text-xl font-bold text-surface-600">#{{ $index + 1 }}</span>
                            <span class="text-sm font-medium text-surface-400 font-mono">{{ $song->formatted_duration }}</span>
                        </div>
                        
                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <a href="{{ route('songs.show', $song) }}" class="text-lg font-bold text-white hover:text-primary-400 transition-colors block truncate" wire:navigate>
                                        {{ $song->title }}
                                    </a>
                                    <p class="text-sm text-surface-400">{{ $song->artist ?: 'Unknown Artist' }}</p>
                                </div>
                                
                                <div class="flex gap-2 shrink-0">
                                    @if($song->key)
                                        <span class="badge badge-surface">{{ $song->key }}</span>
                                    @endif
                                    @if($song->bpm)
                                        <span class="badge badge-surface">{{ $song->bpm }} bpm</span>
                                    @endif
                                </div>
                            </div>
                            
                            {{-- Specific Setlist Notes for this song --}}
                            @if($song->pivot->notes)
                                <div class="mt-3 p-3 bg-surface-900/50 rounded-lg text-sm text-surface-300 border-l-2 border-primary-500">
                                    <span class="text-xs text-surface-500 font-medium uppercase tracking-wider block mb-1">Setlist Note:</span>
                                    {{ $song->pivot->notes }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
