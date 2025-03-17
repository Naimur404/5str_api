<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiSearchController extends Controller
{
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|min:2',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $searchTerm = $request->query;

        // Search businesses
        $businesses = Business::with('mainLocation')
            ->where('status', 'active')
            ->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%");
            })
            ->take(5)
            ->get();

        // Search products
        $products = Product::with('business')
            ->where('is_available', true)
            ->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%");
            })
            ->take(5)
            ->get();

        return response()->json([
            'businesses' => $businesses,
            'products' => $products,
        ]);
    }

    public function filter(Request $request)
    {
        $query = Business::with('mainLocation')
            ->where('status', 'active');

        // Filter by category
        if ($request->has('categories') && is_array($request->categories)) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->whereIn('categories.id', $request->categories);
            });
        }

        // Filter by rating
        if ($request->has('min_rating')) {
            $query->where('average_rating', '>=', $request->min_rating);
        }

        // Filter by location
        if ($request->has('latitude') && $request->has('longitude') && $request->has('distance')) {
            $latitude = $request->latitude;
            $longitude = $request->longitude;
            $distance = $request->distance;

            $query->whereHas('locations', function ($q) use ($latitude, $longitude, $distance) {
                $q->selectRaw("
                    locations.*,
                    (6371 * acos(
                        cos(radians($latitude)) * 
                        cos(radians(latitude)) * 
                        cos(radians(longitude) - radians($longitude)) + 
                        sin(radians($latitude)) * 
                        sin(radians(latitude))
                    )) AS distance
                ")
                ->havingRaw("distance < $distance");
            });
        }

        // Sort results
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'rating':
                    $query->orderBy('average_rating', 'desc');
                    break;
                case 'reviews':
                    $query->orderBy('total_reviews', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('name');
            }
        } else {
            $query->orderBy('average_rating', 'desc');
        }

        $businesses = $query->paginate($request->per_page ?? 15);

        return response()->json($businesses);
    }
}
