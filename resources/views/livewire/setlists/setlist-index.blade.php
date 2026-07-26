<div class="space-y-6 animate-fade-in">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-white">Setlists</h1>
            <p class="text-surface-400 mt-1">Plan rehearsals and gigs.</p>
        </div>
        <a href="{{ route('setlists.create') }}" class="btn-primary shrink-0" wire:navigate>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Create Setlist
        </a>
    </div>

    {{-- Filters --}}
    <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
        <button wire:click="$set('filter', 'upcoming')" class="btn {{ $filter === 'upcoming' ? 'btn-primary' : 'btn-secondary' }} whitespace-nowrap">Upcoming</button>
        <button wire:click="$set('filter', 'past')" class="btn {{ $filter === 'past' ? 'btn-primary' : 'btn-secondary' }} whitespace-nowrap">Past</button>
        <button wire:click="$set('filter', 'rehearsal')" class="btn {{ $filter === 'rehearsal' ? 'btn-primary' : 'btn-secondary' }} whitespace-nowrap">Rehearsals</button>
        <button wire:click="$set('filter', 'performance')" class="btn {{ $filter === 'performance' ? 'btn-primary' : 'btn-secondary' }} whitespace-nowrap">Performances</button>
        <button wire:click="$set('filter', 'all')" class="btn {{ $filter === 'all' ? 'btn-primary' : 'btn-secondary' }} whitespace-nowrap">All</button>
    </div>

    {{-- Grid --}}
    @if($setlists->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach($setlists as $setlist)
                <a href="{{ route('setlists.show', $setlist) }}" class="card group hover:border-primary-500/50 block" wire:navigate>
                    <div class="flex items-start justify-between mb-4">
                        <span class="badge badge-{{ $setlist->type->color() }}">{{ $setlist->type->label() }}</span>
                        
                        @if($setlist->scheduled_at)
                            <div class="text-right">
                                <p class="text-sm font-bold {{ $setlist->scheduled_at->isPast() ? 'text-surface-400' : 'text-primary-400' }}">
                                    {{ $setlist->scheduled_at->format('M d') }}
                                </p>
                                <p class="text-xs text-surface-500">{{ $setlist->scheduled_at->format('g:i A') }}</p>
                            </div>
                        @endif
                    </div>

                    <h3 class="text-xl font-bold text-white group-hover:text-primary-400 transition-colors truncate">{{ $setlist->title }}</h3>
                    
                    @if($setlist->venue)
                        <p class="text-sm text-surface-400 mt-1 flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $setlist->venue }}
                        </p>
                    @endif

                    <div class="mt-6 pt-4 border-t border-surface-800/50 flex items-center justify-between text-sm text-surface-400">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                            {{ $setlist->songs_count }} songs
                        </span>
                        
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $setlist->formatted_total_duration }}
                        </span>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $setlists->links() }}
        </div>
    @else
        <div class="card p-12 text-center">
            <div class="w-16 h-16 rounded-full bg-surface-800 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-surface-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">No setlists found</h3>
            <p class="text-surface-400 mb-6">
                @if($filter !== 'all')
                    Try changing your filter.
                @else
                    Start planning your first rehearsal or gig!
                @endif
            </p>
            @if($filter !== 'all')
                <button wire:click="$set('filter', 'all')" class="btn-secondary">Show All</button>
            @else
                <a href="{{ route('setlists.create') }}" class="btn-primary" wire:navigate>Create Setlist</a>
            @endif
        </div>
    @endif
</div>
