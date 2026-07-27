<div class="max-w-5xl mx-auto space-y-6 animate-fade-in">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <a href="{{ route('gigs.index') }}" class="text-surface-400 hover:text-white flex items-center gap-2 transition-colors" wire:navigate>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Gigs
        </a>
        <div class="flex items-center gap-3">
            @if($gig->setlist && $gig->setlist->songs->count() > 0 && $gig->status->value !== 'cancelled')
                <a href="{{ route('gig-mode', $gig) }}" class="btn-primary" wire:navigate>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Gig Mode
                </a>
            @endif
            <a href="{{ route('gigs.edit', $gig) }}" class="btn-secondary" wire:navigate>Edit</a>
            <button wire:confirm="Delete this gig?" wire:click="delete" class="btn-danger">Delete</button>
        </div>
    </div>

    <div class="card relative overflow-hidden">
        <div class="absolute -top-24 -right-24 w-64 h-64 rounded-full blur-3xl opacity-20 pointer-events-none bg-purple-500"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-4">
                <span class="badge badge-{{ $gig->status->color() }}">{{ $gig->status->label() }}</span>
                @if($gig->date->isFuture())
                    <span class="badge badge-emerald">Upcoming</span>
                @else
                    <span class="badge badge-surface">Past</span>
                @endif
            </div>
            <h1 class="text-3xl font-bold text-white">{{ $gig->title }}</h1>
            <p class="text-xl text-surface-300 mt-1">{{ $gig->venue }}</p>
            @if($gig->address)
                <p class="text-surface-400 text-sm">{{ $gig->address }}</p>
            @endif
            @if($gig->description)
                <p class="text-surface-300 mt-3">{{ $gig->description }}</p>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="stat-card"><div class="stat-icon bg-primary-500/15"><svg class="w-6 h-6 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div><div><div class="stat-value">{{ $gig->date->format('M d') }}</div><div class="stat-label">Date</div></div></div>
        <div class="stat-card"><div class="stat-icon bg-emerald-500/15"><svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div><div><div class="stat-value">Rp{{ number_format($gig->gross_income, 0) }}</div><div class="stat-label">Gross Income</div></div></div>
        <div class="stat-card"><div class="stat-icon bg-red-500/15"><svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg></div><div><div class="stat-value">Rp{{ number_format($gig->total_expenses, 0) }}</div><div class="stat-label">Expenses</div></div></div>
        <div class="stat-card"><div class="stat-icon bg-amber-500/15"><svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div><div><div><div class="stat-value">Rp{{ number_format($gig->net_income, 0) }}</div><div class="stat-label">Net Income</div></div></div></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="card">
            <h2 class="text-lg font-semibold text-white mb-4">Details</h2>
            <div class="space-y-3">
                @if($gig->start_time)<div class="flex justify-between"><span class="text-surface-400">Start Time</span><span class="text-white font-medium">{{ $gig->start_time->format('g:i A') }}</span></div>@endif
                @if($gig->end_time)<div class="flex justify-between"><span class="text-surface-400">End Time</span><span class="text-white font-medium">{{ $gig->end_time->format('g:i A') }}</span></div>@endif
                @if($gig->contact_person)<div class="flex justify-between"><span class="text-surface-400">Contact</span><span class="text-white font-medium">{{ $gig->contact_person }}</span></div>@endif
                @if($gig->phone)<div class="flex justify-between"><span class="text-surface-400">Phone</span><span class="text-white font-medium">{{ $gig->phone }}</span></div>@endif
                <div class="flex justify-between"><span class="text-surface-400">Payment</span><span class="text-white font-medium">Rp{{ number_format($gig->payment, 2) }}</span></div>
                <div class="flex justify-between"><span class="text-surface-400">Tips</span><span class="text-white font-medium">Rp{{ number_format($gig->tips, 2) }}</span></div>
            </div>
        </div>

        <div class="card">
            <h2 class="text-lg font-semibold text-white mb-4">Setlist</h2>
            @if($gig->setlist)
                <a href="{{ route('setlists.show', $gig->setlist) }}" class="flex items-center justify-between p-4 rounded-xl bg-surface-800/30 border border-surface-800 hover:border-purple-500/30 transition-colors group" wire:navigate>
                    <div>
                        <p class="font-medium text-white group-hover:text-purple-400 transition-colors">{{ $gig->setlist->title }}</p>
                        <p class="text-sm text-surface-400">{{ $gig->setlist->songs_count ?? 0 }} songs · {{ $gig->setlist->formatted_total_duration }}</p>
                    </div>
                    <svg class="w-5 h-5 text-surface-500 group-hover:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            @else
                <p class="text-surface-500 text-sm">No setlist assigned.</p>
            @endif
        </div>

        <div class="card">
            <h2 class="text-lg font-semibold text-white mb-4">Members ({{ $gig->members->count() }})</h2>
            @if($gig->members->count() > 0)
                <div class="space-y-2">
                    @foreach($gig->members as $member)
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-surface-800/30 border border-surface-800">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center text-xs font-bold text-white">{{ strtoupper(substr($member->name, 0, 1)) }}</div>
                            <p class="text-sm font-medium text-white">{{ $member->name }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-surface-500 text-sm">No members assigned.</p>
            @endif
        </div>

        {{-- Audience Requests --}}
        <div class="card">
            <div class="flex items-center justify-between border-b border-surface-800 pb-3 mb-4">
                <h2 class="text-lg font-semibold text-white">Audience Requests</h2>
                <span class="badge badge-surface">{{ $gig->requests->count() }}</span>
            </div>
            <form wire:submit="addRequest" class="flex gap-3 mb-4">
                <input type="text" wire:model="requestSong" class="input flex-1" placeholder="Song name...">
                <button type="submit" class="btn-primary btn-sm">Add</button>
            </form>
            @if($gig->requests->count() > 0)
                <div class="space-y-2">
                    @foreach($gig->requests as $request)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-surface-800/30 border border-surface-800">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-white">{{ $request->song_name }}</p>
                                @if($request->requested_by)
                                    <p class="text-xs text-surface-400">by {{ $request->requested_by }} · {{ $request->quantity }}×</p>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                <button wire:click="togglePerformed({{ $request->id }})" class="btn-sm {{ $request->is_performed ? 'badge-emerald' : 'badge-surface' }}">{{ $request->is_performed ? 'Done' : 'Mark' }}</button>
                                <button wire:click="deleteRequest({{ $request->id }})" class="text-surface-500 hover:text-red-400 p-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-surface-500 text-sm text-center py-4">No requests yet.</p>
            @endif
        </div>
    </div>
</div>
