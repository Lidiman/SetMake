<div class="max-w-5xl mx-auto space-y-6 animate-fade-in pb-12">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <a href="{{ $isEditing ? route('schedules.show', $schedule) : route('schedules.index') }}" class="text-surface-400 hover:text-white flex items-center gap-2 transition-colors" wire:navigate>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back
        </a>
    </div>

    <div>
        <h1 class="text-3xl font-bold text-white">{{ $isEditing ? 'Edit Schedule' : 'Create Schedule' }}</h1>
        <p class="text-surface-400 mt-1">Plan your band's next event.</p>
    </div>

    <form wire:submit="save" class="space-y-8">
        
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            {{-- Left Column: Basics & Type Specific --}}
            <div class="xl:col-span-2 space-y-6">
                {{-- Basics Card --}}
                <div class="card space-y-5">
                    <h2 class="text-lg font-semibold text-white border-b border-surface-800 pb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Event Details
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="label">Event Type *</label>
                            <select wire:model.live="type" class="input">
                                @foreach($types as $t)
                                    <option value="{{ $t->value }}">{{ $t->label() }}</option>
                                @endforeach
                            </select>
                            @error('type') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="label">Title *</label>
                            <input type="text" wire:model="title" class="input" placeholder="e.g. Friday Night Rock" autofocus>
                            @error('title') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="label">Date *</label>
                            <input type="date" wire:model="date" class="input">
                            @error('date') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="label">Status *</label>
                            <select wire:model="status" class="input">
                                @foreach($statuses as $s)
                                    <option value="{{ $s->value }}">{{ $s->label() }}</option>
                                @endforeach
                            </select>
                            @error('status') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="label">Start Time</label>
                            <input type="time" wire:model="start_time" class="input">
                            @error('start_time') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="label">End Time</label>
                            <input type="time" wire:model="end_time" class="input">
                            @error('end_time') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="label">Description / Goals</label>
                            <textarea wire:model="description" rows="3" class="input" placeholder="Any additional notes..."></textarea>
                            @error('description') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                {{-- Type Specific Details --}}
                @if($type === 'rehearsal')
                    <div class="card space-y-5 animate-fade-in">
                        <h2 class="text-lg font-semibold text-white border-b border-surface-800 pb-2 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Rehearsal Location
                        </h2>
                        
                        <div>
                            <label class="label">Location / Studio</label>
                            <input type="text" wire:model="location" class="input" placeholder="e.g. Studio A">
                            @error('location') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                @else
                    <div class="card space-y-5 animate-fade-in">
                        <h2 class="text-lg font-semibold text-white border-b border-surface-800 pb-2 flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Gig Details & Venue
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="label">Venue Name *</label>
                                <input type="text" wire:model="venue" class="input" placeholder="e.g. The Grand Pub">
                                @error('venue') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="label">Address</label>
                                <input type="text" wire:model="address" class="input" placeholder="Full address">
                                @error('address') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="label">Contact Person</label>
                                <input type="text" wire:model="contact_person" class="input" placeholder="Name">
                                @error('contact_person') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="label">Phone</label>
                                <input type="text" wire:model="phone" class="input" placeholder="Phone number">
                                @error('phone') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card space-y-5 animate-fade-in">
                        <h2 class="text-lg font-semibold text-white border-b border-surface-800 pb-2 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Financials
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="label text-emerald-400">Base Payment (Income)</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-surface-400">$</span>
                                    <input type="number" step="0.01" wire:model="payment" class="input pl-8">
                                </div>
                                @error('payment') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="label text-emerald-400">Tips / Extras (Income)</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-surface-400">$</span>
                                    <input type="number" step="0.01" wire:model="tips" class="input pl-8">
                                </div>
                                @error('tips') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="label text-red-400">Transport (Expense)</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-surface-400">$</span>
                                    <input type="number" step="0.01" wire:model="transport" class="input pl-8">
                                </div>
                                @error('transport') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="label text-red-400">Equipment Rental (Expense)</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-surface-400">$</span>
                                    <input type="number" step="0.01" wire:model="equipment_rental" class="input pl-8">
                                </div>
                                @error('equipment_rental') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="label text-red-400">Food / Drink (Expense)</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-surface-400">$</span>
                                    <input type="number" step="0.01" wire:model="food" class="input pl-8">
                                </div>
                                @error('food') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="label text-red-400">Other Expenses</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-surface-400">$</span>
                                    <input type="number" step="0.01" wire:model="other_expenses" class="input pl-8">
                                </div>
                                @error('other_expenses') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mt-4 p-4 bg-surface-950 rounded-xl border border-surface-800 flex justify-between items-center">
                            <span class="text-surface-300 font-medium">Est. Net Income:</span>
                            <span class="text-xl font-bold {{ $this->netIncome >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                ${{ number_format($this->netIncome, 2) }}
                            </span>
                        </div>
                    </div>
                @endif
            </div>
            
            {{-- Right Column: Members & Save --}}
            <div class="xl:col-span-1 space-y-6">
                <div class="card space-y-5">
                    <h2 class="text-lg font-semibold text-white border-b border-surface-800 pb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Band Members
                    </h2>
                    
                    <div class="space-y-3">
                        @foreach($members as $member)
                            <label class="flex items-center gap-3 p-3 bg-surface-950/50 rounded-xl border border-surface-800 hover:border-surface-700 cursor-pointer transition-colors">
                                <input type="checkbox" wire:model="selectedMembers" value="{{ $member->id }}" class="w-5 h-5 rounded border-surface-700 text-primary-500 focus:ring-primary-500 focus:ring-offset-surface-900 bg-surface-900">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-surface-800 flex items-center justify-center text-xs font-bold text-white shrink-0">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </div>
                                    <span class="text-surface-200 font-medium">{{ $member->name }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('selectedMembers') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div class="card bg-surface-900 border-primary-500/20 shadow-lg shadow-primary-500/5">
                    <button type="submit" class="btn-primary w-full" wire:loading.attr="disabled">
                        <span wire:loading.remove>{{ $isEditing ? 'Save Changes' : 'Create Schedule' }}</span>
                        <span wire:loading>Saving...</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Setlist Builder Block --}}
        <div class="card border-primary-500/30 shadow-lg shadow-primary-500/5" x-data="{ include: @entangle('includeSetlist') }">
            <div class="flex items-center justify-between border-b border-surface-800 pb-4 mb-6">
                <div>
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        <svg class="w-6 h-6 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                        Event Setlist
                    </h2>
                    <p class="text-surface-400 text-sm mt-1">Plan the songs you'll play for this event.</p>
                </div>
                
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" wire:model.live="includeSetlist" class="sr-only peer">
                    <div class="w-11 h-6 bg-surface-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-500"></div>
                </label>
            </div>

            <div x-show="include" x-transition.opacity class="space-y-8">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="label">Setlist Title *</label>
                        <input type="text" wire:model="setlist_title" class="input" placeholder="e.g. Set 1 & 2">
                        @error('setlist_title') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="label">Setlist Notes</label>
                        <input type="text" wire:model="setlist_description" class="input" placeholder="General mood or notes...">
                        @error('setlist_description') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                    {{-- Search Songs --}}
                    <div>
                        <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Find Songs</h3>
                        <div class="relative z-20">
                            <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-surface-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input wire:model.live.debounce.500ms="searchQuery" wire:keydown.enter="$refresh" type="text" class="input pl-10 bg-surface-950 border-surface-700" placeholder="Search library or YouTube Music...">
                            
                            <div wire:loading wire:target="searchQuery" class="absolute right-3 top-1/2 -translate-y-1/2">
                                <svg class="animate-spin h-5 w-5 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            </div>

                            @if(!empty($searchQuery))
                                @php
                                    $hasLibraryResults = $searchResults->count() > 0;
                                    $hasYtmResults = count($ytmResults) > 0;
                                    $showDropdown = $hasLibraryResults || $hasYtmResults || strlen($searchQuery) >= 2;
                                @endphp
                                
                                @if($showDropdown)
                                    <div class="absolute top-full left-0 right-0 mt-2 bg-surface-800 border border-surface-700 rounded-xl shadow-2xl overflow-hidden z-30">
                                        {{-- Library Results --}}
                                        @if($hasLibraryResults)
                                            <div class="px-4 pt-3 pb-1 text-xs font-semibold text-surface-500 uppercase tracking-wider">Your Library</div>
                                            <ul class="max-h-48 overflow-y-auto">
                                                @foreach($searchResults as $song)
                                                    <li>
                                                        <button type="button" wire:click="addSong({{ $song->id }})" class="w-full text-left px-4 py-3 hover:bg-surface-700 flex items-center justify-between group transition-colors border-b border-surface-700/50 last:border-0">
                                                            <div class="flex items-center gap-3 min-w-0">
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
                                            <div class="px-4 pt-3 pb-1 text-xs font-semibold text-surface-500 uppercase tracking-wider flex items-center gap-2">
                                                <svg class="w-3 h-3 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M21.582 6.186a2.506 2.506 0 00-1.768-1.768C18.254 4 12 4 12 4s-6.254 0-7.814.418c-.832.208-1.486.862-1.694 1.694C2.074 7.746 2.074 12 2.074 12s0 4.254.418 5.814c.208.832.862 1.486 1.694 1.694C5.746 20 12 20 12 20s6.254 0 7.814-.418a2.506 2.506 0 001.768-1.768C22 16.254 22 12 22 12s0-4.254-.418-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                                YouTube Music
                                            </div>
                                            
                                            @if($ytmSearching)
                                                <div class="p-4 text-center text-surface-400">
                                                    <svg class="animate-spin h-5 w-5 text-primary-500 mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                                </div>
                                            @elseif($hasYtmResults)
                                                <ul class="max-h-48 overflow-y-auto">
                                                    @foreach($ytmResults as $ytmIndex => $result)
                                                        <li>
                                                            <button type="button" wire:click="addSongFromYtm({{ $ytmIndex }})" class="w-full text-left px-4 py-3 hover:bg-surface-700 flex items-center justify-between group transition-colors border-b border-surface-700/50 last:border-0">
                                                                <div class="flex items-center gap-3 min-w-0">
                                                                    <div class="w-8 h-8 rounded bg-surface-700 shrink-0 overflow-hidden">
                                                                        @if(!empty($result['thumbnails']))
                                                                            <img src="{{ $result['thumbnails'][0]['url'] ?? '' }}" alt="" class="w-full h-full object-cover">
                                                                        @endif
                                                                    </div>
                                                                    <div class="min-w-0">
                                                                        <p class="font-medium text-white truncate">{{ $result['title'] }}</p>
                                                                        <p class="text-xs text-surface-400 truncate">{{ implode(', ', $result['artists']) }}</p>
                                                                    </div>
                                                                </div>
                                                                <div class="flex items-center gap-3 shrink-0">
                                                                    <svg class="w-5 h-5 text-surface-500 group-hover:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                                                </div>
                                                            </button>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        @endif
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>

                    {{-- Selected Songs --}}
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-semibold text-white uppercase tracking-wider">Setlist Order</h3>
                            <span class="badge badge-surface">{{ count($setlistSongs) }} Songs • {{ $this->totalDuration }}</span>
                        </div>
                        
                        @if(empty($setlistSongs))
                            <div class="text-center py-8 bg-surface-950/30 rounded-xl border border-dashed border-surface-700">
                                <p class="text-surface-400 text-sm">Search for songs to add them.</p>
                            </div>
                        @else
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
                            " class="space-y-2">
                                @foreach($setlistSongs as $index => $song)
                                    <div data-index="{{ $index }}" wire:key="song-{{ $song['id'] }}-{{ $index }}" class="bg-surface-800/40 rounded-xl border border-surface-700 p-2 hover:border-surface-600 transition-colors">
                                        <div class="flex items-center gap-3">
                                            <div class="drag-handle cursor-move p-1 text-surface-500 hover:text-white shrink-0">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                                            </div>
                                            
                                            <div class="text-surface-600 font-bold w-4 text-xs text-center shrink-0">
                                                {{ $index + 1 }}
                                            </div>
                                            
                                            <div class="flex-1 min-w-0">
                                                <p class="font-bold text-sm text-white truncate">{{ $song['title'] }}</p>
                                                <p class="text-xs text-surface-400">{{ $song['artist'] ?: 'Unknown Artist' }}</p>
                                            </div>
                                            
                                            <button type="button" wire:click="removeSong({{ $index }})" class="p-2 text-surface-500 hover:text-red-400 rounded-lg hover:bg-red-500/10 transition-colors shrink-0" title="Remove">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>
