<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $guarded = [];

    protected $casts = [
        'published_at' => 'datetime',
        'thumbnails' => 'array',
        'tags' => 'array',
        'content_details' => 'array',
        'statistics' => 'array',
        'channel' => 'array',
        'status' => 'array',
        'player' => 'array',
    ];


    public function playlist()
    {
        return $this->belongsTo(Playlist::class);
    }
}
