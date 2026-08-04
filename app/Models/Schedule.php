<?php

namespace App\Models;

use App\Enums\ScheduleStatus;
use App\Enums\ScheduleType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type',
        'title',
        'date',
        'start_time',
        'end_time',
        'status',
        'description',
        'location',
        'venue',
        'address',
        'contact_person',
        'phone',
        'payment',
        'tips',
        'transport',
        'parking',
        'food',
        'equipment_rental',
        'other_expenses',
        'setlist_id',
        'created_by',
    ];

    protected $casts = [
        'type'       => ScheduleType::class,
        'status'     => ScheduleStatus::class,
        'date'       => 'date',
        'start_time' => 'datetime:H:i',
        'end_time'   => 'datetime:H:i',
        'payment'    => 'decimal:2',
        'tips'       => 'decimal:2',
        'transport'  => 'decimal:2',
        'parking'    => 'decimal:2',
        'food'       => 'decimal:2',
        'equipment_rental' => 'decimal:2',
        'other_expenses'   => 'decimal:2',
    ];

    /* ── Relations ─────────────────────────────────────────── */

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
        return $this->belongsToMany(User::class, 'schedule_user')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function performances(): HasMany
    {
        return $this->hasMany(Performance::class);
    }

    /* ── Helpers ────────────────────────────────────────────── */

    public function isGig(): bool
    {
        return $this->type === ScheduleType::Gig;
    }

    public function isRehearsal(): bool
    {
        return $this->type === ScheduleType::Rehearsal;
    }

    public function getTotalExpensesAttribute(): float
    {
        return (float) (
            $this->transport + $this->parking + $this->food +
            $this->equipment_rental + $this->other_expenses
        );
    }

    public function getGrossIncomeAttribute(): float
    {
        return (float) ($this->payment + $this->tips);
    }

    public function getNetIncomeAttribute(): float
    {
        return $this->gross_income - $this->total_expenses;
    }

    /* ── Scopes ─────────────────────────────────────────────── */

    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now()->toDateString())
            ->whereNotIn('status', ['cancelled', 'completed'])
            ->orderBy('date');
    }

    public function scopePast($query)
    {
        return $query->where(function ($q) {
            $q->where('date', '<', now()->toDateString())
              ->orWhereIn('status', ['completed', 'cancelled']);
        })->orderByDesc('date');
    }

    public function scopeHistory($query)
    {
        return $query->withTrashed()
            ->whereIn('status', ['completed', 'cancelled'])
            ->orderByDesc('date');
    }

    public function scopeRehearsal($query)
    {
        return $query->where('type', ScheduleType::Rehearsal->value);
    }

    public function scopeGig($query)
    {
        return $query->where('type', ScheduleType::Gig->value);
    }

    public function scopeByMonth($query, int $year, int $month)
    {
        return $query->whereYear('date', $year)->whereMonth('date', $month);
    }
}
