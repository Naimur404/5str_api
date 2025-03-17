<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        // Authorization check
        if (!Auth::user()->isAdmin() && !Auth::user()->isStaff()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $query = Review::with(['user', 'business']);

        // Filter by business
        if ($request->has('business_id')) {
            $query->where('business_id', $request->business_id);
        }

        // Filter by approval status
        if ($request->has('is_approved')) {
            $query->where('is_approved', $request->is_approved);
        }

        $reviews = $query->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15);

        return response()->json($reviews);
    }

    public function show($id)
    {
        // Authorization check
        if (!Auth::user()->isAdmin() && !Auth::user()->isStaff()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $review = Review::with(['user', 'business'])->findOrFail($id);

        return response()->json([
            'review' => $review,
        ]);
    }

    public function update(Request $request, $id)
    {
        // Authorization check
        if (!Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'is_approved' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $review = Review::findOrFail($id);

        if ($request->has('is_approved')) {
            $review->is_approved = $request->is_approved;
        }

        $review->save();

        return response()->json([
            'message' => 'Review updated successfully',
            'review' => $review,
        ]);
    }

    public function destroy($id)
    {
        // Authorization check
        if (!Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $review = Review::findOrFail($id);
        $review->delete();

        return response()->json([
            'message' => 'Review deleted successfully',
        ]);
    }
}
