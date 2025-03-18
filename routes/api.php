<?php

use App\Http\Controllers\Api\ApiAuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiCategoryController;
use App\Http\Controllers\Api\ApiFavoriteController;
use App\Http\Controllers\Api\ApiHomeController;
use App\Http\Controllers\Api\ApiOfferController;
use App\Http\Controllers\Api\ApiProductController;
use App\Http\Controllers\Api\ApiReviewController;
use App\Http\Controllers\Api\ApiSearchController;
use App\Http\Controllers\Api\ApiBusinessController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('auth:sanctum')->group(function () {
    // Auth Routes
    Route::post('/logout', [ApiAuthController::class, 'logout']);
    Route::get('/profile', [ApiAuthController::class, 'profile']);
    Route::post('/profile/update', [ApiAuthController::class, 'updateProfile']);
    Route::post('/password/change', [ApiAuthController::class, 'changePassword']);
    
    // Reviews
    Route::post('/reviews', [ApiReviewController::class, 'store']);
    Route::get('/reviews/user', [ApiReviewController::class, 'userReviews']);
    Route::delete('/reviews/{id}', [ApiReviewController::class, 'destroy']);
    
    // Favorites
    Route::get('/favorites', [ApiFavoriteController::class, 'index']);
    Route::post('/favorites/toggle', [ApiFavoriteController::class, 'toggle']);
});

Route::get('/home', [ApiHomeController::class, 'index']);

// Category-specific businesses nearby
Route::get('/categories/{category_id}/nearby', [ApiHomeController::class, 'categoryNearby']);

// Public Routes
// Categories
Route::get('/categories', [ApiCategoryController::class, 'index']);
Route::get('/categories/{id}', [ApiCategoryController::class, 'show']);

// Businesses
Route::get('/businesses', [ApiBusinessController::class, 'index']);
Route::get('/businesses/{id}', [ApiBusinessController::class, 'show']);
Route::get('/businesses/nearby', [ApiBusinessController::class, 'nearby']);
Route::get('/businesses/popular', [ApiBusinessController::class, 'popular']);
Route::get('/businesses/top-rated', [ApiBusinessController::class, 'topRated']);

// Reviews (public)
Route::get('/businesses/{businessId}/reviews', [ApiReviewController::class, 'businessReviews']);

// Products
Route::get('/products', [ApiProductController::class, 'index']);
Route::get('/products/{id}', [ApiProductController::class, 'show']);
Route::get('/products/popular', [ApiProductController::class, 'popularProducts']);

// Offers
Route::get('/offers', [ApiOfferController::class, 'index']);
Route::get('/businesses/{businessId}/offers', [ApiOfferController::class, 'businessOffers']);

// Search
Route::get('/search', [ApiSearchController::class, 'search']);
Route::post('/filter', [ApiSearchController::class, 'filter']);