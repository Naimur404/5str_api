<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Clear all tables before seeding
        DB::table('users')->truncate();
        DB::table('categories')->truncate();
        DB::table('businesses')->truncate();
        DB::table('business_category')->truncate();
        DB::table('locations')->truncate();
        DB::table('product_categories')->truncate();
        DB::table('products')->truncate();
        DB::table('reviews')->truncate();
        DB::table('product_reviews')->truncate();
        DB::table('offers')->truncate();
        DB::table('favorites')->truncate();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        $this->call([
            SettingSeeder::class,
            PermissionSeeder::class,
   
            SuperAdminSeeder::class,
            CategorySeeder::class,
            BusinessSeeder::class,
            LocationSeeder::class,
            ProductCategorySeeder::class,
            ProductSeeder::class,
            ReviewSeeder::class,
            ProductReviewSeeder::class,
            OfferSeeder::class,
            FavoriteSeeder::class,


        ]);
    }
}
