<div class="space-y-6 animate-fade-in">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-white">Schedule</h1>
            <p class="text-surface-400 mt-1">Manage your band's rehearsals and gigs.</p>
        </div>
        <a href="{{ route('schedules.create') }}" class="btn-primary shrink-0" wire:navigate>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Schedule
        </a>
    </div>

    <div class="flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center bg-surface-900/50 p-4 rounded-xl border border-surface-800/50">
        <div class="flex gap-2 overflow-x-auto pb-2 sm:pb-0 w-full sm:w-auto">
            <button wire:click="$set('filter', 'upcoming')" class="btn {{ $filter === 'upcoming' ? 'btn-primary' : 'btn-secondary' }} whitespace-nowrap">Upcoming</button>
            <button wire:click="$set('filter', 'past')" class="btn {{ $filter === 'past' ? 'btn-primary' : 'btn-secondary' }} whitespace-nowrap">Past</button>
        </div>
        
        <div class="flex gap-3 w-full sm:w-auto">
            <select wire:model.live="typeFilter" class="input py-2">
                <option value="">All Types</option>
                @foreach($types as $type)
                    <option value="{{ $type->value }}">{{ $type->label() }}</option>
                @endforeach
            </select>
            
            <select wire:model.live="statusFilter" class="input py-2">
                <option value="">All Statuses</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->value }}">{{ $status->label() }}</option>
                @endforeach
            </select>
        </div>
    </div>

    @if($schedules->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach($schedules as $schedule)
                <a href="{{ route('schedules.show', $schedule) }}" class="card group hover:border-{{ $schedule->type->color() }}-500/50 block" wire:navigate>
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex gap-2">
                            <span class="badge badge-{{ $schedule->type->color() }} flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $schedule->type->icon() }}"/></svg>
                                {{ $schedule->type->label() }}
                            </span>
                            <span class="badge badge-{{ $schedule->status->color() }}">{{ $schedule->status->label() }}</span>
                        </div>
                        <span class="text-sm text-surface-400">{{ $schedule->date->format('D, M j') }}</span>
                    </div>
                    <h3 class="text-xl font-bold text-white group-hover:text-{{ $schedule->type->color() }}-400 transition-colors truncate mb-2">{{ $schedule->title }}</h3>
                    
                    <p class="text-sm text-surface-400 flex items-center gap-1.5 mb-1">
                        <svg class="w-4 h-4 text-surface-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $schedule->start_time?->format('g:i A') ?: '--' }} {{ $schedule->end_time ? '- ' . $schedule->end_time->format('g:i A') : '' }}
                    </p>
                    
                    @if($schedule->isGig() && $schedule->venue)
                        <p class="text-sm text-surface-400 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-surface-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $schedule->venue }}
                        </p>
                    @elseif($schedule->isRehearsal() && $schedule->location)
                        <p class="text-sm text-surface-400 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-surface-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $schedule->location }}
                        </p>
                    @endif
                    
                    <div class="mt-4 pt-4 border-t border-surface-800/50 flex items-center justify-between text-sm text-surface-400">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $schedule->members->count() }} members
                        </span>
                        @if($schedule->setlist_id)
                            <span class="badge badge-surface flex gap-1 items-center">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                                Setlist Attached
                            </span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
        <div class="mt-6">{{ $schedules->links() }}</div>
    @else
        <div class="card p-12 text-center">
            <div class="w-16 h-16 rounded-full bg-surface-800 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-surface-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">No schedules found</h3>
            <p class="text-surface-400 mb-6">Create a rehearsal or gig to get started.</p>
            <a href="{{ route('schedules.create') }}" class="btn-primary" wire:navigate>Add Schedule</a>
        </div>
    @endif
</div>
