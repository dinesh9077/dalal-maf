<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Http\Helpers\VendorPermissionHelper;
use App\Models\Agent;
use App\Models\BasicSettings\Basic;
use App\Models\BasicSettings\MailTemplate;
use App\Models\Language;
use App\Models\Membership;
use App\Models\Package;
use App\Models\Project\Project;
use App\Models\Property\Property;
use App\Models\SupportTicket;
use App\Models\Vendor;
use App\Models\User;
use App\Models\VendorInfo;
use App\Models\VendorKYC;
use App\Rules\MatchEmailRule;
use App\Rules\MatchOldPasswordRule;
use Carbon\Carbon;
use Config;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Vendor\VendorCheckoutController;
use Illuminate\Support\Facades\Cache;

class VendorController extends Controller
{
    //signup
    public function signup()
    {
        $misc = new MiscellaneousController();

        $language = $misc->getLanguage();

        $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keywords_vendor_signup', 'meta_description_vendor_signup')->first();

        $queryResult['pageHeading'] = $misc->getPageHeading($language);

        $queryResult['recaptchaInfo'] = Basic::select('google_recaptcha_status')->first();

        $queryResult['bgImg'] = $misc->getBreadcrumb();

        return view('frontend.vendor.auth.register', $queryResult);
    }

    //create
    public function create(Request $request)
    {
        $rules = [
            'username' => 'required|unique:vendors',
            'email' => 'required|email|unique:vendors',
            'phone' => 'required',
            'password' => 'required|confirmed|min:6',
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

        if ($request->username == 'admin') {
            Session::flash('username_error', 'You can not use admin as a username!');
            return redirect()->back();
        }

        $in = $request->all();
        $setting = DB::table('basic_settings')->where('uniqid', 12345)->select('vendor_email_verification', 'vendor_admin_approval')->first();

        if ($setting->vendor_email_verification == 1)
		{
            // first, get the mail template information from db
            $mailTemplate = MailTemplate::where('mail_type', 'verify_email')->first();

            $mailSubject = $mailTemplate->mail_subject;
            $mailBody = $mailTemplate->mail_body;

            // second, send a password reset link to user via email
            $info = DB::table('basic_settings')
                ->select('website_title', 'smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
                ->first();

            $token =  $request->email;

            $link = '<a href=' . url("vendor/email/verify?token=" . $token) . '>Click Here</a>';

            $mailBody = str_replace('{username}', $request->username, $mailBody);
            $mailBody = str_replace('{verification_link}', $link, $mailBody);
            $mailBody = str_replace('{website_title}', $info->website_title, $mailBody);

            $data = [
                'subject' => $mailSubject,
                'to' => $request->email,
                'body' => $mailBody,
            ];

            // if smtp status == 1, then set some value for PHPMailer
            if ($info->smtp_status == 1) {
                try {
                    $smtp = [
                        'transport' => 'smtp',
                        'host' => $info->smtp_host,
                        'port' => $info->smtp_port,
                        'encryption' => $info->encryption,
                        'username' => $info->smtp_username,
                        'password' => $info->smtp_password,
                        'timeout' => null,
                        'auth_mode' => 'PLAIN',
                        'verify_peer'       => false,
                    ];
                    Config::set('mail.mailers.smtp', $smtp);
                } catch (\Exception $e) {
                    Session::flash('error', $e->getMessage());
                    return back();
                }
            }

            $in['status'] = 0;
        } else {
            Session::flash('success', 'Sign up successfully completed.Please Login Now');
        }

        $in['status'] = 1;
        $in['password'] = Hash::make($request->password);
        $in['type'] = $request->type;
        $in['is_new'] = '0';
        $in['user_type'] = $request->usertype;
        $in['phone'] = $request->phone;
        $in['email_verified_at'] = now();

        $vendor = Vendor::create($in);

        Auth::guard('vendor')->login($vendor);
        $misc = new MiscellaneousController();

        $language = $misc->getLanguage();

        VendorInfo::create([
            'vendor_id' => $vendor->id,
            'language_id' => $language->id,
            'name' => $request->name
        ]);
        $freePackage = Package::where('title','Free')->first();
        $bs = Basic::first();
        $package['status'] = "1";
        $package['receipt_name'] = null;
        $package['email'] = $request->email;
        $package['price'] = 0.00;
        $package['payment_method'] = "-";
        $package['vendor_id'] = $vendor->id;
        $package['start_date'] = Carbon::now()->toDateString();
        $package['expire_date'] = Carbon::parse($freePackage->expire_date);
        $package['package_id'] = $freePackage->id;
        $transaction_details = "Free";
        $password = uniqid('qrcode');
        $transaction_id = VendorPermissionHelper::uniqidReal(8);
        $controller = new VendorCheckoutController();
        $vendor = $controller->store($package, $transaction_id, $transaction_details, $request['price'], $bs, $password);

        $user = User::where('phone', $request->phone)->delete();

        return redirect()->route('vendor.login');
    }

    //login
    public function login()
    {
        $misc = new MiscellaneousController();

        $language = $misc->getLanguage();

        $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keywords_vendor_login', 'meta_description_vendor_login')->first();

        $queryResult['pageHeading'] = $misc->getPageHeading($language);

        $queryResult['bgImg'] = $misc->getBreadcrumb();

        $queryResult['bs'] = Basic::query()->select('google_recaptcha_status', 'facebook_login_status', 'google_login_status')->first();
        return view('frontend.vendor.auth.login', $queryResult);
    }

    //authenticate
    public function authentication(Request $request)
    {
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
            Session::forget('secret_login');
            $request->session()->forget('redirectTo');
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
            return redirect()->back()->withErrors($validator->errors());
        }

        if (Auth::guard('vendor')->attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            $authAdmin = Auth::guard('vendor')->user();

            $setting = DB::table('basic_settings')->where('uniqid', 12345)->select('vendor_email_verification', 'vendor_admin_approval')->first();

            // check whether the admin's account is active or not
            if ($setting->vendor_email_verification == 1 && $authAdmin->email_verified_at == NULL && $authAdmin->status == 0) {
                Session::flash('error', 'Please verify your email address');

                // logout auth admin as condition not satisfied
                Auth::guard('vendor')->logout();

                return redirect()->back();
            } elseif ($setting->vendor_email_verification == 0 && $setting->vendor_admin_approval == 1) {
                Session::put('secret_login', 0);
                return redirect()->route('vendor.property_management.type');
            } else {
                Session::put('secret_login', 0);
                return redirect()->route('vendor.property_management.type');
            }
        } else {
            return redirect()->back()->with('error', 'Incorrect email or password');
        }
    }
    //confirm_email'
    public function confirm_email()
    {
        $email = request()->input('token');
        $user = Vendor::where('email', $email)->first();
        $user->email_verified_at = now();
        $setting = DB::table('basic_settings')->where('uniqid', 12345)->select('vendor_admin_approval')->first();
        if ($setting->vendor_admin_approval != 1) {
            $user->status = 1;
        }
        $user->save();
        Auth::guard('vendor')->login($user);
        return redirect()->route('vendor.dashboard');
    }
    public function logout(Request $request)
    {
        Auth::guard('vendor')->logout();
        Session::forget('vendor_secret_login');
        Session::forget('vendor_theme_version');
        return redirect()->route('index');
    }

    public function dashboard()
    {
        $vendor_id = Auth::guard('vendor')->user()->id;
        $information['totalProperties'] = Property::query()->where('vendor_id', $vendor_id)->count();
        $information['totalProjects'] = Project::query()->where('vendor_id', $vendor_id)->count();
        $information['totalAgents'] = Agent::query()->where('vendor_id', $vendor_id)->count();

        $information['admin_setting'] = DB::table('basic_settings')->where('uniqid', 12345)->select('vendor_admin_approval', 'admin_approval_notice')->first();

        $support_status = DB::table('support_ticket_statuses')->first();
        if ($support_status->support_ticket_status == 'active') {
            $total_support_tickets = SupportTicket::where([['user_id', Auth::guard('vendor')->user()->id], ['user_type', 'vendor']])->get()->count();
            $information['total_support_tickets'] = $total_support_tickets;
        }
        $information['support_status'] = $support_status;
        $information['admin_setting'] = DB::table('basic_settings')->where('uniqid', 12345)->select('vendor_admin_approval', 'admin_approval_notice')->first();


        $totalProperties = DB::table('properties')
            ->select(DB::raw('month(created_at) as month'), DB::raw('count(id) as total'))
            ->groupBy('month')
            ->where('vendor_id', $vendor_id)
            ->whereYear('created_at', '=', date('Y'))
            ->get();
        $totalProjects = DB::table('projects')
            ->select(DB::raw('month(created_at) as month'), DB::raw('count(id) as total'))
            ->groupBy('month')
            ->where('vendor_id', $vendor_id)
            ->whereYear('created_at', '=', date('Y'))
            ->get();


        $months = [];
        $totalPropertyArr = [];
        $totalProjectsArr = [];


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

            // // get all 12 months's project post
            $projectFound = false;
            foreach ($totalProjects as $totalProject) {
                if ($totalProject->month == $i) {
                    $projectFound = true;
                    array_push($totalProjectsArr, $totalProject->total);
                    break;
                }
            }
            if ($projectFound == false) {
                array_push($totalProjectsArr, 0);
            }
        }

        $payment_logs = Membership::where('vendor_id', $vendor_id)->get()->count();

        //package start
        $nextPackageCount = Membership::query()->where([
            ['vendor_id', Auth::guard('vendor')->user()->id],
            ['expire_date', '>=', Carbon::now()->toDateString()]
        ])->whereYear('start_date', '<>', '9999')->where('status', '<>', 2)->count();
        //current package
        $information['current_membership'] = Membership::query()->where([
            ['vendor_id', Auth::guard('vendor')->user()->id],
            ['start_date', '<=', Carbon::now()->toDateString()],
            ['expire_date', '>=', Carbon::now()->toDateString()]
        ])->where('status', 1)->whereYear('start_date', '<>', '9999')->first();
        if ($information['current_membership'] != null) {
            $countCurrMem = Membership::query()->where([
                ['vendor_id', Auth::guard('vendor')->user()->id],
                ['start_date', '<=', Carbon::now()->toDateString()],
                ['expire_date', '>=', Carbon::now()->toDateString()]
            ])->where('status', 1)->whereYear('start_date', '<>', '9999')->count();
            if ($countCurrMem > 1) {
                $information['next_membership'] = Membership::query()->where([
                    ['vendor_id', Auth::guard('vendor')->user()->id],
                    ['start_date', '<=', Carbon::now()->toDateString()],
                    ['expire_date', '>=', Carbon::now()->toDateString()]
                ])->where('status', '<>', 2)->whereYear('start_date', '<>', '9999')->orderBy('id', 'DESC')->first();
            } else {
                $information['next_membership'] = Membership::query()->where([
                    ['vendor_id', Auth::guard('vendor')->user()->id],
                    ['start_date', '>', $information['current_membership']->expire_date]
                ])->whereYear('start_date', '<>', '9999')->where('status', '<>', 2)->first();
            }
            $information['next_package'] = $information['next_membership'] ? Package::query()->where('id', $information['next_membership']->package_id)->first() : null;
        } else {
            $information['next_package'] = null;
        }
        $information['current_package'] = $information['current_membership'] ? Package::query()->where('id', $information['current_membership']->package_id)->first() : null;
        $information['package_count'] = $nextPackageCount;
        //package start end

        $information['monthArr'] = $months;
        $information['totalPropertiesArr'] = $totalPropertyArr;
        $information['totalProjectsArr'] = $totalProjectsArr;
        $information['payment_logs'] = $payment_logs;

        return view('vendors.index', $information);
    }

    //change_password
    public function change_password()
    {
        return view('vendors.auth.change-password');
    }

    //update_password
    public function updated_password(Request $request)
    {
        $rules = [
            'current_password' => [
                'required',
                new MatchOldPasswordRule('vendor')

            ],
            'new_password' => 'required|confirmed',
            'new_password_confirmation' => 'required'
        ];

        $messages = [
            'new_password.confirmed' => 'Password confirmation does not match.',
            'new_password_confirmation.required' => 'The confirm new password field is required.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $vendor = Auth::guard('vendor')->user();

        $vendor->update([
            'password' => Hash::make($request->new_password)
        ]);

        Session::flash('success', 'Password updated successfully!');

        return response()->json(['status' => 'success'], 200);
    }

    //edit_profile
    public function edit_profile()
    {
        $information = [];
        $misc = new MiscellaneousController();

        $language = $misc->getLanguage();

        $information['language'] = $language;
        $information['languages'] = Language::get();

        $vendor_id = Auth::guard('vendor')->user()->id;
        $information['vendor'] = Vendor::with('vendor_info')->where('id', $vendor_id)->first();
        return view('vendors.auth.edit-profile', $information);
    }
    //add_kyc
    public function add_kyc()
    {
        $data = [];
        $misc = new MiscellaneousController();

        $language = $misc->getLanguage();

        $data['language'] = $language;
        $data['languages'] = Language::get();

        $vendor_id = Auth::guard('vendor')->user()->id;
        $data['vendor'] = Vendor::with('vendor_info')->where('id', $vendor_id)->first();
        $data['get_vendor_kyc_detail'] = VendorKYC::where('user_id',Auth::user()->id)->first();
        return view('vendors.auth.edit-kyc', ['data' => $data]);
    }

    //submitVendorKyc(Request $request)
    public function submitVendorKyc(Request $request)
    {
       try {

            $userkyc = VendorKYC::where('user_id',Auth::user()->id)->first();

            if(empty($userkyc))
            {
                if($request->file('aadhar_front'))
                {
                    $path_avatar = public_path('images/user_document/'.Auth::user()->id.'/');
                    $aadhar_front = $request->file('aadhar_front');
                    $aadharfront = 'aadhar_front_'.time().rand(111111,999999) . '.' . $aadhar_front->getClientOriginalExtension();
                    $filename = $path_avatar."/".$aadharfront;
                    $aadhar_front->move($path_avatar, $aadharfront);
                }

                if($request->file('aadhar_back'))
                {
                    $path_avatar = public_path('images/user_document/'.Auth::user()->id.'/');
                    $aadhar_back = $request->file('aadhar_back');
                    $aadharback = 'aadhar_back_'.time().rand(111111,999999) . '.' . $aadhar_back->getClientOriginalExtension();
                    $filename = $path_avatar."/".$aadharback;
                    $aadhar_back->move($path_avatar, $aadharback);
                }

                if($request->file('pancard'))
                {

                    $path_avatar = public_path('images/user_document/'.Auth::user()->id.'/');
                    $pancard = $request->file('pancard');
                    $pancardimg = 'pancard_'.time().rand(111111,999999) . '.' . $pancard->getClientOriginalExtension();
                    $filename = $path_avatar."/".$pancardimg;
                    $pancard->move($path_avatar, $pancardimg);
                }

                $document_kyc  = new VendorKYC();
                $document_kyc->user_id = Auth::user()->id;
                $document_kyc->bank_name = $request->bank_name;
                $document_kyc->branch_name = $request->branch_name;
                $document_kyc->aadhar_card_number = $request->aadhar_card_number;
                $document_kyc->account_number = $request->account_number;
                $document_kyc->ifsc_code = $request->ifsc_code;
                $document_kyc->gst_number = $request->gst_number;
                $document_kyc->aadhar_front = $aadharfront;
                $document_kyc->aadhar_back = $aadharback;
                $document_kyc->pancard = $pancardimg;
                $document_kyc->save();

                return response()->json([
                    'status' => 1,
                    'message' => __('Send your request successfully. Please wait admin review your account.')
                ]);
            } else
            {

                if(!empty($request->file('aadhar_front')))
                {
                    $oldImagePath =  public_path('images/user_document').'/'.Auth::user()->id.'/'.$userkyc->aadhar_front;

                    if(File::exists($oldImagePath))
                    {
                        unlink($oldImagePath);
                    }
                    $path_avatar = public_path('images/user_document/'.Auth::user()->id.'/');
                    $aadhar_front = $request->file('aadhar_front');
                    $getAvatar = 'gst_'.time().rand(111111,999999) . '.' . $aadhar_front->getClientOriginalExtension();
                    $filename = $path_avatar."/".$getAvatar;
                    $aadhar_front->move($path_avatar, $getAvatar);

                    VendorKYC::where('id', $userkyc->id)->update([
                    'aadhar_front' => $getAvatar,
                    ]);
                }
                if(!empty($request->file('aadhar_back')))
                {
                    $oldImagePath =  public_path('images/user_document').'/'.Auth::user()->id.'/'.$userkyc->aadhar_front;

                    if(File::exists($oldImagePath))
                    {
                        unlink($oldImagePath);
                    }
                    $path_avatar = public_path('images/user_document/'.Auth::user()->id.'/');
                    $aadhar_back = $request->file('aadhar_back');
                    $getAvatar = 'gst_'.time().rand(111111,999999) . '.' . $aadhar_back->getClientOriginalExtension();
                    $filename = $path_avatar."/".$getAvatar;
                    $aadhar_back->move($path_avatar, $getAvatar);

                    VendorKYC::where('id', $userkyc->id)->update([
                    'aadhar_back' => $getAvatar,
                    ]);
                }
                if(!empty($request->file('pancard')))
                {
                    $oldImagePath =  public_path('images/user_document').'/'.Auth::user()->id.'/'.$userkyc->pancard;

                    if(File::exists($oldImagePath))
                    {
                        unlink($oldImagePath);
                    }
                    $path_avatar = public_path('images/user_document/'.Auth::user()->id.'/');
                    $pancard = $request->file('pancard');
                    $getAvatar = 'gst_'.time().rand(111111,999999) . '.' . $pancard->getClientOriginalExtension();
                    $filename = $path_avatar."/".$getAvatar;
                    $pancard->move($path_avatar, $getAvatar);

                    VendorKYC::where('id', $userkyc->id)->update([
                    'pancard' => $getAvatar,
                    ]);

                }

                VendorKYC::where('id', $userkyc->id)->update([
                    'aadhar_card_number' => $request->aadhar_card_number,
                    'bank_name' => $request->bank_name,
                    'branch_name' => $request->branch_name,
                    'account_number' => $request->account_number,
                    'ifsc_code' => $request->ifsc_code,
                    'gst_number' => $request->gst_number,
                ]);

                return response()->json([
                    'status' => 1,
                    'message' => __('Send your request successfully. Please wait admin review your KYC data.')
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => __('Send your request failed')
            ]);
        }
    }

    //update_profile
    public function update_profile(Request $request, Vendor $vendor)
    {
        $vendor = Auth::guard('vendor')->user();
        $id = $vendor->id;

        $languages = Language::get();

        $rules = [
            'username' => [
                'required',
                'not_in:admin',
                Rule::unique('vendors', 'username')->ignore($id),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('vendors', 'email')->ignore($id),
            ]
        ];

        if ($request->hasFile('photo')) {
            $rules['photo'] = 'mimes:png,jpeg,jpg';
        }

        foreach ($languages as $language) {
            $rules[$language->code . '_name'] = 'required';
        }

        $messages = [];
        foreach ($languages as $language) {
            $messages[$language->code . '_name.required'] =
                'The Name field is required for ' . $language->name . ' language.';
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation',
                'errors' => $validator->errors(),
            ], 422);
        }


        $in = $request->all();
        $file = $request->file('photo');
        if ($file) {
            $extension = $file->getClientOriginalExtension();
            $directory = public_path('assets/admin/img/vendor-photo/');
            $fileName = uniqid() . '.' . $extension;
            @mkdir($directory, 0775, true);
            $file->move($directory, $fileName);

            @unlink(public_path('assets/admin/img/vendor-photo/') . $vendor->photo);
            $in['photo'] = $fileName;
        }


        if ($request->show_email_addresss) {
            $in['show_email_addresss'] = 1;
        } else {
            $in['show_email_addresss'] = 0;
        }
        if ($request->show_phone_number) {
            $in['show_phone_number'] = 1;
        } else {
            $in['show_phone_number'] = 0;
        }
        if ($request->show_contact_form) {
            $in['show_contact_form'] = 1;
        } else {
            $in['show_contact_form'] = 0;
        }
        $in['type'] = $request->type;
        $vendor->update($in);

        $languages = Language::get();
        $vendor_id = $vendor->id;
        foreach ($languages as $language) {
            $vendorInfo = VendorInfo::where('vendor_id', $vendor_id)->where('language_id', $language->id)->first();
            if ($vendorInfo == NULL) {
                $vendorInfo = new VendorInfo();
            }
            $vendorInfo->language_id = $language->id;
            $vendorInfo->vendor_id = $vendor_id;
            $vendorInfo->name = $request[$language->code . '_name'];
            $vendorInfo->country = $request[$language->code . '_country'];
            $vendorInfo->city = $request[$language->code . '_city'];
            $vendorInfo->state = $request[$language->code . '_state'];
            $vendorInfo->zip_code = $request[$language->code . '_zip_code'];
            $vendorInfo->address = $request[$language->code . '_address'];
            $vendorInfo->details = $request[$language->code . '_details'];
            $vendorInfo->save();
        }

        Session::flash('success', 'Vendor updated successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function sendPhoneOtp(Request $request)
    {
        // Check if user exists
        $vendor = Auth::guard('vendor')->user();

        // Generate OTP
        $otp = rand(1000, 9999);
        $smsContent = "Use OTP $otp to log in securely. This code is valid for 10 minutes. Keep it confidential._ Team Dalal Maf";

        // Send via MsgClub helper
        $result = msgClubSendSms($request->phone, $smsContent);

        if (!$result) {
            return response()->json([
                'message' => 'OTP sending failed.',
                'otp' => $otp
            ]);
        }

        // Calculate expiry (10 minutes)
        $expiresAt = now()->addMinutes(10);
        // Insert new OTP row, with otp_at = NULL
        DB::table('otp_verification')->updateOrInsert(
            ['phone' => $request->phone], // Condition: if this exists
            [
                'user_id' => $vendor->id,
                'phone' => $request->phone,
                'otp' => $otp,
                'otp_at' => null,
                'expires_at' => $expiresAt,
                'updated_at' => now(),
                'created_at' => now(), // Optional; doesn't update on existing row
            ]
        );

        return response()->json([
            'message' => 'OTP sent successfully.',
            'otp' => $otp
        ]);
    }

    public function resendPhoneOtp(Request $request)
    {
        $phone = $request->phone;
        if (empty($phone)) {
            return response()->json([
                'message' => 'Phone Number is required.',
            ]);
        }
        $now = now();
        $expiresAt = $now->copy()->addMinutes(10);

        $otp = rand(1000, 9999);
        $smsContent = "Use OTP $otp to log in securely. This code is valid for 10 minutes. Keep it confidential._ Team Dalal Maf";

        // Send via MsgClub helper
        $result = msgClubSendSms($request->phone, $smsContent);

        if (!$result) {
            return response()->json([
                'message' => 'OTP sending failed.',
                'otp' => $otp
            ]);
        }

        DB::table('otp_verification')->updateOrInsert(
            ['phone' => $phone],
            [
                'phone' => $phone,
                'otp' => $otp,
                'otp_at' => null,
                'expires_at' => $expiresAt,
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );

        return response()->json([
            'message' => 'OTP sent successfully.',
            'expires_at' => $expiresAt->toIso8601String(),
        ]);
    }

    public function verifyPhoneOtp(Request $request)
    {
        try {
            $phone = $request->phone;
            $otp = $request->otp;

            // Fetch latest OTP record
            $otpRecord = DB::table('otp_verification')
                ->where('phone', $phone)
                ->latest()
                ->first();

            if (!$otpRecord) {
                return response()->json(['message' => 'OTP not found.'], 404);
            }

            // 3️⃣ Check expiry before verifying
            if (isset($otpRecord->expires_at) && now()->gt($otpRecord->expires_at)) {
                return response()->json(['message' => 'OTP has expired. Please request a new one.'], 410);
            }

            if ($otpRecord->otp != $otp) {
                return response()->json(['message' => 'Invalid OTP.'], 422);
            }

            // Mark OTP verified
            DB::table('otp_verification')
                ->where('id', $otpRecord->id)
                ->update(['otp_at' => now()]);

            $vendor = Auth::guard('vendor')->user();
            $vendor->update(['phone' => $request->phone]);

            // Auth::guard('vendor')->logout();

            // // Invalidate session and regenerate CSRF token for security
            // $request->session()->invalidate();
            // $request->session()->regenerateToken();

            return response()->json([
                'message' => 'OTP verified successfully.',
                'url' => url('user/edit-profile')
            ]);

        } catch (\Throwable $e) {
            // Log::error('OTP verification error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'An error occurred during OTP verification.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function changeTheme(Request $request)
    {
        Session::put('vendor_theme_version', $request->vendor_theme_version);
        return redirect()->back();
    }
    //forget_passord
    public function forget_passord()
    {
        $misc = new MiscellaneousController();

        $language = $misc->getLanguage();

        $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keywords_vendor_forget_password', 'meta_descriptions_vendor_forget_password')->first();

        $queryResult['pageHeading'] = $misc->getPageHeading($language);

        $queryResult['bgImg'] = $misc->getBreadcrumb();
        $queryResult['bs'] = Basic::query()->select('google_recaptcha_status', 'facebook_login_status', 'google_login_status')->first();
        return view('frontend.vendor.auth.forget-password', $queryResult);
    }
    //forget_mail
    public function forget_mail(Request $request)
    {
        $rules = [
            'email' => [
                'required',
                'email:rfc,dns',
                new MatchEmailRule('vendor')
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
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = Vendor::where('email', $request->email)->first();

        // first, get the mail template information from db
        $mailTemplate = MailTemplate::where('mail_type', 'reset_password')->first();
        $mailSubject = $mailTemplate->mail_subject;
        $mailBody = $mailTemplate->mail_body;

        // second, send a password reset link to user via email
        $info = DB::table('basic_settings')
            ->select('website_title', 'smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
            ->first();

        $name = $user->username;
        $token =  Str::random(32);
        DB::table('password_resets')->insert([
            'email' => $user->email,
            'token' => $token,
        ]);

        $link = '<a href=' . url("vendor/reset-password?token=" . $token) . '>Click Here</a>';

        $mailBody = str_replace('{customer_name}', $name, $mailBody);
        $mailBody = str_replace('{password_reset_link}', $link, $mailBody);
        $mailBody = str_replace('{website_title}', $info->website_title, $mailBody);

        $data = [
            'to' => $request->email,
            'subject' => $mailSubject,
            'body' => $mailBody,
        ];

        // if smtp status == 1, then set some value for PHPMailer
        if ($info->smtp_status == 1) {
            try {
                $smtp = [
                    'transport' => 'smtp',
                    'host' => $info->smtp_host,
                    'port' => $info->smtp_port,
                    'encryption' => $info->encryption,
                    'username' => $info->smtp_username,
                    'password' => $info->smtp_password,
                    'timeout' => null,
                    'auth_mode' => null,
                ];
                Config::set('mail.mailers.smtp', $smtp);
            } catch (\Exception $e) {
                Session::flash('error', $e->getMessage());
                return back();
            }
        }

        // finally add other informations and send the mail
        try {
            Mail::send([], [], function (Message $message) use ($data, $info) {
                $fromMail = $info->from_mail;
                $fromName = $info->from_name;
                $message->to($data['to'])
                    ->subject($data['subject'])
                    ->from($fromMail, $fromName)
                    ->html($data['body'], 'text/html');
            });

            Session::flash('success', 'A mail has been sent to your email address');
        } catch (\Exception $e) {
            Session::flash('error', 'Mail could not be sent!');
        }

        // store user email in session to use it later
        $request->session()->put('userEmail', $user->email);
        return redirect()->back();
    }
    //reset_password
    public function reset_password()
    {

        $misc = new MiscellaneousController();

        $language = $misc->getLanguage();

        $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keywords_vendor_forget_password', 'meta_descriptions_vendor_forget_password')->first();

        $queryResult['bgImg'] = $misc->getBreadcrumb();
        return view('frontend.vendor.auth.reset-password', $queryResult);
    }
    //update_password
    public function update_password(Request $request)
    {
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
            return redirect()->back()->withErrors($validator);
        }

        $reset = DB::table('password_resets')->where('token', $request->token)->first();
        $email = $reset->email;

        $vendor = Vendor::where('email',  $email)->first();

        $vendor->update([
            'password' => Hash::make($request->new_password)
        ]);
        DB::table('password_resets')->where('token', $request->token)->delete();
        Session::flash('success', 'Reset Your Password Successfully Completed.Please Login Now');

        return redirect()->route('vendor.login');
    }
    public function payment_log(Request $request)
    {
        $search = $request->search;
        $data['memberships'] = Membership::query()->when($search, function ($query, $search) {
            return $query->where('transaction_id', 'like', '%' . $search . '%');
        })
            ->where('vendor_id', Auth::guard('vendor')->user()->id)
            ->orderBy('id', 'DESC')
            ->get();
        return view('vendors.payment_log', $data);
    }
}
