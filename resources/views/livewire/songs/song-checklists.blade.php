<div class="card space-y-4">
    <div class="flex items-center justify-between border-b border-surface-800 pb-3">
        <h2 class="text-lg font-semibold text-white">Rehearsal Checklist</h2>
        @if($checklists->count() > 0)
            <span class="badge-primary">{{ $checklists->where('is_completed', true)->count() }}/{{ $checklists->count() }}</span>
        @endif
    </div>

    <form wire:submit="addTask" class="flex gap-3">
        <input type="text" wire:model="newTask" class="input flex-1" placeholder="Add checklist item...">
        <button type="submit" class="btn-primary btn-sm">Add</button>
    </form>

    @if($checklists->count() > 0)
        <div class="space-y-2">
            @foreach($checklists as $checklist)
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
        <p class="text-surface-500 text-sm text-center py-4">No checklist items. Add tasks like "Intro memorized" or "Solo practiced".</p>
    @endif
</div>
