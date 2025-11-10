<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller; 
use App\Models\BasicSettings\Basic; 
use App\Models\Property\Property;
use App\Http\Helpers\VendorPermissionHelper;
use App\Models\Property\PropertyContact;
use App\Models\Property\Wishlist; 
use App\Models\SupportTicket;
use App\Models\Language;
use App\Models\VendorInfo;
use App\Models\Conversation;
use App\Http\Helpers\UploadFile;
use Carbon\Carbon;
use DateTime; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Validator; 
use App\Traits\ApiResponseTrait;
use App\Traits\AuthGuardTrait;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Config;
use App\Models\Membership;
use App\Models\Package;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\PaymentGateway\OnlineGateway;
use File;
use App\Models\AgentInfo;

class UserDashboardController extends Controller
{
    use ApiResponseTrait;
    use AuthGuardTrait;

    public function dashboard(Request $request)
    { 
        $user_id = Auth::guard('api')->user()->id;
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

        return $this->successResponse($information, 'User dashboard data retrieved successfully.');
    }

    public function userInquiry(Request $request)
    {
        // Get guard info or JSON error automatically
        $auth = $this->resolveAuthGuard($request->auth_type);

        // If invalid auth type, it's already a JsonResponse, so return it
        if ($auth instanceof \Illuminate\Http\JsonResponse) {
            return $auth;
        }

        [$guard, $column] = [$auth[0], $auth[2]];

        $user = Auth::guard($guard)->user();
        if (!$user) {
            return $this->unauthorizedResponse();
        }

        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 10);

        $user_id = $user->id;
        $inquiries = PropertyContact::where($column, $user_id)
            ->with('property:id','property.propertyContent:id,property_id,slug,title')
            ->orderBy('id', 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get();

        return $this->successResponse($inquiries, 'User inquiries retrieved successfully.');
    }

    public function userWishlist(Request $request)
    {
        // Get guard info or JSON error automatically
        $auth = $this->resolveAuthGuard($request->auth_type);

        // If invalid auth type, it's already a JsonResponse, so return it
        if ($auth instanceof \Illuminate\Http\JsonResponse) {
            return $auth;
        }

        [$guard, $column] = [$auth[0], $auth[1]];

        $user = Auth::guard($guard)->user();
        if (!$user) {
            return $this->unauthorizedResponse();
        }

        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 10);

        $user_id = $user->id;
        $wishlists = Wishlist::where($column, $user_id)
            ->with('property:id','property.propertyContent:id,property_id,slug,title')
            ->orderBy('id', 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get();

        return $this->successResponse($wishlists, 'User wishlist retrieved successfully.');
    }

    public function listTicket(Request $request)
    {
        // Get guard info or JSON error automatically
        $auth = $this->resolveAuthGuard($request->auth_type);

        // If invalid auth type, it's already a JsonResponse, so return it
        if ($auth instanceof \Illuminate\Http\JsonResponse) {
            return $auth;
        }

        [$guard, $column] = [$auth[0], $auth[1]];

        $user = Auth::guard($guard)->user();
        if (!$user) {
            return $this->unauthorizedResponse();
        }

        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 10);

        $user_id = $user->id;
        $wishlists = SupportTicket::where([['user_id', $user_id], ['user_type', $request->auth_type]])
            ->orderBy('id', 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get();

        return $this->successResponse($wishlists, 'User wishlist retrieved successfully.');
    }

    public function createTicket(Request $request)
    {
        // Get guard info or JSON error automatically
        $auth = $this->resolveAuthGuard($request->auth_type);

        // If invalid auth type, it's already a JsonResponse, so return it
        if ($auth instanceof \Illuminate\Http\JsonResponse) {
            return $auth;
        }

        [$guard, $column] = [$auth[0], $auth[1]];

        $user = Auth::guard($guard)->user();
        if (!$user) {
            return $this->unauthorizedResponse();
        }

        $validator = Validator::make($request->all(), [
            'subject' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'description' => ['required', 'string'],
            'attachment' => ['nullable', 'file', 'mimes:zip', 'max:20480'], // 20MB
        ]);

        if ($validator->fails()) {
            // Returns JSON in your trait's shape
            return $this->errorResponse($validator->errors()->first());
        } 
        DB::beginTransaction();

        try {
            $data = [
                'subject' => $request->input('subject'),
                'email' => $request->input('email'),
                // sanitize description (keeps youtube if you configured this profile)
                'description' => \Purifier::clean($request->input('description'), 'youtube'),
                'user_id' => $user->id,
                'user_type' => $request->auth_type, // or derive from guard if you support multiple types
            ];

            // 3) Handle file (store in storage/app/support-tickets/attachments)
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $filename = uniqid('st_') . '.' . $file->getClientOriginalExtension();
                // You can choose disk 'public' and create a symbolic link (php artisan storage:link)
                $path = $file->storeAs('support-tickets/attachments', $filename, ['disk' => 'public']);
                // Save relative path or filename — choose one convention and stick to it
                $data['attachment'] = $path; // e.g. "support-tickets/attachments/st_xxx.zip"
            }

            // 4) Create ticket
            $ticket = SupportTicket::create($data);

            DB::commit();

            // 5) Success JSON (include a small meta)
            return $this->successResponse(
                ['ticket' => $ticket],
                'Ticket has been submitted successfully.'
            );

        } catch (\Throwable $e) {
            DB::rollBack();  
            return $this->errorResponse('Failed to submit ticket. Please try again.');
        }
    }

    public function messageTicket($id)
    { 
        $ticket = SupportTicket::with('messages')->where([['id', $id]])->first();
        if (!$ticket) {
            return $this->errorResponse('Ticket not found.', 404);
        } 
        foreach($ticket->messages as $message) {
            $message->load($message->getSenderRelationName());
        }
        return $this->successResponse($ticket, 'Support ticket message retrieved successfully.');
    }

    public function replyTicket(Request $request)
    {
        // Get guard info or JSON error automatically
        $auth = $this->resolveAuthGuard($request->auth_type);

        // If invalid auth type, it's already a JsonResponse, so return it
        if ($auth instanceof \Illuminate\Http\JsonResponse) {
            return $auth;
        }

        [$guard, $column] = [$auth[0], $auth[1]];

        $user = Auth::guard($guard)->user();
        if (!$user) {
            return $this->unauthorizedResponse();
        }

        $file = $request->file('file');
        $allowedExts = array('zip');
        $validator = Validator::make($request->all(), [
            'reply' => 'required',
            'file' => [
                function ($attribute, $value, $fail) use ($file, $allowedExts) {

                    $ext = $file->getClientOriginalExtension();
                    if (!in_array($ext, $allowedExts)) {
                        return $fail("Only zip file supported");
                    }
                },
                'max:20000'
            ],
        ]);

        if ($validator->fails()) {
            // Returns JSON in your trait's shape
            return $this->errorResponse($validator->errors()->first());
        }

        DB::beginTransaction();

        try 
        { 
            // Create reply message
            $ticket = SupportTicket::with('messages')->find($request->ticket_id);
            if (!$ticket) {
                return $this->errorResponse('Ticket not found or access denied.', 404);
            }

            $input = $request->all();

            $input['reply'] = \Purifier::clean($request->reply, 'youtube');
            $input['type'] = 1;
            $input['user_id'] = $user->id;
            $input['support_ticket_id'] = $request->ticket_id;
            if ($request->hasFile('file')) 
            {
                $file = $request->file('file');
                $filename = uniqid() . '.' . $file->getClientOriginalExtension();
                @mkdir(public_path('assets/admin/img/support-ticket/'), 0775, true);
                $file->move(public_path('assets/admin/img/support-ticket/'), $filename);
                $input['file'] = $filename;
            }

            $data = new Conversation();
            $data->create($input);

            $ticket->update([
                'last_message' => Carbon::now()
            ]);

            foreach ($ticket->messages as $message) {
                $message->load($message->getSenderRelationName());
            }

            DB::commit();
            return $this->successResponse($ticket, 'Reply has been submitted successfully.');
         } catch (\Throwable $e) {
            DB::rollBack();  
            return $this->errorResponse('Failed to submit ticket. Please try again.');
        }
    }

    public function profileUpdate(Request $request)
    { 
        $authUser = Auth::guard('api')->user();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'username' => [
                'required',
                'alpha_dash',
                Rule::unique('users', 'username')->ignore($authUser->id),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($authUser->id)
            ],
        ]);

        if ($validator->fails()) { 
            return $this->errorResponse($validator->errors()->first());
        }
        DB::beginTransaction();

        try { 
            $data = $request->all();
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $directory = public_path('assets/img/users/');
                @mkdir($directory, 0775, true);

                // Delete old image if exists
                if (!empty($authUser->image) && file_exists($directory . $authUser->image)) {
                    @unlink($directory . $authUser->image);
                }

                $extension = $file->getClientOriginalExtension();
                $fileName = uniqid('usr_') . '.' . $extension;
                $file->move($directory, $fileName);
                $data['image'] = $fileName;
            }
 
            $authUser->update($data);

            DB::commit();;

            return $this->successResponse($authUser, 'User profile retrieved successfully.');
        } catch (\Throwable $e) {
            DB::rollBack(); 
            return $this->errorResponse('Failed to update profile. Please try again later.'. $e->getMessage(), 500);
        }
    }

    public function vendorProfileUpdate(Request $request)
    {
        /** @var \App\Models\Vendor $vendor */
        $vendor = Auth::guard('vendor_api')->user();
        $id = $vendor->id;

        $languages = Language::all();

        // ---- Validation ----
        $rules = [
            'username' => ['required', 'not_in:admin', Rule::unique('vendors', 'username')->ignore($id)],
            'email' => ['required', 'email', Rule::unique('vendors', 'email')->ignore($id)],
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
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        DB::beginTransaction();
        try {
            // ---- Build payload (keep behavior) ----
            $in = $request->except('photo'); // don't dump file object into update()

            // Booleans normalized
            $in['show_email_addresss'] = $request->boolean('show_email_addresss');
            $in['show_phone_number'] = $request->boolean('show_phone_number');
            $in['show_contact_form'] = $request->boolean('show_contact_form');

            // ---- File upload (same path) ----
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $extension = $file->getClientOriginalExtension();
                $directory = public_path('assets/admin/img/vendor-photo');
                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0775, true);
                }
                $fileName = uniqid('', true) . '.' . $extension;

                // Move new file
                $file->move($directory, $fileName);

                // Delete old file if any
                if (!empty($vendor->photo)) {
                    $old = $directory . DIRECTORY_SEPARATOR . $vendor->photo;
                    if (File::exists($old)) {
                        @File::delete($old);
                    }
                }
                $in['photo'] = $fileName;
            }

            // Update vendor
            $vendor->update($in);

            // ---- VendorInfo per language (same logic) ----
            foreach ($languages as $language) {
                $vendorInfo = VendorInfo::firstOrNew([
                    'vendor_id' => $vendor->id,
                    'language_id' => $language->id,
                ]);

                $code = $language->code;
                $vendorInfo->name = $request->input("{$code}_name");
                $vendorInfo->country = $request->input("{$code}_country");
                $vendorInfo->city = $request->input("{$code}_city");
                $vendorInfo->state = $request->input("{$code}_state");
                $vendorInfo->zip_code = $request->input("{$code}_zip_code");
                $vendorInfo->address = $request->input("{$code}_address");
                $vendorInfo->details = $request->input("{$code}_details");
                $vendorInfo->save();
            }

            DB::commit();

            // IMPORTANT: refresh to return latest values
            $vendor->refresh();

            // ✅ FIXED: use $vendor (not $$vendor)
            return $this->successResponse($vendor, 'Vendor profile updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->errorResponse(
                'Failed to update profile. Please try again later.',
                500
            );
        }
    }

    public function agentProfileUpdate(Request $request)
    {
        // 1) Auth check (agent_api guard)
        /** @var \App\Models\Agent|null $agent */
        $agent = Auth::guard('agent_api')->user();
        if (!$agent) {
            return $this->errorResponse('Unauthenticated.', 401);
        }

        $id = $agent->id;
        $languages = Language::all();

        // 2) Validation
        $rules = [
            'username' => ['required', 'not_in:admin', Rule::unique('agents', 'username')->ignore($id)],
            'email' => ['required', 'email', Rule::unique('agents', 'email')->ignore($id)], 
        ];

        if ($request->hasFile('photo')) {
            $rules['photo'] = 'mimes:png,jpeg,jpg,svg';
        }

        foreach ($languages as $language) {
            $rules[$language->code . '_first_name'] = 'required';
            $rules[$language->code . '_last_name'] = 'required';
        }

        $messages = [];
        foreach ($languages as $language) {
            $messages[$language->code . '_first_name.required'] =
                'The First Name field is required for ' . $language->name . ' language.';
            $messages[$language->code . '_last_name.required'] =
                'The Last Name field is required for ' . $language->name . ' language.';
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        // 3) Prepare payload
        $newImage = $agent->image;
        if ($request->hasFile('photo')) {
            // Ensure your helper signature is: update($dir, UploadedFile $newFile, $oldFileName)
            $newImage = UploadFile::update(public_path('assets/img/agents/'), $request->file('photo'), $agent->image);
        }

        // Normalize booleans
        $showEmail = $request->boolean('show_email_addresss');
        $showPhone = $request->boolean('show_phone_number');
        $showContact = $request->boolean('show_contact_form');

        // 4) Persist
        DB::beginTransaction();
        try {
            // Agent update
            $agent->update([
                'username' => $request->input('username', $agent->username),
                'email' => $request->input('email', $agent->email),
                'phone' => $request->input('phone', $agent->phone),
                'image' => $newImage,
                'show_email_addresss' => $showEmail ? 1 : 0,
                'show_phone_number' => $showPhone ? 1 : 0,
                'show_contact_form' => $showContact ? 1 : 0,
            ]);

            // AgentInfo per language
            foreach ($languages as $language) {
                $code = $language->code;

                $info = AgentInfo::firstOrNew([
                    'agent_id' => $agent->id,
                    'language_id' => $language->id,
                ]);

                $info->first_name = $request->input($code . '_first_name');
                $info->last_name = $request->input($code . '_last_name');
                $info->country = $request->input($code . '_country');
                $info->city = $request->input($code . '_city');
                $info->state = $request->input($code . '_state');
                $info->zip_code = $request->input($code . '_zip_code');
                $info->address = $request->input($code . '_address');
                $info->details = $request->input($code . '_details');
                $info->save();
            }

            DB::commit();

            // Refresh and return
            $agent->refresh();
            return $this->successResponse($agent, 'Agent updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            // Optionally log: \Log::error($e);
            return $this->errorResponse('Something went wrong while updating the agent.', 500);
        }
    } 

    public function sendPhoneOtp(Request $request)
    { 
        // Get guard info or JSON error automatically
        $auth = $this->resolveAuthGuard($request->auth_type);

        // If invalid auth type, it's already a JsonResponse, so return it
        if ($auth instanceof \Illuminate\Http\JsonResponse) {
            return $auth;
        }

        [$guard, $column] = [$auth[0], $auth[1]];

        $user = Auth::guard($guard)->user();
        if (!$user) {
            return $this->unauthorizedResponse();
        }

        // Generate OTP
        $otp = rand(1000, 9999);
        $smsContent = "Use OTP $otp to log in securely. This code is valid for 10 minutes. Keep it confidential._ Team Dalal Maf";

        // Send via MsgClub helper
        $result = msgClubSendSms($request->phone, $smsContent);

        if (!$result) { 
            return $this->errorResponse('OTP sending failed.');
        }

        // Calculate expiry (10 minutes)
        $expiresAt = now()->addMinutes(10);
        // Insert new OTP row, with otp_at = NULL
        DB::table('otp_verification')->updateOrInsert(
            ['phone' => $request->phone], // Condition: if this exists
            [
                'user_id' => $user->id,
                'phone' => $request->phone,
                'otp' => $otp,
                'otp_at' => null,
                'expires_at' => $expiresAt,
                'updated_at' => now(),
                'created_at' => now(), // Optional; doesn't update on existing row
            ]
        );

        return $this->successResponse([], 'OTP sent successfully.');
    }

    public function resendPhoneOtp(Request $request)
    { 
        // Get guard info or JSON error automatically
        $auth = $this->resolveAuthGuard($request->auth_type);

        // If invalid auth type, it's already a JsonResponse, so return it
        if ($auth instanceof \Illuminate\Http\JsonResponse) {
            return $auth;
        }

        [$guard, $column] = [$auth[0], $auth[1]];

        $user = Auth::guard($guard)->user();
        if (!$user) {
            return $this->unauthorizedResponse();
        }
 
        $phone = $request->phone; 
        if(empty($phone))
        { 
            return $this->errorResponse('Phone Number is required.');
        }
        $now = now();
        $expiresAt = $now->copy()->addMinutes(10);  
        
        $otp = rand(1000, 9999);
        $smsContent = "Use OTP $otp to log in securely. This code is valid for 10 minutes. Keep it confidential._ Team Dalal Maf";

        // Send via MsgClub helper
        $result = msgClubSendSms($request->phone, $smsContent);

        if (!$result) { 
            return $this->errorResponse('OTP sending failed.');
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

        return $this->successResponse([], 'OTP sent successfully.'); 
    }

    public function verifyPhoneOtp(Request $request)
    { 
         // Get guard info or JSON error automatically
        $auth = $this->resolveAuthGuard($request->auth_type);

        // If invalid auth type, it's already a JsonResponse, so return it
        if ($auth instanceof \Illuminate\Http\JsonResponse) {
            return $auth;
        }

        [$guard, $column] = [$auth[0], $auth[1]];

        $user = Auth::guard($guard)->user();
        if (!$user) {
            return $this->unauthorizedResponse();
        }

        try {
            $phone = $request->phone;
            $otp = $request->otp; 
            
            // Fetch latest OTP record
            $otpRecord = DB::table('otp_verification')
                ->where('phone', $phone)
                ->latest()
                ->first();

            if (!$otpRecord) { 
                return $this->errorResponse('OTP not found.');
            }

            // 3️⃣ Check expiry before verifying
            if (isset($otpRecord->expires_at) && now()->gt($otpRecord->expires_at)) {
                return $this->errorResponse('OTP has expired. Please request a new one.');  
            }

            if ($otpRecord->otp != $otp) {
                return $this->errorResponse( 'Invalid OTP.'); 
            }

            // Mark OTP verified
            DB::table('otp_verification')
                ->where('id', $otpRecord->id)
                ->update(['otp_at' => now()]);
 
            $user->update(['phone' => $request->phone]); 
          
            return $this->successResponse([], 'OTP verified successfully. phone number has been change.');

        } catch (\Throwable $e) { 
            return $this->errorResponse( 'An error occurred during OTP verification.'); 
        }
    } 
    public function packagePlans()
    { 
        $user = Auth::guard('vendor_api')->user();
        if(!$user)
        {
            return $this->unauthorizedResponse();
        }

        $abs = Basic::first(); 
        Config::set('app.timezone', $abs->timezone);

        $currentLang = Language::where('is_default', 1)->first(); 
        $data['bex'] = $currentLang->basic_extended;
        $data['packages'] = Package::where('status', '1')->get();

        $nextPackageCount = Membership::query()->where([
            ['vendor_id',  $user->id],
            ['expire_date', '>=', Carbon::now()->toDateString()]
        ])->whereYear('start_date', '<>', '9999')->where('status', '<>', 2)->count();
        //current package
        $data['current_membership'] = Membership::query()->where([
            ['vendor_id', $user->id],
            ['start_date', '<=', Carbon::now()->toDateString()],
            ['expire_date', '>=', Carbon::now()->toDateString()]
        ])->where('status', 1)->whereYear('start_date', '<>', '9999')->first();
        if ($data['current_membership'] != null) {
            $countCurrMem = Membership::query()->where([
                ['vendor_id',  $user->id],
                ['start_date', '<=', Carbon::now()->toDateString()],
                ['expire_date', '>=', Carbon::now()->toDateString()]
            ])->where('status', 1)->whereYear('start_date', '<>', '9999')->count();
            if ($countCurrMem > 1) {
                $data['next_membership'] = Membership::query()->where([
                    ['vendor_id', $user->id],
                    ['start_date', '<=', Carbon::now()->toDateString()],
                    ['expire_date', '>=', Carbon::now()->toDateString()]
                ])->where('status', '<>', 2)->whereYear('start_date', '<>', '9999')->orderBy('id', 'DESC')->first();
            } else {
                $data['next_membership'] = Membership::query()->where([
                    ['vendor_id', $user->id],
                    ['start_date', '>', $data['current_membership']->expire_date]
                ])->whereYear('start_date', '<>', '9999')->where('status', '<>', 2)->first();
            }
            $data['next_package'] = $data['next_membership'] ? Package::query()->where('id', $data['next_membership']->package_id)->first() : null;
        } else {
            $data['next_package'] = null;
        }
        $data['current_package'] = $data['current_membership'] ? Package::query()->where('id', $data['current_membership']->package_id)->first() : null;
        $data['package_count'] = $nextPackageCount;  

        return $this->successResponse($data);
    }

    public function checkout($package_id)
    {
        $user = Auth::guard('vendor_api')->user();
        if(!$user)
        {
            return $this->unauthorizedResponse();
        }

        $packageCount = Membership::query()->where([
            ['vendor_id', $user->id],
            ['expire_date', '>=', Carbon::now()->toDateString()]
        ])->whereYear('start_date', '<>', '9999')->where('status', '<>', 2)->count(); 
        $hasPendingMemb = VendorPermissionHelper::hasPendingMembership($user->id);
 
        if ($hasPendingMemb) {
            return $this->errorResponse('You already have a Pending Membership Request.'); 
        }
        if ($packageCount >= 2) { 
            return $this->errorResponse('You have another package to activate after the current package expires. You cannot purchase / extend any package, until the next package is activated');
        }

        $currentLang = Language::where('is_default', 1)->first();

        $be = $currentLang->basic_extended;
        $online = OnlineGateway::query()->where('status', 1)->get();
        $offline = OfflineGateway::where('status', 1)->orderBy('serial_number', 'asc')->get();
        $data['offline'] = $offline;
        $data['payment_methods'] = $online->merge($offline);
        $data['package'] = Package::query()->findOrFail($package_id);
        $data['membership'] = Membership::query()->where([
            ['vendor_id', $user->id],
            ['expire_date', '>=', Carbon::now()->format('Y-m-d')]
        ])->where('status', '<>', 2)->whereYear('start_date', '<>', '9999')
            ->latest()
            ->first();
        $data['previousPackage'] = null;
        if (!is_null($data['membership'])) {
            $data['previousPackage'] = Package::query()
                ->where('id', $data['membership']->package_id)
                ->first();
        }
        $data['bex'] = $be;

        $stripe = OnlineGateway::where('keyword', 'stripe')->first();
        $stripe_info = json_decode($stripe->information, true);
        $data['stripe_key'] = $stripe_info['key'];
        
        return $this->successResponse($data);
    }

    public function checkoutPayment(Request $request)
    {
 
    }
}