<?php

namespace App\Http\Controllers\BackEnd\Property;

use App\Exports\InquiryExport;
use App\Http\Controllers\Controller;
use App\Models\Property\InquiryStatus;
use App\Models\Property\PropertyContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Session;

class PropertyMessageController extends Controller
{
    public function index(Request $request)
    {
        $inquiryRange = $request->input('inquiry_date');
        $inquiryFrom = null;
        $inquiryTo = null;

        if ($inquiryRange) {
            $dates = explode(' to ', $inquiryRange);
            $inquiryFrom = $dates[0] ?? null;
            $inquiryTo = $dates[1] ?? null;
        }
        $purpose = $request->input('purpose');
        PropertyContact::where('is_new', '0')->update(['is_new' => '1']);
        $messages = PropertyContact::with('property')
          ->when($purpose, function ($query) use ($purpose) {
              return $query->whereHas('property', function ($q) use ($purpose) {
                  $q->where('purpose', $purpose);
              });
          })
          ->when($inquiryFrom || $inquiryTo, function ($query) use ($inquiryFrom, $inquiryTo) {
              if ($inquiryFrom) {
                  $query->whereDate('created_at', '>=', $inquiryFrom);
              }

              if ($inquiryTo) {
                  $query->whereDate('created_at', '<=', $inquiryTo);
              }
          })

          ->latest()
          ->get();
          if ($request->has('export') && $request->export == '1') {
              return Excel::download(new InquiryExport($messages), 'inquiry.xlsx');
          }
        $statuses = InquiryStatus::where('status','active')->latest()->get();
        return view('backend.property.message', compact('messages','statuses'));
    }
    public function destroy(Request $request)
    {
        $message = PropertyContact::where('vendor_id', 0)->find($request->message_id);
        if ($message) {

            $message->delete();
        } else {
            Session::flash('warning', 'Something went wrong!');
            return redirect()->back();
        }
        Session::flash('success', 'Message deleted successfully');
        return redirect()->back();
    }
    public function changeStatus(Request $request)
    {
        $property = PropertyContact::findOrFail($request->inquiry_id);
        $property->update(['status' => $request->approve_status, 'comment' => $request->comment]);
        Session::flash('success', 'Inquiry status change Successfully!');

        return redirect()->back();
    }
}
