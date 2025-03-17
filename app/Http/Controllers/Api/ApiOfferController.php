<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;

class ApiOfferController extends Controller
{
    public function index()
    {
        $offers = Offer::with('business', 'product')
            ->active()
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json($offers);
    }

    public function businessOffers($businessId)
    {
        $offers = Offer::with('product')
            ->where('business_id', $businessId)
            ->active()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'offers' => $offers,
        ]);
    }
}
