<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\VendorAuthController;


Route::post('send-otp', [AuthController::class, 'sendOtp']);
Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('register', [AuthController::class, 'register']);

Route::get('login-details', [AuthController::class, 'loginDetails'])->middleware('jwt.verify');
Route::post('logout', [AuthController::class, 'logout'])->middleware('jwt.verify');
 
// User routes 
Route::prefix('user')->middleware(['auth:api', 'jwt.verify'])->group(function () 
{   
    
});

// Vendor routes
Route::prefix('vendor')->middleware(['auth:vendor_api', 'jwt.verify'])->group(function () 
{ 
     
});
