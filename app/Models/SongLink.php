<?php

namespace App\Models;

use App\Enums\LinkType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SongLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'song_id',
        'type',
        'url',
        'label',
    ];

    protected function casts(): array
    {
        return [
            'type' => LinkType::class,
        ];
    }

    public function song(): BelongsTo
    {
        return $this->belongsTo(Song::class);
    }

    /**
     * Extract Spotify embed URL from a Spotify link.
     */
    public function getSpotifyEmbedUrlAttribute(): ?string
    {
        if ($this->type !== LinkType::Spotify) {
            return null;
        }

        // Convert spotify:track:ID or open.spotify.com/track/ID to embed URL
        if (preg_match('/track[\/:]([a-zA-Z0-9]+)/', $this->url, $matches)) {
            return "https://open.spotify.com/embed/track/{$matches[1]}?theme=0";
        }

        return null;
    }

    /**
     * Extract YouTube embed URL from a YouTube link.
     */
    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        if ($this->type !== LinkType::YouTube) {
            return null;
        }

        // Match various YouTube URL formats
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/', $this->url, $matches)) {
            return "https://www.youtube.com/embed/{$matches[1]}";
        }

        return null;
    }
}
