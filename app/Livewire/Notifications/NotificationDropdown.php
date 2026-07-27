<?php

namespace App\Livewire\Notifications;

use App\Models\Notification;
use Livewire\Component;

class NotificationDropdown extends Component
{
    public bool $show = false;
    public int $unreadCount = 0;

    protected $listeners = ['refreshNotifications' => '$refresh'];

    public function mount()
    {
        $this->unreadCount = auth()->user()->unreadNotifications()->count();
    }

    public function toggle()
    {
        $this->show = !$this->show;
    }

    public function markAsRead($notificationId)
    {
        $notification = auth()->user()->notifications()->find($notificationId);
        if ($notification) {
            $notification->update(['is_read' => true]);
            $this->unreadCount = auth()->user()->unreadNotifications()->count();
        }
    }

    public function markAllAsRead()
    {
        auth()->user()->notifications()->where('is_read', false)->update(['is_read' => true]);
        $this->unreadCount = 0;
    }

    public function render()
    {
        $notifications = auth()->user()->notifications()->latest()->limit(20)->get();

        return view('livewire.notifications.notification-dropdown', [
            'notifications' => $notifications,
        ]);
    }
}
