<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductCategoryController extends Controller
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

        $categories = ProductCategory::where('business_id', $request->business_id)
            ->orderBy('display_order')
            ->get();

        return response()->json([
            'categories' => $categories,
        ]);
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
            'icon' => 'nullable|image|max:2048',
            'display_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category = new ProductCategory();
        $category->business_id = $request->business_id;
        $category->name = $request->name;
        $category->display_order = $request->display_order ?? 0;
        $category->is_active = $request->is_active ?? true;

        if ($request->hasFile('icon')) {
            $path = $request->file('icon')->store('product_categories', 'public');
            $category->icon = $path;
        }

        $category->save();

        return response()->json([
            'message' => 'Product category created successfully',
            'category' => $category,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        // Authorization check
        if (!Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'icon' => 'nullable|image|max:2048',
            'display_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category = ProductCategory::findOrFail($id);

        if ($request->filled('name')) {
            $category->name = $request->name;
        }

        if ($request->filled('display_order')) {
            $category->display_order = $request->display_order;
        }

        if ($request->has('is_active')) {
            $category->is_active = $request->is_active;
        }

        if ($request->hasFile('icon')) {
            if ($category->icon) {
                Storage::delete('public/' . $category->icon);
            }
            $path = $request->file('icon')->store('product_categories', 'public');
            $category->icon = $path;
        }

        $category->save();

        return response()->json([
            'message' => 'Product category updated successfully',
            'category' => $category,
        ]);
    }

    public function destroy($id)
    {
        // Authorization check
        if (!Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $category = ProductCategory::findOrFail($id);

        // Check if category has products
        if ($category->products()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete category that has products',
            ], 422);
        }

        if ($category->icon) {
            Storage::delete('public/' . $category->icon);
        }

        $category->delete();

        return response()->json([
            'message' => 'Product category deleted successfully',
        ]);
    }
}
