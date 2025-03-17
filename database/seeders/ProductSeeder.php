<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed products specifically for "stolen." (as seen in the images)
        $stolenBusiness = Business::where('name', 'Stolen')->first();
        if ($stolenBusiness) {
            $stolenProducts = [
                [
                    'name' => 'T-shirt',
                    'description' => 'Stylish t-shirt with artistic design featuring waves and graphics',
                    'price' => 350,
                    'is_popular' => true,
                    'product_category_id' => ProductCategory::where('business_id', $stolenBusiness->id)
                        ->where('name', 'Appetizers')
                        ->first()->id,
                    'average_rating' => 4.9,
                ],
                [
                    'name' => 'Hoodie',
                    'description' => 'Comfortable hoodie with unique stolen brand design',
                    'price' => 950,
                    'is_popular' => true,
                    'product_category_id' => ProductCategory::where('business_id', $stolenBusiness->id)
                        ->where('name', 'Beverages')
                        ->first()->id,
                    'average_rating' => 4.8,
                ],
                [
                    'name' => 'Cap',
                    'description' => 'Stylish cap with stolen logo',
                    'price' => 250,
                    'is_popular' => false,
                    'product_category_id' => ProductCategory::where('business_id', $stolenBusiness->id)
                        ->where('name', 'Beverages')
                        ->first()->id,
                    'average_rating' => 4.7,
                ],
                [
                    'name' => 'Sneakers',
                    'description' => 'Fashionable sneakers with comfortable fit',
                    'price' => 1200,
                    'is_popular' => false,
                    'product_category_id' => ProductCategory::where('business_id', $stolenBusiness->id)
                        ->where('name', 'Beverages')
                        ->first()->id,
                    'average_rating' => 4.6,
                ],
            ];
            
            foreach ($stolenProducts as $productData) {
                Product::create([
                    'business_id' => $stolenBusiness->id,
                    'name' => $productData['name'],
                    'slug' => Str::slug($productData['name'] . '-' . Str::random(5)),
                    'description' => $productData['description'],
                    'price' => $productData['price'],
                    'is_popular' => $productData['is_popular'],
                    'is_available' => true,
                    'product_category_id' => $productData['product_category_id'],
                    'average_rating' => $productData['average_rating'],
                    'total_reviews' => mt_rand(5, 20),
                ]);
            }
        }
        
        // Restaurant menu items
        $restaurantItems = [
            'Appetizers' => [
                ['Chicken Wings', 'Crispy fried chicken wings tossed in your choice of sauce', 350, 0],
                ['Mozzarella Sticks', 'Golden fried mozzarella sticks with marinara sauce', 300, 0],
                ['Garlic Bread', 'Freshly baked bread with garlic butter and herbs', 200, 0],
                ['Spring Rolls', 'Crispy vegetable spring rolls with sweet chili sauce', 250, 0],
                ['Soup of the Day', 'Chef\'s daily soup creation', 150, 0],
            ],
            'Main Courses' => [
                ['Grilled Chicken', 'Marinated grilled chicken with vegetables and rice', 550, 0],
                ['Beef Steak', 'Premium beef steak cooked to your preference', 850, 0],
                ['Fish & Chips', 'Beer-battered fish with crispy fries and tartar sauce', 600, 0],
                ['Vegetable Pasta', 'Fettuccine pasta with mixed vegetables in cream sauce', 450, 0],
                ['Chicken Biryani', 'Aromatic rice dish with spiced chicken and raita', 500, 0],
            ],
            'Desserts' => [
                ['Chocolate Cake', 'Rich chocolate cake with ganache', 250, 0],
                ['Cheesecake', 'New York style cheesecake with berry compote', 300, 0],
                ['Ice Cream', 'Three scoops of assorted ice cream flavors', 200, 0],
                ['Tiramisu', 'Classic Italian coffee-flavored dessert', 350, 0],
            ],
            'Beverages' => [
                ['Fresh Juice', 'Choice of seasonal fresh fruit juices', 150, 0],
                ['Soft Drinks', 'Various carbonated beverages', 100, 0],
                ['Mineral Water', 'Bottle of mineral water', 50, 0],
                ['Hot Coffee', 'Freshly brewed coffee', 120, 0],
                ['Iced Tea', 'Refreshing iced tea with lemon', 120, 0],
            ],
        ];
        
        // Coffee shop items
        $coffeeShopItems = [
            'Hot Coffee' => [
                ['Espresso', 'Strong concentrated coffee shot', 120, 0],
                ['Americano', 'Espresso diluted with hot water', 140, 0],
                ['Cappuccino', 'Espresso with steamed milk and foam', 180, 0],
                ['Latte', 'Espresso with steamed milk', 180, 0],
                ['Mocha', 'Espresso with chocolate and steamed milk', 200, 0],
            ],
            'Cold Coffee' => [
                ['Iced Americano', 'Chilled espresso with water and ice', 160, 0],
                ['Iced Latte', 'Espresso with cold milk and ice', 200, 0],
                ['Frappuccino', 'Blended coffee with ice and whipped cream', 250, 0],
                ['Cold Brew', 'Slow-steeped cold coffee', 220, 0],
            ],
            'Teas' => [
                ['Green Tea', 'Traditional green tea', 120, 0],
                ['Black Tea', 'Strong black tea', 120, 0],
                ['Herbal Tea', 'Caffeine-free herbal infusion', 140, 0],
                ['Chai Tea Latte', 'Spiced tea with steamed milk', 180, 0],
            ],
            'Pastries' => [
                ['Croissant', 'Buttery French pastry', 150, 0],
                ['Chocolate Muffin', 'Soft chocolate muffin', 120, 0],
                ['Cinnamon Roll', 'Sweet pastry with cinnamon and frosting', 180, 0],
                ['Cheesecake', 'Creamy cheesecake with graham crust', 200, 0],
            ],
        ];
        
        // Clothing items
        $clothingItems = [
            'Men' => [
                ['T-shirt', 'Comfortable cotton t-shirt', 350, 0],
                ['Jeans', 'Classic denim jeans', 750, 0],
                ['Shirt', 'Formal button-up shirt', 550, 0],
                ['Sweater', 'Warm knit sweater', 650, 0],
                ['Jacket', 'Stylish casual jacket', 1200, 0],
            ],
            'Women' => [
                ['Blouse', 'Elegant women\'s blouse', 450, 0],
                ['Dress', 'Casual day dress', 850, 0],
                ['Skirt', 'Versatile midi skirt', 550, 0],
                ['Jeans', 'Slim-fit women\'s jeans', 750, 0],
                ['Top', 'Fashionable tank top', 350, 0],
            ],
            'Accessories' => [
                ['Belt', 'Leather belt with metal buckle', 250, 0],
                ['Scarf', 'Soft patterned scarf', 200, 0],
                ['Hat', 'Trendy cap', 250, 0],
                ['Sunglasses', 'UV protection sunglasses', 350, 0],
                ['Jewelry', 'Assorted fashion jewelry', 300, 0],
            ],
        ];
        
        // Dry cleaning services
        $dryCleaningServices = [
            'Regular Laundry' => [
                ['Shirt/Blouse', 'Washing and ironing of shirts or blouses', 50, 0],
                ['Pants/Trousers', 'Washing and pressing of pants or trousers', 70, 0],
                ['T-shirt', 'Washing and folding of t-shirts', 40, 0],
                ['Bed Sheet', 'Washing and pressing of bed sheets', 100, 0],
                ['Towel', 'Washing and drying of towels', 60, 0],
            ],
            'Dry Cleaning' => [
                ['Suit', 'Dry cleaning of complete suit', 350, 0],
                ['Dress', 'Dry cleaning of dresses', 250, 0],
                ['Coat/Jacket', 'Dry cleaning of coats or jackets', 300, 0],
                ['Sweater', 'Dry cleaning of sweaters', 200, 0],
                ['Silk Items', 'Specialized cleaning for silk garments', 250, 0],
            ],
            'Express Service' => [
                ['Same Day Service', 'Rush service with same-day completion', 150, 0],
                ['Next Day Service', 'Priority service with next-day completion', 100, 0],
            ],
        ];
        
        // Generate products for all businesses
        $businesses = Business::with('categories')->get();
        
        foreach ($businesses as $business) {
            $categoryIds = $business->categories->pluck('id')->toArray();
            $businessProductCategories = ProductCategory::where('business_id', $business->id)->get();
            
            // Skip "Stolen" as we've already added its products
            if ($business->name === 'Stolen') {
                continue;
            }
            
            // Different product types based on business category
            if (in_array(1, $categoryIds)) { // Restaurants
                foreach ($businessProductCategories as $productCategory) {
                    if (isset($restaurantItems[$productCategory->name])) {
                        foreach ($restaurantItems[$productCategory->name] as $item) {
                            $isPopular = mt_rand(0, 100) < 20; // 20% chance of being popular
                            $discountedPrice = mt_rand(0, 100) < 30 ? $item[2] - ($item[2] * 0.1) : null; // 30% chance of discount
                            
                            Product::create([
                                'business_id' => $business->id,
                                'name' => $item[0],
                                'slug' => Str::slug($item[0] . '-' . Str::random(5)),
                                'description' => $item[1],
                                'price' => $item[2] + $item[3], // Base price + random addition
                                'discounted_price' => $discountedPrice,
                                'is_popular' => $isPopular,
                                'is_available' => true,
                                'product_category_id' => $productCategory->id,
                                'average_rating' => round(mt_rand(35, 50) / 10, 1), // Random rating between 3.5-5.0
                                'total_reviews' => mt_rand(0, 15),
                            ]);
                        }
                    }
                }
            } elseif (in_array(2, $categoryIds)) { // Coffee & Tea
                foreach ($businessProductCategories as $productCategory) {
                    if (isset($coffeeShopItems[$productCategory->name])) {
                        foreach ($coffeeShopItems[$productCategory->name] as $item) {
                            $isPopular = mt_rand(0, 100) < 20;
                            $discountedPrice = mt_rand(0, 100) < 30 ? $item[2] - ($item[2] * 0.1) : null;
                            
                            Product::create([
                                'business_id' => $business->id,
                                'name' => $item[0],
                                'slug' => Str::slug($item[0] . '-' . Str::random(5)),
                                'description' => $item[1],
                                'price' => $item[2] + $item[3],
                                'discounted_price' => $discountedPrice,
                                'is_popular' => $isPopular,
                                'is_available' => true,
                                'product_category_id' => $productCategory->id,
                                'average_rating' => round(mt_rand(35, 50) / 10, 1),
                                'total_reviews' => mt_rand(0, 15),
                            ]);
                        }
                    }
                }
            } elseif (in_array(7, $categoryIds)) { // Fashion
                foreach ($businessProductCategories as $productCategory) {
                    if (isset($clothingItems[$productCategory->name])) {
                        foreach ($clothingItems[$productCategory->name] as $item) {
                            $isPopular = mt_rand(0, 100) < 20;
                            $discountedPrice = mt_rand(0, 100) < 30 ? $item[2] - ($item[2] * 0.15) : null;
                            
                            Product::create([
                                'business_id' => $business->id,
                                'name' => $item[0],
                                'slug' => Str::slug($item[0] . '-' . Str::random(5)),
                                'description' => $item[1],
                                'price' => $item[2] + mt_rand(0, 200),
                                'discounted_price' => $discountedPrice,
                                'is_popular' => $isPopular,
                                'is_available' => true,
                                'product_category_id' => $productCategory->id,
                                'average_rating' => round(mt_rand(35, 50) / 10, 1),
                                'total_reviews' => mt_rand(0, 15),
                            ]);
                        }
                    }
                }
            } elseif (in_array(3, $categoryIds)) { // Dry Clean
                foreach ($businessProductCategories as $productCategory) {
                    if (isset($dryCleaningServices[$productCategory->name])) {
                        foreach ($dryCleaningServices[$productCategory->name] as $item) {
                            $isPopular = mt_rand(0, 100) < 20;
                            $discountedPrice = mt_rand(0, 100) < 30 ? $item[2] - ($item[2] * 0.1) : null;
                            
                            Product::create([
                                'business_id' => $business->id,
                                'name' => $item[0],
                                'slug' => Str::slug($item[0] . '-' . Str::random(5)),
                                'description' => $item[1],
                                'price' => $item[2] + mt_rand(0, 50),
                                'discounted_price' => $discountedPrice,
                                'is_popular' => $isPopular,
                                'is_available' => true,
                                'product_category_id' => $productCategory->id,
                                'average_rating' => round(mt_rand(35, 50) / 10, 1),
                                'total_reviews' => mt_rand(0, 15),
                            ]);
                        }
                    }
                }
            } else {
                // Generic products for other businesses
                for ($i = 1; $i <= mt_rand(5, 10); $i++) {
                    $isPopular = mt_rand(0, 100) < 30;
                    $price = mt_rand(100, 1000);
                    $discountedPrice = mt_rand(0, 100) < 30 ? $price - ($price * 0.15) : null;
                    
                    Product::create([
                        'business_id' => $business->id,
                        'name' => 'Product ' . $i,
                        'slug' => Str::slug('product-' . $i . '-' . Str::random(5)),
                        'description' => 'Description for Product ' . $i,
                        'price' => $price,
                        'discounted_price' => $discountedPrice,
                        'is_popular' => $isPopular,
                        'is_available' => true,
                        'product_category_id' => $businessProductCategories->random()->id,
                        'average_rating' => round(mt_rand(35, 50) / 10, 1),
                        'total_reviews' => mt_rand(0, 15),
                    ]);
                }
            }
        }
    }
}