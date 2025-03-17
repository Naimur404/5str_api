<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Offer;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Coffee shop offer as seen in the screenshots (50% off)
        $coffeeShops = Business::whereHas('categories', function ($query) {
            $query->where('categories.id', 2); // Coffee & Tea category - Fixed the ambiguous column reference
        })->get();
        
        if ($coffeeShops->count() > 0) {
            foreach ($coffeeShops as $coffeeShop) {
                Offer::create([
                    'business_id' => $coffeeShop->id,
                    'title' => 'Get 50% off',
                    'description' => 'Limited time offer - 50% off on all coffee drinks',
                    'discount_percentage' => 50,
                    'starts_at' => Carbon::now()->subDays(5),
                    'expires_at' => Carbon::now()->addDays(5),
                    'is_active' => true,
                ]);
            }
        }
        
        // Create offers for various businesses
        $businesses = Business::all();
        
        foreach ($businesses as $business) {
            // 60% chance of having an offer
            if (mt_rand(1, 100) <= 60) {
                // Business-wide offers
                $offerType = mt_rand(1, 3);
                
                if ($offerType == 1) {
                    // Percentage discount
                    $percentage = mt_rand(1, 5) * 10; // 10%, 20%, 30%, 40%, or 50%
                    
                    Offer::create([
                        'business_id' => $business->id,
                        'title' => 'Get ' . $percentage . '% off',
                        'description' => 'Limited time offer - ' . $percentage . '% off on all items',
                        'discount_percentage' => $percentage,
                        'starts_at' => Carbon::now()->subDays(mt_rand(1, 10)),
                        'expires_at' => Carbon::now()->addDays(mt_rand(5, 30)),
                        'is_active' => true,
                    ]);
                } elseif ($offerType == 2) {
                    // Fixed amount discount
                    $amount = mt_rand(1, 5) * 50; // 50, 100, 150, 200, or 250 BDT
                    
                    Offer::create([
                        'business_id' => $business->id,
                        'title' => 'Save ৳' . $amount,
                        'description' => 'Get ৳' . $amount . ' off on orders over ৳' . ($amount * 3),
                        'discount_amount' => $amount,
                        'starts_at' => Carbon::now()->subDays(mt_rand(1, 10)),
                        'expires_at' => Carbon::now()->addDays(mt_rand(5, 30)),
                        'is_active' => true,
                    ]);
                } elseif ($offerType == 3) {
                    // Special promotion
                    Offer::create([
                        'business_id' => $business->id,
                        'title' => 'Special Deal',
                        'description' => 'Buy one get one free on selected items',
                        'code' => 'BOGO' . mt_rand(100, 999),
                        'starts_at' => Carbon::now()->subDays(mt_rand(1, 10)),
                        'expires_at' => Carbon::now()->addDays(mt_rand(5, 30)),
                        'is_active' => true,
                    ]);
                }
                
                // 40% chance of having product-specific offers
                if (mt_rand(1, 100) <= 40) {
                    // Get products for this business
                    $products = Product::where('business_id', $business->id)
                        ->where('is_popular', true)
                        ->inRandomOrder()
                        ->take(2)
                        ->get();
                    
                    foreach ($products as $product) {
                        $discountPercentage = mt_rand(1, 3) * 10; // 10%, 20%, or 30%
                        
                        Offer::create([
                            'business_id' => $business->id,
                            'product_id' => $product->id,
                            'title' => $discountPercentage . '% off ' . $product->name,
                            'description' => 'Limited time offer - Save ' . $discountPercentage . '% on ' . $product->name,
                            'discount_percentage' => $discountPercentage,
                            'starts_at' => Carbon::now()->subDays(mt_rand(1, 5)),
                            'expires_at' => Carbon::now()->addDays(mt_rand(10, 20)),
                            'is_active' => true,
                        ]);
                    }
                }
            }
        }
    }
}