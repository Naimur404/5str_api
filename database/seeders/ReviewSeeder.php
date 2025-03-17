<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all businesses and users
        $businesses = Business::all();
        $users = User::where('user_type', 'user')->get();
        
        // Review titles and comments for realism
        $reviewTitles = [
            'A Must Try',
            'Excellent Service',
            'Great Experience',
            'Highly Recommended',
            'Will Come Back Again',
            'Outstanding',
            'Loved It',
            'Impressed',
            'Satisfied Customer',
            'Worth the Visit',
        ];
        
        $positiveComments = [
            'Place was great. My wife and I loved it. The food was amazing and felt very authentic.',
            'Amazing service and very friendly staff. The quality was top-notch.',
            'Absolutely loved the experience. Will definitely be returning soon!',
            'Best place in Chittagong for this type of service. Highly recommended to everyone.',
            'Very impressed with the quality and the attention to detail. The staff were very professional.',
            'Exceeded my expectations in every way. Can\'t wait to visit again.',
            'Outstanding value for money. The quality was much better than I expected.',
            'A hidden gem in Chittagong. So glad I discovered this place!',
            'Great ambiance and excellent service. Made our special occasion even more memorable.',
            'Friendly staff and excellent service. The quality is consistent every time I visit.',
        ];
        
        $mixedComments = [
            'Good overall but a few things could be improved. The service was a bit slow at times.',
            'Nice place but a bit pricey for what you get. The quality is good though.',
            'Enjoyed most aspects but there is room for improvement. Will give it another try.',
            'Good experience but the place was very crowded. May be better on weekdays.',
            'The service was excellent but the location is a bit hard to find.',
        ];
        
        $gaveReviewer = [
            'Gav',
            'Riyad',
            'Tahmid',
            'Anik',
            'Sajid',
            'Rubel',
            'Pritom',
        ];
        
        // Create specific review for Stolen as seen in the screenshots
        $stolenBusiness = Business::where('name', 'Stolen')->first();
        $specificReviewer = User::where('user_type', 'user')->inRandomOrder()->first();
        
        if ($stolenBusiness && $specificReviewer) {
            for ($i = 0; $i < 3; $i++) {
                Review::create([
                    'user_id' => $specificReviewer->id,
                    'business_id' => $stolenBusiness->id,
                    'rating' => 5.0,
                    'title' => 'A Must Try',
                    'comment' => 'Place was great. My wife and I loved it. The food was amazing and felt very...',
                    'is_approved' => true,
                ]);
            }
        }
        
        // Generate random reviews for all businesses
        foreach ($businesses as $business) {
            // Skip Stolen because we already added reviews
            if ($business->name === 'Stolen' && $stolenBusiness) {
                continue;
            }
            
            // Calculate number of reviews for this business (based on total_reviews from business table)
            $numReviews = $business->total_reviews;
            if ($numReviews == 0) {
                $numReviews = mt_rand(5, 15); // Default if not set
            }
            
            // Create reviews
            for ($i = 0; $i < $numReviews; $i++) {
                $user = $users->random();
                $rating = $this->generateRatingBasedOnAverage($business->average_rating);
                
                // Determine comment based on rating
                if ($rating >= 4.0) {
                    $comment = $positiveComments[array_rand($positiveComments)];
                    $title = $reviewTitles[array_rand($reviewTitles)];
                } else {
                    $comment = $mixedComments[array_rand($mixedComments)];
                    $title = 'Decent Experience';
                }
                
                // Create the review
                Review::create([
                    'user_id' => $user->id,
                    'business_id' => $business->id,
                    'rating' => $rating,
                    'title' => $title,
                    'comment' => $comment,
                    'is_approved' => true,
                ]);
            }
        }
    }
    
    /**
     * Generate a rating close to the business average
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