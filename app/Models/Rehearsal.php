<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rehearsal extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'date',
        'start_time',
        'end_time',
        'location',
        'description',
        'setlist_id',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function setlist(): BelongsTo
    {
        return $this->belongsTo(Setlist::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'rehearsal_user')->withPivot('status')->withTimestamps();
    }

    public function checklists(): HasMany
    {
        return $this->hasMany(RehearsalChecklist::class);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now()->toDateString())->orderBy('date');
    }

    public function scopePast($query)
    {
        return $query->where('date', '<', now()->toDateString())->orderByDesc('date');
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])->orderBy('date');
    }

    public function scopeByMonth($query, int $year, int $month)
    {
        return $query->whereYear('date', $year)->whereMonth('date', $month);
    }
}
