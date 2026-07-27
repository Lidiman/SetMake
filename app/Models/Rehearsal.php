<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rehearsal extends Model
{
    /** @use HasFactory<\Database\Factories\RehearsalFactory> */
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
        return $this->belongsToMany(User::class, 'rehearsal_user')->withPivot('status')->withTimestamps();
    }

    public function checklists()
    {
        return $this->hasMany(RehearsalChecklist::class);
    }
}
