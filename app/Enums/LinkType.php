<?php

namespace App\Enums;

enum LinkType: string
{
    case Spotify = 'spotify';
    case YouTube = 'youtube';
    case UltimateGuitar = 'ultimate_guitar';
    case Chordify = 'chordify';
    case Songsterr = 'songsterr';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Spotify => 'Spotify',
            self::YouTube => 'YouTube',
            self::UltimateGuitar => 'Ultimate Guitar',
            self::Chordify => 'Chordify',
            self::Songsterr => 'Songsterr',
            self::Other => 'Other',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Spotify => 'spotify',
            self::YouTube => 'youtube',
            self::UltimateGuitar => 'guitar',
            self::Chordify => 'music',
            self::Songsterr => 'music-note',
            self::Other => 'link',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Spotify => '#1DB954',
            self::YouTube => '#FF0000',
            self::UltimateGuitar => '#FF6600',
            self::Chordify => '#4ECDC4',
            self::Songsterr => '#1E90FF',
            self::Other => '#9CA3AF',
        };
    }
}
