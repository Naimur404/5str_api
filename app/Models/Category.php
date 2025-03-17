<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    use HasFactory;
    protected $hidden = [
       
        'created_at',
        'updated_at',
       
    ];

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'description',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    // Relationships
    public function businesses()
    {
        return $this->belongsToMany(Business::class);
    }
}
