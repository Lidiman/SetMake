<div class="space-y-6 animate-fade-in">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-white">History</h1>
            <p class="text-surface-400 mt-1">All completed and cancelled gigs and rehearsals.</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card p-4">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-surface-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input wire:model.live.debounce.300ms="search" type="text" class="input pl-10" placeholder="Search by title, venue, or location...">
                </div>
            </div>
            <select wire:model.live="typeFilter" class="input py-2 min-w-[120px]">
                <option value="">All Types</option>
                @foreach($types as $type)
                    <option value="{{ $type->value }}">{{ $type->label() }}</option>
                @endforeach
            </select>
            <select wire:model.live="statusFilter" class="input py-2 min-w-[120px]">
                <option value="">All Statuses</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->value }}">{{ $status->label() }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- History Table --}}
    <div class="card p-0 overflow-hidden">
        @if($schedules->count() > 0)
            <div class="table-container border-0 rounded-none">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="bg-surface-800/80 px-6 py-4 text-xs font-semibold text-surface-400 uppercase tracking-wider">Type</th>
                            <th class="bg-surface-800/80 px-6 py-4 text-xs font-semibold text-surface-400 uppercase tracking-wider">Title</th>
                            <th class="bg-surface-800/80 px-6 py-4 text-xs font-semibold text-surface-400 uppercase tracking-wider">Date</th>
                            <th class="bg-surface-800/80 px-6 py-4 text-xs font-semibold text-surface-400 uppercase tracking-wider">Location</th>
                            <th class="bg-surface-800/80 px-6 py-4 text-xs font-semibold text-surface-400 uppercase tracking-wider">Status</th>
                            <th class="bg-surface-800/80 px-6 py-4 text-xs font-semibold text-surface-400 uppercase tracking-wider text-right">Income</th>
                            <th class="bg-surface-800/80 px-6 py-4 text-xs font-semibold text-surface-400 uppercase tracking-wider text-right">Expenses</th>
                            <th class="bg-surface-800/80 px-6 py-4 text-xs font-semibold text-surface-400 uppercase tracking-wider text-right">Net</th>
                            <th class="bg-surface-800/80 px-6 py-4 text-xs font-semibold text-surface-400 uppercase tracking-wider text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-800/50">
                        @foreach($schedules as $schedule)
                            <tr class="hover:bg-surface-800/30 transition-colors {{ $schedule->trashed() ? 'opacity-60' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="badge badge-{{ $schedule->type->color() }} flex items-center gap-1 w-max">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $schedule->type->icon() }}"/></svg>
                                        {{ $schedule->type->label() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($schedule->trashed())
                                        <span class="text-surface-500 line-through">{{ $schedule->title }}</span>
                                    @else
                                        <a href="{{ route('schedules.show', $schedule) }}" class="font-bold text-white hover:text-primary-400 transition-colors" wire:navigate>
                                            {{ $schedule->title }}
                                        </a>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-white">{{ $schedule->date->format('M d, Y') }}</span>
                                        <span class="text-xs text-surface-500">{{ $schedule->date->format('D') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-surface-300">{{ $schedule->venue ?: ($schedule->location ?: '—') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="badge badge-{{ $schedule->status->color() }}">
                                        {{ $schedule->status->label() }}
                                        @if($schedule->trashed())
                                            (deleted)
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    @if($schedule->isGig())
                                        <span class="text-white">${{ number_format($schedule->gross_income, 2) }}</span>
                                    @else
                                        <span class="text-surface-500">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    @if($schedule->isGig() && $schedule->total_expenses > 0)
                                        <span class="text-red-400/80">-${{ number_format($schedule->total_expenses, 2) }}</span>
                                    @else
                                        <span class="text-surface-500">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    @if($schedule->isGig())
                                        <span class="font-medium {{ $schedule->net_income >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                            ${{ number_format($schedule->net_income, 2) }}
                                        </span>
                                    @else
                                        <span class="text-surface-500">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex justify-center gap-1">
                                        @if($schedule->trashed())
                                            <button wire:click="restore({{ $schedule->id }})" wire:confirm="Restore this schedule?" class="text-surface-500 hover:text-emerald-400 transition-colors p-1" title="Restore">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h4l3-3m0 0l3 3m-3-3v10a2 2 0 002 2h3m-6 0h6"/></svg>
                                            </button>
                                            <button wire:click="delete({{ $schedule->id }})" wire:confirm="Permanently delete this schedule from history? This cannot be undone." class="text-surface-500 hover:text-red-400 transition-colors p-1" title="Delete permanently">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        @else
                                            <a href="{{ route('schedules.show', $schedule) }}" class="text-surface-500 hover:text-white transition-colors p-1" title="View" wire:navigate>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.084 12s.001.04.003.117a1 1 0 000 .152c.028.325.069.532.126.667a.75.75 0 00.147.187l1.078 1.078a.75.75 0 00.187.147c.135.057.342.098.667.126.077.002.116.003.117.003s.04-.001.117-.003c.325-.029.532-.07.667-.126a.75.75 0 00.187-.147l1.078-1.078a.75.75 0 00.147-.187c.057-.135.098-.342.126-.667a1 1 0 000-.152c-.002-.077-.003-.116-.003-.117s.001-.04.003-.117a1 1 0 000-.152c-.029-.325-.07-.532-.126-.667a.75.75 0 00-.147-.187l-1.078-1.078a.75.75 0 00-.187-.147c-.135-.057-.342-.098-.667-.126a1 1 0 00-.117-.002z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-surface-800">
                {{ $schedules->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-16 h-16 rounded-full bg-surface-800 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-surface-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">No history found</h3>
                <p class="text-surface-400 mb-6">
                    @if($search || $typeFilter || $statusFilter)
                        No records match your filters. Try adjusting your search.
                    @else
                        No completed or cancelled schedules yet.
                    @endif
                </p>
                @if($search || $typeFilter || $statusFilter)
                    <button wire:click="$set('search', ''); $set('typeFilter', ''); $set('statusFilter', '')" class="btn-secondary">Clear Filters</button>
                @endif
            </div>
        @endif
    </div>
</div>
