<div class="max-w-5xl mx-auto space-y-6 animate-fade-in">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <a href="{{ $isEditing ? route('setlists.show', $setlist) : route('setlists.index') }}" class="text-surface-400 hover:text-white flex items-center gap-2 transition-colors" wire:navigate>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back
        </a>
    </div>

    <div>
        <h1 class="text-3xl font-bold text-white">{{ $isEditing ? 'Edit Setlist' : 'Create Setlist' }}</h1>
        <p class="text-surface-400 mt-1">Plan your next rehearsal or performance.</p>
    </div>

    <form wire:submit="save" class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        
        {{-- Left Column: Details --}}
        <div class="xl:col-span-1 space-y-6">
            <div class="card space-y-5">
                <h2 class="text-lg font-semibold text-white border-b border-surface-800 pb-2">Setlist Details</h2>
                
                <div>
                    <label class="label">Title *</label>
                    <input type="text" wire:model="title" class="input" placeholder="e.g. Friday Rehearsal" autofocus>
                    @error('title') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="label">Type *</label>
                    <select wire:model="type" class="input">
                        @foreach($types as $t)
                            <option value="{{ $t->value }}">{{ $t->label() }}</option>
                        @endforeach
                    </select>
                    @error('type') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="label">Date & Time</label>
                    <input type="datetime-local" wire:model="scheduled_at" class="input">
                    @error('scheduled_at') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="label">Venue</label>
                    <input type="text" wire:model="venue" class="input" placeholder="e.g. The Garage">
                    @error('venue') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="label">Description / Goals</label>
                    <textarea wire:model="description" rows="3" class="input" placeholder="What are we focusing on?"></textarea>
                    @error('description') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
            
            {{-- Summary card --}}
            <div class="card bg-surface-900 border-primary-500/20 shadow-lg shadow-primary-500/5 hidden xl:block">
                <h3 class="text-sm font-medium text-surface-400 mb-4">Setlist Summary</h3>
                <div class="flex justify-between items-end mb-2">
                    <span class="text-white font-medium">Total Songs</span>
                    <span class="text-xl font-bold text-primary-400">{{ count($setlistSongs) }}</span>
                </div>
                <div class="flex justify-between items-end">
                    <span class="text-white font-medium">Est. Duration</span>
                    <span class="text-xl font-bold text-primary-400">{{ $this->totalDuration }}</span>
                </div>
                
                <button type="submit" class="btn-primary w-full mt-6" wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ $isEditing ? 'Save Changes' : 'Create Setlist' }}</span>
                    <span wire:loading>Saving...</span>
                </button>
            </div>
        </div>
        
        {{-- Right Column: Songs --}}
        <div class="xl:col-span-2 space-y-6">
            {{-- Song Search --}}
            <div class="card relative overflow-visible z-20">
                <h2 class="text-lg font-semibold text-white border-b border-surface-800 pb-2 mb-4">Add Songs</h2>
                
                <div class="relative">
                    <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-surface-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input wire:model.live.debounce.500ms="searchQuery" wire:keydown.enter="$refresh" type="text" class="input pl-10 bg-surface-950 border-surface-700" placeholder="Search library or YouTube Music...">
                    
                    <div wire:loading wire:target="searchQuery" class="absolute right-3 top-1/2 -translate-y-1/2">
                        <svg class="animate-spin h-5 w-5 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    </div>
                </div>
                
                @if(!empty($searchQuery))
                    @php
                        $hasLibraryResults = $searchResults->count() > 0;
                        $hasYtmResults = count($ytmResults) > 0;
                        $showDropdown = $hasLibraryResults || $hasYtmResults || strlen($searchQuery) >= 2;
                    @endphp
                    
                    @if($showDropdown)
                        <div class="absolute top-full left-3 right-3 mt-3 bg-surface-800 border border-surface-700 rounded-xl shadow-2xl overflow-hidden z-30">
                            {{-- Library Results --}}
                            @if($hasLibraryResults)
                                <div class="px-4 pt-3 pb-1 text-xs font-semibold text-surface-500 uppercase tracking-wider">Your Library</div>
                                <ul class="max-h-48 overflow-y-auto">
                                    @foreach($searchResults as $song)
                                        <li>
                                            <button type="button" wire:click="addSong({{ $song->id }})" class="w-full text-left px-4 py-4 hover:bg-surface-700 flex items-center justify-between group transition-colors border-b border-surface-700/50 last:border-0">
                                                <div class="flex items-center gap-3 min-w-0">
                                                    <div class="w-8 h-8 rounded-lg bg-surface-700 flex items-center justify-center shrink-0">
                                                        <svg class="w-4 h-4 text-surface-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2z"/></svg>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <p class="font-medium text-white truncate">{{ $song->title }}</p>
                                                        <p class="text-xs text-surface-400 truncate">{{ $song->artist ?: 'Unknown Artist' }}</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-3 shrink-0">
                                                    <span class="text-xs text-surface-500">{{ $song->formatted_duration }}</span>
                                                    <svg class="w-5 h-5 text-surface-500 group-hover:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                                </div>
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            {{-- YouTube Music Results --}}
                            @if($hasLibraryResults && $hasYtmResults)
                                <div class="border-t border-surface-700"></div>
                            @endif

                            @if($hasYtmResults || (!$hasLibraryResults && strlen($searchQuery) >= 2))
                                @if(!$hasLibraryResults)
                                    <div class="px-4 pt-3 pb-1 text-xs font-semibold text-surface-500 uppercase tracking-wider">YouTube Music</div>
                                @else
                                    <div class="px-4 pt-3 pb-1 text-xs font-semibold text-surface-500 uppercase tracking-wider flex items-center gap-2">
                                        <svg class="w-3 h-3 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M21.582 6.186a2.506 2.506 0 00-1.768-1.768C18.254 4 12 4 12 4s-6.254 0-7.814.418c-.832.208-1.486.862-1.694 1.694C2.074 7.746 2.074 12 2.074 12s0 4.254.418 5.814c.208.832.862 1.486 1.694 1.694C5.746 20 12 20 12 20s6.254 0 7.814-.418a2.506 2.506 0 001.768-1.768C22 16.254 22 12 22 12s0-4.254-.418-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                        YouTube Music
                                    </div>
                                @endif
                                
                                @if($ytmSearching)
                                    <div class="p-4 text-center text-surface-400">
                                        <svg class="animate-spin h-5 w-5 text-primary-500 mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                        Searching YouTube Music...
                                    </div>
                                @elseif($hasYtmResults)
                                    <ul class="max-h-48 overflow-y-auto">
                                        @foreach($ytmResults as $ytmIndex => $result)
                                            <li>
                                                <button type="button" wire:click="addSongFromYtm({{ $ytmIndex }})" class="w-full text-left px-4 py-4 hover:bg-surface-700 flex items-center justify-between group transition-colors border-b border-surface-700/50 last:border-0">
                                                    <div class="flex items-center gap-3 min-w-0">
                                                        <div class="w-8 h-8 rounded-lg bg-surface-700 flex items-center justify-center shrink-0 overflow-hidden">
                                                            @if(!empty($result['thumbnails']))
                                                                <img src="{{ $result['thumbnails'][0]['url'] ?? '' }}" alt="" class="w-full h-full object-cover">
                                                            @else
                                                                <svg class="w-4 h-4 text-surface-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2z"/></svg>
                                                            @endif
                                                        </div>
                                                        <div class="min-w-0">
                                                            <p class="font-medium text-white truncate">{{ $result['title'] }}</p>
                                                            <p class="text-xs text-surface-400 truncate">{{ implode(', ', $result['artists']) }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center gap-3 shrink-0">
                                                        @if($result['duration_seconds'])
                                                            <span class="text-xs text-surface-500">{{ gmdate('i:s', $result['duration_seconds']) }}</span>
                                                        @endif
                                                        <svg class="w-5 h-5 text-surface-500 group-hover:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                                    </div>
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>
                                @elseif($ytmError)
                                    <div class="p-4 text-center text-surface-400">
                                        <p>YouTube Music: {{ $ytmError }}</p>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endif
                @endif
            </div>
            
            {{-- Song List (Drag & Drop) --}}
            <div class="card space-y-4">
                <div class="flex items-center justify-between border-b border-surface-800 pb-2">
                    <h2 class="text-lg font-semibold text-white">Setlist Order</h2>
                    <span class="badge badge-surface">{{ count($setlistSongs) }} Songs</span>
                </div>
                
                @if(empty($setlistSongs))
                    <div class="text-center py-12 bg-surface-950/30 rounded-xl border border-dashed border-surface-700">
                        <svg class="w-12 h-12 text-surface-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                        <p class="text-surface-400">Search for songs above to add them to your setlist.</p>
                    </div>
                @else
                    {{-- Alpine Component for SortableJS --}}
                    <div x-data x-init="
                        new Sortable($el, {
                            handle: '.drag-handle',
                            animation: 150,
                            ghostClass: 'sortable-ghost',
                            dragClass: 'sortable-drag',
                            chosenClass: 'sortable-chosen',
                            onEnd: function (evt) {
                                let list = [];
                                Array.from($el.children).forEach((el, index) => {
                                    list.push({ value: el.dataset.index, order: index + 1 });
                                });
                                @this.updateSongOrder(list);
                            }
                        });
                    " class="space-y-3">
                        @foreach($setlistSongs as $index => $song)
                            <div data-index="{{ $index }}" wire:key="song-{{ $song['id'] }}-{{ $index }}" class="bg-surface-800/40 rounded-xl border border-surface-700 p-3 hover:border-surface-600 transition-colors">
                                <div class="flex items-start gap-3">
                                    {{-- Drag handle --}}
                                    <div class="drag-handle cursor-move mt-2 p-1 text-surface-500 hover:text-white shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                                    </div>
                                    
                                    {{-- Position --}}
                                    <div class="mt-2 text-surface-600 font-bold w-6 text-center shrink-0">
                                        {{ $index + 1 }}
                                    </div>
                                    
                                    {{-- Info & Inputs --}}
                                    <div class="flex-1 min-w-0 flex flex-col md:flex-row gap-4">
                                        <div class="flex-1 min-w-0">
                                            <p class="font-bold text-white truncate">{{ $song['title'] }}</p>
                                            <p class="text-sm text-surface-400">{{ $song['artist'] ?: 'Unknown Artist' }} • {{ $song['duration'] }}</p>
                                        </div>
                                        
                                        <div class="md:w-1/2 relative">
                                            <input type="text" wire:model="setlistSongs.{{ $index }}.notes" class="input input-sm bg-surface-900 border-surface-700" placeholder="Notes for this set (optional)">
                                        </div>
                                    </div>
                                    
                                    {{-- Remove --}}
                                    <button type="button" wire:click="removeSong({{ $index }})" class="mt-1 p-2 text-surface-500 hover:text-red-400 rounded-lg hover:bg-red-500/10 transition-colors shrink-0" title="Remove">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Mobile submit --}}
            <div class="xl:hidden mt-6 pb-8">
                <button type="submit" class="btn-primary w-full" wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ $isEditing ? 'Save Changes' : 'Create Setlist' }}</span>
                    <span wire:loading>Saving...</span>
                </button>
            </div>
        </div>
    </form>
</div>
