<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\UserDashboardController;
use App\Http\Controllers\Api\PropertyController;


Route::post('send-otp', [AuthController::class, 'sendOtp']);
Route::post('resend-otp', [AuthController::class, 'resendOtp']);
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
    Route::get('unit-types', [HomeController::class, 'unitTypes']);
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

Route::post('inquiry', [UserDashboardController::class, 'userInquiry'])->middleware('jwt.verify');
Route::post('wishlist', [UserDashboardController::class, 'userWishlist'])->middleware('jwt.verify');

Route::prefix('support-ticket')->middleware(['jwt.verify'])->group(function () {
    Route::post('list', [UserDashboardController::class, 'listTicket']);   
    Route::post('create', [UserDashboardController::class, 'createTicket']); 
    Route::get('message/{id}', [UserDashboardController::class, 'messageTicket']);
    Route::post('reply', [UserDashboardController::class, 'replyTicket']);
}); 

Route::prefix('property-management')->middleware(['jwt.verify'])->group(function () {
    Route::post('list', [PropertyController::class, 'index']);
    Route::post('create', [PropertyController::class, 'createProperty']);
    Route::get('edit/{id}', [PropertyController::class, 'editProperty']);
    Route::post('update', [PropertyController::class, 'updateProperty']);
    Route::post('remove-slider-image', [PropertyController::class, 'removeSliderImage']);
    Route::get('delete/{id}', [PropertyController::class, 'deleteProperty']);
    Route::post('bulk-delete', [PropertyController::class, 'deleteBulkProperty']);
});

// User routes  
Route::get('dashboard', [UserDashboardController::class, 'dashboard'])->middleware('jwt.verify');
Route::post('user/profile-update', [UserDashboardController::class, 'profileUpdate'])->middleware('jwt.verify');

Route::post('vendor/profile-update', [UserDashboardController::class, 'vendorProfileUpdate'])->middleware('jwt.verify'); 
Route::get('vendor/package-list', [UserDashboardController::class, 'packagePlans'])->middleware('jwt.verify');
Route::get('vendor/package-checkout/{package_id}', [UserDashboardController::class, 'checkout'])->middleware('jwt.verify');
Route::post('vendor/package/checkout', [UserDashboardController::class, 'checkoutPayment'])->middleware('jwt.verify');

Route::post('agent/profile-update', [UserDashboardController::class, 'agentProfileUpdate'])->middleware('jwt.verify');

Route::post('/phone/send-otp', [UserDashboardController::class, 'sendPhoneOtp'])->middleware('jwt.verify');
Route::post('/phone/resend-otp', [UserDashboardController::class, 'resendPhoneOtp'])->middleware('jwt.verify');
Route::post('/phone/verify-otp', [UserDashboardController::class, 'verifyPhoneOtp'])->middleware('jwt.verify');


 
 