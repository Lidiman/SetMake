<div class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-primary-500 shadow-lg shadow-primary-500/30 mb-4">
                <svg class="w-8 h-8 text-surface-950" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55C7.79 13 6 14.79 6 17s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-white tracking-tight">Band<span class="text-primary-400">Set</span></h1>
            <p class="text-surface-400 mt-2">Sign in to manage your band</p>
        </div>

        {{-- Login form --}}
        <div class="card">
            <form wire:submit="login" class="space-y-5">
                <div>
                    <label for="username" class="label">Username</label>
                    <input
                        wire:model="username"
                        type="text"
                        id="username"
                        class="input"
                        placeholder="yourusername"
                        autofocus
                    >
                    @error('username')
                        <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="label">Password</label>
                    <input
                        wire:model="password"
                        type="password"
                        id="password"
                        class="input"
                        placeholder="••••••••"
                    >
                    @error('password')
                        <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input wire:model="remember" type="checkbox" class="w-4 h-4 rounded bg-surface-800 border-surface-600 text-primary-500 focus:ring-primary-500/20">
                        <span class="text-sm text-surface-400">Remember me</span>
                    </label>
                </div>

                <button type="submit" class="btn-primary w-full" wire:loading.attr="disabled">
                    <span wire:loading.remove>Sign In</span>
                    <span wire:loading>
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </span>
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-surface-800/50 space-y-2">
                <p class="text-xs text-surface-500 text-center">
                    Demo: <span class="text-surface-400">admin</span> / <span class="text-surface-400">password</span>
                </p>
                <p class="text-sm text-surface-400 text-center">
                    No account?
                    <a href="{{ route('register') }}" class="text-primary-400 hover:text-primary-300 font-medium" wire:navigate>Create one</a>
                </p>
            </div>
        </div>
    </div>
</div>
