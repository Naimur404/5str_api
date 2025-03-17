<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $hidden = [
       
        'created_at',
        'updated_at',
       
    ];

    protected $fillable = [
        'business_id',
        'name',
        'slug',
        'description',
        'price',
        'discounted_price',
        'image',
        'is_popular',
        'is_available',
        'average_rating',
        'product_category_id',
    ];

    protected $casts = [
        'price' => 'float',
        'discounted_price' => 'float',
        'is_popular' => 'boolean',
        'is_available' => 'boolean',
        'average_rating' => 'float',
    ];

    // Relationships
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }
    public function recalculateRating()
    {
        $this->average_rating = $this->reviews()->avg('rating') ?? 0;
        $this->total_reviews = $this->reviews()->count();
        $this->save();
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }
}
