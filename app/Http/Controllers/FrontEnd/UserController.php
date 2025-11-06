<?php
	
	namespace App\Http\Controllers\FrontEnd;
	
	use App\Http\Controllers\Controller;
	use App\Http\Controllers\FrontEnd\MiscellaneousController;
	use App\Http\Helpers\BasicMailer;
	use App\Models\BasicSettings\Basic;
	use App\Models\BasicSettings\MailTemplate;
	use App\Models\Property\Property;
	use App\Models\Property\PropertyContact;
	use App\Models\Property\Wishlist;
	use App\Models\User;
	use App\Models\Agent;
	use App\Models\SupportTicket;
	use App\Rules\MatchEmailRule;
	use App\Rules\MatchOldPasswordRule;
	use Carbon\Carbon;
	use DateTime;
	use Exception;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\Config;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Hash;
	use Illuminate\Support\Facades\Log;
	use Illuminate\Support\Facades\Session;
	use Illuminate\Support\Facades\Validator;
	use Illuminate\Validation\Rule;
	use Laravel\Socialite\Facades\Socialite;
	use App\Http\Controllers\Vendor\VendorController;
	use App\Models\Vendor;
	
	class UserController extends Controller
	{
		public function __construct()
		{
			$bs = DB::table('basic_settings')
			->select('facebook_app_id', 'facebook_app_secret', 'google_client_id', 'google_client_secret')
			->first();
			
			Config::set('services.facebook.client_id', $bs->facebook_app_id);
			Config::set('services.facebook.client_secret', $bs->facebook_app_secret);
			Config::set('services.facebook.redirect', url('user/login/facebook/callback'));
			
			Config::set('services.google.client_id', $bs->google_client_id);
			Config::set('services.google.client_secret', $bs->google_client_secret);
			Config::set('services.google.redirect', url('login/google/callback'));
		}
		
		public function login(Request $request)
		{
			$misc = new MiscellaneousController();
			
			$language = $misc->getLanguage();
			
			$queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_login', 'meta_description_login')->first();
			
			$queryResult['pageHeading'] = $misc->getPageHeading($language);
			
			$queryResult['bgImg'] = $misc->getBreadcrumb();
			
			// get the status of digital product (exist or not in the cart)
			if (!empty($request->input('digital_item'))) {
				$queryResult['digitalProductStatus'] = $request->input('digital_item');
			}
			
			$queryResult['bs'] = Basic::query()->select('google_recaptcha_status', 'facebook_login_status', 'google_login_status')->first();
			
			return view('frontend.user.login', $queryResult);
		}
		
		public function redirectToFacebook()
		{
			return Socialite::driver('facebook')->redirect();
		}
		
		public function handleFacebookCallback(Request $request)
		{
			if ($request->has('error_code')) {
				Session::flash('error', $request->error_message);
				return redirect()->route('user.login');
			}
			return $this->authenticationViaProvider('facebook');
		}
		
		public function redirectToGoogle()
		{
			return Socialite::driver('google')->redirect();
		}
		
		public function handleGoogleCallback()
		{
			return $this->authenticationViaProvider('google');
		}
		
		public function authenticationViaProvider($driver)
		{
			// get the url from session which will be redirect after login
			if (Session::has('redirectTo')) {
				$redirectURL = Session::get('redirectTo');
				} else {
				$redirectURL = route('index');
			}
			
			$responseData = Socialite::driver($driver)->user();
			$userInfo = $responseData->user;
			
			$isUser = User::query()->where('email', '=', $userInfo['email'])->first();
			
			if (!empty($isUser)) {
				// log in
				if ($isUser->status == 1) {
					Auth::guard('web')->login($isUser);
					
					return redirect($redirectURL);
					} else {
					Session::flash('error', 'Sorry, your account has been deactivated.');
					
					return redirect()->route('user.login');
				}
				} else {
				// get user avatar and save it
				$avatar = $responseData->getAvatar();
				$fileContents = file_get_contents($avatar);
				
				$avatarName = $responseData->getId() . '.jpg';
				$path = public_path('assets/img/users/');
				
				file_put_contents($path . $avatarName, $fileContents);
				
				// sign up
				$user = new User();
				
				if ($driver == 'facebook') {
					$user->name = $userInfo['name'];
					} else {
					$user->name = $userInfo['given_name'];
				}
				
				$user->image = $avatarName;
				$user->username = $userInfo['id'];
				$user->email = $userInfo['email'];
				$user->email_verified_at = date('Y-m-d H:i:s');
				$user->status = 1;
				$user->provider = ($driver == 'facebook') ? 'facebook' : 'google';
				$user->provider_id = $userInfo['id'];
				$user->save();
				
				Auth::guard('web')->login($user);
				
				return redirect($redirectURL);
			}
		}
		
		public function loginSubmit(Request $request)
		{
			
			if (Auth::guard('vendor')->check()) {
				Auth::guard('vendor')->logout();
				Session::forget('vendor_secret_login');
				Session::forget('vendor_theme_version');
			}
			// get the url from session which will be redirect after login
			if ($request->session()->has('redirectTo')) {
				$redirectURL = $request->session()->get('redirectTo');
				} else {
				$redirectURL = null;
			}
			 
			$rules = [
				'email' => 'required',
				'password' => 'required'
			];
			
			$info = Basic::select('google_recaptcha_status')->first();
			if ($info->google_recaptcha_status == 1) {
				$rules['g-recaptcha-response'] = 'required|captcha';
			}
			
			$messages = [];
			
			if ($info->google_recaptcha_status == 1) {
				$messages['g-recaptcha-response.required'] = 'Please verify that you are not a robot.';
				$messages['g-recaptcha-response.captcha'] = 'Captcha error! try again later or contact site admin.';
			}
			
			$validator = Validator::make($request->all(), $rules, $messages);
			
			if ($validator->fails()) {
				return redirect()->route('user.login')->withErrors($validator->errors())->withInput();
			}
			
			// get the email and password which has provided by the user
			$credentials = $request->only('email', 'password');
			
			// login attempt
			if (Auth::guard('web')->attempt($credentials)) {
				$authUser = Auth::guard('web')->user();
				// second, check whether the user's account is active or not
				if ($authUser->email_verified_at == null) {
					Session::flash('error', 'Please verify your email address');
					
					// logout auth user as condition not satisfied
					Auth::guard('web')->logout();
					
					return redirect()->back();
				}
				if ($authUser->status == 0) {
					Session::flash('error', 'Sorry, your account has been deactivated');
					
					// logout auth user as condition not satisfied
					Auth::guard('web')->logout();
					
					return redirect()->back();
				}
				
				// otherwise, redirect auth user to next url
				if (is_null($redirectURL)) {
					return redirect()->route('index');
					} else {
					// before, redirect to next url forget the session value
					$request->session()->forget('redirectTo');
					
					return redirect($redirectURL);
				}
				} else {
				Session::flash('error', 'Incorrect email or password');
				
				return redirect()->back();
			}
		}
		
		public function forgetPassword()
		{
			$misc = new MiscellaneousController();
			
			$language = $misc->getLanguage();
			
			$queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_forget_password', 'meta_description_forget_password')->first();
			
			$queryResult['pageHeading'] = $misc->getPageHeading($language);
			
			$queryResult['bgImg'] = $misc->getBreadcrumb();
			$queryResult['bs'] = Basic::query()->select('google_recaptcha_status', 'facebook_login_status', 'google_login_status')->first();
			
			return view('frontend.user.forget-password', $queryResult);
		}
		
		public function forgetPasswordMail(Request $request)
		{
			$rules = [
			'email' => [
			'required',
			'email:rfc,dns',
			new MatchEmailRule('user')
			]
			];
			
			$info = Basic::select('google_recaptcha_status')->first();
			if ($info->google_recaptcha_status == 1) {
				$rules['g-recaptcha-response'] = 'required|captcha';
			}
			
			$messages = [];
			
			if ($info->google_recaptcha_status == 1) {
				$messages['g-recaptcha-response.required'] = 'Please verify that you are not a robot.';
				$messages['g-recaptcha-response.captcha'] = 'Captcha error! try again later or contact site admin.';
			}
			
			$validator = Validator::make($request->all(), $rules, $messages);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator->errors())->withInput();
			}
			
			$user = User::query()->where('email', '=', $request->email)->first();
			
			// store user email in session to use it later
			$request->session()->put('userEmail', $user->email);
			
			// get the mail template information from db
			$mailTemplate = MailTemplate::query()->where('mail_type', '=', 'reset_password')->first();
			$mailData['subject'] = $mailTemplate->mail_subject;
			$mailBody = $mailTemplate->mail_body;
			
			// get the website title info from db
			$info = Basic::select('website_title')->first();
			
			$name = $user->username;
			
			$link = '<a href=' . url("user/reset-password") . '>Click Here</a>';
			
			$mailBody = str_replace('{customer_name}', $name, $mailBody);
			$mailBody = str_replace('{password_reset_link}', $link, $mailBody);
			$mailBody = str_replace('{website_title}', $info->website_title, $mailBody);
			
			$mailData['body'] = $mailBody;
			
			$mailData['recipient'] = $user->email;
			
			$mailData['sessionMessage'] = 'A mail has been sent to your email address';
			
			BasicMailer::sendMail($mailData);
			
			return redirect()->back();
		}
		
		public function resetPassword()
		{
			$misc = new MiscellaneousController();
			
			$bgImg = $misc->getBreadcrumb();
			
			return view('frontend.user.reset-password', compact('bgImg'));
		}
		
		public function resetPasswordSubmit(Request $request)
		{
			if ($request->session()->has('userEmail')) {
				// get the user email from session
				$emailAddress = $request->session()->get('userEmail');
				
				$rules = [
				'new_password' => 'required|confirmed',
				'new_password_confirmation' => 'required'
				];
				
				$messages = [
				'new_password.confirmed' => 'Password confirmation failed.',
				'new_password_confirmation.required' => 'The confirm new password field is required.'
				];
				
				$validator = Validator::make($request->all(), $rules, $messages);
				
				if ($validator->fails()) {
					return redirect()->back()->withErrors($validator->errors());
				}
				
				$user = User::query()->where('email', '=', $emailAddress)->first();
				
				$user->update([
				'password' => Hash::make($request->new_password)
				]);
				
				Session::flash('success', 'Password updated successfully.');
				} else {
				Session::flash('error', 'Something went wrong!');
			}
			
			return redirect()->route('user.login');
		}
		
		public function signup()
		{ 
			$misc = new MiscellaneousController();
			
			$language = $misc->getLanguage();
			
			$queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_signup', 'meta_description_signup')->first();
			
			$queryResult['pageHeading'] = $misc->getPageHeading($language);
			
			$queryResult['bgImg'] = $misc->getBreadcrumb();
			
			$queryResult['recaptchaInfo'] = Basic::select('google_recaptcha_status')->first();
			
			return view('frontend.user.signup', $queryResult);
		}
		
		public function signupSubmit(Request $request)
		{ 
			try { 
				// Common validation rules
				$rules = [
					'usertype' => 'required|string',
					'phone' => 'required|string|max:20',
				];

				// If vendor (builder) — handle separately
				if ($request->usertype === 'Builder')
				{ 
					$vendorController = new VendorController();
					return $vendorController->create($request);
				}
	
				// --- USER SIGNUP LOGIC ---
				$info = Basic::select('google_recaptcha_status', 'website_title')->first();

				$rules = array_merge($rules, [
					'username' => 'required|max:255|unique:users,username',
					'email' => 'required|email:rfc,dns|max:255|unique:users,email',
					'password' => 'required|confirmed|min:6',
					'password_confirmation' => 'required',
				]);

				if ($info->google_recaptcha_status == 1) {
					$rules['g-recaptcha-response'] = 'required|captcha';
				}

				$messages = [
					'password_confirmation.required' => 'The confirm password field is required.',
					'g-recaptcha-response.required' => 'Please verify that you are not a robot.',
					'g-recaptcha-response.captcha' => 'Captcha error! Try again later or contact site admin.',
				];

				$validator = Validator::make($request->all(), $rules, $messages);
				if ($validator->fails()) {
					return redirect()->back()->withErrors($validator)->withInput();
				}
				
				if ($request->username == 'admin') {
					Session::flash('username_error', 'You can not use admin as a username!');
					return redirect()->back();
				}
				
				// --- Check if user with same phone already exists ---
				$user = User::where('phone', $request->phone)->first();  
				if (!$user) {
					return redirect()->back()->with('error', 'Something went wrong.')->withInput();
				}
		
				$user->username = $request->username;
				$user->email = $request->email;
				$user->phone = $request->phone;
				$user->password = Hash::make($request->password);
				$user->email_verified_at = Carbon::now();
				$user->user_type = $request->usertype;
				$user->save();
				
				Session::forget('login_phone');
				Auth::login($user);
				
				// Flash message and redirect
				Session::flash('success', 'Signup successful!.');
				return redirect()->route('user.dashboard');

			} catch (\Exception $e) {
				return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
			}
		}

		
		public function signupVerify($id)
		{
			$user = User::where('id', $id)->firstOrFail();
			$user->email_verified_at = Carbon::now();
			$user->save();
			Auth::login($user);
			return redirect()->route('user.dashboard');
		}
		
		public function redirectToDashboard()
		{
			$misc = new MiscellaneousController();
			
			$language = $misc->getLanguage();
			
			$queryResult['language'] = $language;
			
			$queryResult['bgImg'] = $misc->getBreadcrumb();
			$queryResult['pageHeading'] = $misc->getPageHeading($language);
			
			$user = Auth::guard('web')->user();
			
			$queryResult['authUser'] = $user;
			$queryResult['wishlists'] = Wishlist::where('user_id', $user->id)
			->get();
			
			return view('frontend.user.dashboard', $queryResult);
		}
		
		public function editProfile()
		{
			
			$misc = new MiscellaneousController();
			
			$queryResult['bgImg'] = $misc->getBreadcrumb();
			$language = $misc->getLanguage();
			$queryResult['pageHeading'] = $misc->getPageHeading($language);
			
			$queryResult['authUser'] = Auth::guard('web')->user();
			//Front side user edit
			// return view('frontend.user.edit-profile', $queryResult);
			
			//User Panel edit User
			return view('users.edit-profile', $queryResult);
		}
		
		public function updateProfile(Request $request)
		{ 
			$request->validate([
			'name' => 'required',
			'username' => [
			'required',
			'alpha_dash',
			Rule::unique('users', 'username')->ignore(Auth::guard('web')->user()->id),
			],
			'email' => [
			'required',
			'email',
			Rule::unique('users', 'email')->ignore(Auth::guard('web')->user()->id)
			],
			]);
			
			$authUser = Auth::guard('web')->user();
			$in = $request->all();
			$file = $request->file('image');
			if ($file) {
				$extension = $file->getClientOriginalExtension();
				$directory = public_path('assets/img/users/');
				$fileName = uniqid() . '.' . $extension;
				@mkdir($directory, 0775, true);
				$file->move($directory, $fileName);
				$in['image'] = $fileName;
			}
			
			$authUser->update($in);
			
			Session::flash('success', 'Your profile has been updated successfully.');
			
			return redirect()->back();
		}
		
		public function changePassword()
		{
			$misc = new MiscellaneousController();
			
			$bgImg = $misc->getBreadcrumb();
			$language = $misc->getLanguage();
			$pageHeading = $misc->getPageHeading($language);
			
			//Front side user edit
			// return view('frontend.user.change-password', compact('bgImg', 'pageHeading'));
			
			//User Panel edit User
			return view('users.change-password', compact('bgImg', 'pageHeading'));
		}
		
		public function updatePassword(Request $request)
		{
			$rules = [
			'current_password' => [
			'required',
			new MatchOldPasswordRule('user')
			],
			'new_password' => 'required|confirmed',
			'new_password_confirmation' => 'required'
			];
			
			$messages = [
			'new_password.confirmed' => 'Password confirmation failed.',
			'new_password_confirmation.required' => 'The confirm new password field is required.'
			];
			
			$validator = Validator::make($request->all(), $rules, $messages);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator->errors());
			}
			
			$user = Auth::guard('web')->user();
			
			$user->update([
			'password' => Hash::make($request->new_password)
			]);
			
			Session::flash('success', 'Password updated successfully.');
			
			return redirect()->back();
		}
		
		//wishlist
		public function wishlist()
		{
			$misc = new MiscellaneousController();
			$bgImg = $misc->getBreadcrumb();
			$language = $misc->getLanguage();
			$information['language'] = $language;
			$information['pageHeading'] = $misc->getPageHeading($language);
			
			$queryResult['language'] = $language;
			$user_id = Auth::guard('web')->user()->id;
			$wishlists = Wishlist::where('user_id', $user_id)->paginate(10);
			$information['bgImg'] = $bgImg;
			$information['wishlists'] = $wishlists;
			
			//Front side user edit
			// return view('frontend.user.wishlist', $information);
			
			//User Panel edit User
			return view('users.wishlist', $information);
		}
		
		public function inquiry()
		{
			$misc = new MiscellaneousController();
			$bgImg = $misc->getBreadcrumb();
			$language = $misc->getLanguage();
			$information['language'] = $language;
			$information['pageHeading'] = $misc->getPageHeading($language);
			
			$queryResult['language'] = $language;
			$user_id = Auth::guard('web')->user()->id;
			$inquiry = PropertyContact::where('inquiry_by_user', $user_id)->get();
			$information['bgImg'] = $bgImg;
			$information['inquiries'] = $inquiry;
			return view('frontend.user.inquiry', $information);
		}
		
		
		//add to wishlist 
		public function add_to_wishlist(Request $request, $id)
		{
			try {
				// 🔹 Determine logged-in guard type
				if (Auth::guard('web')->check()) {
					$guard = 'web';
				} elseif (Auth::guard('vendor')->check()) {
					$guard = 'vendor';
				} elseif (Auth::guard('agent')->check()) {
					$guard = 'agent';
				} else {
					$guard = null;
				}

				// 🔹 Not logged in → handle accordingly
				if (!$guard) {
					if ($request->ajax()) {
						return response()->json([
							'status' => 'error',
							'message' => 'Please login to add items to wishlist.'
						]);
					}
					return redirect()->route('user.login');
				}

				// 🔹 Identify user/vendor/agent ID
				$userId = Auth::guard($guard)->user()->id;

				// 🔹 Check if already in wishlist
				$query = Wishlist::where('property_id', $id);

				if ($guard === 'web') {
					$query->where('user_id', $userId);
				} elseif ($guard === 'vendor') {
					$query->where('vendor_id', $userId);
				} elseif ($guard === 'agent') {
					$query->where('agent_id', $userId);
				}

				$exists = $query->exists();

				if ($exists) {
					$message = 'You already added this property to your wishlist!';
					$status = 'error';
				} else {
					// 🔹 Create new wishlist record
					$wishlist = new Wishlist();
					$wishlist->property_id = $id;

					if ($guard === 'web') {
						$wishlist->user_id = $userId;
					} elseif ($guard === 'vendor') {
						$wishlist->vendor_id = $userId;
					} elseif ($guard === 'agent') {
						$wishlist->agent_id = $userId;
					}

					$wishlist->save();

					$message = 'Added to your wishlist successfully!';
					$status = 'success';
				}

				// 🔹 Handle AJAX vs normal request
				if ($request->ajax()) {
					return response()->json([
						'status' => $status,
						'message' => $message,
					]);
				}

				return back()->with([
					'message' => $message,
					'alert-type' => $status,
				]);

			} catch (\Exception $e) {
				// 🔹 Handle unexpected exceptions safely
				if ($request->ajax()) {
					return response()->json([
						'status' => 'error',
						'message' => 'Something went wrong.',
						'error' => $e->getMessage(),
					], 500);
				}

				return back()->with([
					'message' => 'Something went wrong.',
					'alert-type' => 'error',
				]);
			}
		}

		
		//remove_wishlist
		public function remove_wishlist($id)
		{
			try {
				// 🔹 Detect which guard is logged in
				if (Auth::guard('web')->check()) {
					$guard = 'web';
				} elseif (Auth::guard('vendor')->check()) {
					$guard = 'vendor';
				} elseif (Auth::guard('agent')->check()) {
					$guard = 'agent';
				} else {
					$guard = null;
				}

				// 🔹 If no one is logged in
				if (!$guard) {
					if (request()->ajax()) {
						return response()->json([
							'status' => 'error',
							'message' => 'Login required.'
						], 401);
					}
					return redirect()->route('user.login');
				}

				$userId = Auth::guard($guard)->id();

				// 🔹 Build query dynamically based on guard
				$query = Wishlist::where('property_id', $id);

				if ($guard === 'web') {
					$query->where('user_id', $userId);
				} elseif ($guard === 'vendor') {
					$query->where('vendor_id', $userId);
				} elseif ($guard === 'agent') {
					$query->where('agent_id', $userId);
				}

				$wishlist = $query->first();

				if ($wishlist) {
					$wishlist->delete();
					$response = [
						'status' => 'success',
						'message' => 'Removed from wishlist successfully!'
					];
				} else {
					$response = [
						'status' => 'error',
						'message' => 'Item not found in wishlist.'
					];
				}

				// 🔹 Return JSON if AJAX request
				if (request()->ajax()) {
					return response()->json($response);
				}

				// 🔹 Otherwise redirect with session message
				$alertType = $response['status'] === 'success' ? 'info' : 'danger';
				return back()->with([
					'message' => $response['message'],
					'alert-type' => $alertType
				]);

			} catch (\Exception $e) {
				// 🔹 Handle exceptions gracefully
				if (request()->ajax()) {
					return response()->json([
						'status' => 'error',
						'message' => 'Something went wrong.',
						'error' => $e->getMessage()
					], 500);
				}

				return back()->with([
					'message' => 'Something went wrong.',
					'alert-type' => 'danger'
				]);
			}
		}

		
		public function wishListCount()
		{
			$count = 0;  
			if (Auth::guard('web')->check()) {
				$count = Wishlist::where('user_id', Auth::guard('web')->id())->count(); 
			} elseif (Auth::guard('vendor')->check()) {
				$count = Wishlist::where('vendor_id', Auth::guard('vendor')->id())->count(); 
			}elseif (Auth::guard('agent')->check()) {
				$count = Wishlist::where('agent_id', Auth::guard('agent')->id())->count(); 
			}
 
			return response()->json([
				'status' => 'success',
				'count' => $count, 
			]);
		} 
		
		public function logoutSubmit(Request $request)
		{
			Auth::guard('web')->logout();
			Session::forget('secret_login');
			
			if ($request->session()->has('redirectTo')) {
				$request->session()->forget('redirectTo');
			}
			
			return redirect()->route('index');
		}
		
		public function sendOtp(Request $request)
		{
			// Check if user exists
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
			
			// Generate OTP (static for testing)
			$otp = '1234'; // Replace with rand(1000, 9999) in production
			
			// Insert new OTP row, with otp_at = NULL
			DB::table('otp_verification')->updateOrInsert(
				['phone' => $request->phone], // Condition: if this exists
				[
					'user_id' => $user->id,
					'phone' => $request->phone,
					'otp' => $otp,
					'otp_at' => null,
					'updated_at' => now(),
					'created_at' => now(), // Optional; doesn't update on existing row
				]
			);
			
			return response()->json([
				'message' => 'OTP sent successfully.',
				'otp' => $otp
			]);
		}
		
		public function verifyOtp(Request $request)
		{
			try {
				$phone = $request->phone;
				$otp = $request->otp;
				$from = $request->input('from');
 
				// Find user in both guards
				$user = User::where('phone', $phone)->first();
				$userType = 'user';

				if (!$user) {
					$user = Vendor::where('phone', $phone)->first();
					$userType = 'vendor';
				}
				
				$withoutLogin = null;
				if (!$user) {
					$user = Agent::where('phone', $request->phone)->first();
					$withoutLogin = $user ? "agent" : null;
				}
				
				if (!$user) {
					return response()->json(['message' => 'User not found.'], 404);
				}

				// Fetch latest OTP record
				$otpRecord = DB::table('otp_verification')
					->where('phone', $phone)
					->latest()
					->first();

				if (!$otpRecord) {
					return response()->json(['message' => 'OTP not found.'], 404);
				}

				if ($otpRecord->otp != $otp) {
					return response()->json(['message' => 'Invalid OTP.'], 422);
				}

				// Mark OTP verified
				DB::table('otp_verification')
					->where('id', $otpRecord->id)
					->update(['otp_at' => now()]);

				// Log in user
				$guard = $userType === 'vendor' ? ($withoutLogin ? 'agent' : 'vendor') : 'web';
				
				// Determine redirect route
				if (empty($user->email) && !$withoutLogin) {
					$route = route('user.signup');
					session(['login_phone' => $phone]);
				} 
				elseif ($from === 'post_property') 
				{
					$route = $guard === 'vendor'
						? ($withoutLogin ? route('agent.property_management.type') : route('vendor.property_management.type'))
						: route('user.property_management.type'); 
						Auth::guard($guard)->login($user);
				} else {
					$route = $userType === 'vendor' ? ($withoutLogin ? url('agent/dashboard') : url('vendor/dashboard')) : route('user.dashboard');  
					Auth::guard($guard)->login($user);
				} 
				 
				return response()->json([
					'message' => 'OTP verified successfully.',
					'url' => $route
				]);

			} catch (\Throwable $e) {
				Log::error('OTP verification error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
				return response()->json([
					'message' => 'An error occurred during OTP verification.',
					'error' => config('app.debug') ? $e->getMessage() : null
				], 500);
			}
		} 
		
		public function dashboard()
		{
			$user_id = Auth::guard('web')->user()->id;
			$information['totalProperties'] = Property::query()->where('user_id', $user_id)->count();
			$information['totalInquiry'] = PropertyContact::query()->where('inquiry_by_user', $user_id)->count();
		    $information['totalTickets'] = SupportTicket::where([['user_id', Auth::guard('web')->user()->id], ['user_type', 'user']])->count();
			$information['totalWishlist'] = Wishlist::where([['user_id', Auth::guard('web')->user()->id]])->count();

			
			$totalProperties = DB::table('properties')
			->select(DB::raw('month(created_at) as month'), DB::raw('count(id) as total'))
			->groupBy('month')
			->where('user_id', $user_id)
			->whereYear('created_at', '=', date('Y'))
			->get();
			 
			$months = [];
			$totalPropertyArr = [];
			
			$heroImg = Basic::query()->pluck('hero_static_img')->first(); 
			$information['heroImg'] = $heroImg ? json_decode($heroImg, true) : [];
	   
			//event icome calculation
			for ($i = 1; $i <= 12; $i++) {
				// get all 12 months name
				$monthNum = $i;
				$dateObj = DateTime::createFromFormat('!m', $monthNum);
				$monthName = $dateObj->format('M');
				array_push($months, $monthName);
				
				// get all 12 months's property posts
				$propertyFound = false;
				foreach ($totalProperties as $totalProperty) {
					if ($totalProperty->month == $i) {
						$propertyFound = true;
						array_push($totalPropertyArr, $totalProperty->total);
						break;
					}
				}
				if ($propertyFound == false) {
					array_push($totalPropertyArr, 0);
				}
			}
			
			$information['monthArr'] = $months;
			$information['totalPropertiesArr'] = $totalPropertyArr;
			return view('users.index', $information);
		}
	}
