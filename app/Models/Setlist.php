<?php

namespace App\Models;

use App\Enums\SetlistType;
use App\Enums\SongReadiness;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Setlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'scheduled_at',
        'venue',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'type' => SetlistType::class,
            'scheduled_at' => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function songs(): BelongsToMany
    {
        return $this->belongsToMany(Song::class, 'setlist_song')
            ->withPivot('position', 'notes', 'readiness')
            ->withTimestamps()
            ->orderByPivot('position');
    }

    public function performances(): HasMany
    {
        return $this->hasMany(Performance::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function getTotalDurationAttribute(): int
    {
        return $this->songs->sum('duration') ?? 0;
    }

    public function getFormattedTotalDurationAttribute(): string
    {
        $total = $this->total_duration;
        $hours = floor($total / 3600);
        $minutes = floor(($total % 3600) / 60);
        $seconds = $total % 60;

        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%d:%02d', $minutes, $seconds);
    }

    public function getSongCountAttribute(): int
    {
        return $this->songs->count();
    }

    public function getAverageBpmAttribute(): ?int
    {
        $songsWithBpm = $this->songs->filter(fn($s) => $s->bpm);
        if ($songsWithBpm->isEmpty()) {
            return null;
        }
        return (int) round($songsWithBpm->avg('bpm'));
    }

    public function getReadinessCountsAttribute(): array
    {
        $ready = 0;
        $needsPractice = 0;
        $notReady = 0;

        foreach ($this->songs as $song) {
            $readiness = $song->pivot->readiness ?? 'needs_practice';
            switch ($readiness) {
                case 'ready':
                    $ready++;
                    break;
                case 'not_ready':
                    $notReady++;
                    break;
                default:
                    $needsPractice++;
                    break;
            }
        }

        return [
            'ready' => $ready,
            'needs_practice' => $needsPractice,
            'not_ready' => $notReady,
            'total' => $this->song_count,
            'percentage' => $this->song_count > 0 ? round(($ready / $this->song_count) * 100) : 0,
        ];
    }

    public function scopeUpcoming($query)
    {
        return $query->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at');
    }

    public function scopePast($query)
    {
        return $query->where('scheduled_at', '<', now())
            ->orderByDesc('scheduled_at');
    }

    public function scopeRehearsals($query)
    {
        return $query->where('type', SetlistType::Rehearsal);
    }

    public function scopePerformances($query)
    {
        return $query->where('type', SetlistType::Performance);
    }
}
