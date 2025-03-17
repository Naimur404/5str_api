<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\Models\Business;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApiBusinessController extends Controller
{
    public function index(Request $request)
    {
        $query = Business::with('mainLocation')
            ->where('status', 'active');

        // Filter by category if provided
        if ($request->has('category_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }

        // Filter by featured
        if ($request->has('featured') && $request->featured) {
            $query->where('is_featured', true);
        }

        // Search by name
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Sort options
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'rating':
                    $query->orderBy('average_rating', 'desc');
                    break;
                case 'popularity':
                    $query->orderBy('total_reviews', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('name', 'asc');
            }
        } else {
            $query->orderBy('average_rating', 'desc');
        }

        // Default pagination
        $businesses = $query->paginate($request->per_page ?? 15);

        return response()->json($businesses);
    }

    public function show($id)
    {
        $business = Business::with([
            'locations',
            'categories',
            'products' => function ($query) {
                $query->where('is_available', true)
                      ->orderBy('is_popular', 'desc');
            },
            'productCategories',
            'offers' => function ($query) {
                $query->active();
            }
        ])->findOrFail($id);

        // Check if business is favorited by authenticated user
        $isFavorite = false;
        if (Auth::check()) {
            $isFavorite = Favorite::where('user_id', Auth::id())
                ->where('business_id', $business->id)
                ->exists();
        }

        $business->is_favorite = $isFavorite;

        return response()->json([
            'business' => $business,
        ]);
    }

    public function nearby(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'nullable|numeric|min:1',
            'category_id' => 'nullable|exists:categories,id',
            'search' => 'nullable|string',
            'sort' => 'nullable|string|in:distance,rating,popularity',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $radius = $request->radius ?? 20; // Default 20 kilometers
        $perPage = $request->per_page ?? 15;

        // Build the query with location distance calculation
        $query = DB::table('businesses')
            ->join('locations', 'businesses.id', '=', 'locations.business_id')
            ->select(
                'businesses.*',
                DB::raw("(6371 * acos(cos(radians($latitude)) * 
                cos(radians(latitude)) * cos(radians(longitude) - 
                radians($longitude)) + sin(radians($latitude)) * 
                sin(radians(latitude)))) AS distance")
            )
            ->where('businesses.status', 'active')
            ->having('distance', '<', $radius);
        
        // Apply category filter if provided
        if ($request->has('category_id')) {
            $query->join('business_category', 'businesses.id', '=', 'business_category.business_id')
                ->where('business_category.category_id', $request->category_id);
        }
        
        // Apply search filter if provided
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('businesses.name', 'like', "%{$searchTerm}%")
                  ->orWhere('businesses.description', 'like', "%{$searchTerm}%");
            });
        }
        
        // Apply sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'distance':
                    $query->orderBy('distance', 'asc');
                    break;
                case 'rating':
                    $query->orderBy('businesses.average_rating', 'desc')
                          ->orderBy('distance', 'asc');
                    break;
                case 'popularity':
                    $query->orderBy('businesses.total_reviews', 'desc')
                          ->orderBy('distance', 'asc');
                    break;
                default:
                    $query->orderBy('distance', 'asc');
            }
        } else {
            // Default sort by distance
            $query->orderBy('distance', 'asc');
        }
        
        // Convert to collection and add additional info
        $businesses = $query->get();
        
        // Add distance formattedand load relationships
        $businessesWithDistance = $businesses->map(function($business) {
            // Format distance
            $business->distance_km = round($business->distance, 1);
            $business->distance_text = $business->distance_km . ' km away';
            
            // Load main location
            $location = DB::table('locations')
                ->where('business_id', $business->id)
                ->where('is_primary', true)
                ->first();
                
            if ($location) {
                $business->location = $location;
            }
            
            return $business;
        });
        
        // Paginate manually
        $total = count($businessesWithDistance);
        $page = $request->page ?? 1;
        $offset = ($page - 1) * $perPage;
        
        $paginatedItems = array_slice($businessesWithDistance->toArray(), $offset, $perPage);
        
        $result = [
            'current_page' => (int)$page,
            'data' => $paginatedItems,
            'first_page_url' => url('/api/businesses/nearby?page=1'),
            'from' => $offset + 1,
            'last_page' => ceil($total / $perPage),
            'last_page_url' => url('/api/businesses/nearby?page=' . ceil($total / $perPage)),
            'next_page_url' => $page < ceil($total / $perPage) ? url('/api/businesses/nearby?page=' . ($page + 1)) : null,
            'path' => url('/api/businesses/nearby'),
            'per_page' => (int)$perPage,
            'prev_page_url' => $page > 1 ? url('/api/businesses/nearby?page=' . ($page - 1)) : null,
            'to' => min($offset + $perPage, $total),
            'total' => $total,
        ];

        return response()->json($result);
    }

    public function popular()
    {
        $businesses = Business::with('mainLocation')
            ->where('status', 'active')
            ->where('is_featured', true)
            ->orderBy('average_rating', 'desc')
            ->take(10)
            ->get();

        return response()->json([
            'businesses' => $businesses,
        ]);
    }

    public function topRated()
    {
        $businesses = Business::with('mainLocation')
            ->where('status', 'active')
            ->where('average_rating', '>=', 4.0)
            ->orderBy('average_rating', 'desc')
            ->take(10)
            ->get();

        return response()->json([
            'businesses' => $businesses,
        ]);
    }
}
