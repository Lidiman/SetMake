<div class="max-w-4xl mx-auto space-y-6 animate-fade-in">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <a href="{{ route('rehearsals.index') }}" class="text-surface-400 hover:text-white flex items-center gap-2 transition-colors" wire:navigate>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Rehearsals
        </a>
        <div class="flex items-center gap-3">
            <a href="{{ route('rehearsals.edit', $rehearsal) }}" class="btn-secondary" wire:navigate>Edit</a>
            <button wire:confirm="Delete this rehearsal?" wire:click="delete" class="btn-danger">Delete</button>
        </div>
    </div>

    <div class="card relative overflow-hidden">
        <div class="absolute -top-24 -right-24 w-64 h-64 rounded-full blur-3xl opacity-20 pointer-events-none bg-blue-500"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-4">
                <span class="badge badge-blue">Rehearsal</span>
                @if($rehearsal->date->isFuture())
                    <span class="badge badge-emerald">Upcoming</span>
                @else
                    <span class="badge badge-surface">Past</span>
                @endif
            </div>
            <h1 class="text-3xl font-bold text-white">{{ $rehearsal->title }}</h1>
            @if($rehearsal->description)
                <p class="text-surface-300 mt-2">{{ $rehearsal->description }}</p>
            @endif
            <div class="flex flex-wrap gap-6 mt-4">
                <div class="flex items-center gap-2 text-surface-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="font-medium text-white">{{ $rehearsal->date->format('l, F j, Y') }}</span>
                </div>
                @if($rehearsal->start_time)
                    <div class="flex items-center gap-2 text-surface-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="font-medium text-white">{{ $rehearsal->start_time->format('g:i A') }} @if($rehearsal->end_time) - {{ $rehearsal->end_time->format('g:i A') }} @endif</span>
                    </div>
                @endif
                @if($rehearsal->location)
                    <div class="flex items-center gap-2 text-surface-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span class="font-medium text-white">{{ $rehearsal->location }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Members --}}
        <div class="card">
            <h2 class="text-lg font-semibold text-white mb-4">Members ({{ $rehearsal->members->count() }})</h2>
            @if($rehearsal->members->count() > 0)
                <div class="space-y-2">
                    @foreach($rehearsal->members as $member)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-surface-800/30 border border-surface-800">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-500 to-emerald-600 flex items-center justify-center text-xs font-bold text-white">
                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-white">{{ $member->name }}</p>
                                    <p class="text-xs text-surface-500">{{ $member->pivot->status ?? 'Invited' }}</p>
                                </div>
                            </div>
                            <button wire:click="toggleMemberStatus({{ $member->id }})" class="btn-sm {{ $member->pivot->status === 'available' ? 'badge-emerald' : ($member->pivot->status === 'busy' ? 'badge-red' : 'badge-surface') }}">
                                {{ $member->pivot->status === 'available' ? 'Available' : ($member->pivot->status === 'busy' ? 'Busy' : 'Maybe') }}
                            </button>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-surface-500 text-sm">No members assigned yet.</p>
            @endif
        </div>

        {{-- Setlist --}}
        <div class="card">
            <h2 class="text-lg font-semibold text-white mb-4">Setlist</h2>
            @if($rehearsal->setlist)
                <a href="{{ route('setlists.show', $rehearsal->setlist) }}" class="flex items-center justify-between p-4 rounded-xl bg-surface-800/30 border border-surface-800 hover:border-primary-500/30 transition-colors group" wire:navigate>
                    <div>
                        <p class="font-medium text-white group-hover:text-primary-400 transition-colors">{{ $rehearsal->setlist->title }}</p>
                        <p class="text-sm text-surface-400">{{ $rehearsal->setlist->songs_count ?? 0 }} songs</p>
                    </div>
                    <svg class="w-5 h-5 text-surface-500 group-hover:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            @else
                <p class="text-surface-500 text-sm">No setlist assigned.</p>
            @endif
        </div>
    </div>

    {{-- Checklist --}}
    <div class="card">
        <div class="flex items-center justify-between border-b border-surface-800 pb-3 mb-4">
            <h2 class="text-lg font-semibold text-white">Checklist</h2>
            @if($rehearsal->checklists->count() > 0)
                <span class="badge badge-primary">{{ $rehearsal->checklists->where('is_completed', true)->count() }}/{{ $rehearsal->checklists->count() }}</span>
            @endif
        </div>

        <form wire:submit="addTask" class="flex gap-3 mb-6">
            <input type="text" wire:model="newTask" class="input flex-1" placeholder="Add a task...">
            <button type="submit" class="btn-primary btn-sm">Add</button>
        </form>

        @if($rehearsal->checklists->count() > 0)
            <div class="space-y-2">
                @foreach($rehearsal->checklists as $checklist)
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-surface-800/30 hover:bg-surface-800/50 border border-surface-800 transition-colors group">
                        <button wire:click="toggleTask({{ $checklist->id }})" class="shrink-0">
                            @if($checklist->is_completed)
                                <div class="w-6 h-6 rounded-lg bg-emerald-500 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            @else
                                <div class="w-6 h-6 rounded-lg border-2 border-surface-600 hover:border-primary-500 transition-colors"></div>
                            @endif
                        </button>
                        <span class="flex-1 text-sm {{ $checklist->is_completed ? 'line-through text-surface-500' : 'text-white' }}">{{ $checklist->task }}</span>
                        <button wire:click="deleteTask({{ $checklist->id }})" class="opacity-0 group-hover:opacity-100 text-surface-500 hover:text-red-400 transition-all p-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-surface-500 text-sm text-center py-4">No tasks yet. Add your first checklist item above.</p>
        @endif
    </div>
</div>
