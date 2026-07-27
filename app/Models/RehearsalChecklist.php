<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RehearsalChecklist extends Model
{
    /** @use HasFactory<\Database\Factories\RehearsalChecklistFactory> */
    use HasFactory;

    protected $fillable = [
        'rehearsal_id',
        'task',
        'is_completed',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
    ];

    public function rehearsal()
    {
        return $this->belongsTo(Rehearsal::class);
    }
}
