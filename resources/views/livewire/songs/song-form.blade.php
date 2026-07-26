<div class="max-w-4xl mx-auto space-y-6 animate-fade-in">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <a href="{{ $isEditing ? route('songs.show', $song) : route('songs.index') }}" class="text-surface-400 hover:text-white flex items-center gap-2 transition-colors" wire:navigate>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back
        </a>
    </div>

    <div>
        <h1 class="text-3xl font-bold text-white">{{ $isEditing ? 'Edit Song' : 'Add New Song' }}</h1>
        <p class="text-surface-400 mt-1">Fill in the details below.</p>
    </div>

    {{-- YouTube Music Import Section --}}
    @if(!$isEditing)
    <div class="card space-y-4 border border-primary-500/20">
        <div class="flex items-center gap-2 border-b border-surface-800 pb-2">
            <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M21.582 6.186a2.506 2.506 0 00-1.768-1.768C18.254 4 12 4 12 4s-6.254 0-7.814.418c-.832.208-1.486.862-1.694 1.694C2.074 7.746 2.074 12 2.074 12s0 4.254.418 5.814c.208.832.862 1.486 1.694 1.694C5.746 20 12 20 12 20s6.254 0 7.814-.418a2.506 2.506 0 001.768-1.768C22 16.254 22 12 22 12s0-4.254-.418-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
            <h2 class="text-lg font-semibold text-white flex-1">Import from YouTube Music</h2>
        </div>

        <div class="flex gap-3">
            <input type="text" wire:model="ytmQuery" wire:keydown.enter="searchYtm" class="input flex-1" placeholder="Search YouTube Music...">
            <button type="button" wire:click="searchYtm" wire:loading.attr="disabled" class="btn-primary btn-sm whitespace-nowrap">
                <span wire:loading.remove wire:target="searchYtm">Search</span>
                <span wire:loading wire:target="searchYtm">Searching...</span>
            </button>
        </div>
        @error('ytmQuery') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror

        @if($ytmError)
            <p class="text-red-400 text-sm">{{ $ytmError }}</p>
        @endif

        @if(!empty($ytmResults))
            <div class="space-y-3 max-h-80 overflow-y-auto">
                @foreach($ytmResults as $result)
                    <div class="flex items-center gap-3 bg-surface-800/50 p-4 rounded-xl border border-surface-800 hover:border-surface-700 transition-colors">
                        <div class="w-12 h-12 rounded-lg bg-surface-700 flex-shrink-0 overflow-hidden">
                            @if(!empty($result['thumbnails']))
                                <img src="{{ $result['thumbnails'][0]['url'] ?? '' }}" alt="" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-white font-medium truncate">{{ $result['title'] }}</p>
                            <p class="text-surface-400 text-sm truncate">{{ implode(', ', $result['artists']) }}</p>
                        </div>
                        @if($result['duration_seconds'])
                            <span class="text-surface-400 text-sm">{{ gmdate('i:s', $result['duration_seconds']) }}</span>
                        @endif
                        <button type="button" wire:click="importFromYtm({{ $loop->index }})" class="btn-ghost btn-sm text-primary-400 hover:text-primary-300 whitespace-nowrap">
                            Import
                        </button>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    @endif

    <form wire:submit="save" class="space-y-6">
        <div class="card space-y-6">
            {{-- Basic Info --}}
            <h2 class="text-lg font-semibold text-white border-b border-surface-800 pb-2">Basic Info</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="label">Title *</label>
                    <input type="text" wire:model="title" class="input" placeholder="e.g. Hotel California" autofocus>
                    @error('title') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="label">Artist</label>
                    <input type="text" wire:model="artist" class="input" placeholder="e.g. Eagles">
                    @error('artist') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="label">Genre</label>
                    <input type="text" wire:model="genre" class="input" placeholder="e.g. Rock">
                    @error('genre') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="label">Key</label>
                    <input type="text" wire:model="key" class="input" placeholder="e.g. Bm">
                    @error('key') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="label">BPM</label>
                    <input type="number" wire:model="bpm" class="input" placeholder="e.g. 74">
                    @error('bpm') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Playing Details --}}
            <h2 class="text-lg font-semibold text-white border-b border-surface-800 pb-2 pt-4">Playing Details</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label class="label">Duration (sec)</label>
                    <input type="number" wire:model="duration" class="input" placeholder="e.g. 391">
                    <p class="text-xs text-surface-500 mt-1">Total seconds</p>
                    @error('duration') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="label">Difficulty</label>
                    <select wire:model="difficulty" class="input">
                        <option value="">Select...</option>
                        @foreach($difficulties as $diff)
                            <option value="{{ $diff->value }}">{{ $diff->label() }}</option>
                        @endforeach
                    </select>
                    @error('difficulty') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="label">Tuning</label>
                    <input type="text" wire:model="tuning" class="input" placeholder="Standard">
                    @error('tuning') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="label">Capo</label>
                    <input type="number" wire:model="capo" class="input" placeholder="Fret #">
                    @error('capo') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="label">Notes / Arrangement</label>
                <textarea wire:model="notes" rows="4" class="input" placeholder="Guitar intro, bass enters on verse 2..."></textarea>
                @error('notes') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>

            <label class="flex items-center gap-2 cursor-pointer mt-4">
                <input type="checkbox" wire:model="is_favorite" class="w-5 h-5 rounded bg-surface-800 border-surface-600 text-primary-500 focus:ring-primary-500/20">
                <span class="text-white font-medium">Mark as Favorite</span>
            </label>
        </div>

        {{-- Audio Upload --}}
        <div class="card space-y-4">
            <h2 class="text-lg font-semibold text-white border-b border-surface-800 pb-2">Rehearsal Audio</h2>
            <div>
                <label class="label">Upload Audio File</label>
                <input type="file" wire:model="audioFile" accept="audio/*" class="input file:bg-surface-700 file:text-white file:border-0 file:rounded-lg file:px-4 file:py-2 file:mr-3 file:cursor-pointer">
                <p class="text-xs text-surface-500 mt-1">MP3, WAV, OGG, FLAC, AAC, M4A (max 100MB)</p>
                @error('audioFile') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                <div wire:loading wire:target="audioFile" class="text-primary-400 text-sm mt-1">Uploading...</div>
            </div>
            @if($song && $song->audio_path)
                <div class="flex items-center gap-2 text-sm text-surface-400">
                    <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                    Existing audio file: {{ basename($song->audio_path) }}
                </div>
            @endif
        </div>

        {{-- Tags --}}
        <div class="card space-y-4">
            <h2 class="text-lg font-semibold text-white border-b border-surface-800 pb-2">Tags</h2>
            <div class="flex flex-wrap gap-2">
                @foreach($allTags as $tag)
                    <label class="cursor-pointer">
                        <input type="checkbox" wire:model="selectedTags" value="{{ $tag['id'] }}" class="hidden peer">
                        <span class="badge badge-surface peer-checked:bg-primary-500/20 peer-checked:text-primary-400 peer-checked:border-primary-500/30">
                            {{ $tag['name'] }}
                        </span>
                    </label>
                @endforeach
            </div>
            @error('selectedTags') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Links --}}
        <div class="card space-y-4">
            <div class="flex items-center justify-between border-b border-surface-800 pb-2">
                <h2 class="text-lg font-semibold text-white">Links & Resources</h2>
                <button type="button" wire:click="addLink" class="btn-ghost btn-sm text-primary-400 hover:text-primary-300">
                    + Add Link
                </button>
            </div>
            
            <div class="space-y-4">
                @foreach($links as $index => $link)
                    <div class="flex flex-col sm:flex-row gap-3 items-start bg-surface-800/30 p-4 rounded-xl border border-surface-800">
                        <div class="w-full sm:w-1/4">
                            <select wire:model="links.{{ $index }}.type" class="input">
                                @foreach($linkTypes as $type)
                                    <option value="{{ $type->value }}">{{ $type->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="flex-1 w-full relative">
                            <input type="url" wire:model="links.{{ $index }}.url" class="input" placeholder="https://...">
                            @error('links.'.$index.'.url') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex-1 w-full relative">
                            <input type="text" wire:model="links.{{ $index }}.label" class="input" placeholder="Label (optional)">
                        </div>

                        <button type="button" wire:click="removeLink({{ $index }})" class="btn-ghost text-surface-500 hover:text-red-400 p-2 sm:mt-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                @endforeach
                
                @if(empty($links))
                    <p class="text-sm text-surface-500 text-center py-4">No links added. Add Spotify, YouTube, or tab links for reference.</p>
                @endif
            </div>
        </div>

        <div class="flex justify-end gap-3 pb-8">
            <a href="{{ $isEditing ? route('songs.show', $song) : route('songs.index') }}" class="btn-ghost" wire:navigate>Cancel</a>
            <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                <span wire:loading.remove>Save Song</span>
                <span wire:loading>Saving...</span>
            </button>
        </div>
    </form>
</div>
