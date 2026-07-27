<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SongChecklist extends Model
{
    /** @use HasFactory<\Database\Factories\SongChecklistFactory> */
    use HasFactory;

    protected $fillable = [
        'song_id',
        'task',
        'is_completed',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
    ];

    public function song()
    {
        return $this->belongsTo(Song::class);
    }
}
