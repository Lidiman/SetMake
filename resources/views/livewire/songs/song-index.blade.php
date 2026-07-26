<div class="space-y-6 animate-fade-in">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-white">Songs Library</h1>
            <p class="text-surface-400 mt-1">Manage and discover your band's repertoire.</p>
        </div>
        <a href="{{ route('songs.create') }}" class="btn-primary shrink-0" wire:navigate>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add New Song
        </a>
    </div>

    {{-- Filters & Search --}}
    <div class="card p-4">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-surface-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input wire:model.live.debounce.300ms="search" type="text" class="input pl-10" placeholder="Search by title, artist, or key...">
            </div>
            
            <div class="flex gap-2">
                <select wire:model.live="filter" class="input w-36">
                    <option value="all">All Songs</option>
                    <option value="favorites">Favorites</option>
                </select>
                
                <select wire:model.live="sort" class="input w-40">
                    <option value="title">A-Z</option>
                    <option value="newest">Newest First</option>
                    <option value="oldest">Oldest First</option>
                </select>
            </div>
        </div>
        
        {{-- Tags Filter (Optional, uncomment if desired) --}}
        {{-- 
        <div class="mt-4 flex flex-wrap gap-2">
            @foreach($allTags as $tag)
                <label class="cursor-pointer">
                    <input type="checkbox" wire:model.live="selectedTags" value="{{ $tag->id }}" class="hidden peer">
                    <span class="badge badge-surface peer-checked:bg-primary-500/20 peer-checked:text-primary-400 peer-checked:border-primary-500/30">
                        {{ $tag->name }}
                    </span>
                </label>
            @endforeach
        </div>
        --}}
    </div>

    {{-- Song Grid --}}
    @if($songs->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($songs as $song)
                <div class="card-compact group flex flex-col h-full relative overflow-hidden">
                    {{-- Favorite Toggle --}}
                    <button wire:click="toggleFavorite({{ $song->id }})" class="absolute top-4 right-4 z-10 favorite-star {{ $song->is_favorite ? 'active' : 'text-surface-500 hover:text-surface-300' }}">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    </button>

                    <div class="flex-1">
                        <a href="{{ route('songs.show', $song) }}" wire:navigate class="block">
                            <h3 class="text-lg font-bold text-white group-hover:text-primary-400 transition-colors pr-8 truncate">{{ $song->title }}</h3>
                            <p class="text-sm text-surface-400 truncate">{{ $song->artist ?: 'Unknown Artist' }}</p>
                        </a>

                        <div class="mt-4 flex flex-wrap gap-2">
                            @if($song->key)
                                <span class="badge badge-surface" title="Key">{{ $song->key }}</span>
                            @endif
                            @if($song->bpm)
                                <span class="badge badge-surface" title="BPM">{{ $song->bpm }}</span>
                            @endif
                            @if($song->difficulty)
                                <span class="badge badge-{{ $song->difficulty->color() }}">{{ $song->difficulty->label() }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-surface-800/50 flex items-center justify-between text-xs text-surface-500">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $song->formatted_duration }}
                        </span>
                        
                        <div class="flex items-center gap-1">
                            <span>{{ $song->performances_count }} plays</span>
                        </div>
                    </div>
                    
                    {{-- Action overlay on hover --}}
                    <div class="absolute inset-0 bg-surface-900/90 backdrop-blur-sm flex items-center justify-center gap-3 opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-20 pointer-events-none group-hover:pointer-events-auto">
                        <a href="{{ route('songs.show', $song) }}" class="btn-primary" wire:navigate>View</a>
                        @can('update', $song)
                            <a href="{{ route('songs.edit', $song) }}" class="btn-secondary" wire:navigate>Edit</a>
                        @endcan
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $songs->links() }}
        </div>
    @else
        <div class="card p-12 text-center">
            <div class="w-16 h-16 rounded-full bg-surface-800 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-surface-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">No songs found</h3>
            <p class="text-surface-400 mb-6">
                @if($search || $filter !== 'all' || !empty($selectedTags))
                    Try adjusting your filters or search term.
                @else
                    Your library is empty. Let's add some music!
                @endif
            </p>
            @if($search || $filter !== 'all' || !empty($selectedTags))
                <button wire:click="$set('search', ''); $set('filter', 'all'); $set('selectedTags', [])" class="btn-secondary">Clear Filters</button>
            @else
                <a href="{{ route('songs.create') }}" class="btn-primary" wire:navigate>Add First Song</a>
            @endif
        </div>
    @endif
</div>
