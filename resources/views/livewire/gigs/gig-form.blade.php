<div class="max-w-4xl mx-auto space-y-6 animate-fade-in">
    <div class="flex items-center justify-between">
        <a href="{{ $isEditing ? route('gigs.show', $gig) : route('gigs.index') }}" class="text-surface-400 hover:text-white flex items-center gap-2 transition-colors" wire:navigate>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back
        </a>
    </div>
    <div>
        <h1 class="text-3xl font-bold text-white">{{ $isEditing ? 'Edit Gig' : 'Create Gig' }}</h1>
        <p class="text-surface-400 mt-1">Plan your band's performance.</p>
    </div>

    <form wire:submit="save" class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 space-y-6">
            <div class="card space-y-5">
                <h2 class="text-lg font-semibold text-white border-b border-surface-800 pb-2">Gig Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="label">Title *</label>
                        <input type="text" wire:model="title" class="input" placeholder="e.g. Live at The Roxy">
                        @error('title') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="label">Venue *</label>
                        <input type="text" wire:model="venue" class="input" placeholder="e.g. The Roxy">
                        @error('venue') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div>
                    <label class="label">Address</label>
                    <input type="text" wire:model="address" class="input" placeholder="123 Main St, City">
                    @error('address') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="label">Contact Person</label>
                        <input type="text" wire:model="contact_person" class="input" placeholder="Venue contact name">
                    </div>
                    <div>
                        <label class="label">Phone</label>
                        <input type="text" wire:model="phone" class="input" placeholder="Contact phone">
                    </div>
                </div>
                <div>
                    <label class="label">Description</label>
                    <textarea wire:model="description" rows="3" class="input" placeholder="Set times, special notes..."></textarea>
                </div>
                <div>
                    <label class="label">Status</label>
                    <select wire:model="status" class="input">
                        @foreach($statuses as $s)
                            <option value="{{ $s->value }}">{{ $s->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">Setlist</label>
                    <select wire:model="setlist_id" class="input">
                        <option value="">None</option>
                        @foreach($setlists as $setlist)
                            <option value="{{ $setlist->id }}">{{ $setlist->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="card space-y-5">
                <h2 class="text-lg font-semibold text-white border-b border-surface-800 pb-2">Income & Expenses</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div>
                        <label class="label">Payment</label>
                        <input type="number" wire:model="payment" step="0.01" class="input" placeholder="0.00">
                    </div>
                    <div>
                        <label class="label">Tips</label>
                        <input type="number" wire:model="tips" step="0.01" class="input" placeholder="0.00">
                    </div>
                    <div>
                        <label class="label">Transport</label>
                        <input type="number" wire:model="transport" step="0.01" class="input" placeholder="0.00">
                    </div>
                    <div>
                        <label class="label">Parking</label>
                        <input type="number" wire:model="parking" step="0.01" class="input" placeholder="0.00">
                    </div>
                    <div>
                        <label class="label">Food</label>
                        <input type="number" wire:model="food" step="0.01" class="input" placeholder="0.00">
                    </div>
                    <div>
                        <label class="label">Equipment Rental</label>
                        <input type="number" wire:model="equipment_rental" step="0.01" class="input" placeholder="0.00">
                    </div>
                    <div>
                        <label class="label">Other Expenses</label>
                        <input type="number" wire:model="other_expenses" step="0.01" class="input" placeholder="0.00">
                    </div>
                </div>
            </div>

            <div class="card space-y-4">
                <h2 class="text-lg font-semibold text-white border-b border-surface-800 pb-2">Members</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($members as $member)
                        <label class="flex items-center gap-3 p-3 rounded-xl bg-surface-800/30 hover:bg-surface-800/50 border border-surface-800 cursor-pointer transition-colors">
                            <input type="checkbox" wire:model="selectedMembers" value="{{ $member->id }}" class="w-5 h-5 rounded bg-surface-800 border-surface-600 text-primary-500 focus:ring-primary-500/20">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center text-xs font-bold text-white">{{ strtoupper(substr($member->name, 0, 1)) }}</div>
                            <div>
                                <p class="text-sm font-medium text-white">{{ $member->name }}</p>
                                <p class="text-xs text-surface-500">{{ $member->role->label() }}</p>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="xl:col-span-1 space-y-6">
            <div class="card bg-surface-900 border-purple-500/20 shadow-lg shadow-purple-500/5">
                <h3 class="text-sm font-medium text-surface-400 mb-4">Financial Summary</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-end">
                        <span class="text-surface-400 text-sm">Gross Income</span>
                        <span class="text-xl font-bold text-primary-400">Rp{{ number_format(($payment ?? 0) + ($tips ?? 0), 2) }}</span>
                    </div>
                    <div class="flex justify-between items-end">
                        <span class="text-surface-400 text-sm">Total Expenses</span>
                        <span class="text-xl font-bold text-red-400">Rp{{ number_format(($transport ?? 0) + ($parking ?? 0) + ($food ?? 0) + ($equipment_rental ?? 0) + ($other_expenses ?? 0), 2) }}</span>
                    </div>
                    <div class="border-t border-surface-700 pt-3 flex justify-between items-end">
                        <span class="text-white font-medium">Net Income</span>
                        <span class="text-2xl font-bold {{ $this->netIncome >= 0 ? 'text-emerald-400' : 'text-red-400' }}">Rp{{ number_format($this->netIncome, 2) }}</span>
                    </div>
                </div>
                <button type="submit" class="btn-primary w-full mt-6" wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ $isEditing ? 'Save Changes' : 'Create Gig' }}</span>
                    <span wire:loading>Saving...</span>
                </button>
            </div>
        </div>
    </form>
</div>
