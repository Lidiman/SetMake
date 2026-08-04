<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="BandBuddy - Band Management Platform">

    <title>{{ $title ?? 'Dashboard' }} — BandBuddy</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen" x-data="{ sidebarOpen: false, searchOpen: false }" @keydown.cmd-k.window="searchOpen = true" @keydown.ctrl-k.window="searchOpen = true">
    @auth
    <div class="min-h-screen">
        {{-- Sidebar overlay --}}
        <div
            x-show="sidebarOpen"
            x-transition:enter="transition-opacity ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40"
            @click="sidebarOpen = false"
        ></div>

        {{-- Sidebar --}}
        <aside
            class="fixed inset-y-0 left-0 z-50 w-64 bg-surface-900/95 backdrop-blur-xl border-r border-surface-800/50 flex flex-col transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]"
            :class="sidebarOpen ? 'translate-x-0 shadow-2xl shadow-black/50' : '-translate-x-full'"
        >
            {{-- Logo --}}
            <div class="relative px-6 py-5 border-b border-surface-800/50 flex items-center min-h-[4rem]">
                <div class="absolute transition-transform duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]"
                    :class="sidebarOpen ? 'rotate-[360deg]' : 'rotate-0'"
                    style="top: 50%; transform: translateX(2px) translateY(-50%);">
                    <img src="{{ asset('images/BandBuddytransparent.png') }}" alt="BandBuddy" class="h-14 w-auto object-contain">
                </div>
                <span class="text-xl font-bold text-white tracking-tight" x-cloak x-show="sidebarOpen" x-transition:enter="transition-all duration-300 delay-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition-all duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" style="margin-left: 4rem;">Band<span class="text-primary-400">Buddy</span></span>
                <button @click="sidebarOpen = false" class="ml-auto text-surface-500 hover:text-surface-200 transition-colors" x-cloak x-show="sidebarOpen" x-transition:enter="transition-all duration-300 delay-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-all duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                <p class="px-4 py-2 text-xs font-semibold text-surface-500 uppercase tracking-wider"
                    x-cloak x-show="sidebarOpen"
                    x-transition:enter="transition-all duration-300 delay-75"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition-all duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-1">Menu</p>

                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" wire:navigate
                    x-cloak x-show="sidebarOpen"
                    x-transition:enter="transition-all duration-300 delay-100"
                    x-transition:enter-start="opacity-0 translate-x-2"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition-all duration-150"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 translate-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>

                <a href="{{ route('songs.index') }}" class="sidebar-link {{ request()->routeIs('songs.*') ? 'active' : '' }}" wire:navigate
                    x-cloak x-show="sidebarOpen"
                    x-transition:enter="transition-all duration-300 delay-150"
                    x-transition:enter-start="opacity-0 translate-x-2"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition-all duration-150"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 translate-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                    Songs
                </a>

                <a href="{{ route('schedules.index') }}" class="sidebar-link {{ request()->routeIs('schedules.*') ? 'active' : '' }}" wire:navigate
                    x-cloak x-show="sidebarOpen"
                    x-transition:enter="transition-all duration-300 delay-200"
                    x-transition:enter-start="opacity-0 translate-x-2"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition-all duration-150"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 translate-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Schedule
                </a>

                <a href="{{ route('schedules.history') }}" class="sidebar-link {{ request()->routeIs('schedules.history') ? 'active' : '' }}" wire:navigate
                    x-cloak x-show="sidebarOpen"
                    x-transition:enter="transition-all duration-300 delay-210"
                    x-transition:enter-start="opacity-0 translate-x-2"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition-all duration-150"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 translate-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Schedule History
                </a>

                <a href="{{ route('performances.index') }}" class="sidebar-link {{ request()->routeIs('performances.*') ? 'active' : '' }}" wire:navigate
                    x-cloak x-show="sidebarOpen"
                    x-transition:enter="transition-all duration-300 delay-250"
                    x-transition:enter-start="opacity-0 translate-x-2"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition-all duration-150"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 translate-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    History
                </a>

                <a href="{{ route('analytics') }}" class="sidebar-link {{ request()->routeIs('analytics') ? 'active' : '' }}" wire:navigate
                    x-cloak x-show="sidebarOpen"
                    x-transition:enter="transition-all duration-300 delay-300"
                    x-transition:enter-start="opacity-0 translate-x-2"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition-all duration-150"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 translate-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    Analytics
                </a>
            </nav>

        </aside>

        {{-- Main content --}}
        <main id="main-scroll" class="flex flex-col min-h-screen transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]"
            :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-0'">
            {{-- Top header bar --}}
            <header class="sticky top-0 z-30 bg-surface-950/80 backdrop-blur-xl border-b border-surface-800/50">
                <div class="flex items-center justify-between px-4 py-3 lg:px-8">
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = !sidebarOpen" class="text-surface-400 hover:text-surface-200 transition-transform duration-300" :class="sidebarOpen ? 'rotate-90' : 'rotate-0'">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                        <img src="{{ asset('images/BandBuddyAlone.png') }}" alt="BandBuddy" class="h-8 lg:hidden object-contain">
                    </div>
                    <div class="flex items-center gap-2">
                        @livewire('search.global-search')
                        @livewire('notifications.notification-dropdown')
                        <a href="{{ route('settings.profile') }}" wire:navigate
                            class="flex items-center gap-3 px-4 py-1.5 rounded-xl text-surface-400 hover:text-surface-200 hover:bg-surface-800/50 transition-all duration-200 group"
                            title="{{ auth()->user()->name }}">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-500 to-emerald-600 flex items-center justify-center text-xs font-bold text-white shrink-0">
                                @if (auth()->user()->hasAvatar())
                                    <img src="{{ auth()->user()->avatar_url }}" alt="" class="w-full h-full rounded-full object-cover">
                                @else
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                @endif
                            </div>
                            <span class="hidden lg:block text-sm font-medium truncate max-w-[120px]">{{ auth()->user()->name }}</span>
                        </a>
                    </div>
                </div>
            </header>

            <div class="p-4 lg:p-8 flex-1">
                {{ $slot }}
            </div>
        </main>
    </div>
    @else
        {{ $slot }}
    @endauth

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
    <script src="https://unpkg.com/lenis@1.1.9/dist/lenis.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.js"></script>

    @stack('scripts')
    <script>
        let lenis;
        function initLenis() {
            if (lenis) { lenis.destroy(); lenis = null; }
            lenis = new Lenis({
                smoothWheel: true,
                lerp: 0.08,
                duration: 1.2,
                easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
                touchMultiplier: 2,
                infinite: false,
            });
            function raf(time) {
                lenis.raf(time);
                requestAnimationFrame(raf);
            }
            requestAnimationFrame(raf);
        }
        document.addEventListener('DOMContentLoaded', initLenis);
        document.addEventListener('livewire:navigated', initLenis);
    </script>
</body>
</html>
