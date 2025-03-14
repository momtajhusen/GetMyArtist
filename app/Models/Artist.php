<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'stage_name',
        'profile_managed_by',
        'contact_first_name',
        'contact_last_name',
        'phone',
        'email',
        'bio',
        'profile_photo',
        'is_premium',
        'social_links',
        'experience_years',
        'portfolio',
        'genre',
        'events',
        'booking_rate',   
        'location',     
        'awards',         
        'is_verified',   
    ];

    protected $casts = [
        'social_links' => 'array',
        'portfolio'    => 'array',
        'genre'        => 'array',
        'events'       => 'array',
        'awards'       => 'array', 
        'is_premium'   => 'boolean',
        'is_verified'  => 'boolean',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
