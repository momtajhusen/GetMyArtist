<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Album extends Model
{
    use HasFactory;

    protected $fillable = [
        'artist_id', 'title', 'description', 'media_type', 
        'storage_path', 'media_url', 'store', 'duration', 
        'size', 'status'
    ];

    // Artist relation
    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    // Get media URL (for local & cloud)
    public function getMediaUrlAttribute()
    {
        if ($this->media_type === 'youtube' || $this->media_type === 'cloud') {
            return $this->media_url;
        } elseif ($this->media_type === 'video' || $this->media_type === 'image') {
            return Storage::url($this->storage_path);
        }
        return null;
    }
}
