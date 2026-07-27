<?php

namespace App\Models;

use App\Enums\Difficulty;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Song extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'artist',
        'genre',
        'key',
        'bpm',
        'duration',
        'difficulty',
        'tuning',
        'capo',
        'notes',
        'cover_image',
        'description',
        'is_favorite',
        'audio_path',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'bpm' => 'integer',
            'duration' => 'integer',
            'capo' => 'integer',
            'is_favorite' => 'boolean',
            'difficulty' => Difficulty::class,
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function links(): HasMany
    {
        return $this->hasMany(SongLink::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'song_tag');
    }

    public function setlists(): BelongsToMany
    {
        return $this->belongsToMany(Setlist::class, 'setlist_song')
            ->withPivot('position', 'notes', 'readiness')
            ->withTimestamps()
            ->orderByPivot('position');
    }

    public function performances(): HasMany
    {
        return $this->hasMany(Performance::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(SongAttachment::class);
    }

    public function checklists(): HasMany
    {
        return $this->hasMany(SongChecklist::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(SongRequest::class);
    }

    public function getFormattedDurationAttribute(): string
    {
        if (!$this->duration) {
            return '--:--';
        }

        $minutes = floor($this->duration / 60);
        $seconds = $this->duration % 60;

        return sprintf('%d:%02d', $minutes, $seconds);
    }

    public function getPerformanceCountAttribute(): int
    {
        return $this->performances()->count();
    }

    public function getChecklistCompletionAttribute(): int
    {
        $total = $this->checklists()->count();
        if ($total === 0) {
            return 0;
        }
        $completed = $this->checklists()->where('is_completed', true)->count();
        return (int) round(($completed / $total) * 100);
    }

    public function scopeSearch($query, ?string $search)
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('artist', 'like', "%{$search}%")
              ->orWhere('genre', 'like', "%{$search}%")
              ->orWhere('key', 'like', "%{$search}%")
              ->orWhereHas('tags', function ($tagQuery) use ($search) {
                  $tagQuery->where('name', 'like', "%{$search}%");
              });
        });
    }

    public function scopeFavorites($query)
    {
        return $query->where('is_favorite', true);
    }

    public function scopeNeedsPractice($query)
    {
        return $query->whereHas('checklists', function ($q) {
            $q->where('is_completed', false);
        })->orWhereDoesntHave('checklists');
    }

    public function scopePlayedRecently($query, int $days = 30)
    {
        return $query->whereHas('performances', function ($q) use ($days) {
            $q->where('performed_at', '>=', now()->subDays($days));
        });
    }
}
