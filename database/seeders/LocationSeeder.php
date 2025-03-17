<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Array of Chittagong areas with coordinates
        $chittagongAreas = [
            'Halishahar' => ['latitude' => 22.3369, 'longitude' => 91.7978],
            'M M Ali Road' => ['latitude' => 22.3383, 'longitude' => 91.8135],
            'GEC Circle' => ['latitude' => 22.3590, 'longitude' => 91.8226],
            'Nasirabad' => ['latitude' => 22.3622, 'longitude' => 91.8217],
            'Jamalkhan' => ['latitude' => 22.3316, 'longitude' => 91.8351],
            'CDA Avenue' => ['latitude' => 22.3641, 'longitude' => 91.8170],
            'Agrabad' => ['latitude' => 22.3244, 'longitude' => 91.8107],
            'Khulshi' => ['latitude' => 22.3479, 'longitude' => 91.8027],
            'Chawkbazar' => ['latitude' => 22.3398, 'longitude' => 91.8339],
            'Patenga' => ['latitude' => 22.2384, 'longitude' => 91.7865],
            'Probortok Circle' => ['latitude' => 22.3622, 'longitude' => 91.8273],
            'New Market' => ['latitude' => 22.3370, 'longitude' => 91.8302],
        ];
        
        // Get all businesses
        $businesses = Business::all();
        
        // Specific business locations (matching the images)
        $specificLocations = [
            'Stolen' => [
                'address' => 'Halishahar, Khulshi, Road 2, CTG',
                'city' => 'Chittagong',
                'area' => 'Halishahar',
                'postal_code' => '4224',
                'latitude' => 22.3369,
                'longitude' => 91.7978,
            ],
            'Pasta Roma' => [
                'address' => 'M M Ali Road, Near AC Shopping Complex',
                'city' => 'Chittagong',
                'area' => 'M M Ali Road',
                'postal_code' => '4000',
                'latitude' => 22.3383,
                'longitude' => 91.8135,
            ],
            'Panshi Restaurant' => [
                'address' => 'GEC Circle, Near GEC Convention Center',
                'city' => 'Chittagong',
                'area' => 'GEC Circle',
                'postal_code' => '4100',
                'latitude' => 22.3590,
                'longitude' => 91.8226,
            ],
            'Koyla Restaurant' => [
                'address' => 'Nasirabad, Near Nasirabad Housing',
                'city' => 'Chittagong',
                'area' => 'Nasirabad',
                'postal_code' => '4218',
                'latitude' => 22.3622,
                'longitude' => 91.8217,
            ],
            "Kareem's Restaurant" => [
                'address' => 'Jamalkhan, Near Central Plaza',
                'city' => 'Chittagong',
                'area' => 'Jamalkhan',
                'postal_code' => '4000',
                'latitude' => 22.3316,
                'longitude' => 91.8351,
            ],
            'Bella Italia' => [
                'address' => 'CDA Avenue, Next to Chittagong Shopping Complex',
                'city' => 'Chittagong',
                'area' => 'CDA Avenue',
                'postal_code' => '4100',
                'latitude' => 22.3641,
                'longitude' => 91.8170,
            ],
            "Luigi's Pizzeria" => [
                'address' => '123 Main St, New Market',
                'city' => 'Chittagong',
                'area' => 'New Market',
                'postal_code' => '4000',
                'latitude' => 22.3370,
                'longitude' => 91.8302,
            ],
            'Slice of Heaven' => [
                'address' => '456 Oak Ave, Agrabad',
                'city' => 'Chittagong',
                'area' => 'Agrabad',
                'postal_code' => '4100',
                'latitude' => 22.3244,
                'longitude' => 91.8107,
            ],
            'Urban Threads' => [
                'address' => '123 Fashion Ave, Agrabad',
                'city' => 'Chittagong',
                'area' => 'Agrabad',
                'postal_code' => '4100',
                'latitude' => 22.3244,
                'longitude' => 91.8107,
            ],
            'Chic Boutique' => [
                'address' => '456 Style St, Khulshi',
                'city' => 'Chittagong',
                'area' => 'Khulshi',
                'postal_code' => '4225',
                'latitude' => 22.3479,
                'longitude' => 91.8027,
            ],
        ];
        
        // Add locations for businesses
        foreach ($businesses as $business) {
            // Check if specific location exists for this business
            if (array_key_exists($business->name, $specificLocations)) {
                $locationData = $specificLocations[$business->name];
                
                Location::create([
                    'business_id' => $business->id,
                    'address' => $locationData['address'],
                    'city' => $locationData['city'],
                    'area' => $locationData['area'],
                    'postal_code' => $locationData['postal_code'],
                    'latitude' => $locationData['latitude'],
                    'longitude' => $locationData['longitude'],
                    'is_primary' => true,
                ]);
            } else {
                // Select a random area for this business
                $areaName = array_rand($chittagongAreas);
                $area = $chittagongAreas[$areaName];
                
                Location::create([
                    'business_id' => $business->id,
                    'address' => fake()->streetAddress() . ', ' . $areaName,
                    'city' => 'Chittagong',
                    'area' => $areaName,
                    'postal_code' => fake()->numberBetween(4000, 4300),
                    'latitude' => $area['latitude'] + (mt_rand(-10, 10) / 1000),
                    'longitude' => $area['longitude'] + (mt_rand(-10, 10) / 1000),
                    'is_primary' => true,
                ]);
                
                // 30% chance to add a second location
                if (mt_rand(1, 100) <= 30) {
                    // Pick a different area
                    $secondAreaKey = array_rand(array_diff_key($chittagongAreas, [$areaName => null]));
                    $secondArea = $chittagongAreas[$secondAreaKey];
                    
                    Location::create([
                        'business_id' => $business->id,
                        'address' => fake()->streetAddress() . ', ' . $secondAreaKey,
                        'city' => 'Chittagong',
                        'area' => $secondAreaKey,
                        'postal_code' => fake()->numberBetween(4000, 4300),
                        'latitude' => $secondArea['latitude'] + (mt_rand(-10, 10) / 1000),
                        'longitude' => $secondArea['longitude'] + (mt_rand(-10, 10) / 1000),
                        'is_primary' => false,
                    ]);
                }
            }
        }
    }
}