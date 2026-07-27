<?php

namespace App\Enums;

enum NotificationType: string
{
    case UpcomingRehearsal = 'upcoming_rehearsal';
    case UpcomingGig = 'upcoming_gig';
    case SongNotReady = 'song_not_ready';
    case MissingAttachments = 'missing_attachments';
    case ChecklistIncomplete = 'checklist_incomplete';
    case GigTomorrow = 'gig_tomorrow';
    case MemberUnavailable = 'member_unavailable';

    public function label(): string
    {
        return match ($this) {
            self::UpcomingRehearsal => 'Upcoming Rehearsal',
            self::UpcomingGig => 'Upcoming Gig',
            self::SongNotReady => 'Song Not Ready',
            self::MissingAttachments => 'Missing Attachments',
            self::ChecklistIncomplete => 'Checklist Incomplete',
            self::GigTomorrow => 'Gig Tomorrow',
            self::MemberUnavailable => 'Member Unavailable',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::UpcomingRehearsal => 'blue',
            self::UpcomingGig => 'purple',
            self::SongNotReady => 'amber',
            self::MissingAttachments => 'orange',
            self::ChecklistIncomplete => 'red',
            self::GigTomorrow => 'purple',
            self::MemberUnavailable => 'red',
        };
    }
}
