<?php

namespace App\Enums;

enum GigStatus: string
{
    case Planned = 'planned';
    case Confirmed = 'confirmed';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Planned => 'Planned',
            self::Confirmed => 'Confirmed',
            self::Completed => 'Completed',
            self::Cancelled => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Planned => 'surface',
            self::Confirmed => 'blue',
            self::Completed => 'emerald',
            self::Cancelled => 'red',
        };
    }
}
