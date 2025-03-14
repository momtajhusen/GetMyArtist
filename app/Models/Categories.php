<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;

      protected $fillable = ['name', 'description', 'parent_id', 'status', 'image', 'created_by'];

    public function parent()
    {
        return $this->belongsTo(Categories::class, 'parent_id');
    }

    public function subcategories()
    {
        return $this->hasMany(Categories::class, 'parent_id')->with('subcategories');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }
}
