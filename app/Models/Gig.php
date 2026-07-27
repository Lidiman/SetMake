<?php

namespace App\Models;

use App\Enums\GigStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gig extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'venue',
        'address',
        'date',
        'start_time',
        'end_time',
        'contact_person',
        'phone',
        'description',
        'payment',
        'tips',
        'transport',
        'parking',
        'food',
        'equipment_rental',
        'other_expenses',
        'status',
        'setlist_id',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'payment' => 'decimal:2',
        'tips' => 'decimal:2',
        'transport' => 'decimal:2',
        'parking' => 'decimal:2',
        'food' => 'decimal:2',
        'equipment_rental' => 'decimal:2',
        'other_expenses' => 'decimal:2',
        'status' => GigStatus::class,
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
        return $this->belongsToMany(User::class, 'gig_user')->withPivot('status')->withTimestamps();
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(SongRequest::class);
    }

    public function performances(): HasMany
    {
        return $this->hasMany(Performance::class);
    }

    public function getTotalExpensesAttribute(): float
    {
        return (float) ($this->transport + $this->parking + $this->food + $this->equipment_rental + $this->other_expenses);
    }

    public function getGrossIncomeAttribute(): float
    {
        return (float) ($this->payment + $this->tips);
    }

    public function getNetIncomeAttribute(): float
    {
        return $this->gross_income - $this->total_expenses;
    }

    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now()->toDateString())
            ->whereIn('status', [GigStatus::Planned, GigStatus::Confirmed])
            ->orderBy('date');
    }

    public function scopePast($query)
    {
        return $query->where('date', '<', now()->toDateString())
            ->orWhere('status', GigStatus::Completed)
            ->orderByDesc('date');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', GigStatus::Completed);
    }

    public function scopeByStatus($query, GigStatus $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByMonth($query, int $year, int $month)
    {
        return $query->whereYear('date', $year)->whereMonth('date', $month);
    }
}
