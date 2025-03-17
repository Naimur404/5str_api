<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all products and users
        $products = Product::all();
        $users = User::where('user_type', 'user')->get();
        
        // Review titles for products
        $reviewTitles = [
            'Great Quality',
            'Excellent Product',
            'Highly Recommend',
            'Worth the Price',
            'Best Purchase',
            'Exactly as Described',
            'Very Satisfied',
            'Exceeded Expectations',
            'Perfect Fit',
            'Outstanding Value',
        ];
        
        $positiveComments = [
            'This product is excellent. The quality is top-notch and it exceeds expectations.',
            'Very satisfied with my purchase. Exactly what I was looking for!',
            'Great value for money. The quality is much better than I expected for the price.',
            'Highly recommend this product. It\'s durable and well-made.',
            'Perfect item! Looks exactly as shown in the images and works flawlessly.',
            'I\'ve purchased this multiple times and never been disappointed. Consistent quality.',
            'This has become my go-to product. Can\'t imagine using anything else now.',
            'Outstanding quality and excellent design. Very pleased with this purchase.',
            'Impressed with how well this works. Definitely worth every penny!',
            'The quality is exceptional. This will last for a long time.',
        ];
        
        $mixedComments = [
            'Good product but a bit overpriced. Would be perfect if it cost less.',
            'Nice quality but arrived with minor defects. Still usable though.',
            'Decent product but took longer than expected to arrive.',
            'The product is good but the description could be more accurate.',
            'Works as expected but nothing extraordinary. Solid 3-star product.',
        ];
        
        // Generate reviews for T-shirts from Stolen (matching image)
        $stolenTshirts = Product::where('name', 'T-shirt')
            ->whereHas('business', function ($query) {
                $query->where('name', 'Stolen');
            })
            ->get();
        
        if ($stolenTshirts->count() > 0) {
            foreach ($stolenTshirts as $tshirt) {
                // Number of reviews should match the total_reviews field
                $numReviews = $tshirt->total_reviews > 0 ? $tshirt->total_reviews : mt_rand(5, 15);
                
                for ($i = 0; $i < $numReviews; $i++) {
                    $user = $users->random();
                    $rating = 4.9; // Match the rating shown in the image
                    
                    ProductReview::create([
                        'user_id' => $user->id,
                        'product_id' => $tshirt->id,
                        'rating' => $rating,
                        'title' => $reviewTitles[array_rand($reviewTitles)],
                        'comment' => $positiveComments[array_rand($positiveComments)],
                        'is_approved' => true,
                    ]);
                }
            }
        }
        
        // Generate random reviews for other products
        foreach ($products as $product) {
            // Skip stolen T-shirts as we already added their reviews
            if ($product->name === 'T-shirt' && $product->business->name === 'Stolen') {
                continue;
            }
            
            // 60% chance of having reviews for each product
            if (mt_rand(1, 100) <= 60) {
                $numReviews = $product->total_reviews > 0 ? $product->total_reviews : mt_rand(1, 8);
                
                for ($i = 0; $i < $numReviews; $i++) {
                    $user = $users->random();
                    $rating = $this->generateRatingBasedOnAverage($product->average_rating);
                    
                    // Determine comment based on rating
                    if ($rating >= 4.0) {
                        $comment = $positiveComments[array_rand($positiveComments)];
                        $title = $reviewTitles[array_rand($reviewTitles)];
                    } else {
                        $comment = $mixedComments[array_rand($mixedComments)];
                        $title = 'Decent Product';
                    }
                    
                    // 5% chance of an unapproved review
                    $isApproved = mt_rand(1, 100) <= 95;
                    
                    ProductReview::create([
                        'user_id' => $user->id,
                        'product_id' => $product->id,
                        'rating' => $rating,
                        'title' => $title,
                        'comment' => $comment,
                        'is_approved' => $isApproved,
                    ]);
                }
            }
        }
    }
    
    /**
     * Generate a rating close to the product average
     */
    private function generateRatingBasedOnAverage($averageRating)
    {
        // Generate a random rating close to the average (within 1.0 point)
        $min = max(1.0, $averageRating - 1.0);
        $max = min(5.0, $averageRating + 1.0);
        
        // Round to nearest 0.5
        $rating = mt_rand($min * 10, $max * 10) / 10;
        return round($rating * 2) / 2;
    }
}