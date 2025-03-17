<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
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

        $query = Product::where('business_id', $request->business_id);

        // Search
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('product_category_id', $request->category_id);
        }

        $products = $query->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15);

        return response()->json($products);
    }

    public function store(Request $request)
    {
        // Authorization check
        if (!Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'business_id' => 'required|exists:businesses,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'is_popular' => 'nullable|boolean',
            'is_available' => 'nullable|boolean',
            'product_category_id' => 'nullable|exists:product_categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Verify product_category_id belongs to the specified business
        if ($request->filled('product_category_id')) {
            $categoryBelongsToBusiness = ProductCategory::where('id', $request->product_category_id)
                ->where('business_id', $request->business_id)
                ->exists();
            
            if (!$categoryBelongsToBusiness) {
                return response()->json([
                    'message' => 'The selected product category does not belong to this business',
                ], 422);
            }
        }

        $product = new Product();
        $product->business_id = $request->business_id;
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->description = $request->description;
        $product->price = $request->price;
        $product->discounted_price = $request->discounted_price;
        $product->is_popular = $request->is_popular ?? false;
        $product->is_available = $request->is_available ?? true;
        $product->product_category_id = $request->product_category_id;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $product->image = $path;
        }

        $product->save();

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product,
        ], 201);
    }

    public function show($id)
    {
        // Authorization check
        if (!Auth::user()->isAdmin() && !Auth::user()->isStaff()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $product = Product::with(['business', 'category'])->findOrFail($id);

        return response()->json([
            'product' => $product,
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
            'price' => 'nullable|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'is_popular' => 'nullable|boolean',
            'is_available' => 'nullable|boolean',
            'product_category_id' => 'nullable|exists:product_categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = Product::findOrFail($id);

        // Verify product_category_id belongs to the specified business
        if ($request->filled('product_category_id')) {
            $categoryBelongsToBusiness = ProductCategory::where('id', $request->product_category_id)
                ->where('business_id', $product->business_id)
                ->exists();
            
            if (!$categoryBelongsToBusiness) {
                return response()->json([
                    'message' => 'The selected product category does not belong to this business',
                ], 422);
            }
        }

        if ($request->filled('name')) {
            $product->name = $request->name;
            $product->slug = Str::slug($request->name);
        }

        if ($request->filled('description')) {
            $product->description = $request->description;
        }

        if ($request->filled('price')) {
            $product->price = $request->price;
        }

        if ($request->filled('discounted_price')) {
            $product->discounted_price = $request->discounted_price;
        }

        if ($request->has('is_popular')) {
            $product->is_popular = $request->is_popular;
        }

        if ($request->has('is_available')) {
            $product->is_available = $request->is_available;
        }

        if ($request->filled('product_category_id')) {
            $product->product_category_id = $request->product_category_id;
        }

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::delete('public/' . $product->image);
            }
            $path = $request->file('image')->store('products', 'public');
            $product->image = $path;
        }

        $product->save();

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product,
        ]);
    }

    public function destroy($id)
    {
        // Authorization check
        if (!Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $product = Product::findOrFail($id);

        if ($product->image) {
            Storage::delete('public/' . $product->image);
        }

        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully',
        ]);
    }
}
