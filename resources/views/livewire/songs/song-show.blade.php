<div class="max-w-4xl mx-auto space-y-6 animate-fade-in">
    {{-- Navigation & Actions --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('songs.index') }}" class="text-surface-400 hover:text-white flex items-center gap-2 transition-colors" wire:navigate>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Library
        </a>

        <div class="flex items-center gap-3">
            <button wire:click="toggleFavorite" class="btn-ghost !p-2" title="{{ $song->is_favorite ? 'Remove from Favorites' : 'Add to Favorites' }}">
                <svg class="w-6 h-6 {{ $song->is_favorite ? 'text-amber-400' : 'text-surface-400' }}" fill="{{ $song->is_favorite ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            </button>
            @can('update', $song)
                <a href="{{ route('songs.edit', $song) }}" class="btn-secondary" wire:navigate>Edit</a>
            @endcan
            @can('delete', $song)
                <button wire:confirm="Are you sure you want to delete this song?" wire:click="delete" class="btn-danger">Delete</button>
            @endcan
        </div>
    </div>

    {{-- Main Info Header --}}
    <div class="card bg-gradient-to-br from-surface-900 to-surface-800">
        <div class="flex flex-col md:flex-row gap-6 items-start">
            <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-2xl bg-gradient-to-br from-primary-500/20 to-emerald-600/20 border border-primary-500/30 flex items-center justify-center shrink-0">
                <svg class="w-12 h-12 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
            </div>
            
            <div class="flex-1 space-y-4 w-full">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">{{ $song->title }}</h1>
                    <p class="text-xl text-surface-400 mt-1">{{ $song->artist ?: 'Unknown Artist' }}</p>
                </div>

                <div class="flex flex-wrap gap-2">
                    @if($song->genre)
                        <span class="badge badge-surface">{{ $song->genre }}</span>
                    @endif
                    @foreach($song->tags as $tag)
                        <span class="badge badge-surface">{{ $tag->name }}</span>
                    @endforeach
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-4 border-t border-surface-700/50">
                    <div>
                        <p class="text-xs text-surface-500 uppercase tracking-wider">Key</p>
                        <p class="font-medium text-white">{{ $song->key ?: '--' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-surface-500 uppercase tracking-wider">BPM</p>
                        <p class="font-medium text-white">{{ $song->bpm ?: '--' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-surface-500 uppercase tracking-wider">Duration</p>
                        <p class="font-medium text-white">{{ $song->formatted_duration }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-surface-500 uppercase tracking-wider">Difficulty</p>
                        @if($song->difficulty)
                            <span class="badge badge-{{ $song->difficulty->color() }} mt-1">{{ $song->difficulty->label() }}</span>
                        @else
                            <p class="font-medium text-surface-400">--</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 space-y-6">
            {{-- Performance Details --}}
            <div class="card space-y-4">
                <h2 class="text-lg font-semibold text-white">Performance Details</h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-surface-800/50 rounded-xl p-4 border border-surface-700/50">
                        <p class="text-sm text-surface-400 mb-1">Tuning</p>
                        <p class="font-medium text-white">{{ $song->tuning ?: 'Standard' }}</p>
                    </div>
                    <div class="bg-surface-800/50 rounded-xl p-4 border border-surface-700/50">
                        <p class="text-sm text-surface-400 mb-1">Capo</p>
                        <p class="font-medium text-white">{{ $song->capo !== null ? 'Fret ' . $song->capo : 'None' }}</p>
                    </div>
                </div>

                @if($song->notes)
                    <div class="bg-surface-800/50 rounded-xl p-4 border border-surface-700/50">
                        <p class="text-sm text-surface-400 mb-2">Notes & Arrangement</p>
                        <p class="text-surface-200 whitespace-pre-wrap">{{ $song->notes }}</p>
                    </div>
                @endif
            </div>

            {{-- Embedded Audio Players --}}
            @php
                $spotifyLink = $song->links->firstWhere('type', \App\Enums\LinkType::Spotify);
                $youtubeLink = $song->links->firstWhere('type', \App\Enums\LinkType::YouTube);
            @endphp
            
            @if($spotifyLink?->spotify_embed_url || $youtubeLink?->youtube_embed_url || $song->audio_path)
                <div class="card space-y-4">
                    <h2 class="text-lg font-semibold text-white">Audio & Reference</h2>
                    
                    @if($spotifyLink?->spotify_embed_url)
                        <div class="rounded-xl overflow-hidden bg-surface-800">
                            <iframe src="{{ $spotifyLink->spotify_embed_url }}" width="100%" height="152" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>
                        </div>
                    @endif
                    
                    @if($youtubeLink?->youtube_embed_url)
                        <div class="rounded-xl overflow-hidden aspect-video bg-black relative">
                            <iframe class="absolute inset-0 w-full h-full" src="{{ $youtubeLink->youtube_embed_url }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    @endif
                    
                    @if($song->audio_path)
                        <div class="audio-player">
                            <p class="text-sm text-surface-400 mb-2">Rehearsal Track</p>
                            <audio controls class="w-full">
                                <source src="{{ Storage::url($song->audio_path) }}" type="audio/mpeg">
                                Your browser does not support the audio element.
                            </audio>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <div class="space-y-6">
            {{-- Links --}}
            @if($song->links->isNotEmpty())
                <div class="card">
                    <h2 class="text-lg font-semibold text-white mb-4">Links & Resources</h2>
                    <div class="space-y-2">
                        @foreach($song->links as $link)
                            <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-3 p-3 rounded-xl hover:bg-surface-800/50 border border-transparent hover:border-surface-700/50 transition-all group">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-surface-800 group-hover:bg-surface-700 transition-colors" style="color: {{ $link->type->color() }}">
                                    @if($link->type === \App\Enums\LinkType::Spotify)
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.66 0 12 0zm5.521 17.34c-.24.359-.66.48-1.021.24-2.82-1.74-6.36-2.101-10.561-1.141-.418.122-.779-.179-.899-.539-.12-.421.18-.78.54-.9 4.56-1.021 8.52-.6 11.64 1.32.42.18.479.659.24 1.02zm1.44-3.3c-.301.42-.841.6-1.262.3-3.239-1.98-8.159-2.58-11.939-1.38-.479.12-1.02-.12-1.14-.6-.12-.48.12-1.021.6-1.141C9.6 9.9 15.001 10.62 18.6 12.84c.361.181.54.78.361 1.2zm.12-3.36C15.24 8.4 8.82 8.16 5.16 9.301c-.6.179-1.2-.181-1.38-.721-.18-.6.18-1.2.72-1.38 4.26-1.26 11.28-1.02 15.721 1.621.539.3.719 1.02.419 1.56-.299.421-1.02.599-1.559.3z"/></svg>
                                    @elseif($link->type === \App\Enums\LinkType::YouTube)
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-white group-hover:text-primary-400 transition-colors">{{ $link->label ?: $link->type->label() }}</p>
                                    <p class="text-xs text-surface-500 truncate">{{ $link->url }}</p>
                                </div>
                                <svg class="w-4 h-4 text-surface-600 group-hover:text-primary-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Performance History --}}
            <div class="card">
                <h2 class="text-lg font-semibold text-white mb-4 flex items-center justify-between">
                    Performance History
                    <span class="badge badge-surface">{{ $song->performances_count }}</span>
                </h2>
                
                @if($song->performances->count() > 0)
                    <div class="space-y-4">
                        @foreach($song->performances->take(5) as $performance)
                            <div class="relative pl-6 border-l-2 border-surface-800 pb-4 last:pb-0">
                                <div class="absolute -left-[5px] top-1.5 w-2 h-2 rounded-full bg-primary-500"></div>
                                <p class="text-sm font-medium text-white">
                                    {{ $performance->performed_at->format('M d, Y') }}
                                </p>
                                <p class="text-xs text-surface-400 mt-1">
                                    @if($performance->setlist)
                                        <a href="{{ route('setlists.show', $performance->setlist) }}" class="hover:text-primary-400 transition-colors" wire:navigate>
                                            {{ $performance->setlist->title }}
                                        </a>
                                    @else
                                        {{ $performance->venue ?: 'Unknown Venue' }}
                                    @endif
                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-surface-500 text-center py-4">No performance history yet.</p>
                @endif
            </div>
            
            <div class="text-xs text-surface-500 text-center mt-4">
                Added by {{ $song->creator->name }} on {{ $song->created_at->format('M d, Y') }}
            </div>
        </div>
    </div>
</div>
