<div class="space-y-6 animate-fade-in pb-12">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('schedules.index') }}" class="w-10 h-10 rounded-xl bg-surface-800 flex items-center justify-center text-surface-400 hover:text-white hover:bg-surface-700 transition-colors" wire:navigate>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="badge badge-{{ $schedule->type->color() }} flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $schedule->type->icon() }}"/></svg>
                        {{ $schedule->type->label() }}
                    </span>
                    <span class="badge badge-{{ $schedule->status->color() }}">{{ $schedule->status->label() }}</span>
                </div>
                <h1 class="text-3xl font-bold text-white">{{ $schedule->title }}</h1>
            </div>
        </div>
        
        <div class="flex gap-2">
            <a href="{{ route('schedules.edit', $schedule) }}" class="btn-secondary" wire:navigate>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 space-y-6">
            {{-- Main Details --}}
            <div class="card p-0 overflow-hidden">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-surface-800">
                    <div class="p-4 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-500/10 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs text-surface-400 font-medium">Date</p>
                            <p class="text-sm font-semibold text-white">{{ $schedule->date->format('l, M j, Y') }}</p>
                        </div>
                    </div>
                    
                    <div class="p-4 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-purple-500/10 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs text-surface-400 font-medium">Time</p>
                            <p class="text-sm font-semibold text-white">{{ $schedule->start_time?->format('g:i A') ?: '--' }} {{ $schedule->end_time ? ' - ' . $schedule->end_time->format('g:i A') : '' }}</p>
                        </div>
                    </div>
                    
                    <div class="p-4 flex items-center gap-3 sm:col-span-2">
                        <div class="w-10 h-10 rounded-full bg-emerald-500/10 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs text-surface-400 font-medium">Location</p>
                            <p class="text-sm font-semibold text-white truncate">{{ $schedule->venue ?: ($schedule->location ?: 'No location specified') }}</p>
                        </div>
                    </div>
                </div>

                @if($schedule->description)
                    <div class="p-6 border-t border-surface-800 bg-surface-900/30">
                        <p class="text-surface-300 whitespace-pre-wrap">{{ $schedule->description }}</p>
                    </div>
                @endif
            </div>

            {{-- Setlist --}}
            @if($schedule->setlist)
                <div class="card space-y-4">
                    <div class="flex items-center justify-between border-b border-surface-800 pb-2">
                        <div>
                            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                                <svg class="w-6 h-6 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                                {{ $schedule->setlist->title }}
                            </h2>
                            @if($schedule->setlist->description)
                                <p class="text-sm text-surface-400 mt-1">{{ $schedule->setlist->description }}</p>
                            @endif
                        </div>
                        <div class="text-right">
                            <span class="badge badge-surface">{{ $schedule->setlist->songs->count() }} Songs</span>
                            <p class="text-xs text-surface-500 mt-1">Est. {{ $schedule->setlist->formatted_total_duration }}</p>
                        </div>
                    </div>

                    <div class="space-y-2 pt-2">
                        @forelse($schedule->setlist->songs as $index => $song)
                            <div class="flex items-center gap-4 p-3 bg-surface-950/50 rounded-xl border border-surface-800">
                                <div class="text-surface-600 font-bold w-6 text-center shrink-0">{{ $index + 1 }}</div>
                                
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-white truncate">{{ $song->title }}</p>
                                    <p class="text-sm text-surface-400">{{ $song->artist ?: 'Unknown Artist' }} • {{ $song->formatted_duration }}</p>
                                </div>
                                
                                @if($song->pivot->notes)
                                    <div class="hidden md:flex text-sm text-surface-500 bg-surface-900 px-3 py-1.5 rounded-lg max-w-xs truncate">
                                        <svg class="w-4 h-4 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        {{ $song->pivot->notes }}
                                    </div>
                                @endif
                            </div>
                        @empty
                            <p class="text-surface-500 text-center py-4">No songs in this setlist.</p>
                        @endforelse
                    </div>
                </div>
            @else
                <div class="card p-12 text-center bg-surface-900/30 border-dashed">
                    <svg class="w-12 h-12 text-surface-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                    <p class="text-surface-400">No setlist attached to this event.</p>
                    <a href="{{ route('schedules.edit', $schedule) }}" class="btn-secondary mt-4" wire:navigate>Add Setlist</a>
                </div>
            @endif
        </div>

        <div class="xl:col-span-1 space-y-6">
            {{-- Team Members --}}
            <div class="card">
                <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-surface-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Band Members
                </h3>
                
                <div class="space-y-3">
                    @forelse($schedule->members as $member)
                        <div class="flex items-center gap-3 p-3 bg-surface-950/50 rounded-xl border border-surface-800">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-500 to-emerald-600 flex items-center justify-center text-xs font-bold text-white shrink-0 shadow-lg">
                                {{ strtoupper(substr($member->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-white truncate">{{ $member->name }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-surface-500">No members assigned.</p>
                    @endforelse
                </div>
            </div>

            {{-- Gig Details (if gig) --}}
            @if($schedule->isGig())
                <div class="card">
                    <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Gig Details
                    </h3>
                    
                    <ul class="space-y-3 text-sm">
                        @if($schedule->address)
                            <li class="flex items-start gap-2">
                                <span class="text-surface-500 w-20 shrink-0">Address:</span>
                                <span class="text-white">{{ $schedule->address }}</span>
                            </li>
                        @endif
                        @if($schedule->contact_person)
                            <li class="flex items-start gap-2">
                                <span class="text-surface-500 w-20 shrink-0">Contact:</span>
                                <span class="text-white">{{ $schedule->contact_person }}</span>
                            </li>
                        @endif
                        @if($schedule->phone)
                            <li class="flex items-start gap-2">
                                <span class="text-surface-500 w-20 shrink-0">Phone:</span>
                                <span class="text-white">{{ $schedule->phone }}</span>
                            </li>
                        @endif
                    </ul>
                </div>

                <div class="card bg-surface-900 border-emerald-500/20">
                    <h3 class="text-sm font-semibold text-emerald-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Financial Summary
                    </h3>
                    
                    <div class="space-y-2 text-sm mb-4 border-b border-surface-800 pb-4">
                        <div class="flex justify-between">
                            <span class="text-surface-400">Payment</span>
                            <span class="text-white">${{ number_format($schedule->payment, 2) }}</span>
                        </div>
                        @if($schedule->tips > 0)
                            <div class="flex justify-between">
                                <span class="text-surface-400">Tips/Extras</span>
                                <span class="text-white">${{ number_format($schedule->tips, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between pt-2 mt-2 border-t border-surface-800">
                            <span class="text-surface-300">Gross Income</span>
                            <span class="text-white font-medium">${{ number_format($schedule->gross_income, 2) }}</span>
                        </div>
                    </div>

                    <div class="space-y-2 text-sm mb-4 border-b border-surface-800 pb-4">
                        @if($schedule->transport > 0)
                            <div class="flex justify-between text-red-400/80">
                                <span>Transport</span>
                                <span>-${{ number_format($schedule->transport, 2) }}</span>
                            </div>
                        @endif
                        @if($schedule->parking > 0)
                            <div class="flex justify-between text-red-400/80">
                                <span>Parking</span>
                                <span>-${{ number_format($schedule->parking, 2) }}</span>
                            </div>
                        @endif
                        @if($schedule->food > 0)
                            <div class="flex justify-between text-red-400/80">
                                <span>Food/Drink</span>
                                <span>-${{ number_format($schedule->food, 2) }}</span>
                            </div>
                        @endif
                        @if($schedule->equipment_rental > 0)
                            <div class="flex justify-between text-red-400/80">
                                <span>Rental</span>
                                <span>-${{ number_format($schedule->equipment_rental, 2) }}</span>
                            </div>
                        @endif
                        @if($schedule->other_expenses > 0)
                            <div class="flex justify-between text-red-400/80">
                                <span>Other</span>
                                <span>-${{ number_format($schedule->other_expenses, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between pt-2 mt-2 border-t border-surface-800 text-red-400">
                            <span>Total Expenses</span>
                            <span class="font-medium">${{ number_format($schedule->total_expenses, 2) }}</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center text-lg font-bold">
                        <span class="text-surface-200">Net Income</span>
                        <span class="{{ $schedule->net_income >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                            ${{ number_format($schedule->net_income, 2) }}
                        </span>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
