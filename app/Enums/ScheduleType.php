<?php

namespace App\Enums;

enum ScheduleType: string
{
    case Rehearsal = 'rehearsal';
    case Gig = 'gig';

    public function label(): string
    {
        return match ($this) {
            self::Rehearsal => 'Rehearsal',
            self::Gig => 'Gig',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Rehearsal => 'blue',
            self::Gig => 'purple',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Rehearsal => 'M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3',
            self::Gig => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
        };
    }
}
