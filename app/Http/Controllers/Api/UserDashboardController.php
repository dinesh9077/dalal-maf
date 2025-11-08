<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller; 
use App\Models\BasicSettings\Basic; 
use App\Models\Property\Property;
use App\Models\Property\PropertyContact;
use App\Models\Property\Wishlist; 
use App\Models\SupportTicket;
use App\Models\Conversation; 
use Carbon\Carbon;
use DateTime; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Validator; 
use App\Traits\ApiResponseTrait;
use App\Traits\AuthGuardTrait;
use Illuminate\Validation\Rule; 

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
        
    }
}