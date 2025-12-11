<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Helpers\MegaMailer;
use App\Http\Helpers\VendorPermissionHelper;
use App\Models\Admin;
use App\Models\BasicSettings\Basic;
use App\Models\Language;
use App\Models\Membership;
use App\Models\Package;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Property\Property;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorInfo;
use App\Models\VendorKYC;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class VendorManagementController extends Controller
{
    public function settings()
    {
        $setting = DB::table('basic_settings')->where('uniqid', 12345)->select('vendor_email_verification', 'vendor_admin_approval', 'admin_approval_notice')->first();
        return view('backend.end-user.vendor.settings', compact('setting'));
    }
    //update_setting
    public function update_setting(Request $request)
    {
        if ($request->vendor_email_verification) {
            $vendor_email_verification = 1;
        } else {
            $vendor_email_verification = 0;
        }
        if ($request->vendor_admin_approval) {
            $vendor_admin_approval = 1;
        } else {
            $vendor_admin_approval = 0;
        }
        // finally, store the favicon into db
        DB::table('basic_settings')->updateOrInsert(
            ['uniqid' => 12345],
            [
                'vendor_email_verification' => $vendor_email_verification,
                'vendor_admin_approval' => $vendor_admin_approval,
                'admin_approval_notice' => $request->admin_approval_notice,
            ]
        );

        Session::flash('success', 'Update Settings Successfully!');
        return back();
    }
    public function index(Request $request)
    {
        $searchKey = null;
        Vendor::where('is_new', '0')->update(['is_new' => '1']);
        if ($request->filled('info')) {
            $searchKey = $request['info'];
        }
        $packageId = $request->input('package_id');
        $expireRange = $request->input('expire_range');
        $expireFrom = null;
        $expireTo = null;

        if ($expireRange) {
            $dates = explode(' to ', $expireRange);
            $expireFrom = $dates[0] ?? null;
            $expireTo = $dates[1] ?? null;
        }

       $vendors = Vendor::query()
        ->when($searchKey, function ($query, $searchKey) {
            return $query->where(function($q) use ($searchKey) {
                $q->where('username', 'like', '%' . $searchKey . '%')
                  ->orWhere('email', 'like', '%' . $searchKey . '%');
            });
        })
        ->when($packageId || $expireFrom || $expireTo, function ($query) use ($packageId, $expireFrom, $expireTo) {
            $query->whereHas('memberships', function ($q) use ($packageId, $expireFrom, $expireTo) {
                $q->where('status', 1)
                  ->whereDate('start_date', '<=', now())
                  ->whereDate('expire_date', '>=', now());

                if ($packageId) {
                    $q->where('package_id', $packageId);
                }

                if ($expireFrom) {
                    $q->whereDate('expire_date', '>=', $expireFrom);
                }

                if ($expireTo) {
                    $q->whereDate('expire_date', '<=', $expireTo);
                }
            });
        })
        ->where('id', '!=', 0)
        ->orderBy('id', 'desc')
        ->get();

        $packages = Package::query()->where('status', '1')->get();
        return view('backend.end-user.vendor.index', compact('vendors','packages'));
    }

    //kyc vendor
    public function add(Request $request)
    {
        // first, get the language info from db
        $language = Language::query()->where('code', '=', $request->language)->first();
        $information['language'] = $language;
        $information['languages'] = Language::get();
        // load all cities for city dropdown
        $information['cities'] = \App\Models\Property\CityContent::all();

        return view('backend.end-user.vendor.create', $information);
    }

    public function vendorKyc(Request $request)
    {
        $searchKey = null;

        if ($request->filled('info')) {
            $searchKey = $request['info'];
        }

        $vendors = Vendor::query()
          ->join('vendor_kycs', 'vendors.id', '=', 'vendor_kycs.user_id') // adjust table name if needed
          ->when($searchKey, function ($query, $searchKey) {
              return $query->where(function ($q) use ($searchKey) {
                  $q->where('vendors.username', 'like', '%' . $searchKey . '%')
                    ->orWhere('vendors.email', 'like', '%' . $searchKey . '%');
              });
          })
          ->orderBy('vendor_kycs.id', 'desc')
          ->select('vendors.*') // important: avoid selecting vendorkyc fields unless needed
          ->get();

      return view('backend.end-user.vendor.kyc-list', compact('vendors'));
    }

    public function vendorKycDetails($id)
    {
        $vendor = VendorKYC::where('user_id',$id)->first();

        return view('backend.end-user.vendor.kyc-edit', compact('vendor'));
    }

    public function changeStatus(Request $request)
    {
        $statusname = $request->input('statusname');
        $code = $request->input('code');
        $note = $request->input('note');
        $id = $request->input('id');

        if($statusname == "pancard")
        {
          VendorKYC::where('id',$id)->update([
            'is_pancard' => $code,
            'admin_pancard_note' => $note,
            'updated_at' => date('Y-m-d H:i:s'),
          ]);
        }

        if($statusname == "document")
        {
          VendorKYC::where('id',$id)->update([
            'is_aadhar' => $code,
            'admin_document_note' => $note,
            'updated_at' => date('Y-m-d H:i:s'),
          ]);
        }

        if($statusname == "gst")
        {
          VendorKYC::where('id',$id)->update([
            'is_gst' => $code,
            'admin_gst_note' => $note,
            'updated_at' => date('Y-m-d H:i:s'),
          ]);
        }

        $get_kyc_approve = VendorKYC::where('id',$id)->first();

          if($get_kyc_approve->is_pancard == '1' && $get_kyc_approve->is_aadhar == '1' && (empty($get_kyc_approve->gst_number) || $get_kyc_approve->is_gst == '1'))
          {
              Vendor::where('id',$get_kyc_approve->user_id)->update([
                  'is_kyc_approved' => '1',
              ]);
          }

        return back();
    }

    public function create(Request $request)
    {
        $admin = Admin::select('username')->first();
        $admin_username = $admin->username;
        $rules = [
            'username' => "required|unique:vendors|not_in:$admin_username",
            'email' => 'required|email|unique:vendors',
            'phone' => [
                'required',
                function ($attribute, $value, $fail) {
                    $existsInUsers = DB::table('users')->where('phone', $value)->exists();
                    $existsInVendors = DB::table('vendors')->where('phone', $value)->exists();
                    $existsInAgents = DB::table('agents')->where('phone', $value)->exists();

                    if ($existsInUsers || $existsInVendors || $existsInAgents) {
                        $fail('The phone number is already registered.');
                    }
                },
            ],
            //'password' => 'required|min:6',
        ]; 

        $languages = Language::get();
        foreach ($languages as $language) {
            $rules[$language->code . '_name'] = 'required';
        }
        $messages = [];
        foreach ($languages as $language) {
            $messages[$language->code . '_name.required'] = 'The name feild is required';
        }
 
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }

        $in = $request->all();
        $in['password'] = Hash::make($request->password);
        $in['status'] = 1;

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

        $file = $request->file('photo');
        if ($file) {
            $extension = $file->getClientOriginalExtension();
            $directory = public_path('assets/admin/img/vendor-photo/');
            $fileName = uniqid() . '.' . $extension;
            @mkdir($directory, 0775, true);
            $file->move($directory, $fileName);
            $in['photo'] = $fileName;
        }
        $in['email_verified_at'] = Carbon::now();
        $in['status'] = 1;
        $in['type'] = $request->type;
        $vendor = Vendor::create($in);

        $vendor_id = $vendor->id;
        foreach ($languages as $language) {
            $vendorInfo = new VendorInfo();
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


        Session::flash('success', 'Add Partner Successfully!');
        return Response::json(['status' => 'success'], 200);
    }

    public function show($id)
    {

        $information['langs'] = Language::all();

        $currency_info = $this->getCurrencyInfo();
        $information['currency_info'] = $currency_info;

        $language = Language::where('code', request()->input('language'))->firstOrFail();
        $information['language'] = $language;
        $vendor = Vendor::with([
            'vendor_info' => function ($query) use ($language) {
                return $query->where('language_id', $language->id);
            }
        ])->where('id', $id)->firstOrFail();
        $information['vendor'] = $vendor;
        $information['properties'] = Property::where('vendor_id', $vendor->id)->with(['propertyContent' => function ($q) use ($language) {
            $q->where('language_id', $language->id);
        }])->get();
        $information['langs'] = Language::all();
        $information['packages'] = Package::query()->where('status', '1')->get();
        $online = OnlineGateway::query()->where('status', 1)->get();
        $offline = OfflineGateway::where('status', 1)->get();
        $information['gateways'] = $online->merge($offline);


        return view('backend.end-user.vendor.details', $information);
    }
    public function updateAccountStatus(Request $request, $id)
    {
        $user = Vendor::find($id);
        if ($request->account_status == 1) {
            $user->update(['status' => 1]);
        } else {
            $user->update(['status' => 0]);
        }
        Session::flash('success', 'Account status updated successfully!');

        return redirect()->back();
    }

    public function updateEmailStatus(Request $request, $id)
    {
        $vendor = Vendor::find($id);
        if ($request->email_status == 1) {
            $vendor->update(['email_verified_at' => now()]);
        } else {
            $vendor->update(['email_verified_at' => NULL]);
        }
        Session::flash('success', 'Email status updated successfully!');

        return redirect()->back();
    }
    public function changePassword($id)
    {
        $userInfo = Vendor::findOrFail($id);

        return view('backend.end-user.vendor.change-password', compact('userInfo'));
    }
    public function updatePassword(Request $request, $id)
    {
        $rules = [
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

        $user = Vendor::find($id);

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        Session::flash('success', 'Password updated successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function edit($id)
    {
        $information['languages'] = Language::get();
        $vendor = Vendor::where('id', $id)->firstOrFail();
        $information['vendor'] = $vendor;
        $information['currencyInfo'] = $this->getCurrencyInfo();
        // load all cities for city dropdown
        $information['cities'] = \App\Models\Property\CityContent::all();
        return view('backend.end-user.vendor.edit', $information);
    }

    //update
    public function update(Request $request, $id, Vendor $vendor)
    {
        $rules = [ 
            'username' => [
                'required',
                'not_in:admin',
                Rule::unique('vendors', 'username')->ignore($id),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('vendors', 'email')->ignore($id)
            ],
            'phone' => [
                'required',
                Rule::unique('users', 'phone')->ignore($id),  // allow same user phone

                function ($attribute, $value, $fail) use ($id) {

                    // Check in VENDORS table
                    $existsInVendors = DB::table('vendors')
                        ->where('phone', $value)
                        ->where('id', '!=', $id)
                        ->exists();

                    // Check in AGENTS table
                    $existsInAgents = DB::table('agents')
                        ->where('phone', $value)
                        ->where('id', '!=', $id)
                        ->exists();

                    // If duplicate found in any other table → fail
                    if ($existsInVendors || $existsInAgents) {
                        $fail('The phone number is already registered.');
                    }
                }
            ],
        ];

        if ($request->hasFile('photo')) {
            $rules['photo'] = 'mimes:png,jpeg,jpg';
        }

        $languages = Language::get();
        foreach ($languages as $language) {
            $rules[$language->code . '_name'] = 'required';
        }

        $messages = [];

        foreach ($languages as $language) {
            $messages[$language->code . '_name.required'] = 'The name field is required.';
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }


        $in = $request->all();
        $vendor  = Vendor::where('id', $id)->first();
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


    public function sendMail($memb, $package, $paymentMethod, $vendor, $bs, $mailType, $replacedPackage = NULL, $removedPackage = NULL)
    {

        if ($mailType != 'admin_removed_current_package' && $mailType != 'admin_removed_next_package') {
            $transaction_id = VendorPermissionHelper::uniqidReal(8);
            $activation = $memb->start_date;
            $expire = $memb->expire_date;
            $info['start_date'] = $activation->toFormattedDateString();
            $info['expire_date'] = $expire->toFormattedDateString();
            $info['payment_method'] = $paymentMethod;
            $lastMemb = $vendor->memberships()->orderBy('id', 'DESC')->first();

            $file_name = $this->makeInvoice($info, "membership", $vendor, NULL, $package->price, "Stripe", $vendor->phone, $bs->base_currency_symbol_position, $bs->base_currency_symbol, $bs->base_currency_text, $transaction_id, $package->title, $lastMemb);
        }

        $mailer = new MegaMailer();
        $data = [
            'toMail' => $vendor->email,
            'toName' => $vendor->username,
            'username' => $vendor->username,
            'website_title' => $bs->website_title,
            'templateType' => $mailType
        ];

        if ($mailType != 'admin_removed_current_package' && $mailType != 'admin_removed_next_package') {
            $data['package_title'] = $package->title;
            $data['package_price'] = ($bs->base_currency_text_position == 'left' ? $bs->base_currency_text . ' ' : '') . $package->price . ($bs->base_currency_text_position == 'right' ? ' ' . $bs->base_currency_text : '');
            $data['activation_date'] = $activation->toFormattedDateString();
            $data['expire_date'] = Carbon::parse($expire->toFormattedDateString())->format('Y') == '9999' ? 'Lifetime' : $expire->toFormattedDateString();
            $data['membership_invoice'] = $file_name;
        }
        if ($mailType != 'admin_removed_current_package' || $mailType != 'admin_removed_next_package') {
            $data['removed_package_title'] = $removedPackage;
        }

        if (!empty($replacedPackage)) {
            $data['replaced_package'] = $replacedPackage;
        }

        $mailer->mailFromAdmin($data);
        @unlink(public_path('assets/front/invoices/' . $file_name));
    }

    public function addCurrPackage(Request $request)
    {
        $vendorId = (int) $request->vendor_id;

        // Fail fast if vendor or package not found
        $vendor = Vendor::findOrFail($vendorId);
        $selectedPackage = Package::findOrFail($request->package_id);

        // You can also cache this if Basic::first() is used a lot
        $bs = Basic::first();

        // Base date (start of current day)
        $today = Carbon::today(); // 00:00:00 of today

        // Calculate expire date for selected package
        $expireDate = null;

        switch ($selectedPackage->term) {
            case 'monthly':
                $expireDate = $today->copy()->addMonthNoOverflow();
                break;

            case 'yearly':
                $expireDate = $today->copy()->addYearNoOverflow();
                break;

            case 'lifetime':
                // Same as your previous maxValue(), but more practical (adjust if needed)
                $expireDate = Carbon::create(2099, 12, 31)->endOfDay();
                break;

            default:
                // If term is unknown, log it and bail out
                Log::warning('Unknown package term when adding current package', [
                    'package_id' => $selectedPackage->id,
                    'term' => $selectedPackage->term,
                ]);

                Session::flash('error', 'Invalid package term. Unable to add package.');
                return back();
        }

        // Use a transaction to be safe if you extend logic later
        DB::beginTransaction();

        try {
            $selectedMemb = Membership::create([
                'price' => $selectedPackage->price,
                'currency' => $bs->base_currency_text,
                'currency_symbol' => $bs->base_currency_symbol,
                'payment_method' => $request->payment_method,
                'transaction_id' => uniqid(), // if you need a real txn ID, replace later
                'status' => 1,
                'receipt' => null,
                'transaction_details' => null,
                'settings' => null,
                'package_id' => $selectedPackage->id,
                'vendor_id' => $vendorId,
                'start_date' => $today,       // same effect as your parse(format)
                'expire_date' => $expireDate,  // Carbon instance directly
                'is_trial' => 0,
                'trial_days' => 0,
            ]);

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Failed to add current package', [
                'vendor_id' => $vendorId,
                'package_id' => $selectedPackage->id,
                'message' => $e->getMessage(),
            ]);

            Session::flash('error', 'Something went wrong while adding the package. Please try again.');
            return back();
        }

        // ⚠️ Email is usually the slow part — consider queueing this
        try {
            $this->sendMail(
                $selectedMemb,
                $selectedPackage,
                $request->payment_method,
                $vendor,
                $bs,
                'admin_added_current_package'
            );
        } catch (\Throwable $e) {
            Log::warning('Package added but email failed', [
                'vendor_id' => $vendorId,
                'package_id' => $selectedPackage->id,
                'message' => $e->getMessage(),
            ]);
            // Don’t block success for mail failure
        }

        Session::flash('success', 'Current Package has been added successfully!');
        return back();
    }


    public function changeCurrPackage(Request $request)
    {
        $vendor_id = $request->vendor_id;
        $vendor = Vendor::findOrFail($vendor_id);
        $currMembership = VendorPermissionHelper::currMembOrPending($vendor_id);
        $nextMembership = VendorPermissionHelper::nextMembership($vendor_id);

        $bs = Basic::first();

        $selectedPackage = Package::find($request->package_id);

        // if the vendor has a next package to activate & selected package is 'lifetime' package
        if (!empty($nextMembership) && $selectedPackage->term == 'lifetime') {
            Session::flash('warning', 'To add a Lifetime package as Current Package, You have to remove the next package');
            return back();
        }

        // expire the current package
        $currMembership->expire_date = Carbon::parse(Carbon::now()->subDay()->format('d-m-Y'));
        $currMembership->modified = 1;
        if ($currMembership->status == 0) {
            $currMembership->status = 2;
        }
        $currMembership->save();

        // calculate expire date for selected package
        if ($selectedPackage->term == 'monthly') {
            $exDate = Carbon::now()->addMonth()->format('d-m-Y');
        } elseif ($selectedPackage->term == 'yearly') {
            $exDate = Carbon::now()->addYear()->format('d-m-Y');
        } elseif ($selectedPackage->term == 'lifetime') {
            $exDate = Carbon::maxValue()->format('d-m-Y');
        }
        // store a new membership for selected package
        $selectedMemb = Membership::create([
            'price' => $selectedPackage->price,
            'currency' => $bs->base_currency_text,
            'currency_symbol' => $bs->base_currency_symbol,
            'payment_method' => $request->payment_method,
            'transaction_id' => uniqid(),
            'status' => 1,
            'receipt' => NULL,
            'transaction_details' => NULL,
            'settings' => null,
            'package_id' => $selectedPackage->id,
            'vendor_id' => $vendor_id,
            'start_date' => Carbon::parse(Carbon::now()->format('d-m-Y')),
            'expire_date' => Carbon::parse($exDate),
            'is_trial' => 0,
            'trial_days' => 0,
        ]);

        // if the user has a next package to activate & selected package is not 'lifetime' package
        if (!empty($nextMembership) && $selectedPackage->term != 'lifetime') {
            $nextPackage = Package::find($nextMembership->package_id);

            // calculate & store next membership's start_date
            $nextMembership->start_date = Carbon::parse(Carbon::parse($exDate)->addDay()->format('d-m-Y'));

            // calculate & store expire date for next membership
            if ($nextPackage->term == 'monthly') {
                $exDate = Carbon::parse(Carbon::parse(Carbon::parse($exDate)->addDay()->format('d-m-Y'))->addMonth()->format('d-m-Y'));
            } elseif ($nextPackage->term == 'yearly') {
                $exDate = Carbon::parse(Carbon::parse(Carbon::parse($exDate)->addDay()->format('d-m-Y'))->addYear()->format('d-m-Y'));
            } else {
                $exDate = Carbon::parse(Carbon::maxValue()->format('d-m-Y'));
            }
            $nextMembership->expire_date = $exDate;
            $nextMembership->save();
        }

        $currentPackage = Package::select('title')->findOrFail($currMembership->package_id);
        $this->sendMail($selectedMemb, $selectedPackage, $request->payment_method, $vendor, $bs, 'admin_changed_current_package', $currentPackage->title);


        Session::flash('success', 'Current Package changed successfully!');
        return back();
    }

    public function removeCurrPackage(Request $request)
    {
        $vendorId = (int) $request->vendor_id;

        // fail fast if vendor not found
        $vendor = Vendor::findOrFail($vendorId);

        // wrap all membership changes in a transaction
        DB::beginTransaction();

        try {
            $currMembership = VendorPermissionHelper::currMembOrPending($vendorId);

            if (!$currMembership) {
                DB::rollBack();
                Session::flash('error', 'No current membership found for this vendor.');
                return back();
            }

            // Only load what you need from package
            $currPackage = Package::select('id', 'title')->findOrFail($currMembership->package_id);
            $currPackageTitle = $currPackage->title;

            $nextMembership = VendorPermissionHelper::nextMembership($vendorId);

            // Dates
            $now = Carbon::now();
            $yesterday = $now->copy()->subDay()->startOfDay();
            $today = $now->copy()->startOfDay();

            // Expire current package
            $currMembership->expire_date = $yesterday;
            $currMembership->modified = 1;

            // If pending (0), mark as "expired/removed" (2)
            if ((int) $currMembership->status === 0) {
                $currMembership->status = 2;
            }
            $currMembership->save();

            // If next package exists, activate it immediately
            if ($nextMembership) {
                $nextPackage = Package::select('id', 'term')->find($nextMembership->package_id);

                if ($nextPackage) {
                    $nextMembership->start_date = $today;

                    // Use simple date ops, no extra parse/format
                    if ($nextPackage->term === 'monthly') {
                        $nextMembership->expire_date = $today->copy()->addMonthNoOverflow();
                    } elseif ($nextPackage->term === 'yearly') {
                        $nextMembership->expire_date = $today->copy()->addYearNoOverflow();
                    } elseif ($nextPackage->term === 'lifetime') {
                        // You can also use NULL to mean "no expiry" if your schema supports it
                        $nextMembership->expire_date = Carbon::create(2099, 12, 31)->endOfDay();
                    }

                    $nextMembership->save();
                }
            }

            // Basic settings: if this is expensive, consider caching
            $bs = Basic::first();

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();

            // Log error for debugging
            \Log::error('Error removing current package', [
                'vendor_id' => $vendorId,
                'message' => $e->getMessage(),
            ]);

            Session::flash('error', 'Something went wrong while removing the package. Please try again.');
            return back();
        }

        // ✅ Send email AFTER transaction (and ideally as a queued job)
        // If sendMail() is heavy, convert it to a queued job for big performance gain.
        try {
            $this->sendMail(
                null,
                null,
                $request->payment_method,
                $vendor,
                $bs,
                'admin_removed_current_package',
                null,
                $currPackageTitle
            );
        } catch (\Throwable $e) {
            \Log::warning('Package removed but email failed', [
                'vendor_id' => $vendorId,
                'message' => $e->getMessage(),
            ]);
            // don’t block user for mail failure
        }

        Session::flash('success', 'Current package removed successfully!');
        return back();
    }

    public function addNextPackage(Request $request)
    {
        $vendor_id = $request->vendor_id;

        $hasPendingMemb = VendorPermissionHelper::hasPendingMembership($vendor_id);
        if ($hasPendingMemb) {
            Session::flash('warning', 'This user already has a Pending Package. Please take an action (change / remove / approve / reject) for that package first.');
            return back();
        }

        $currMembership = VendorPermissionHelper::userPackage($vendor_id);
        $currPackage = Package::find($currMembership->package_id);
        $vendor = Vendor::where('id', $vendor_id)->first();
        $bs = Basic::first();

        $selectedPackage = Package::find($request->package_id);

        if ($currMembership->is_trial == 1) {
            Session::flash('warning', 'If your current package is trial package, then you have to change / remove the current package first.');
            return back();
        }


        // if current package is not lifetime package
        if ($currPackage->term != 'lifetime') {
            // calculate expire date for selected package
            if ($selectedPackage->term == 'monthly') {
                $exDate = Carbon::parse($currMembership->expire_date)->addDay()->addMonth()->format('d-m-Y');
            } elseif ($selectedPackage->term == 'yearly') {
                $exDate = Carbon::parse($currMembership->expire_date)->addDay()->addYear()->format('d-m-Y');
            } elseif ($selectedPackage->term == 'lifetime') {
                $exDate = Carbon::parse(Carbon::maxValue()->format('d-m-Y'));
            }
            // store a new membership for selected package
            $selectedMemb = Membership::create([
                'price' => $selectedPackage->price,
                'currency' => $bs->base_currency_text,
                'currency_symbol' => $bs->base_currency_symbol,
                'payment_method' => $request->payment_method,
                'transaction_id' => uniqid(),
                'status' => 1,
                'receipt' => NULL,
                'transaction_details' => NULL,
                'settings' => null,
                'package_id' => $selectedPackage->id,
                'vendor_id' => $vendor_id,
                'start_date' => Carbon::parse(Carbon::parse($currMembership->expire_date)->addDay()->format('d-m-Y')),
                'expire_date' => Carbon::parse($exDate),
                'is_trial' => 0,
                'trial_days' => 0,
            ]);

            // $this->sendMail($selectedMemb, $selectedPackage, $request->payment_method, $vendor, $bs, 'admin_added_next_package');
        } else {
            Session::flash('warning', 'If your current package is lifetime package, then you have to change / remove the current package first.');
            return back();
        }


        Session::flash('success', 'Next Package has been added successfully!');
        return back();
    }

    public function changeNextPackage(Request $request)
    {
        $vendor_id = $request->vendor_id;
        $vendor = Vendor::where('id', $vendor_id)->first();
        $bs = Basic::first();
        $nextMembership = VendorPermissionHelper::nextMembership($vendor_id);
        $nextPackage = Package::find($nextMembership->package_id);
        $selectedPackage = Package::find($request->package_id);

        $prevStartDate = $nextMembership->start_date;
        // set the start_date to unlimited
        $nextMembership->start_date = Carbon::parse(Carbon::maxValue()->format('d-m-Y'));
        $nextMembership->modified = 1;
        $nextMembership->save();

        // calculate expire date for selected package
        if ($selectedPackage->term == 'monthly') {
            $exDate = Carbon::parse($prevStartDate)->addMonth()->format('d-m-Y');
        } elseif ($selectedPackage->term == 'yearly') {
            $exDate = Carbon::parse($prevStartDate)->addYear()->format('d-m-Y');
        } elseif ($selectedPackage->term == 'lifetime') {
            $exDate = Carbon::parse(Carbon::maxValue()->format('d-m-Y'));
        }

        // store a new membership for selected package
        $selectedMemb = Membership::create([
            'price' => $selectedPackage->price,
            'currency' => $bs->base_currency_text,
            'currency_symbol' => $bs->base_currency_symbol,
            'payment_method' => $request->payment_method,
            'transaction_id' => uniqid(),
            'status' => 1,
            'receipt' => NULL,
            'transaction_details' => NULL,
            'settings' => json_encode($bs),
            'package_id' => $selectedPackage->id,
            'vendor_id' => $vendor_id,
            'start_date' => Carbon::parse($prevStartDate),
            'expire_date' => Carbon::parse($exDate),
            'is_trial' => 0,
            'trial_days' => 0,
        ]);

        $this->sendMail($selectedMemb, $selectedPackage, $request->payment_method, $vendor, $bs, 'admin_changed_next_package', $nextPackage->title);

        Session::flash('success', 'Next Package changed successfully!');
        return back();
    }

    public function removeNextPackage(Request $request)
    {
        $vendor_id = $request->vendor_id;
        $vendor = Vendor::where('id', $vendor_id)->first();
        $bs = Basic::first();
        $nextMembership = VendorPermissionHelper::nextMembership($vendor_id);
        // set the start_date to unlimited
        $nextMembership->start_date = Carbon::parse(Carbon::maxValue()->format('d-m-Y'));
        $nextMembership->modified = 1;
        $nextMembership->save();

        $nextPackage = Package::select('title')->findOrFail($nextMembership->package_id);


        $this->sendMail(NULL, NULL, $request->payment_method, $vendor, $bs, 'admin_removed_next_package', NULL, $nextPackage->title);

        Session::flash('success', 'Next Package removed successfully!');
        return back();
    }

    //secrtet login
    public function secret_login($id)
    {
        Session::put('secret_login', 1);
        $vendor = Vendor::where('id', $id)->first();
        Auth::guard('vendor')->login($vendor);
        return redirect()->route('vendor.dashboard');
    }

    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);
        // vendor memeberships
        $memberships = $vendor->memberships()->get();
        foreach ($memberships as $membership) {
            @unlink(public_path('assets/front/img/membership/receipt/') . $membership->receipt);
            $membership->delete();
        }
        //vendor infos
        $vendor_infos = $vendor->vendor_infos()->get();
        foreach ($vendor_infos as $info) {
            $info->delete();
        }
        // all properties delete
        $properties = $vendor->properties()->get();
        foreach ($properties as $property) {

            if (!is_null($property->featured_image)) {
                @unlink(public_path('assets/img/property/featureds/' . $property->featured_image));
            }

            if (!is_null($property->floor_planning_image)) {
                @unlink(public_path('assets/img/property/plannings/' . $property->floor_planning_image));
            }
            if (!is_null($property->video_image)) {
                @unlink(public_path('assets/img/property/video/' . $property->video_image));
            }
            $property->propertyContents()->delete();

            $galleryImages = $property->galleryImages()->get();
            foreach ($galleryImages as $image) {
                @unlink(public_path('assets/img/property/slider-images/' . $image->image));
                $image->delete();
            }

            $property->proertyAmenities()->delete();

            $specifications = $property->specifications()->get();
            foreach ($specifications as  $specification) {
                $specification->specificationContents()->delete();
            }

            $featuredProperties = $property->featuredProperties()->get();

            foreach ($featuredProperties as $featured) {
                if ($featured->attachment != null) {
                    @unlink(public_path('assets/front/img/feature/attachment/' . $featured->attachment));
                }
                $featured->delete();
            }
            // delete wishlists
            $property->wishlists()->delete();

            $property->delete();
        }
        // all property message delete
        $vendor->propertyMessages()->delete();

        // all project delete
        $projects = $vendor->projects()->get();
        foreach ($projects as $project) {
            @unlink(public_path('assets/img/project/featured/' . $project->featured_image));
            $project->proejctContents()->delete();

            $projectGalleryImages = $project->galleryImages()->get();
            foreach ($projectGalleryImages as $image) {
                @unlink(public_path('assets/img/project/gallery-images/' . $image->image));
                $image->delete();
            }

            $projectFloorplanImages = $project->floorplanImages()->get();
            foreach ($projectFloorplanImages as $image) {
                @unlink(public_path('assets/img/project/floor-paln-images/' . $image->image));
                $image->delete();
            }

            $specifications = $project->specifications()->get();
            foreach ($specifications as  $specification) {
                $specification->specificationContents()->delete();
            }

            $projectTypes = $project->projectTypes()->get();
            foreach ($projectTypes as $type) {
                $type->projectTypeContnents()->delete();
                $type->delete();
            }

            $project->delete();
        }
        //  all agents delete
        $agents = $vendor->agents()->get();
        foreach ($agents as $agent) {
            $agent->agent_infos()->delete();
            @unlink(public_path('assets/img/agents/' . $agent->image));
            $agent->delete();
        }

        //delete all vendor's support ticket
        $support_tickets = SupportTicket::where([['user_id', $vendor->id], ['user_type', 'vendor']])->get();
        foreach ($support_tickets as $support_ticket) {
            //delete conversation
            $messages = $support_ticket->messages()->get();
            foreach ($messages as $message) {
                @unlink(public_path('assets/admin/img/support-ticket/' . $message->file));
                $message->delete();
            }
            @unlink(public_path('assets/admin/img/support-ticket/attachment/') . $support_ticket->attachment);
            $support_ticket->delete();
        }

        //finally delete the vendor
        @unlink(public_path('assets/admin/img/vendor-photo/') . $vendor->photo);
        $vendor->delete();

        return redirect()->back()->with('success', 'Vendor info deleted successfully!');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $vendor = Vendor::findOrFail($id);
            // vendor memeberships
            $memberships = $vendor->memberships()->get();
            foreach ($memberships as $membership) {
                @unlink(public_path('assets/front/img/membership/receipt/') . $membership->receipt);
                $membership->delete();
            }
            //vendor infos
            $vendor_infos = $vendor->vendor_infos()->get();
            foreach ($vendor_infos as $info) {
                $info->delete();
            }

            // all properties delete
            $properties = $vendor->properties()->get();
            foreach ($properties as $property) {
                @unlink(public_path('assets/img/property/featureds/' . $property->featured_image));
                @unlink(public_path('assets/img/property/plannings/' . $property->floor_planning_image));
                $property->propertyContents()->delete();

                $galleryImages = $property->galleryImages()->get();
                foreach ($galleryImages as $image) {
                    @unlink(public_path('assets/img/property/slider-images/' . $image->image));
                    $image->delete();
                }

                $property->proertyAmenities()->delete();

                $specifications = $property->specifications()->get();
                foreach ($specifications as  $specification) {
                    $specification->specificationContents()->delete();
                }

                $featuredProperties = $property->featuredProperties()->get();

                foreach ($featuredProperties as $featured) {
                    if ($featured->attachment != null) {
                        @unlink(public_path('assets/front/img/feature/attachment/' . $featured->attachment));
                    }
                    $featured->delete();
                }

                $property->delete();
            }
            // all property message delete
            $vendor->propertyMessages()->delete();

            // all project delete
            $projects = $vendor->projects()->get();
            foreach ($projects as $project) {
                @unlink(public_path('assets/img/project/featured/' . $project->featured_image));
                $project->proejctContents()->delete();

                $projectGalleryImages = $project->galleryImages()->get();
                foreach ($projectGalleryImages as $image) {
                    @unlink(public_path('assets/img/project/gallery-images/' . $image->image));
                    $image->delete();
                }

                $projectFloorplanImages = $project->floorplanImages()->get();
                foreach ($projectFloorplanImages as $image) {
                    @unlink(public_path('assets/img/project/floor-paln-images/' . $image->image));
                    $image->delete();
                }

                $specifications = $project->specifications()->get();
                foreach ($specifications as  $specification) {
                    $specification->specificationContents()->delete();
                }

                $projectTypes = $project->projectTypes()->get();
                foreach ($projectTypes as $type) {
                    $type->projectTypeContnents()->delete();
                    $type->delete();
                }

                $project->delete();
            }
            //  all agents delete
            $agents = $vendor->agents()->get();
            foreach ($agents as $agent) {
                $agent->agent_infos()->delete();
                @unlink(public_path('assets/img/agents/' . $agent->image));
                $agent->delete();
            }

            //delete all vendor's support ticket
            $support_tickets = SupportTicket::where([['user_id', $vendor->id], ['user_type', 'vendor']])->get();
            foreach ($support_tickets as $support_ticket) {
                //delete conversation
                $messages = $support_ticket->messages()->get();
                foreach ($messages as $message) {
                    @unlink(public_path('assets/admin/img/support-ticket/' . $message->file));
                    $message->delete();
                }
                @unlink(public_path('assets/admin/img/support-ticket/attachment/') . $support_ticket->attachment);
                $support_ticket->delete();
            }

            //finally delete the vendor
            @unlink(public_path('assets/admin/img/vendor-photo/') . $vendor->photo);
            $vendor->delete();
        }
        Session::flash('success', 'Partners info deleted successfully!');

        return Response::json(['status' => 'success'], 200);
    }
}
