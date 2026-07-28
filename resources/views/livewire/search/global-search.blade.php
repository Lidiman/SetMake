<div>
    {{-- Trigger button --}}
    <button wire:click="toggle" class="flex items-center gap-2 px-3 py-2 rounded-xl bg-surface-800/50 border border-surface-700/50 text-surface-400 hover:text-surface-200 hover:border-surface-600 transition-all text-sm w-full max-w-xl">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <span class="flex-1 text-left">Search...</span>
        <kbd class="hidden sm:inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md bg-surface-700 text-xs text-surface-400 font-mono">⌘K</kbd>
    </button>

    {{-- Search modal overlay --}}
    @if($show)
        <div class="fixed inset-0 z-50 flex items-start justify-center pt-[15vh]" @keydown.escape.window="close" x-data @keydown.cmd-k.window="$wire.toggle()" @keydown.ctrl-k.window="$wire.toggle()">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="close"></div>
            <div class="relative w-full max-w-xl bg-surface-900 border border-surface-700 rounded-2xl shadow-2xl overflow-hidden">
                <div class="flex items-center gap-3 px-4 py-3 border-b border-surface-800">
                    <svg class="w-5 h-5 text-surface-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input wire:model.live="query" type="text" class="flex-1 bg-transparent border-0 text-white placeholder-surface-500 focus:outline-none text-lg" placeholder="Search songs, artists, venues, setlists..." autofocus>
                    <button wire:click="close" class="text-surface-500 hover:text-surface-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                @if(strlen($query) >= 2)
                    <div class="max-h-96 overflow-y-auto p-2 space-y-1">
                        @if(isset($results['songs']) && $results['songs']->count() > 0)
                            <p class="px-3 pt-2 pb-1 text-xs font-semibold text-surface-500 uppercase tracking-wider">Songs</p>
                            @foreach($results['songs'] as $song)
                                <a href="{{ route('songs.show', $song) }}" wire:navigate @click="close" class="flex items-center gap-3 px-3 py-3 rounded-xl hover:bg-surface-800 transition-colors">
                                    <svg class="w-5 h-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2z"/></svg>
                                    <div class="flex-1 min-w-0"><p class="text-sm font-medium text-white truncate">{{ $song->title }}</p><p class="text-xs text-surface-400 truncate">{{ $song->artist }}</p></div>
                                </a>
                            @endforeach
                        @endif
                        @if(isset($results['setlists']) && $results['setlists']->count() > 0)
                            <p class="px-3 pt-2 pb-1 text-xs font-semibold text-surface-500 uppercase tracking-wider">Setlists</p>
                            @foreach($results['setlists'] as $setlist)
                                <a href="{{ route('setlists.show', $setlist) }}" wire:navigate @click="close" class="flex items-center gap-3 px-3 py-3 rounded-xl hover:bg-surface-800 transition-colors">
                                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                                    <div class="flex-1 min-w-0"><p class="text-sm font-medium text-white truncate">{{ $setlist->title }}</p><p class="text-xs text-surface-400 truncate">{{ $setlist->type->label() }}</p></div>
                                </a>
                            @endforeach
                        @endif
                        @if(isset($results['gigs']) && $results['gigs']->count() > 0)
                            <p class="px-3 pt-2 pb-1 text-xs font-semibold text-surface-500 uppercase tracking-wider">Gigs</p>
                            @foreach($results['gigs'] as $gig)
                                <a href="{{ route('gigs.show', $gig) }}" wire:navigate @click="close" class="flex items-center gap-3 px-3 py-3 rounded-xl hover:bg-surface-800 transition-colors">
                                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <div class="flex-1 min-w-0"><p class="text-sm font-medium text-white truncate">{{ $gig->title }}</p><p class="text-xs text-surface-400 truncate">{{ $gig->venue }}</p></div>
                                </a>
                            @endforeach
                        @endif
                        @if(isset($results['rehearsals']) && $results['rehearsals']->count() > 0)
                            <p class="px-3 pt-2 pb-1 text-xs font-semibold text-surface-500 uppercase tracking-wider">Rehearsals</p>
                            @foreach($results['rehearsals'] as $rehearsal)
                                <a href="{{ route('rehearsals.show', $rehearsal) }}" wire:navigate @click="close" class="flex items-center gap-3 px-3 py-3 rounded-xl hover:bg-surface-800 transition-colors">
                                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <div class="flex-1 min-w-0"><p class="text-sm font-medium text-white truncate">{{ $rehearsal->title }}</p><p class="text-xs text-surface-400 truncate">{{ $rehearsal->date->format('M d') }}</p></div>
                                </a>
                            @endforeach
                        @endif
                        @if(isset($results['members']) && $results['members']->count() > 0)
                            <p class="px-3 pt-2 pb-1 text-xs font-semibold text-surface-500 uppercase tracking-wider">Members</p>
                            @foreach($results['members'] as $member)
                                <div class="flex items-center gap-3 px-3 py-3 rounded-xl">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-500 to-emerald-600 flex items-center justify-center text-xs font-bold text-white">{{ strtoupper(substr($member->name, 0, 1)) }}</div>
                                    <div class="flex-1 min-w-0"><p class="text-sm font-medium text-white truncate">{{ $member->name }}</p><p class="text-xs text-surface-400 truncate">{{ $member->role->label() }}</p></div>
                                </div>
                            @endforeach
                        @endif
                        @php $hasResults = collect($results)->flatten()->count() > 0; @endphp
                        @if(!$hasResults)
                            <div class="p-8 text-center text-surface-500">No results found for "{{ $query }}"</div>
                        @endif
                    </div>
                @else
                    <div class="p-8 text-center text-surface-500">Start typing to search across songs, setlists, gigs, and more.</div>
                @endif
            </div>
        </div>
    @endif
</div>
