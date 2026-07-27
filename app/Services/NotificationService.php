<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    public function send(User $user, string $type, string $title, ?string $body = null, ?string $link = null): Notification
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'link' => $link,
        ]);
    }

    public function sendToAll(string $type, string $title, ?string $body = null, ?string $link = null): void
    {
        $users = User::whereIn('role', ['admin', 'member'])->get();
        foreach ($users as $user) {
            $this->send($user, $type, $title, $body, $link);
        }
    }

    public function upcomingRehearsal($rehearsal): void
    {
        $this->sendToAll(
            'upcoming_rehearsal',
            "Upcoming Rehearsal: {$rehearsal->title}",
            "Scheduled for {$rehearsal->date->format('l, F j, Y')} at {$rehearsal->location}",
            route('rehearsals.show', $rehearsal)
        );
    }

    public function upcomingGig($gig): void
    {
        $this->sendToAll(
            'upcoming_gig',
            "Upcoming Gig: {$gig->title}",
            "At {$gig->venue} on {$gig->date->format('l, F j, Y')}",
            route('gigs.show', $gig)
        );
    }

    public function gigTomorrow($gig): void
    {
        $this->sendToAll(
            'gig_tomorrow',
            "Gig Tomorrow: {$gig->title}",
            "Don't forget! You're playing at {$gig->venue} tomorrow.",
            route('gigs.show', $gig)
        );
    }
}
