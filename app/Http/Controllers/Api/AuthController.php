<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\VendorPermissionHelper;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Agent;
use App\Models\VendorInfo;
use App\Models\BasicSettings\Basic;
use App\Models\Package;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponseTrait;
use DB, Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Http\Controllers\Vendor\VendorCheckoutController;

class AuthController extends Controller
{
	use ApiResponseTrait;
	
	public function sendOtp(Request $request)
    {
        $user = User::where('phone', $request->phone)->first();
		$type = 'user';

		if (!$user) {
			$user = Vendor::where('phone', $request->phone)->first();
			$type = 'vendor';
		}

		$withoutLogin = null; 
		if (!$user) {
			$user = Agent::where('phone', $request->phone)->first();
			$withoutLogin = $user ? "agent" : null;
		}
			 
		// If not found in either table, create in the correct one
		if (!$user && !$withoutLogin) {
			$user = new User(); 
			$user->phone = $request->phone;
			$user->status = 1;
			$user->is_new = "0";
			$user->save();
		}
		 
		$otp = '1234'; 
		DB::table('otp_verification')->updateOrInsert(
			['phone' => $request->phone], 
			[
				'user_id' => $user->id,
				'phone' => $request->phone,
				'otp' => $otp,
				'otp_at' => null,
				'updated_at' => now(),
				'created_at' => now(), 
			]
		);
		
		return $this->successResponse(['otp' => $otp, 'auth_type' => $type], 'OTP sent successfully.'); 
    }
	
	public function verifyOtp(Request $request)
	{
		try {
			$phone = $request->phone;
			$otp = $request->otp; 

			// Find user in both guards
			$user = User::where('phone', $phone)->first();
			$userType = 'user';

			if (!$user) {
				$user = Vendor::where('phone', $phone)->first();
				$userType = 'vendor';
			}
			 
			if (!$user) {
				$user = Agent::where('phone', $request->phone)->first();
				$userType = $user ? "agent" : null;
			}
				
			if (!$user) {
				return $this->errorResponse('Detail not found.');
			}
			
			// Fetch latest OTP record
			$otpRecord = DB::table('otp_verification')
				->where('phone', $phone)
				->latest()
				->first();

			if (!$otpRecord) {
				return $this->errorResponse('OTP not found.'); 
			}

			if ($otpRecord->otp != $otp) {
				return $this->errorResponse('Invalid OTP.', 422);  
			}

			// Mark OTP verified
			DB::table('otp_verification')
				->where('id', $otpRecord->id)
				->update(['otp_at' => now()]);

			// Log in user
			$guard = $userType === 'vendor' ? 'vendor_api' : ($userType === "agent"  ? 'agent_api' : 'api'); 
			
			// Determine redirect route
			if (empty($user->email)) {
				$route = "signup";  
				$user->token = null;
			} 
			else 
			{
				$route = 'loggedin'; 
				$token = auth($guard)->login($user);
				$user->token = $token;
			} 
			
			$user->auth_type = $userType;
			$data = [
				'route' => $route,
				'user' => $user,
			];
			return $this->successResponse($data, 'OTP verified successfully.');   

		} catch (\Throwable $e) {  
			return $this->errorResponse('An error occurred during OTP verification.', 500, [$e->getMessage()]);  
		}
	}
	
    public function register(Request $request)
    { 
		DB::beginTransaction();	
		try 
		{ 
			// If vendor (builder) — handle separately
			if ($request->usertype === 'Builder')
			{  
				DB::commit();
				return $this->createBuilder($request);
			}
			
			$rules = [
				'usertype' => 'required|string',
				'phone' => 'required|string|max:10',
				'username' => 'required|max:255|unique:users,username',
				'email' => 'required|email:rfc,dns|max:255|unique:users,email',
				'password' => 'required|confirmed|min:6',
				'password_confirmation' => 'required',
			];  

			$validator = Validator::make($request->all(), $rules);
			if ($validator->fails()) { 
				return $this->errorResponse($validator->errors()->first());
			}
			
			if ($request->username == 'admin') { 
				return $this->errorResponse('You can not use admin as a username!');  
			}
			
			$user = User::where('phone', $request->phone)->first();  
			if (!$user) { 
				return $this->errorResponse('Something went wrong.');
			}
	
			$user->username = $request->username;
			$user->email = $request->email;
			$user->phone = $request->phone;
			$user->password = Hash::make($request->password);
			$user->email_verified_at = Carbon::now();
			$user->user_type = $request->usertype;
			$user->save();
			 
			$user->auth_type = "user";
			$token = auth('api')->login($user);
			$user->token = $token; 
			
			DB::commit();
			return $this->successResponse($user, 'Register successfully.');   

		} catch (\Exception $e) {
			DB::rollBack();
			return $this->errorResponse('An error occurred.', 500, [$e->getMessage()]);   
		} 
    } 
	
	public function createBuilder(Request $request)
	{
		DB::beginTransaction();

		try {
			// ✅ Validation
			$rules = [
				'usertype' => 'required|string',
				'username' => 'required|unique:vendors',
				'email' => 'required|email|unique:vendors',
				'phone' => 'required|string|max:15',
				'password' => 'required|confirmed|min:6',
				'password_confirmation' => 'required',
			];

			$validator = Validator::make($request->all(), $rules);
			if ($validator->fails()) { 
				return $this->errorResponse($validator->errors()->first());
			}

			if ($request->username === 'admin') { 
				return $this->errorResponse('You can not use admin as a username!');
			}

			// ✅ Prepare data
			$data = $request->only([
				'username', 'email', 'phone'
			]);

			$data['type'] = null;
			$data['user_type'] = $request->usertype;
			$data['status'] = 1;
			$data['is_new'] = '0';
			$data['password'] = Hash::make($request->password);
			$data['email_verified_at'] = now();

			// ✅ Create Vendor
			$vendor = Vendor::create($data);

			// ✅ Vendor Info
			$language = (new MiscellaneousController())->getLanguage();
			VendorInfo::create([
				'vendor_id' => $vendor->id,
				'language_id' => $language->id,
				'name' => $request->name,
			]);

			// ✅ Assign Free Package
			$freePackage = Package::where('title', 'Free')->first();
			$bs = Basic::first();

			$package = [
				'status' => '1',
				'receipt_name' => null,
				'email' => $vendor->email,
				'price' => 0.00,
				'payment_method' => '-',
				'vendor_id' => $vendor->id,
				'start_date' => now()->toDateString(),
				'expire_date' => Carbon::parse($freePackage->expire_date),
				'package_id' => $freePackage->id,
			];

			$transaction_id = VendorPermissionHelper::uniqidReal(8);
			$transaction_details = 'Free';
			$password = uniqid('qrcode');

			(new VendorCheckoutController())->store(
				$package,
				$transaction_id,
				$transaction_details,
				$request['price'] ?? 0,
				$bs,
				$password
			);

			// ✅ Delete user (if phone already exists)
			User::where('phone', $request->phone)->delete();

			// ✅ JWT token for vendor
			$vendor->auth_type = 'vendor';
			$token = auth('vendor_api')->login($vendor);
			$vendor->token = $token;

			DB::commit();
			return $this->successResponse($vendor, 'Register successfully.');

		} catch (Exception $e) {
			DB::rollBack();
			return $this->errorResponse('An error occurred.', 500, [$e->getMessage()]);
		}
	}
	
    public function loginDetails()
	{
		$authType = request('auth_type');

		if (!$authType) {
			return $this->errorResponse('Resource not found.', 404);
		}

		$guard = $authType == 'vendor' ? 'vendor_api' : ($authType === "agent" ? 'agent_api' : 'api');

		$user = auth($guard)->user();

		if (!$user) {
			return $this->errorResponse('Unauthorized', 401);
		}

		return $this->successResponse($user, 'Details successfully fetched.');
	}

    public function logout()
    {
        $authType = request('auth_type');

		if (!$authType) {
			return $this->errorResponse('Resource not found.', 404);
		}

		$guard = $authType == 'vendor' ? 'vendor_api' : ($authType === "agent" ? 'agent_api' : 'api');

		auth($guard)->logout();
		return $this->successResponse([], 'User logged out'); 
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('user-api')->factory()->getTTL() * 60,
        ]);
    }
}
