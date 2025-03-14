<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'type',
        'value',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
