<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gig extends Model
{
    /** @use HasFactory<\Database\Factories\GigFactory> */
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
    ];

    public function setlist()
    {
        return $this->belongsTo(Setlist::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'gig_user')->withPivot('status')->withTimestamps();
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function requests()
    {
        return $this->hasMany(SongRequest::class);
    }

    public function getNetIncomeAttribute()
    {
        $totalExpenses = $this->expenses()->sum('amount');
        return ($this->payment + $this->tips) - $totalExpenses;
    }
}
