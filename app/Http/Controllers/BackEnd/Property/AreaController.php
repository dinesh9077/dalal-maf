<?php

namespace App\Http\Controllers\BackEnd\Property;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\Property\Areacreate;
use App\Http\Requests\Property\Citycreate;
use App\Models\Language;
use App\Models\Property\Area;
use App\Models\Property\City;
use App\Models\Property\CityContent;
use App\Models\Property\Country;
use App\Models\Property\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AreaController extends Controller
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
        $information['countries'] = Country::with(['countryContent' => function ($q) use ($language) {
            $q->where('language_id', $language->id);
        }])->orderByDesc('id')->get();

        $information['states'] = State::with(['stateContent' => function ($q) use ($language) {
            $q->where('language_id', $language->id);
        }])->orderByDesc('id')->get();


        $information['cities'] = City::with(['cityContent' => function ($q) use ($language) {
            $q->where('language_id', $language->id);
        }])->orderByDesc('id')->get();

        $information['areas'] = Area::get();

        // also, get all the languages from db
        $information['langs'] = Language::all();

        return view('backend.property.area.index', $information);
    }

    public function getAreas(Request $request)
    {
        $areas = Area::where('city_id',$request->city_id)->get();

        return Response::json(['areas' => $areas], 200);
    }

    public function store(Areacreate $request)
    {

        try {
            $city = City::where('id',$request->city_id)->first();
            if ($city) {
               $city =  Area::create([
                    'country_id' => $city->country_id,
                    'state_id' => $city->state_id,
                    'city_id' => $request->city_id,
                    'name' => $request->name,
                    'status' => $request->status,
                ]);
            }


        } catch (\Exception $e) {
            Session::flash('warning', 'Something went wrong!');
            return Response::json(['status' => 'success'], 200);
        }
        Session::flash('success', 'New Area added successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function update(Request $request)
    {
        $rules = [
          'en_name' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }

        try {
            $area = Area::find($request->id);
            $area->update([
              'name' => $request->en_name,
              'status' => $request->status,
            ]);

        } catch (\Exception $e) {
            Session::flash('warning', 'Something went wrong!');
            return Response::json(['status' => 'success'], 200);
        }

        Session::flash('success', 'Area updated successfully!');

        return Response::json(['status' => 'success'], 200);
    }


    public function destroy(Request $request)
    {
        $area = Area::find($request->id);
        $area->delete();
        return redirect()->back()->with('success', 'Area deleted successfully!');
    }


    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;
        DB::beginTransaction();

        try {
            foreach ($ids as $id) {
                $city = Area::find($id);
                $city->delete();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('warning', 'First, please delete properties under this city.');
            return Response::json(['status' => 'success'], 200);
        }

        Session::flash('success', 'Areas deleted successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function getLocationByArea(Request $request)
    {
        $language = \App\Models\Language::where('is_default', 1)->first();

        $area = Area::with(['city.cityContent' => function ($q) use ($language) {
            $q->where('language_id', $language->id);
        }, 'city.state.stateContent' => function ($q) use ($language) {
            $q->where('language_id', $language->id);
        }, 'city.state.country.countryContent' => function ($q) use ($language) {
            $q->where('language_id', $language->id);
        }])->find($request->area_id);

        if (!$area) {
            return response()->json(['error' => 'Area not found'], 404);
        }

        return response()->json([
            'area' => [
                'id' => $area->id,
                'name' => $area->name,
            ],
            'city' => [
                'id' => $area->city->id ?? null,
                'name' => $area->city->cityContent->name ?? null,
            ],
            'state' => [
                'id' => $area->city->state->id ?? null,
                'name' => $area->city->state->stateContent->name ?? null,
            ],
            'country' => [
                'id' => $area->city->state->country->id ?? null,
                'name' => $area->city->state->country->countryContent->name ?? null,
            ],
        ]);
    }
}