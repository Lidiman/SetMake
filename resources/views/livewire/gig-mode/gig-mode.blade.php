<div class="min-h-screen bg-surface-950 text-white flex flex-col" x-data="gigMode()" x-init="init()" @keydown.window="handleKey($event)">
    {{-- Header --}}
    <header class="px-4 md:px-8 py-4 flex items-center justify-between border-b border-surface-800/50">
        <div class="flex items-center gap-4">
            <button wire:click="exitGigMode" class="btn-ghost btn-sm text-surface-400 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Exit
            </button>
            <h1 class="text-lg font-bold text-white hidden sm:block">{{ $gig->title }}</h1>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-surface-400 text-sm">Song {{ $currentIndex + 1 }} / {{ count($songs) }}</span>
            <button x-on:click="toggleFullscreen" class="btn-ghost btn-sm text-surface-400 hover:text-white" title="Fullscreen (F)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
            </button>
        </div>
    </header>

    {{-- Progress bar --}}
    <div class="h-1 bg-surface-800">
        <div class="h-full bg-primary-500 transition-all duration-500 ease-out" style="width: {{ $this->progress }}%"></div>
    </div>

    {{-- Main content --}}
    <main class="flex-1 flex flex-col items-center justify-center px-4 py-8 md:py-12 text-center">
        {{-- Current Song --}}
        @if($currentSong)
            <div class="space-y-6 max-w-2xl mx-auto">
                <div class="space-y-2">
                    <p class="text-sm text-surface-500 uppercase tracking-widest">Now Playing</p>
                    <h2 class="text-4xl md:text-6xl lg:text-7xl font-extrabold text-white tracking-tight leading-tight">
                        {{ $currentSong['title'] }}
                    </h2>
                    <p class="text-xl md:text-2xl text-surface-400">{{ $currentSong['artist'] ?? 'Unknown Artist' }}</p>
                </div>

                {{-- Song details --}}
                <div class="flex flex-wrap justify-center gap-4 mt-6">
                    @if(!empty($currentSong['key']))
                        <div class="px-4 py-2 rounded-xl bg-surface-800/50 border border-surface-700"><span class="text-xs text-surface-500 block">Key</span><span class="font-bold text-white text-lg">{{ $currentSong['key'] }}</span></div>
                    @endif
                    @if(!empty($currentSong['bpm']))
                        <div class="px-4 py-2 rounded-xl bg-surface-800/50 border border-surface-700"><span class="text-xs text-surface-500 block">BPM</span><span class="font-bold text-white text-lg">{{ $currentSong['bpm'] }}</span></div>
                    @endif
                    @if(!empty($currentSong['tuning']))
                        <div class="px-4 py-2 rounded-xl bg-surface-800/50 border border-surface-700"><span class="text-xs text-surface-500 block">Tuning</span><span class="font-bold text-white text-lg">{{ $currentSong['tuning'] }}</span></div>
                    @endif
                    @if(array_key_exists('capo', $currentSong) && $currentSong['capo'] !== null)
                        <div class="px-4 py-2 rounded-xl bg-surface-800/50 border border-surface-700"><span class="text-xs text-surface-500 block">Capo</span><span class="font-bold text-white text-lg">Fret {{ $currentSong['capo'] }}</span></div>
                    @endif
                    @if(!empty($currentSong['duration']))
                        <div class="px-4 py-2 rounded-xl bg-surface-800/50 border border-surface-700"><span class="text-xs text-surface-500 block">Duration</span><span class="font-bold text-white text-lg">{{ gmdate('i:s', $currentSong['duration']) }}</span></div>
                    @endif
                </div>
            </div>
        @else
            <div class="text-center">
                <h2 class="text-4xl font-bold text-surface-400">No songs in setlist</h2>
            </div>
        @endif
    </main>

    {{-- Controls --}}
    <footer class="px-4 md:px-8 py-6 border-t border-surface-800/50">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 max-w-4xl mx-auto">
            <div class="flex items-center gap-3">
                @if($previousSong)
                    <div class="text-left">
                        <p class="text-xs text-surface-500 uppercase">Previous</p>
                        <p class="text-sm text-surface-400 truncate max-w-[150px]">{{ $previousSong['title'] }}</p>
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <button wire:click="previousSong" class="btn-secondary btn-lg" @disabled($currentIndex === 0)>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>

                <button wire:click="completeSong" class="btn-primary btn-lg px-8">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Complete
                </button>

                <button wire:click="skipSong" class="btn-ghost btn-lg text-surface-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
                </button>

                <button wire:click="nextSong" class="btn-secondary btn-lg" @disabled($currentIndex >= count($songs) - 1)>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>

            <div class="flex items-center gap-3">
                @if($nextSong)
                    <div class="text-right">
                        <p class="text-xs text-surface-500 uppercase">Next Up</p>
                        <p class="text-sm text-surface-400 truncate max-w-[150px]">{{ $nextSong['title'] }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Keyboard shortcuts hint --}}
        <div class="mt-4 text-center text-xs text-surface-600 space-x-4">
            <span>← Previous</span>
            <span>→ Next</span>
            <span>Space Complete</span>
            <span>F Fullscreen</span>
        </div>
    </footer>

    <script>
        function gigMode() {
            return {
                init() {
                    if ('wakeLock' in navigator) {
                        navigator.wakeLock.request('screen').catch(() => {});
                    }
                },
                toggleFullscreen() {
                    if (!document.fullscreenElement) {
                        document.documentElement.requestFullscreen().catch(() => {});
                    } else {
                        document.exitFullscreen().catch(() => {});
                    }
                },
                handleKey(event) {
                    if (event.key === 'ArrowLeft') {
                        event.preventDefault();
                        @this.previousSong();
                    } else if (event.key === 'ArrowRight') {
                        event.preventDefault();
                        @this.nextSong();
                    } else if (event.key === ' ') {
                        event.preventDefault();
                        @this.completeSong();
                    } else if (event.key === 'f' || event.key === 'F') {
                        this.toggleFullscreen();
                    }
                }
            }
        }
    </script>
</div>
