<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SongRequest extends Model
{
    /** @use HasFactory<\Database\Factories\SongRequestFactory> */
    use HasFactory;

    protected $fillable = [
        'song_name',
        'requested_by',
        'quantity',
        'is_performed',
        'gig_id',
    ];

    protected $casts = [
        'is_performed' => 'boolean',
    ];

    public function gig()
    {
        return $this->belongsTo(Gig::class);
    }
}
