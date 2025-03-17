<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BusinessController extends Controller
{
    public function index(Request $request)
    {
        // Authorization check
        if (!Auth::user()->isAdmin() && !Auth::user()->isStaff()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $query = Business::with('mainLocation');

        // Search
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->has('category_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }

        $businesses = $query->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15);

        return response()->json($businesses);
    }

    public function store(Request $request)
    {
        // Authorization check
        if (!Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|max:2048',
            'cover_image' => 'nullable|image|max:2048',
            'is_verified' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_online' => 'nullable|boolean',
            'is_offline' => 'nullable|boolean',
            'status' => 'nullable|in:active,inactive,pending',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'area' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create business
        $business = new Business();
        $business->name = $request->name;
        $business->slug = Str::slug($request->name);
        $business->description = $request->description;
        $business->phone = $request->phone;
        $business->email = $request->email;
        $business->website = $request->website;
        $business->is_verified = $request->is_verified ?? false;
        $business->is_featured = $request->is_featured ?? false;
        $business->is_online = $request->is_online ?? true;
        $business->is_offline = $request->is_offline ?? true;
        $business->status = $request->status ?? 'active';

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('businesses/logos', 'public');
            $business->logo = $path;
        }

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('businesses/covers', 'public');
            $business->cover_image = $path;
        }

        $business->save();

        // Attach categories
        $business->categories()->attach($request->category_ids);

        // Create location
        $location = new Location();
        $location->business_id = $business->id;
        $location->address = $request->address;
        $location->city = $request->city;
        $location->area = $request->area;
        $location->postal_code = $request->postal_code;
        $location->latitude = $request->latitude;
        $location->longitude = $request->longitude;
        $location->is_primary = true;
        $location->save();

        return response()->json([
            'message' => 'Business created successfully',
            'business' => $business,
        ], 201);
    }

    public function show($id)
    {
        // Authorization check
        if (!Auth::user()->isAdmin() && !Auth::user()->isStaff()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $business = Business::with([
            'categories',
            'locations',
            'products',
            'productCategories',
            'offers',
        ])->findOrFail($id);

        return response()->json([
            'business' => $business,
        ]);
    }

    public function update(Request $request, $id)
    {
        // Authorization check
        if (!Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|max:2048',
            'cover_image' => 'nullable|image|max:2048',
            'is_verified' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_online' => 'nullable|boolean',
            'is_offline' => 'nullable|boolean',
            'status' => 'nullable|in:active,inactive,pending',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $business = Business::findOrFail($id);

        if ($request->filled('name')) {
            $business->name = $request->name;
            $business->slug = Str::slug($request->name);
        }

        if ($request->filled('description')) {
            $business->description = $request->description;
        }

        if ($request->filled('phone')) {
            $business->phone = $request->phone;
        }

        if ($request->filled('email')) {
            $business->email = $request->email;
        }

        if ($request->filled('website')) {
            $business->website = $request->website;
        }

        if ($request->has('is_verified')) {
            $business->is_verified = $request->is_verified;
        }

        if ($request->has('is_featured')) {
            $business->is_featured = $request->is_featured;
        }

        if ($request->has('is_online')) {
            $business->is_online = $request->is_online;
        }

        if ($request->has('is_offline')) {
            $business->is_offline = $request->is_offline;
        }

        if ($request->filled('status')) {
            $business->status = $request->status;
        }

        if ($request->hasFile('logo')) {
            if ($business->logo) {
                Storage::delete('public/' . $business->logo);
            }
            $path = $request->file('logo')->store('businesses/logos', 'public');
            $business->logo = $path;
        }

        if ($request->hasFile('cover_image')) {
            if ($business->cover_image) {
                Storage::delete('public/' . $business->cover_image);
            }
            $path = $request->file('cover_image')->store('businesses/covers', 'public');
            $business->cover_image = $path;
        }

        $business->save();

        // Update categories if provided
        if ($request->has('category_ids')) {
            $business->categories()->sync($request->category_ids);
        }

        return response()->json([
            'message' => 'Business updated successfully',
            'business' => $business,
        ]);
    }

    public function updateLocation(Request $request, $id)
    {
        // Authorization check
        if (!Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'location_id' => 'required|exists:locations,id',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_primary' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $business = Business::findOrFail($id);
        $location = Location::where('id', $request->location_id)
            ->where('business_id', $business->id)
            ->firstOrFail();

        if ($request->filled('address')) {
            $location->address = $request->address;
        }

        if ($request->filled('city')) {
            $location->city = $request->city;
        }

        if ($request->filled('area')) {
            $location->area = $request->area;
        }

        if ($request->filled('postal_code')) {
            $location->postal_code = $request->postal_code;
        }

        if ($request->filled('latitude')) {
            $location->latitude = $request->latitude;
        }

        if ($request->filled('longitude')) {
            $location->longitude = $request->longitude;
        }

        if ($request->has('is_primary') && $request->is_primary) {
            // Set all other locations as not primary
            Location::where('business_id', $business->id)
                ->where('id', '!=', $location->id)
                ->update(['is_primary' => false]);
            
            $location->is_primary = true;
        }

        $location->save();

        return response()->json([
            'message' => 'Location updated successfully',
            'location' => $location,
        ]);
    }

    public function addLocation(Request $request, $id)
    {
        // Authorization check
        if (!Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'area' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_primary' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $business = Business::findOrFail($id);

        $location = new Location();
        $location->business_id = $business->id;
        $location->address = $request->address;
        $location->city = $request->city;
        $location->area = $request->area;
        $location->postal_code = $request->postal_code;
        $location->latitude = $request->latitude;
        $location->longitude = $request->longitude;
        $location->is_primary = $request->is_primary ?? false;

        if ($location->is_primary) {
            // Set all other locations as not primary
            Location::where('business_id', $business->id)
                ->update(['is_primary' => false]);
        }

        $location->save();

        return response()->json([
            'message' => 'Location added successfully',
            'location' => $location,
        ], 201);
    }

    public function deleteLocation(Request $request, $id)
    {
        // Authorization check
        if (!Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'location_id' => 'required|exists:locations,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $business = Business::findOrFail($id);
        $location = Location::where('id', $request->location_id)
            ->where('business_id', $business->id)
            ->firstOrFail();

        // Can't delete if it's the only location
        if (Location::where('business_id', $business->id)->count() <= 1) {
            return response()->json([
                'message' => 'Cannot delete the only location for a business',
            ], 422);
        }

        // If deleting primary location, set another as primary
        if ($location->is_primary) {
            $newPrimary = Location::where('business_id', $business->id)
                ->where('id', '!=', $location->id)
                ->first();
            
            if ($newPrimary) {
                $newPrimary->is_primary = true;
                $newPrimary->save();
            }
        }

        $location->delete();

        return response()->json([
            'message' => 'Location deleted successfully',
        ]);
    }

    public function destroy($id)
    {
        // Authorization check
        if (!Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $business = Business::findOrFail($id);

        // Delete associated images
        if ($business->logo) {
            Storage::delete('public/' . $business->logo);
        }
        
        if ($business->cover_image) {
            Storage::delete('public/' . $business->cover_image);
        }

        // Note: Associated locations, products, offers, etc. will be deleted by cascade

        $business->delete();

        return response()->json([
            'message' => 'Business deleted successfully',
        ]);
    }
}

