<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Restaurants in Chittagong
        $restaurants = [
            [
                'name' => 'Stolen',
                'description' => 'Popular restaurant and cafe serving a variety of local and international dishes',
                'phone' => '01188855',
                'email' => 'info@stolen.com',
                'website' => 'https://stolen.com',
                'is_verified' => true,
                'is_featured' => true,
                'is_online' => true,
                'is_offline' => true,
                'status' => 'active',
                'categories' => [1], // Restaurants
                'average_rating' => 4.9,
                'total_reviews' => 22,
            ],
            [
                'name' => 'Pasta Roma',
                'description' => 'Authentic Italian restaurant specializing in pasta and pizza',
                'phone' => '01723456789',
                'email' => 'info@pastaroma.com',
                'website' => 'https://pastaroma.com',
                'is_verified' => true,
                'is_featured' => true,
                'is_online' => true,
                'is_offline' => true,
                'status' => 'active',
                'categories' => [1], // Restaurants
                'average_rating' => 4.3,
                'total_reviews' => 15,
            ],
            [
                'name' => 'Panshi Restaurant',
                'description' => 'Traditional Bangladeshi restaurant known for authentic local cuisine',
                'phone' => '01812345678',
                'email' => 'info@panshirestaurant.com',
                'website' => 'https://panshirestaurant.com',
                'is_verified' => true,
                'is_featured' => true,
                'is_online' => true,
                'is_offline' => true,
                'status' => 'active',
                'categories' => [1], // Restaurants
                'average_rating' => 4.1,
                'total_reviews' => 18,
            ],
            [
                'name' => 'Koyla Restaurant',
                'description' => 'Specializing in grilled and barbecue dishes with a modern twist',
                'phone' => '01912345678',
                'email' => 'info@koylarestaurant.com',
                'website' => 'https://koylarestaurant.com',
                'is_verified' => true,
                'is_featured' => false,
                'is_online' => true,
                'is_offline' => true,
                'status' => 'active',
                'categories' => [1], // Restaurants
                'average_rating' => 4.5,
                'total_reviews' => 12,
            ],
            [
                'name' => "Kareem's Restaurant",
                'description' => 'Middle Eastern and Arabian cuisine in a luxurious setting',
                'phone' => '01723456780',
                'email' => 'info@kareems.com',
                'website' => 'https://kareems.com',
                'is_verified' => true,
                'is_featured' => true,
                'is_online' => true,
                'is_offline' => true,
                'status' => 'active',
                'categories' => [1], // Restaurants
                'average_rating' => 4.0,
                'total_reviews' => 10,
            ],
            [
                'name' => 'Bella Italia',
                'description' => 'Cozy Italian restaurant with authentic flavors from Italy',
                'phone' => '01812345679',
                'email' => 'info@bellaitalia.com',
                'website' => 'https://bellaitalia.com',
                'is_verified' => true,
                'is_featured' => false,
                'is_online' => true,
                'is_offline' => true,
                'status' => 'active',
                'categories' => [1], // Restaurants
                'average_rating' => 4.6,
                'total_reviews' => 14,
            ],
            [
                'name' => "Luigi's Pizzeria",
                'description' => 'Authentic wood-fired pizza made with imported Italian ingredients',
                'phone' => '01912345679',
                'email' => 'info@luigispizzeria.com',
                'website' => 'https://luigispizzeria.com',
                'is_verified' => true,
                'is_featured' => false,
                'is_online' => true,
                'is_offline' => true,
                'status' => 'active',
                'categories' => [1], // Restaurants
                'average_rating' => 4.5,
                'total_reviews' => 16,
            ],
            [
                'name' => 'Slice of Heaven',
                'description' => 'Specialty pizza and Italian-inspired dishes in a casual setting',
                'phone' => '01723456781',
                'email' => 'info@sliceofheaven.com',
                'website' => 'https://sliceofheaven.com',
                'is_verified' => true,
                'is_featured' => false,
                'is_online' => true,
                'is_offline' => true,
                'status' => 'active',
                'categories' => [1], // Restaurants
                'average_rating' => 4.2,
                'total_reviews' => 9,
            ],
        ];
        
        // Coffee Shops in Chittagong
        $coffeeShops = [
            [
                'name' => 'Coffee World',
                'description' => 'International coffee chain offering premium coffee and pastries',
                'phone' => '01812345680',
                'email' => 'info@coffeeworld.com',
                'website' => 'https://coffeeworld.com',
                'is_verified' => true,
                'is_featured' => true,
                'is_online' => true,
                'is_offline' => true,
                'status' => 'active',
                'categories' => [2], // Coffee & Tea
                'average_rating' => 4.7,
                'total_reviews' => 20,
            ],
            [
                'name' => 'Café Barista',
                'description' => 'Specialty coffee shop with skilled baristas and house-roasted beans',
                'phone' => '01912345680',
                'email' => 'info@cafebarista.com',
                'website' => 'https://cafebarista.com',
                'is_verified' => true,
                'is_featured' => false,
                'is_online' => true,
                'is_offline' => true,
                'status' => 'active',
                'categories' => [2], // Coffee & Tea
                'average_rating' => 4.4,
                'total_reviews' => 11,
            ],
            [
                'name' => 'Tea Lounge',
                'description' => 'Traditional and modern tea blends in a relaxing atmosphere',
                'phone' => '01723456782',
                'email' => 'info@tealounge.com',
                'website' => 'https://tealounge.com',
                'is_verified' => true,
                'is_featured' => false,
                'is_online' => true,
                'is_offline' => true,
                'status' => 'active',
                'categories' => [2], // Coffee & Tea
                'average_rating' => 4.3,
                'total_reviews' => 8,
            ],
        ];
        
        // Clothing stores in Chittagong
        $clothingStores = [
            [
                'name' => 'Urban Threads',
                'description' => 'Contemporary fashion for young adults with a focus on urban style',
                'phone' => '01812345681',
                'email' => 'info@urbanthreads.com',
                'website' => 'https://urbanthreads.com',
                'is_verified' => true,
                'is_featured' => true,
                'is_online' => true,
                'is_offline' => true,
                'status' => 'active',
                'categories' => [4, 7], // Shopping, Fashion
                'average_rating' => 4.5,
                'total_reviews' => 13,
            ],
            [
                'name' => 'Chic Boutique',
                'description' => 'Curated fashion pieces and designer clothing for women',
                'phone' => '01912345681',
                'email' => 'info@chicboutique.com',
                'website' => 'https://chicboutique.com',
                'is_verified' => true,
                'is_featured' => false,
                'is_online' => true,
                'is_offline' => true,
                'status' => 'active',
                'categories' => [4, 7], // Shopping, Fashion
                'average_rating' => 4.2,
                'total_reviews' => 9,
            ],
            [
                'name' => 'Trendy Fashions',
                'description' => 'Latest fashion trends at affordable prices',
                'phone' => '01723456783',
                'email' => 'info@trendyfashions.com',
                'website' => 'https://trendyfashions.com',
                'is_verified' => true,
                'is_featured' => false,
                'is_online' => true,
                'is_offline' => true,
                'status' => 'active',
                'categories' => [4, 7], // Shopping, Fashion
                'average_rating' => 4.0,
                'total_reviews' => 7,
            ],
        ];
        
        // Dry Cleaning in Chittagong
        $dryCleaning = [
            [
                'name' => 'Sparkle Clean',
                'description' => 'Professional dry cleaning services with eco-friendly options',
                'phone' => '01812345682',
                'email' => 'info@sparkleclean.com',
                'website' => 'https://sparkleclean.com',
                'is_verified' => true,
                'is_featured' => true,
                'is_online' => true,
                'is_offline' => true,
                'status' => 'active',
                'categories' => [3], // Dry Clean
                'average_rating' => 4.6,
                'total_reviews' => 15,
            ],
            [
                'name' => 'Express Laundry',
                'description' => 'Quick turnaround laundry and dry cleaning services',
                'phone' => '01912345682',
                'email' => 'info@expresslaundry.com',
                'website' => 'https://expresslaundry.com',
                'is_verified' => true,
                'is_featured' => false,
                'is_online' => true,
                'is_offline' => true,
                'status' => 'active',
                'categories' => [3], // Dry Clean
                'average_rating' => 4.2,
                'total_reviews' => 8,
            ],
        ];
        
        // Beauty & Spa in Chittagong
        $beautySpa = [
            [
                'name' => 'Serenity Spa',
                'description' => 'Luxury spa treatments and massage therapy',
                'phone' => '01812345683',
                'email' => 'info@serenityspa.com',
                'website' => 'https://serenityspa.com',
                'is_verified' => true,
                'is_featured' => true,
                'is_online' => true,
                'is_offline' => true,
                'status' => 'active',
                'categories' => [5], // Beauty & Spa
                'average_rating' => 4.8,
                'total_reviews' => 22,
            ],
            [
                'name' => 'Glamour Salon',
                'description' => 'Full-service beauty salon offering hair, nails, and makeup',
                'phone' => '01912345683',
                'email' => 'info@glamoursalon.com',
                'website' => 'https://glamoursalon.com',
                'is_verified' => true,
                'is_featured' => false,
                'is_online' => true,
                'is_offline' => true,
                'status' => 'active',
                'categories' => [5], // Beauty & Spa
                'average_rating' => 4.5,
                'total_reviews' => 14,
            ],
        ];
        
        // Electronics Stores in Chittagong
        $electronics = [
            [
                'name' => 'Tech Haven',
                'description' => 'Latest gadgets, computers, and electronic accessories',
                'phone' => '01812345684',
                'email' => 'info@techhaven.com',
                'website' => 'https://techhaven.com',
                'is_verified' => true,
                'is_featured' => true,
                'is_online' => true,
                'is_offline' => true,
                'status' => 'active',
                'categories' => [4, 6], // Shopping, Electronics
                'average_rating' => 4.4,
                'total_reviews' => 16,
            ],
            [
                'name' => 'Gadget World',
                'description' => 'Wide range of electronics and smart home devices',
                'phone' => '01912345684',
                'email' => 'info@gadgetworld.com',
                'website' => 'https://gadgetworld.com',
                'is_verified' => true,
                'is_featured' => false,
                'is_online' => true,
                'is_offline' => true,
                'status' => 'active',
                'categories' => [4, 6], // Shopping, Electronics
                'average_rating' => 4.3,
                'total_reviews' => 12,
            ],
        ];
        
        // Combine all businesses
        $allBusinesses = array_merge(
            $restaurants,
            $coffeeShops,
            $clothingStores,
            $dryCleaning,
            $beautySpa,
            $electronics
        );
        
        // Create each business and its category relationships
        foreach ($allBusinesses as $businessData) {
            $business = Business::create([
                'name' => $businessData['name'],
                'slug' => Str::slug($businessData['name']),
                'description' => $businessData['description'],
                'phone' => $businessData['phone'],
                'email' => $businessData['email'],
                'website' => $businessData['website'],
                'is_verified' => $businessData['is_verified'],
                'is_featured' => $businessData['is_featured'],
                'is_online' => $businessData['is_online'],
                'is_offline' => $businessData['is_offline'],
                'status' => $businessData['status'],
                'average_rating' => $businessData['average_rating'],
                'total_reviews' => $businessData['total_reviews'],
            ]);
            
            // Attach categories
            $business->categories()->attach($businessData['categories']);
        }
    }
}