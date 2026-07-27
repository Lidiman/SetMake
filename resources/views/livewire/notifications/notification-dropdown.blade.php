<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" class="relative p-2 text-surface-400 hover:text-surface-200 transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        @if($unreadCount > 0)
            <span class="absolute -top-0.5 -right-0.5 w-5 h-5 rounded-full bg-red-500 text-white text-xs font-bold flex items-center justify-center">{{ min($unreadCount, 99) }}</span>
        @endif
    </button>

    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="absolute right-0 mt-2 w-80 bg-surface-900 border border-surface-700 rounded-2xl shadow-2xl overflow-hidden z-50">
        <div class="flex items-center justify-between px-4 py-3 border-b border-surface-800">
            <h3 class="font-semibold text-white">Notifications</h3>
            @if($unreadCount > 0)
                <button wire:click="markAllAsRead" class="text-xs text-primary-400 hover:text-primary-300">Mark all as read</button>
            @endif
        </div>

        <div class="max-h-80 overflow-y-auto">
            @if($notifications->count() > 0)
                <div class="divide-y divide-surface-800/50">
                    @foreach($notifications as $notification)
                        <div class="px-4 py-3 hover:bg-surface-800/30 transition-colors {{ $notification->is_read ? '' : 'bg-surface-800/20 border-l-2 border-primary-500' }}">
                            <div class="flex items-start gap-3">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-white truncate">{{ $notification->title }}</p>
                                    @if($notification->body)
                                        <p class="text-xs text-surface-400 mt-0.5">{{ $notification->body }}</p>
                                    @endif
                                    <p class="text-xs text-surface-600 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                                @if(!$notification->is_read)
                                    <button wire:click="markAsRead({{ $notification->id }})" class="text-surface-500 hover:text-primary-400 p-1 shrink-0" title="Mark as read">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </button>
                                @endif
                            </div>
                            @if($notification->link)
                                <a href="{{ $notification->link }}" class="text-xs text-primary-400 hover:text-primary-300 mt-1 inline-block" wire:navigate>View →</a>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-8 text-center text-surface-500 text-sm">No notifications yet.</div>
            @endif
        </div>
    </div>
</div>
