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
    Route::get('properties', [HomeController::class, 'properties']);
    Route::get('cities', [HomeController::class, 'cities']);
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
