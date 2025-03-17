<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Favorite;
use App\Models\User;
use Illuminate\Database\Seeder;

class FavoriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users and businesses
        $users = User::where('user_type', 'user')->get();
        $businesses = Business::all();
        
        // Each user will have 2-6 favorite businesses
        foreach ($users as $user) {
            $numFavorites = mt_rand(2, 6);
            $favoritedBusinesses = $businesses->random($numFavorites);
            
            foreach ($favoritedBusinesses as $business) {
                Favorite::create([
                    'user_id' => $user->id,
                    'business_id' => $business->id,
                ]);
            }
        }
        
        // Make sure Stolen has extra favorites
        $stolenBusiness = Business::where('name', 'Stolen')->first();
        if ($stolenBusiness) {
            // Add more users who favorite Stolen
            foreach ($users->random(mt_rand(5, 10)) as $user) {
                // Check if favorite already exists
                $existingFavorite = Favorite::where('user_id', $user->id)
                    ->where('business_id', $stolenBusiness->id)
                    ->exists();
                
                if (!$existingFavorite) {
                    Favorite::create([
                        'user_id' => $user->id,
                        'business_id' => $stolenBusiness->id,
                    ]);
                }
            }
        }
    }
}