<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="BandFlow - Band Management Platform">

    <title>{{ $title ?? 'Dashboard' }} — BandFlow</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen" x-data="{ sidebarOpen: false, searchOpen: false }" @keydown.cmd-k.window="searchOpen = true" @keydown.ctrl-k.window="searchOpen = true">
    @auth
    <div class="min-h-screen">
        {{-- Mobile sidebar overlay --}}
        <div
            x-show="sidebarOpen"
            x-transition:enter="transition-opacity ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 lg:hidden"
            @click="sidebarOpen = false"
        ></div>

        {{-- Sidebar --}}
        <aside
            class="fixed inset-y-0 left-0 z-50 w-64 bg-surface-900/95 backdrop-blur-xl border-r border-surface-800/50 transform transition-transform duration-300 flex flex-col"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        >
            {{-- Logo --}}
            <div class="flex items-center gap-3 px-6 py-5 border-b border-surface-800/50">
                <div class="w-9 h-9 rounded-xl bg-primary-500 flex items-center justify-center shadow-lg shadow-primary-500/30">
                    <svg class="w-5 h-5 text-surface-950" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55C7.79 13 6 14.79 6 17s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/>
                    </svg>
                </div>
                <span class="text-xl font-bold text-white tracking-tight">Band<span class="text-primary-400">Flow</span></span>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                <p class="px-4 py-2 text-xs font-semibold text-surface-500 uppercase tracking-wider">Menu</p>

                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" wire:navigate>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>

                <a href="{{ route('songs.index') }}" class="sidebar-link {{ request()->routeIs('songs.*') ? 'active' : '' }}" wire:navigate>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                    Songs
                </a>

                <a href="{{ route('setlists.index') }}" class="sidebar-link {{ request()->routeIs('setlists.*') ? 'active' : '' }}" wire:navigate>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    Setlists
                </a>

                <a href="{{ route('rehearsals.index') }}" class="sidebar-link {{ request()->routeIs('rehearsals.*') ? 'active' : '' }}" wire:navigate>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Rehearsals
                </a>

                <a href="{{ route('gigs.index') }}" class="sidebar-link {{ request()->routeIs('gigs.*') ? 'active' : '' }}" wire:navigate>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Gigs
                </a>

                <a href="{{ route('performances.index') }}" class="sidebar-link {{ request()->routeIs('performances.*') ? 'active' : '' }}" wire:navigate>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    History
                </a>

                <a href="{{ route('analytics') }}" class="sidebar-link {{ request()->routeIs('analytics') ? 'active' : '' }}" wire:navigate>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    Analytics
                </a>
            </nav>

            {{-- User section --}}
            <div class="px-3 py-4 border-t border-surface-800/50">
                <div class="flex items-center gap-3 px-4 py-2">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-500 to-emerald-600 flex items-center justify-center text-xs font-bold text-white">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-surface-200 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-surface-500">{{ auth()->user()->role->label() }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-surface-500 hover:text-surface-300 transition-colors" title="Logout">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Main content --}}
        <main id="main-scroll" class="flex flex-col min-h-screen" style="padding-left: 0;" x-init="$el.style.paddingLeft = window.innerWidth >= 1024 ? '16rem' : '0'">
            {{-- Top header bar --}}
            <header class="sticky top-0 z-30 bg-surface-950/80 backdrop-blur-xl border-b border-surface-800/50">
                <div class="flex items-center justify-between px-4 py-3 lg:px-8">
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = true" class="lg:hidden text-surface-400 hover:text-surface-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                        <span class="text-lg font-bold text-white lg:hidden">Band<span class="text-primary-400">Flow</span></span>
                    </div>
                    <div class="flex items-center gap-3">
                        @livewire('search.global-search')
                        @livewire('notifications.notification-dropdown')
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
