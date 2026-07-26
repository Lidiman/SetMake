<?php

namespace App\Enums;

enum SetlistType: string
{
    case Rehearsal = 'rehearsal';
    case Performance = 'performance';

    public function label(): string
    {
        return match ($this) {
            self::Rehearsal => 'Rehearsal',
            self::Performance => 'Performance',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Rehearsal => 'blue',
            self::Performance => 'purple',
        };
    }
}
