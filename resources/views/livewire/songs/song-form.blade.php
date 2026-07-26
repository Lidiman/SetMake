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
