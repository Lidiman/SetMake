<?php

namespace App\Enums;

enum SongReadiness: string
{
    case Ready = 'ready';
    case NeedsPractice = 'needs_practice';
    case NotReady = 'not_ready';

    public function label(): string
    {
        return match ($this) {
            self::Ready => 'Ready',
            self::NeedsPractice => 'Needs Practice',
            self::NotReady => 'Not Ready',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Ready => 'emerald',
            self::NeedsPractice => 'amber',
            self::NotReady => 'red',
        };
    }
}
