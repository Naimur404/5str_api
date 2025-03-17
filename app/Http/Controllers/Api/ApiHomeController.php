<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\Business;
use App\Models\Offer;
use Illuminate\Support\Facades\DB;


class ApiHomeController extends Controller
{
    /**
     * Get all data needed for the home page
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'nullable|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $radius = $request->radius ?? 20; // Default radius is 20 kilometers

        // Get categories for top services section
        $categories = Category::where('is_active', true)
            ->orderBy('display_order')
            ->take(8)
            ->get();

        // Get businesses within radius
        $nearbyBusinessIds = $this->getNearbyBusinessIds($latitude, $longitude, $radius);
        
        // If no businesses are found nearby, just return top rated ones without location filter
        if (count($nearbyBusinessIds) == 0) {
            return response()->json([
                'message' => 'No businesses found within ' . $radius . 'km of your location. Showing top rated businesses instead.',
                'categories' => $categories,
                'top_services' => $this->getTopServicesByCategory($categories),
                'popular_services' => $this->getPopularBusinesses(),
                'special_offers' => $this->getSpecialOffers(),
                'top_pizza' => $this->getBusinessesByKeyword('pizza'),
                'top_clothing' => $this->getBusinessesByCategory('Fashion'),
            ]);
        }

        // Get data for home page sections
        $topServices = $this->getTopServicesByCategory($categories, $nearbyBusinessIds);
        $popularServices = $this->getPopularBusinesses($nearbyBusinessIds);
        $specialOffers = $this->getSpecialOffers($nearbyBusinessIds);
        $topPizza = $this->getBusinessesByKeyword('pizza', $nearbyBusinessIds);
        $topClothing = $this->getBusinessesByCategory('Fashion', $nearbyBusinessIds);

        return response()->json([
            'categories' => $categories,
            'top_services' => $topServices,
            'popular_services' => $popularServices,
            'special_offers' => $specialOffers,
            'top_pizza' => $topPizza,
            'top_clothing' => $topClothing,
        ]);
    }

    /**
     * Get top services grouped by category
     * 
     * @param \Illuminate\Database\Eloquent\Collection $categories
     * @param array $businessIds
     * @return array
     */
    protected function getTopServicesByCategory($categories, array $businessIds = [])
    {
        $result = [];

        foreach ($categories as $category) {
            $query = Business::with('mainLocation')
                ->where('status', 'active')
                ->whereHas('categories', function ($query) use ($category) {
                    $query->where('categories.id', $category->id);
                })
                ->orderBy('average_rating', 'desc');

            // Filter by business IDs if provided
            if (!empty($businessIds)) {
                $query->whereIn('id', $businessIds);
            }

            $businesses = $query->take(5)->get();

            // Only add category if it has businesses
            if ($businesses->count() > 0) {
                $result[] = [
                    'category' => $category,
                    'businesses' => $businesses,
                ];
            }
        }

        return $result;
    }

    /**
     * Get popular services (highest rated businesses)
     * 
     * @param array $businessIds
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getPopularBusinesses(array $businessIds = [])
    {
        $query = Business::with('mainLocation')
            ->where('status', 'active')
            ->where('average_rating', '>=', 4.0)
            ->orderBy('average_rating', 'desc')
            ->orderBy('total_reviews', 'desc');

        // Filter by business IDs if provided
        if (!empty($businessIds)) {
            $query->whereIn('id', $businessIds);
        }

        return $query->take(10)->get();
    }

    /**
     * Get special offers
     * 
     * @param array $businessIds
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getSpecialOffers(array $businessIds = [])
    {
        $query = Offer::with('business.mainLocation')
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            });

        // Filter by business IDs if provided
        if (!empty($businessIds)) {
            $query->whereIn('business_id', $businessIds);
        }

        return $query->orderBy('created_at', 'desc')->take(10)->get();
    }

    /**
     * Get businesses by keyword in name or description
     * 
     * @param string $keyword
     * @param array $businessIds
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getBusinessesByKeyword($keyword, array $businessIds = [])
    {
        $query = Business::with('mainLocation')
            ->where('status', 'active')
            ->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%");
            })
            ->orderBy('average_rating', 'desc');

        // Filter by business IDs if provided
        if (!empty($businessIds)) {
            $query->whereIn('id', $businessIds);
        }

        return $query->take(5)->get();
    }

    /**
     * Get businesses by category name
     * 
     * @param string $categoryName
     * @param array $businessIds
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getBusinessesByCategory($categoryName, array $businessIds = [])
    {
        $query = Business::with('mainLocation')
            ->where('status', 'active')
            ->whereHas('categories', function ($query) use ($categoryName) {
                $query->where('name', $categoryName);
            })
            ->orderBy('average_rating', 'desc');

        // Filter by business IDs if provided
        if (!empty($businessIds)) {
            $query->whereIn('id', $businessIds);
        }

        return $query->take(5)->get();
    }

    /**
     * Get IDs of businesses within radius
     * 
     * @param float $latitude
     * @param float $longitude
     * @param float $radius
     * @return array
     */
    protected function getNearbyBusinessIds($latitude, $longitude, $radius)
    {
        // Using Haversine formula to calculate distance
        $businesses = DB::table('businesses')
            ->join('locations', 'businesses.id', '=', 'locations.business_id')
            ->select('businesses.id', 
                DB::raw("(6371 * acos(cos(radians($latitude)) * 
                cos(radians(latitude)) * cos(radians(longitude) - 
                radians($longitude)) + sin(radians($latitude)) * 
                sin(radians(latitude)))) AS distance"))
            ->where('businesses.status', 'active')
            ->having('distance', '<', $radius)
            ->orderBy('distance')
            ->get();

        return $businesses->pluck('id')->toArray();
    }
    
    /**
     * Get nearby businesses for a specific category
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function categoryNearby(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'nullable|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $categoryId = $request->category_id;
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $radius = $request->radius ?? 20; // Default radius is 20 kilometers

        // Get nearby businesses in this category with distance
        $businesses = DB::table('businesses')
            ->join('locations', 'businesses.id', '=', 'locations.business_id')
            ->join('business_category', 'businesses.id', '=', 'business_category.business_id')
            ->select('businesses.*', 
                DB::raw("(6371 * acos(cos(radians($latitude)) * 
                cos(radians(latitude)) * cos(radians(longitude) - 
                radians($longitude)) + sin(radians($latitude)) * 
                sin(radians(latitude)))) AS distance"))
            ->where('business_category.category_id', $categoryId)
            ->where('businesses.status', 'active')
            ->having('distance', '<', $radius)
            ->orderBy('distance')
            ->take(15)
            ->get();

        // Map the results to include the distance in km
        $businessesWithDistance = $businesses->map(function($business) {
            $business->distance_km = round($business->distance, 1);
            return $business;
        });

        return response()->json([
            'businesses' => $businessesWithDistance,
        ]);
    }
}
