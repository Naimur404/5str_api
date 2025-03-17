<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApiFavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        $favorites = Favorite::with('business.mainLocation')
            ->where('user_id', Auth::id())
            ->paginate(15);

        return response()->json($favorites);
    }

    public function toggle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_id' => 'required|exists:businesses,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $favorite = Favorite::where('user_id', Auth::id())
            ->where('business_id', $request->business_id)
            ->first();

        if ($favorite) {
            // Remove from favorites
            $favorite->delete();
            return response()->json([
                'message' => 'Removed from favorites',
                'is_favorite' => false,
            ]);
        } else {
            // Add to favorites
            Favorite::create([
                'user_id' => Auth::id(),
                'business_id' => $request->business_id,
            ]);
            return response()->json([
                'message' => 'Added to favorites',
                'is_favorite' => true,
            ]);
        }
    }
}
