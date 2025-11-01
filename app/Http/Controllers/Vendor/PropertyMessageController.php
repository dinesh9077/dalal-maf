<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Property\PropertyContact;
use App\Models\Property\Wishlist;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PropertyMessageController extends Controller
{
    public function index()
    {
        $messages = PropertyContact::with('property')->where('vendor_id', Auth::guard('vendor')->user()->id)->latest()->get();
        return view('vendors.property.message', compact('messages'));
    }

    public function destroy(Request $request)
    {
        $message = PropertyContact::where('vendor_id', Auth::guard('vendor')->user()->id)->find($request->message_id);
        if ($message) {

            $message->delete();
        } else {
            Session::flash('warning', 'Something went wrong!');
            return redirect()->back();
        }
        Session::flash('success', 'Message deleted successfully');
        return redirect()->back();
    }

    public function sentInquiry()
    {
        $inquiries = PropertyContact::with('property')->where('inquiry_by_vendor', Auth::guard('vendor')->user()->id)->latest()->get();

        return view('vendors.property.inquiry', compact('inquiries'));
    }

    public function wishlist()
    {
        $vendor_id = Auth::guard('vendor')->user()->id;
        $wishlists = Wishlist::where('vendor_id', $vendor_id)->paginate(10);
        $information['wishlists'] = $wishlists;

        //Front side user edit
        // return view('frontend.user.wishlist', $information);

        //User Panel edit User
        return view('vendors.wishlist', $information);
    }
}
