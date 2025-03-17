<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Restaurants',
                'description' => 'All types of restaurants including fine dining, casual, and fast food',
                'display_order' => 1,
            ],
            [
                'name' => 'Coffee & Tea',
                'description' => 'Coffee shops, tea houses, and cafés',
                'display_order' => 2,
            ],
            [
                'name' => 'Dry Clean',
                'description' => 'Laundry services, dry cleaning, and garment care',
                'display_order' => 3,
            ],
            [
                'name' => 'Shopping',
                'description' => 'Retail stores, boutiques, and shopping malls',
                'display_order' => 4,
            ],
            [
                'name' => 'Beauty & Spa',
                'description' => 'Beauty salons, spas, and wellness centers',
                'display_order' => 5,
            ],
            [
                'name' => 'Electronics',
                'description' => 'Electronic gadgets, computers, and accessories',
                'display_order' => 6,
            ],
            [
                'name' => 'Fashion',
                'description' => 'Clothing stores, fashion boutiques, and accessories',
                'display_order' => 7,
            ],
            [
                'name' => 'Grocery',
                'description' => 'Grocery stores, supermarkets, and food markets',
                'display_order' => 8,
            ],
            [
                'name' => 'Home & Garden',
                'description' => 'Home decor, furniture, and gardening supplies',
                'display_order' => 9,
            ],
            [
                'name' => 'Health & Medical',
                'description' => 'Hospitals, clinics, and pharmacies',
                'display_order' => 10,
            ],
        ];
        
        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'display_order' => $category['display_order'],
                'is_active' => true,
            ]);
        }
    }
}