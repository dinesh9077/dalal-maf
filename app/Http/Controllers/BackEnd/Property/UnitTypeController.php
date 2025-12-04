<?php

namespace App\Http\Controllers\BackEnd\Property;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\AmenityContent;
use App\Models\Language;
use App\Models\Property\Unit; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response; 
use Session;
use Validator;
use Helper;
use Auth;

class UnitTypeController extends Controller
{
    public function index(Request $request)
    {
        $information = [];
        // then, get the equipment categories of that language from db
        $information['unitContents'] = Unit::orderBy('unit_name', 'asc')->get();
         
        return view('backend.property.unit-type.index', $information);
    }

    public function store(Request $request)
    {
        $user = Auth::user(); 
        $validator = Validator::make($request->all(), [
            'unit_name' => 'required'
        ]);

        $validator->after(function ($validator) use ($request) {
            if (Unit::whereUnit_name($request->unit_name)->exists()) {
                $validator->errors()->add('unit_name', 'The unit name has already been taken');
            }
        });

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }

        $data = $request->except('_token');
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['added_id'] = $user->id;

        try {
            DB::beginTransaction();

            $object = new Unit();
            Helper::saveData($object, $data);
            $id = $object->id;
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('warning', 'Something went wrong!');
            return Response::json(['status' => 'error', 'message' => 'Something went wrong.'], 500);
        }

        Session::flash('success', 'New Unit Type added successfully!');
        return Response::json(['status' => 'success'], 200);
    }

    public function update(Request $request)
    {  
        $rules = [
            'status' => 'required|numeric',
            'unit_name' => 'required'
        ];
   
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }

        DB::beginTransaction();
        try {
            $unit = Unit::find($request->unit_id);

            if (!$unit) {
                return Response::json([
                    'status' => 'error',
                    'message' => 'Unit not found.'
                ], 404);
            }

            // update main amenity fields (types stored as JSON/array via casts)
            $unit->update([
                'status' => $request->status,
                'unit_name' => $request->unit_name
            ]); 

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback(); 
            Session::flash('warning', 'Something went wrong!');
            return Response::json([
                'status' => 'error',
                'message' => 'Something went wrong while updating the unit.'
            ], 500);
        }

        Session::flash('success', 'Unit updated successfully!');
        return Response::json(['status' => 'success'], 200);
    }

    public function destroy(Request $request)
    {
        $unit = Unit::find($request->id);
        $unit->delete();
        Session::flash('success', 'Unit Type deleted successfully!'); 
        return redirect()->back()->with('success', 'Unit Type deleted successfully!');
    }


    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;
        if(empty($ids)){
            return Response::json([
                'status' => 'error',
                'message' => 'No unit types selected for deletion.'
            ], 400);
        }
        $unit = Unit::whereIn('id', $ids)->delete();
        Session::flash('success', 'All unit types deleted successfully!');

        return Response::json(['status' => 'success'], 200);
    }
}
