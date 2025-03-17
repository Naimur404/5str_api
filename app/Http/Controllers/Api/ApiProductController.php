<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ApiProductController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_id' => 'required|exists:businesses,id',
            'category_id' => 'nullable|exists:product_categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $query = Product::where('business_id', $request->business_id)
            ->where('is_available', true);

        if ($request->has('category_id')) {
            $query->where('product_category_id', $request->category_id);
        }

        $products = $query->orderBy('is_popular', 'desc')
            ->orderBy('name')
            ->paginate(15);

        return response()->json($products);
    }

    public function show($id)
    {
        $product = Product::with(['business', 'category', 'offers' => function ($query) {
            $query->active();
        }])->findOrFail($id);

        return response()->json([
            'product' => $product,
        ]);
    }

    public function popularProducts()
    {
        $products = Product::with('business')
            ->where('is_available', true)
            ->where('is_popular', true)
            ->orderBy('average_rating', 'desc')
            ->take(10)
            ->get();

        return response()->json([
            'products' => $products,
        ]);
    }
}
