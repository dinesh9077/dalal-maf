<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\VendorAuthController;


Route::post('send-otp', [AuthController::class, 'sendOtp']);
Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('register', [AuthController::class, 'register']);

Route::get('login-details', [AuthController::class, 'loginDetails'])->middleware('jwt.verify');
Route::post('logout', [AuthController::class, 'logout'])->middleware('jwt.verify');

//Home
Route::prefix('front')->group(function () 
{   
    Route::get('index', [HomeController::class, 'index']);
    Route::get('view-all-properties/{type}', [HomeController::class, 'viewAllProperties']);
    Route::post('view-all-partners', [HomeController::class, 'viewAllPartners']);
    Route::post('view-all-blogs', [HomeController::class, 'viewAllBlogs']);
    Route::get('blog/{slug}', [HomeController::class, 'blogDetails']);

    //Properties
    Route::get('properties', [HomeController::class, 'properties']);
    Route::get('cities', [HomeController::class, 'cities']);
    Route::get('areas', [HomeController::class, 'areas']);
    Route::get('states', [HomeController::class, 'states']);
    Route::get('countries', [HomeController::class, 'countries']);
    Route::get('categories', [HomeController::class, 'categories']);
    Route::get('amenities', [HomeController::class, 'amenities']);
    Route::get('property/details/{slug}', [HomeController::class, 'propertyDetails']);
    Route::post('property/enquiry', [HomeController::class, 'propertyEnquiry'])->middleware('jwt.verify');

    //Wishlist
    Route::post('property/add-to-wishlist', [HomeController::class, 'addToWishlist'])->middleware('jwt.verify');
    Route::post('property/remove-to-wishlist', [HomeController::class, 'removeToWishlist'])->middleware('jwt.verify');
    Route::post('property/wishlist-count', [HomeController::class, 'wishlistCount'])->middleware('jwt.verify');

    Route::get('projects', [HomeController::class, 'projects']);
    Route::get('projects/{slug}', [HomeController::class, 'projectDetails']);
    Route::get('vendors', [HomeController::class, 'vendors']);
}); 

// User routes 
Route::prefix('user')->middleware(['auth:api', 'jwt.verify'])->group(function () 
{   
		
});

// Vendor routes
Route::prefix('vendor')->middleware(['auth:vendor_api', 'jwt.verify'])->group(function () 
{ 
     
});

// Vendor routes
Route::prefix('agent')->middleware(['auth:agent_api', 'jwt.verify'])->group(function () 
{ 
     
});
