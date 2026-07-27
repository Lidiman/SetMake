<div class="max-w-3xl mx-auto space-y-6 animate-fade-in">
    <div class="flex items-center justify-between">
        <a href="{{ $isEditing ? route('rehearsals.show', $rehearsal) : route('rehearsals.index') }}" class="text-surface-400 hover:text-white flex items-center gap-2 transition-colors" wire:navigate>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back
        </a>
    </div>

    <div>
        <h1 class="text-3xl font-bold text-white">{{ $isEditing ? 'Edit Rehearsal' : 'Schedule Rehearsal' }}</h1>
        <p class="text-surface-400 mt-1">Plan your band's practice session.</p>
    </div>

    <form wire:submit="save" class="space-y-6">
        <div class="card space-y-5">
            <h2 class="text-lg font-semibold text-white border-b border-surface-800 pb-2">Rehearsal Details</h2>

            <div>
                <label class="label">Title *</label>
                <input type="text" wire:model="title" class="input" placeholder="e.g. Thursday Night Practice">
                @error('title') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="label">Date *</label>
                    <input type="date" wire:model="date" class="input">
                    @error('date') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="label">Start Time</label>
                    <input type="time" wire:model="start_time" class="input">
                </div>
                <div>
                    <label class="label">End Time</label>
                    <input type="time" wire:model="end_time" class="input">
                </div>
            </div>

            <div>
                <label class="label">Location</label>
                <input type="text" wire:model="location" class="input" placeholder="e.g. The Garage Studio">
                @error('location') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="label">Description / Goals</label>
                <textarea wire:model="description" rows="3" class="input" placeholder="What should the band focus on?"></textarea>
                @error('description') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="label">Setlist</label>
                <select wire:model="setlist_id" class="input">
                    <option value="">None (create later)</option>
                    @foreach($setlists as $setlist)
                        <option value="{{ $setlist->id }}">{{ $setlist->title }} ({{ $setlist->type->label() }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="card space-y-4">
            <h2 class="text-lg font-semibold text-white border-b border-surface-800 pb-2">Members</h2>
            @if($members->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($members as $member)
                        <label class="flex items-center gap-3 p-3 rounded-xl bg-surface-800/30 hover:bg-surface-800/50 border border-surface-800 cursor-pointer transition-colors">
                            <input type="checkbox" wire:model="selectedMembers" value="{{ $member->id }}" class="w-5 h-5 rounded bg-surface-800 border-surface-600 text-primary-500 focus:ring-primary-500/20">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-500 to-emerald-600 flex items-center justify-center text-xs font-bold text-white">
                                {{ strtoupper(substr($member->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-white">{{ $member->name }}</p>
                                <p class="text-xs text-surface-500">{{ $member->role->label() }}</p>
                            </div>
                        </label>
                    @endforeach
                </div>
            @else
                <p class="text-surface-500 text-sm">No members available.</p>
            @endif
        </div>

        <div class="flex justify-end gap-3 pb-8">
            <a href="{{ $isEditing ? route('rehearsals.show', $rehearsal) : route('rehearsals.index') }}" class="btn-ghost" wire:navigate>Cancel</a>
            <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ $isEditing ? 'Save Changes' : 'Create Rehearsal' }}</span>
                <span wire:loading>Saving...</span>
            </button>
        </div>
    </form>
</div>
