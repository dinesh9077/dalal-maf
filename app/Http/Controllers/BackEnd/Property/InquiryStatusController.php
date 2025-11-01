<?php

namespace App\Http\Controllers\BackEnd\Property;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Property\InquiryStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class InquiryStatusController extends Controller
{
     public function index(Request $request)
    {
        // first, get the language info from db
        if ($request->has('language')) {
            $language = Language::where('code', $request->language)->firstOrFail();
        } else {

            $language = Language::where('is_default', 1)->first();
        }
        $information['language'] = $language;

        // then, get the equipment categories of that language from db
        $information['status'] = InquiryStatus::query()->get();
        $information['langs'] = Language::all();
        return view('backend.property.status.index', $information);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => "required",
            'status' => 'required'
        ];

        $message = [
            'name.required' => 'The name field is required.',
            'status.required' => 'The status field is required.'
        ];

        $validator = Validator::make($request->all(), $rules, $message);


        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }
        DB::beginTransaction();

        try {
            InquiryStatus::create([
                'name' => $request->name,
                'status' => $request->status,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('warning', 'Something went wrong!');

            return Response::json(['status' => 'success'], 200);
        }
        Session::flash('success', 'New Property category added successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function update(Request $request)
    {

       $rules = [
            'name' => "required",
            'status' => 'required'
        ];

        $message = [
            'name.required' => 'The name field is required.',
            'status.required' => 'The status field is required.'
        ];

        $validator = Validator::make($request->all(), $rules, $message);


        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }

        $category = InquiryStatus::find($request->id);


        $category->update([
            'name' => $request->name,
            'status' => $request->status
        ]);

        Session::flash('success', 'Status updated successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function delete(Request $request)
    {
        $status = InquiryStatus::find($request->id);
        $status->delete();

        return redirect()->back()->with('success', 'Status deleted successfully!');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        DB::beginTransaction();

        try {
            foreach ($ids as $id) {
                $category = InquiryStatus::find($id);
                $category->delete();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('warning', 'Something went wrong!.');
            return Response::json(['status' => 'error'], 400);
        }

        Session::flash('success', 'Status deleted successfully!');

        return Response::json(['status' => 'success'], 200);
    }
}
