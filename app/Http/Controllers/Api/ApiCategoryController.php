<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class ApiCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)
            ->orderBy('display_order')
            ->get();

        return response()->json([
            'categories' => $categories,
        ]);
    }

    public function show($id)
    {
        $category = Category::with('businesses.mainLocation')
            ->findOrFail($id);

        return response()->json([
            'category' => $category,
        ]);
    }
}
