<?php

namespace App\Http\Controllers\BackEnd\User;

use App\Exports\UserExport;
use App\Http\Controllers\BackEnd\Project\ProjectController;
use App\Http\Controllers\BackEnd\Property\PropertyController;
use App\Http\Controllers\Controller;
use App\Models\BasicSettings\Basic;
use App\Models\Language;
use App\Models\SupportTicket;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Session;

class UserController extends Controller
{
  public function index(Request $request)
  {
      $searchKey = null;

      if ($request->filled('info')) {
          $searchKey = $request['info'];
      }

      User::where('is_new', '0')->update(['is_new' => '1']);

      $query = User::query()->when($searchKey, function ($query, $searchKey) {
          return $query->where('username', 'like', '%' . $searchKey . '%')
              ->orWhere('email', 'like', '%' . $searchKey . '%');
      });

      // ✅ Export logic
      if ($request->has('export') && $request->export == '1') {
          $users = $query->orderByDesc('id')->get();
          return Excel::download(new UserExport($users), 'users.xlsx');
      }

      // ✅ Normal pagination for listing
      $users = $query->orderByDesc('id')->paginate(10);

      return view('backend.end-user.user.index', compact('users'));
  }

  
public function getCityDetails($cityId)
{
    // Get the city content to find the city_id
    $cityContent = \App\Models\Property\CityContent::findOrFail($cityId);
    
    // Get the city with its state and country relationships
    $city = \App\Models\Property\City::with(['state', 'country'])
        ->findOrFail($cityContent->city_id);
    
    // Get state name
    $stateName = 'N/A';
    if ($city->state) {
        $stateContent = $city->state->stateContents->first();
        $stateName = $stateContent ? $stateContent->name : $city->state->name;
    }
    
    // Get country name
    $countryName = 'N/A';
    if ($city->country) {
        $countryContent = $city->country->countryContents->first();
        $countryName = $countryContent ? $countryContent->name : $city->country->name;
    }
    
    return response()->json([
        'city_id' => $city->id,
        'city_name' => $cityContent->name,
        'state_id' => $city->state ? $city->state->id : null,
        'state_name' => $stateName,
        'country_id' => $city->country ? $city->country->id : null,
        'country_name' => $countryName
    ]);
}
  public function create()
  {
    // Load all cities from CityContent for dropdown on create form
    $cities = \App\Models\Property\CityContent::all();

    return view('backend.end-user.user.create', compact('cities'));
  }
  public function store(Request $request)
  {
    $rules = [
      'name' => 'required',
      'username' => [
        'required',
        Rule::unique('users', 'username')
      ],
      'email' => [
        'required',
        Rule::unique('users', 'email')
      ],
      'image' => 'required',
      'password' => 'required|min:6',
    ];

    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return response()->json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }
    $file = $request->file('image');
    $in = $request->all();
    if ($file) {
      $extension = $file->getClientOriginalExtension();
      $directory = public_path('assets/img/users/');
      $fileName = uniqid() . '.' . $extension;
      @mkdir($directory, 0775, true);
      $file->move($directory, $fileName);
    }
    $user = new User();
    $user->image = $fileName;
    $user->name = $request->name;
    $user->username = $request->username;
    $user->email = $request->email;
    $user->phone = $request->phone;
    $user->country = $request->country;
    $user->city = $request->city;
    $user->state = $request->state;
    $user->zip_code = $request->zip_code;
    $user->address = $request->address;
    $user->password = Hash::make($request->password);
    $user->email_verified_at = Carbon::now();
    $user->status = 1;
    $user->save();
    Session::flash('success', 'A new user has been added successfully.');
    return response()->json(['status' => 'success'], 200);
  }

  public function updateEmailStatus(Request $request, $id)
  {
    $user = User::query()->find($id);

    if ($request['email_status'] == 1) {
      $user->update([
        'email_verified_at' => date('Y-m-d H:i:s')
      ]);
    } else {
      $user->update([
        'email_verified_at' => NULL
      ]);
    }

    $request->session()->flash('success', 'Email status updated successfully!');

    return redirect()->back();
  }

  public function updateAccountStatus(Request $request, $id)
  {
    $user = User::query()->find($id);

    if ($request['account_status'] == 1) {
      $user->update([
        'status' => 1
      ]);
    } else {
      $user->update([
        'status' => 0
      ]);
    }

    $request->session()->flash('success', 'Account status updated successfully!');

    return redirect()->back();
  }

  public function edit($id)
  {
    $user = User::query()->findOrFail($id);

    // Load all cities (from contents) for dropdown
    $cities = \App\Models\Property\CityContent::all();

    return view('backend.end-user.user.edit', [
      'user' => $user,
      'cities' => $cities,
    ]);
  }

  public function update(Request $request, $id)
  {
    $rules = [
      'name' => 'required',
      'username' => [
        'required',
        Rule::unique('users', 'username')->ignore($id)
      ],
      'email' => [
        'required',
        Rule::unique('users', 'email')->ignore($id)
      ],
    ];
    if ($request->hasFile('image')) {
      $rules['image'] = 'mimes:png,jpeg,jpg,svg';
    }

    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return response()->json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }
    $file = $request->file('image');
    $user = User::where('id', $id)->firstOrFail();

    // handle image upload if new image provided
    if ($file) {
      $extension = $file->getClientOriginalExtension();
      $directory = public_path('assets/img/users/');
      $fileName = uniqid() . '.' . $extension;
      @mkdir($directory, 0775, true);
      $file->move($directory, $fileName);
      $user->image = $fileName;
    }

    // only assign real columns on users table (avoid city_id, state_id, etc.)
    $user->name     = $request->name;
    $user->username = $request->username;
    $user->email    = $request->email;
    $user->phone    = $request->phone;
    $user->zip_code = $request->zip_code;
    $user->address  = $request->address;

    // location strings come from dropdown logic
    $user->city     = $request->city;
    $user->state    = $request->state;
    $user->country  = $request->country;

    $user->save();
    Session::flash('success', 'User has been updated successfully.');
    return response()->json(['status' => 'success'], 200);
  }

  public function changePassword($id)
  {
    $userInfo = User::query()->findOrFail($id);

    return view('backend.end-user.user.change-password', compact('userInfo'));
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

    $user = User::query()->find($id);

    $user->update([
      'password' => Hash::make($request->new_password)
    ]);

    $request->session()->flash('success', 'Password updated successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function secret_login($id)
  {
    $user = User::where('id', $id)->first();
    Auth::guard('web')->login($user);
    Session::put('secret_login', 1);
    return redirect()->route('user.dashboard');
  }

  public function destroy($id)
  {

    $this->deleteUser($id);
    return redirect()->back()->with('success', 'User deleted successfully!');
  }

  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $this->deleteUser($id);
    }

    Session::flash('success', 'Users deleted successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function deleteUser($id)
  {
    $user = User::query()->findOrFail($id);

    //delete all vendor's support ticket
    $support_tickets = SupportTicket::where([['user_id', $user->id], ['user_type', 'user']])->get();
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
    // delete user image
    @unlink(public_path('assets/img/users/') . $user->image);

    $user->delete();
    return;
  }
}
