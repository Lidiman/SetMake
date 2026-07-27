<div class="max-w-2xl mx-auto space-y-8">
    <div>
        <h1 class="text-2xl font-bold text-white tracking-tight">Settings</h1>
        <p class="text-surface-400 mt-1">Manage your profile and account settings</p>
    </div>

    {{-- Profile Information --}}
    <div class="card">
        <h2 class="text-lg font-semibold text-white mb-6">Profile Information</h2>

        <form wire:submit="updateProfile" class="space-y-6">
            {{-- Avatar --}}
            <div>
                <label class="label">Profile Picture</label>
                <div class="mt-2 flex flex-col items-center gap-4 p-6 bg-surface-800/30 rounded-xl border-2 border-dashed border-surface-700 hover:border-primary-500/50 transition-colors">
                    <div class="relative group">
                        <div class="w-24 h-24 rounded-full bg-gradient-to-br from-primary-500 to-emerald-600 flex items-center justify-center text-3xl font-bold text-white overflow-hidden ring-4 ring-surface-800">
                            @if (auth()->user()->avatar)
                                <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                            @else
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            @endif
                        </div>
                        <label class="absolute inset-0 flex items-center justify-center bg-black/50 rounded-full opacity-0 group-hover:opacity-100 cursor-pointer transition-opacity">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <input wire:model="avatar" type="file" accept="image/*" class="hidden">
                        </label>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-surface-300">Click the avatar to upload a new photo</p>
                        <p class="text-xs text-surface-500 mt-1">JPG, PNG or GIF. Max 2MB.</p>
                    </div>
                    @if ($avatar)
                        <div class="flex items-center gap-2 text-sm text-primary-400 bg-primary-500/10 px-3 py-1.5 rounded-full">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>
                            {{ $avatar->getClientOriginalName() }}
                        </div>
                    @endif
                    @error('avatar') <p class="text-sm text-red-400">{{ $message }}</p> @enderror
                    @if (auth()->user()->avatar)
                        <button type="button" wire:click="removeAvatar" wire:confirm="Remove your profile picture?" class="text-sm text-red-400 hover:text-red-300 transition-colors">
                            Remove current photo
                        </button>
                    @endif
                </div>
            </div>

            {{-- Name --}}
            <div>
                <label for="name" class="label">Name</label>
                <input wire:model="name" type="text" id="name" class="input" placeholder="Your name">
                @error('name') <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>

            {{-- Username --}}
            <div>
                <label for="username" class="label">Username</label>
                <input wire:model="username" type="text" id="username" class="input" placeholder="yourusername">
                @error('username') <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                    <span wire:loading.remove>Save Changes</span>
                    <span wire:loading>
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </span>
                </button>
                <span wire:loading wire:target="avatar" class="text-sm text-surface-400">Uploading...</span>
            </div>
        </form>
    </div>

    {{-- Change Password --}}
    <div class="card">
        <h2 class="text-lg font-semibold text-white mb-6">Change Password</h2>

        <form wire:submit="updatePassword" class="space-y-5">
            <div>
                <label for="current_password" class="label">Current Password</label>
                <input wire:model="current_password" type="password" id="current_password" class="input" placeholder="••••••••">
                @error('current_password') <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="new_password" class="label">New Password</label>
                <input wire:model="new_password" type="password" id="new_password" class="input" placeholder="••••••••">
                @error('new_password') <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="new_password_confirmation" class="label">Confirm New Password</label>
                <input wire:model="new_password_confirmation" type="password" id="new_password_confirmation" class="input" placeholder="••••••••">
            </div>

            <div class="pt-2">
                <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                    <span wire:loading.remove>Change Password</span>
                    <span wire:loading>
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </span>
                </button>
            </div>
        </form>
    </div>

    {{-- Danger Zone --}}
    <div class="card border border-red-500/20">
        <h2 class="text-lg font-semibold text-red-400 mb-6">Danger Zone</h2>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-surface-200">Sign Out</p>
                    <p class="text-xs text-surface-500 mt-0.5">Sign out of your account on this device</p>
                </div>
                <button type="submit" class="btn-danger">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Logout
                </button>
            </div>
        </form>
    </div>
</div>
