<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Restaurant categories
        $restaurantCategories = [
            'Appetizers' => 1,
            'Main Courses' => 2,
            'Desserts' => 3,
            'Beverages' => 4,
            'Specials' => 5,
        ];
        
        // Coffee & Tea categories
        $coffeeCategories = [
            'Hot Coffee' => 1,
            'Cold Coffee' => 2,
            'Teas' => 3,
            'Pastries' => 4,
            'Snacks' => 5,
        ];
        
        // Clothing store categories
        $clothingCategories = [
            'Men' => 1,
            'Women' => 2,
            'Kids' => 3,
            'Accessories' => 4,
            'Footwear' => 5,
        ];
        
        // Dry cleaning categories
        $dryCleaningCategories = [
            'Regular Laundry' => 1,
            'Dry Cleaning' => 2,
            'Express Service' => 3,
            'Special Items' => 4,
        ];
        
        // Beauty & Spa categories
        $beautyCategories = [
            'Hair Services' => 1,
            'Massage' => 2,
            'Facials' => 3,
            'Manicure & Pedicure' => 4,
            'Makeup' => 5,
        ];
        
        // Electronics categories
        $electronicsCategories = [
            'Phones & Tablets' => 1,
            'Computers' => 2,
            'Accessories' => 3,
            'Home Appliances' => 4,
            'Audio & Video' => 5,
        ];
        
        // Get businesses by category
        $businesses = Business::with('categories')->get();
        
        foreach ($businesses as $business) {
            $categoryIds = $business->categories->pluck('id')->toArray();
            
            // Assign product categories based on business type
            if (in_array(1, $categoryIds)) { // Restaurants
                foreach ($restaurantCategories as $name => $order) {
                    ProductCategory::create([
                        'business_id' => $business->id,
                        'name' => $name,
                        'display_order' => $order,
                        'is_active' => true,
                    ]);
                }
            } elseif (in_array(2, $categoryIds)) { // Coffee & Tea
                foreach ($coffeeCategories as $name => $order) {
                    ProductCategory::create([
                        'business_id' => $business->id,
                        'name' => $name,
                        'display_order' => $order,
                        'is_active' => true,
                    ]);
                }
            } elseif (in_array(7, $categoryIds)) { // Fashion
                foreach ($clothingCategories as $name => $order) {
                    ProductCategory::create([
                        'business_id' => $business->id,
                        'name' => $name,
                        'display_order' => $order,
                        'is_active' => true,
                    ]);
                }
            } elseif (in_array(3, $categoryIds)) { // Dry Clean
                foreach ($dryCleaningCategories as $name => $order) {
                    ProductCategory::create([
                        'business_id' => $business->id,
                        'name' => $name,
                        'display_order' => $order,
                        'is_active' => true,
                    ]);
                }
            } elseif (in_array(5, $categoryIds)) { // Beauty & Spa
                foreach ($beautyCategories as $name => $order) {
                    ProductCategory::create([
                        'business_id' => $business->id,
                        'name' => $name,
                        'display_order' => $order,
                        'is_active' => true,
                    ]);
                }
            } elseif (in_array(6, $categoryIds)) { // Electronics
                foreach ($electronicsCategories as $name => $order) {
                    ProductCategory::create([
                        'business_id' => $business->id,
                        'name' => $name,
                        'display_order' => $order,
                        'is_active' => true,
                    ]);
                }
            } else {
                // Default categories
                ProductCategory::create([
                    'business_id' => $business->id,
                    'name' => 'Popular Items',
                    'display_order' => 1,
                    'is_active' => true,
                ]);
                
                ProductCategory::create([
                    'business_id' => $business->id,
                    'name' => 'New Arrivals',
                    'display_order' => 2,
                    'is_active' => true,
                ]);
                
                ProductCategory::create([
                    'business_id' => $business->id,
                    'name' => 'Featured',
                    'display_order' => 3,
                    'is_active' => true,
                ]);
            }
        }
    }
}