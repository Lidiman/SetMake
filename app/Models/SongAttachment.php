<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SongAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'song_id',
        'name',
        'type',
        'file_path',
        'uploaded_by',
    ];

    public function song()
    {
        return $this->belongsTo(Song::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
