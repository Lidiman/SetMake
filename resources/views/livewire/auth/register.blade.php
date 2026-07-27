<div class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-primary-500 shadow-lg shadow-primary-500/30 mb-4">
                <svg class="w-8 h-8 text-surface-950" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55C7.79 13 6 14.79 6 17s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-white tracking-tight">Band<span class="text-primary-400">Buddy</span></h1>
            <p class="text-surface-400 mt-2">Create your band account</p>
        </div>

        <div class="card">
            <form wire:submit="register" class="space-y-5">
                <div>
                    <label for="name" class="label">Name</label>
                    <input wire:model="name" type="text" id="name" class="input" placeholder="Your name" autofocus>
                    @error('name') <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="username" class="label">Username</label>
                    <input wire:model="username" type="text" id="username" class="input" placeholder="yourusername">
                    @error('username') <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="label">Password</label>
                    <input wire:model="password" type="password" id="password" class="input" placeholder="••••••••">
                    @error('password') <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="label">Confirm Password</label>
                    <input wire:model="password_confirmation" type="password" id="password_confirmation" class="input" placeholder="••••••••">
                </div>

                <button type="submit" class="btn-primary w-full" wire:loading.attr="disabled">
                    <span wire:loading.remove>Create Account</span>
                    <span wire:loading>
                        <svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </span>
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-surface-800/50 text-center">
                <p class="text-sm text-surface-400">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-primary-400 hover:text-primary-300 font-medium" wire:navigate>Sign in</a>
                </p>
            </div>
        </div>
    </div>
</div>
