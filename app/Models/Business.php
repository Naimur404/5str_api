<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Business extends Model
{
    protected $table = 'businesses';

    use HasFactory, SoftDeletes;
    protected $hidden = [
       
        'created_at',
        'updated_at',
       
    ];

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'cover_image',
        'description',
        'phone',
        'email',
        'website',
        'is_verified',
        'is_featured',
        'is_online',
        'is_offline',
        'status',
        'average_rating',
        'total_reviews',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_featured' => 'boolean',
        'is_online' => 'boolean',
        'is_offline' => 'boolean',
        'average_rating' => 'float',
        'total_reviews' => 'integer',
    ];

    // Relationships
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public function mainLocation()
    {
        return $this->hasOne(Location::class)->where('is_primary', true);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function productCategories()
    {
        return $this->hasMany(ProductCategory::class);
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    // Recalculate average rating
    public function recalculateRating()
    {
        $this->average_rating = $this->reviews()->avg('rating') ?? 0;
        $this->total_reviews = $this->reviews()->count();
        $this->save();
    }
}
