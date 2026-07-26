<div class="space-y-6 animate-fade-in">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-white">Performance History</h1>
            <p class="text-surface-400 mt-1">A log of every time you've played a song live or in rehearsal.</p>
        </div>
    </div>

    {{-- Search --}}
    <div class="card p-4">
        <div class="relative w-full max-w-md">
            <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-surface-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input wire:model.live.debounce.300ms="search" type="text" class="input pl-10" placeholder="Search by song, venue, or setlist...">
        </div>
    </div>

    {{-- List --}}
    <div class="card p-0 overflow-hidden">
        @if($performances->count() > 0)
            <div class="table-container border-0 rounded-none">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="bg-surface-800/80 px-6 py-4 text-xs font-semibold text-surface-400 uppercase tracking-wider">Date</th>
                            <th class="bg-surface-800/80 px-6 py-4 text-xs font-semibold text-surface-400 uppercase tracking-wider">Song</th>
                            <th class="bg-surface-800/80 px-6 py-4 text-xs font-semibold text-surface-400 uppercase tracking-wider hidden md:table-cell">Context</th>
                            <th class="bg-surface-800/80 px-6 py-4 text-xs font-semibold text-surface-400 uppercase tracking-wider hidden lg:table-cell">Venue</th>
                            <th class="bg-surface-800/80 px-6 py-4 text-xs font-semibold text-surface-400 uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-800/50">
                        @foreach($performances as $performance)
                            <tr class="hover:bg-surface-800/30 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-white">{{ $performance->performed_at->format('M d, Y') }}</span>
                                        <span class="text-xs text-surface-500">{{ $performance->performed_at->diffForHumans() }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($performance->song)
                                        <a href="{{ route('songs.show', $performance->song) }}" class="font-bold text-white hover:text-primary-400 transition-colors" wire:navigate>
                                            {{ $performance->song->title }}
                                        </a>
                                        <p class="text-xs text-surface-400">{{ $performance->song->artist }}</p>
                                    @else
                                        <span class="text-surface-500 italic">Song Deleted</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 hidden md:table-cell">
                                    @if($performance->setlist)
                                        <a href="{{ route('setlists.show', $performance->setlist) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-surface-800 text-sm text-surface-300 hover:text-primary-400 hover:bg-surface-700 transition-colors" wire:navigate>
                                            <span class="w-1.5 h-1.5 rounded-full bg-{{ $performance->setlist->type->color() }}-400"></span>
                                            {{ $performance->setlist->title }}
                                        </a>
                                    @else
                                        <span class="text-sm text-surface-500">Manual Entry</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 hidden lg:table-cell">
                                    @if($performance->venue)
                                        <span class="text-sm text-surface-300">{{ $performance->venue }}</span>
                                    @else
                                        <span class="text-sm text-surface-600">--</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    @can('delete', $performance)
                                        <button wire:confirm="Are you sure you want to delete this performance record?" wire:click="delete({{ $performance->id }})" class="text-surface-500 hover:text-red-400 transition-colors p-2" title="Delete record">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="p-4 border-t border-surface-800">
                {{ $performances->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-16 h-16 rounded-full bg-surface-800 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-surface-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">No performance history</h3>
                <p class="text-surface-400 mb-6">
                    @if($search)
                        No records match your search.
                    @else
                        Go to a past setlist to log performances for all its songs.
                    @endif
                </p>
                @if($search)
                    <button wire:click="$set('search', '')" class="btn-secondary">Clear Search</button>
                @endif
            </div>
        @endif
    </div>
</div>
