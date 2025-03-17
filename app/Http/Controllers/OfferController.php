<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class OfferController extends Controller
{
    public function index(Request $request)
    {
        // Authorization check
        if (!Auth::user()->isAdmin() && !Auth::user()->isStaff()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'business_id' => 'required|exists:businesses,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $offers = Offer::with('product')
            ->where('business_id', $request->business_id)
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15);

        return response()->json($offers);
    }

    public function store(Request $request)
    {
        // Authorization check
        if (!Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'business_id' => 'required|exists:businesses,id',
            'product_id' => 'nullable|exists:products,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'code' => 'nullable|string|max:50',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Verify product belongs to the business
        if ($request->filled('product_id')) {
            $productBelongsToBusiness = Product::where('id', $request->product_id)
                ->where('business_id', $request->business_id)
                ->exists();
            
            if (!$productBelongsToBusiness) {
                return response()->json([
                    'message' => 'The selected product does not belong to this business',
                ], 422);
            }
        }

        $offer = new Offer();
        $offer->business_id = $request->business_id;
        $offer->product_id = $request->product_id;
        $offer->title = $request->title;
        $offer->description = $request->description;
        $offer->code = $request->code;
        $offer->discount_percentage = $request->discount_percentage;
        $offer->discount_amount = $request->discount_amount;
        $offer->starts_at = $request->starts_at;
        $offer->expires_at = $request->expires_at;
        $offer->is_active = $request->is_active ?? true;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('offers', 'public');
            $offer->image = $path;
        }

        $offer->save();

        return response()->json([
            'message' => 'Offer created successfully',
            'offer' => $offer,
        ], 201);
    }

    public function show($id)
    {
        // Authorization check
        if (!Auth::user()->isAdmin() && !Auth::user()->isStaff()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $offer = Offer::with(['business', 'product'])->findOrFail($id);

        return response()->json([
            'offer' => $offer,
        ]);
    }

    public function update(Request $request, $id)
    {
        // Authorization check
        if (!Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'product_id' => 'nullable|exists:products,id',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'code' => 'nullable|string|max:50',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $offer = Offer::findOrFail($id);

        // Verify product belongs to the business
        if ($request->filled('product_id')) {
            $productBelongsToBusiness = Product::where('id', $request->product_id)
                ->where('business_id', $offer->business_id)
                ->exists();
            
            if (!$productBelongsToBusiness) {
                return response()->json([
                    'message' => 'The selected product does not belong to this business',
                ], 422);
            }
            
            $offer->product_id = $request->product_id;
        }

        if ($request->filled('title')) {
            $offer->title = $request->title;
        }

        if ($request->filled('description')) {
            $offer->description = $request->description;
        }

        if ($request->filled('code')) {
            $offer->code = $request->code;
        }

        if ($request->filled('discount_percentage')) {
            $offer->discount_percentage = $request->discount_percentage;
        }

        if ($request->filled('discount_amount')) {
            $offer->discount_amount = $request->discount_amount;
        }

        if ($request->filled('starts_at')) {
            $offer->starts_at = $request->starts_at;
        }

        if ($request->filled('expires_at')) {
            $offer->expires_at = $request->expires_at;
        }

        if ($request->has('is_active')) {
            $offer->is_active = $request->is_active;
        }

        if ($request->hasFile('image')) {
            if ($offer->image) {
                Storage::delete('public/' . $offer->image);
            }
            $path = $request->file('image')->store('offers', 'public');
            $offer->image = $path;
        }

        $offer->save();

        return response()->json([
            'message' => 'Offer updated successfully',
            'offer' => $offer,
        ]);
    }

    public function destroy($id)
    {
        // Authorization check
        if (!Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $offer = Offer::findOrFail($id);

        if ($offer->image) {
            Storage::delete('public/' . $offer->image);
        }

        $offer->delete();

        return response()->json([
            'message' => 'Offer deleted successfully',
        ]);
    }
}
