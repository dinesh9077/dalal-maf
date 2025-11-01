<?php

namespace App\Http\Controllers\Vendor;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Helpers\MegaMailer;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\Property\PropertyUpdateRequest;
use App\Http\Requests\Property\StoreRequest;
use App\Http\Requests\Property\UpdateRequest;
use App\Models\Agent;
use App\Models\Amenity;
use App\Models\BasicSettings\Basic;
use App\Models\Customer;
use App\Models\FeaturedPricing;
use App\Models\Language;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Property\Area;
use App\Models\Property\City;
use App\Models\Property\CityContent;
use App\Models\Property\Content;
use App\Models\Property\Country;
use App\Models\Property\FeaturedProperty;
use App\Models\Property\Property;
use App\Models\Property\PropertyAmenity;
use App\Models\Property\PropertyCategory;
use App\Models\Property\PropertyCategoryContent;
use App\Models\Property\PropertySliderImage;
use App\Models\Property\PropertyUnit;
use App\Models\Property\PrtFloor;
use App\Models\Property\PrtFloorStatus;
use App\Models\Property\PrtUnit;
use App\Models\Property\PrtWing;
use App\Models\Property\Spacification;
use App\Models\Property\SpacificationCotent;
use App\Models\Property\State;
use App\Models\Property\Unit;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Purifier;
use Session;

class PropertyController extends Controller
{
    protected $routeName;
    public function __construct(Request $request)
    {
        $this->routeName = $request->route()->getName();
    }

    public function type(Request $request)
    {
        $data['commertialCount'] = Property::where([['type', 'commercial'], ['vendor_id', Auth::guard('vendor')->user()->id]])->count();
        $data['residentialCount'] = Property::where([['type', 'residential'], ['vendor_id', Auth::guard('vendor')->user()->id]])->count();
        $data['industrialCount'] = Property::where([['type', 'industrial'], ['vendor_id', Auth::guard('vendor')->user()->id]])->count();
        if ($this->routeName === 'vendor.property_inventory.type') {
            $data['url'] = 'vendor.property_inventory.create_property';
        } else {
            $data['url'] = 'vendor.property_management.create_property';
        }
        return view('vendors.property.type', $data);
    }
    public function index(Request $request)
    {
        $data['langs'] = Language::all();
        if ($request->has('language')) {
            $language = Language::where('code', $request->language)->firstOrFail();
        } else {
            $language = Language::where('is_default', 1)->first();
        }

        $data['language'] = $language;

        $language_id = $language->id;
        $title = $purpose = $category = $city = null;

        if (request()->filled('title')) {
            $title = $request->title;
        }
        if (request()->filled('purpose')) {
            $purpose = $request->purpose;
        }
        if (request()->filled('category_id')) {
            $category = $request->category_id;
        }
        if (request()->filled('city_id')) {
            $city = $request->city_id;
        }

        $query = Property::where('vendor_id', Auth::guard('vendor')->user()->id)
            ->join('property_contents', 'properties.id', 'property_contents.property_id')
            ->leftJoin('property_category_contents', 'properties.category_id', '=', 'property_category_contents.category_id')
            ->leftJoin('property_city_contents', 'properties.city_id', '=', 'property_city_contents.city_id')
            ->with([
                'propertyContents' => function ($q) use ($language_id) {
                    $q->where('language_id', $language_id);
                }, 'vendor', 'featuredProperties', 'cityContent' => function ($q) use ($language) {
                    $q->where('language_id', $language->id);
                }
              ]);
              if ($this->routeName === 'vendor.property_inventory.properties') {
                  $query->where('property_type', 'full');
              } else {
                  $query->where('property_type', 'partial');
              }
             $query->when($title, function ($query) use ($title, $language_id) {
                return $query->where('property_contents.title', 'LIKE', '%' . $title . '%');
            });
            $query->when($category, function ($query) use ($category) {
                return $query->where('property_category_contents.category_id', $category);
            });
            $query->when($purpose, function ($query) use ($purpose) {
                return $query->where('purpose', 'LIKE', '%' . $purpose . '%');
            });
            $query->when($city, function ($query) use ($city) {
                return $query->where('property_city_contents.city_id', $city);
            });
        $data['properties'] = $query->where('property_contents.language_id', $language_id)
            ->select('properties.*')
            ->orderBy('id', 'desc')
            ->paginate(10);


        $data['vendors'] = Vendor::where('id', '!=', 0)->get();
        $data['featurePricing'] =  FeaturedPricing::where('status', 1)->get();



        $stripe = OnlineGateway::where('keyword', 'stripe')->where('status', '=', 1)->first();
        if (is_null($stripe)) {
            $data['stripe_key'] = null;
        } else {
            $stripe_info = json_decode($stripe->information, true);
            $data['stripe_key'] = $stripe_info['key'];
        }

        $onlineGateways = OnlineGateway::query()->where('status', '=', 1)->get();
        $offlineGateways = OfflineGateway::query()->where('status', '=', 1)->orderBy('serial_number', 'asc')->get();
        $data['onlineGateways'] = $onlineGateways;
        $data['offlineGateways'] = $offlineGateways;
        $data['categotyConetnt'] = PropertyCategoryContent::orderBy('id', 'asc')->get();
        $data['cities'] = CityContent::orderBy('id', 'asc')->get();

        $data['url'] = $this->routeName == 'vendor.property_inventory.properties' ? 'vendor.property_inventory.edit' : 'vendor.property_management.edit';
        $data['search_url'] = $this->routeName == 'vendor.property_inventory.properties' ? 'vendor.property_inventory.properties' : 'vendor.property_management.properties';

        return view('vendors.property.index', $data);
    }

    public function create(Request $request)
    {

        $information = [];
        $language = Language::where('is_default', 1)->first();
        $languages = Language::get();
        $information['languages'] = $languages;
        $information['propertyCategories'] = PropertyCategory::where([['type', $request->type], ['status', 1]])->with(['categoryContent' => function ($q) use ($language) {
            $q->where('language_id', $language->id);
        }])->get();
        $information['propertyCountries'] = Country::with(['countryContent' => function ($q) use ($language) {
            $q->where('language_id', $language->id);
        }])->get();
        $information['states'] = State::with(['stateContent' => function ($q) use ($language) {
            $q->where('language_id', $language->id);
        }])->get();
        $information['cities'] = City::where('status', 1)->with(['cityContent' => function ($q) use ($language) {
            $q->where('language_id', $language->id);
        }])->get();
        $information['amenities'] = Amenity::with(['amenityContent' => function ($q) use ($language) {
            $q->where('language_id', $language->id);
        }])->where('status', 1)->get();
        $information['agents'] = Agent::where('vendor_id', Auth::guard('vendor')->user()->id)->get();
        $information['unitTypes'] = Unit::get();
        $information['areas'] = Area::where('status', 1)->get();
        if($this->routeName == 'vendor.property_inventory.create_property') {
          $information['url'] = 'vendor.property_inventory.store_property';
        } else {
          $information['url'] = 'vendor.property_management.store_property';
        }
        return view('vendors.property.create', $information);
    }

    public function updateFeatured(Request $request)
    {
        $property = FeaturedProperty::findOrFail($request->requestId);

        if ($request->status == 1) {
            $property->update(['status' => 1]);

            Session::flash('success', 'Property featured successfully!');
        } else {
            $property->update(['status' => 0]);

            Session::flash('success', 'Property remove from featured successfully!');
        }

        return redirect()->back();
    }

    public function imagesstore(Request $request)
    {
        $img = $request->file('file');
        $allowedExts = array('jpg', 'png', 'jpeg', 'svg', 'webp');
        $rules = [
            'file' => [
                function ($attribute, $value, $fail) use ($img, $allowedExts) {
                    $ext = $img->getClientOriginalExtension();
                    if (!in_array($ext, $allowedExts)) {
                        return $fail("Only png, jpg, jpeg images are allowed");
                    }
                },
            ]
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $imageName = UploadFile::store(public_path('assets/img/property/slider-images/'), $request->file('file'));

        $pi = new PropertySliderImage();
        if (!empty($request->property_id)) {
            $pi->property_id = $request->property_id;
        }
        $pi->image = $imageName;
        $pi->save();
        return response()->json(['status' => 'success', 'file_id' => $pi->id]);
    }
    public function imagermv(Request $request)
    {
        $pi = PropertySliderImage::findOrFail($request->fileid);
        $imageCount = PropertySliderImage::where('property_id', $pi->property_id)->get()->count();
        if ($imageCount > 1) {
            @unlink(public_path('assets/img/property/slider-images/') . $pi->image);
            $pi->delete();
            return $pi->id;
        } else {
            return 'false';
        }
    }

    //imagedbrmv
    public function imagedbrmv(Request $request)
    {
        $pi = PropertySliderImage::findOrFail($request->fileid);
        $imageCount = PropertySliderImage::where('property_id', $pi->property_id)->get()->count();
        if ($imageCount > 1) {
            @unlink(public_path('assets/img/property/slider-images/') . $pi->image);
            $pi->delete();
            return $pi->id;
        } else {
            return 'false';
        }
    }
    public function store(StoreRequest $request)
    {

        DB::transaction(function () use ($request) {

            $featuredImgURL = $request->featured_image;
            if (request()->hasFile('featured_image')) {
                $featuredImgName = UploadFile::store(public_path('assets/img/property/featureds'), $featuredImgURL);
            }

            $languages = Language::all();
            $floorPlanningImage = null;
            $videoImage = null;
            if (request()->hasFile('floor_planning_image')) {
                $floorPlanningImage = UploadFile::store(public_path('assets/img/property/plannings'), $request->floor_planning_image);
            }
            if ($request->hasFile('video_image')) {
                $videoImage = UploadFile::store(public_path('assets/img/property/video/'), $request->video_image);
            }
            $bs = Basic::select('property_approval_status')->first();
            if ($bs->property_approval_status == 1) {
                $approveStatus = 0;
            } else {
                $approveStatus = 1;
            }
            $property = Property::create([
                'property_type' => $this->routeName == 'vendor.property_inventory.store_property' ? 'full' : 'partial',
                'vendor_id' => Auth::guard('vendor')->user()->id,
                'agent_id' => $request->agent_id,
                'is_new' => '0',
                'category_id' => $request->category_id,
                'country_id' => $request->country_id,
                'state_id' => $request->state_id,
                'city_id' => $request->city_id,
                'area_id' => $request->area_id,
                'featured_image' => $featuredImgName,
                'floor_planning_image' => $floorPlanningImage,
                'video_image' => $videoImage,
                'price' => $request->price,
                'purpose' => $request->purpose,
                'type' => $request->type,
                'beds' => $request->beds,
                'bath' => $request->bath,
                'area' => $request->area,
                'video_url' => $request->video_url,
                'status' => $request->status,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'approve_status' => $approveStatus,
                'furnishing' => $request->furnishing,
                'possession_date' => $request->possession_date
            ]);

            $slders = $request->slider_images;
            if ($slders) {
                $pis = PropertySliderImage::findOrFail($slders);
                foreach ($pis as $key => $pi) {
                    $pi->property_id = $property->id;
                    $pi->save();
                }
            }
            if ($request->has('amenities')) {
                foreach ($request->amenities as $amenity) {
                    PropertyAmenity::create([
                        'property_id' => $property->id,
                        'amenity_id' => $amenity
                    ]);
                }
            }

            if ($request->has('unit_type')) {
                foreach ($request->unit_type as $unit) {
                    PropertyUnit::create([
                        'property_id' => $property->id,
                        'unit_id' => $unit
                    ]);
                }
            }

            foreach ($languages as $language) {
                $propertyContent = new Content();
                $propertyContent->language_id = $language->id;
                $propertyContent->property_id = $property->id;
                $propertyContent->title = $request[$language->code . '_title'];
                $propertyContent->slug = createSlug($request[$language->code . '_title']);

                $propertyContent->address = $request[$language->code . '_address'];
                $propertyContent->description = Purifier::clean($request[$language->code . '_description'], 'youtube');
                $propertyContent->meta_keyword = $request[$language->code . '_meta_keyword'];
                $propertyContent->meta_description = $request[$language->code . '_meta_description'];
                $propertyContent->save();

                $label_datas = $request[$language->code . '_label'];
                foreach ($label_datas as $key => $data) {
                    if (!empty($request[$language->code . '_value'][$key])) {
                        $property_specification = Spacification::where([['property_id', $property->id], ['key', $key]])->first();
                        if (is_null($property_specification)) {
                            $property_specification = new Spacification();
                            $property_specification->property_id = $property->id;
                            $property_specification->key  = $key;
                            $property_specification->save();
                        }
                        $property_specification_content = new SpacificationCotent();
                        $property_specification_content->language_id = $language->id;
                        $property_specification_content->property_spacification_id = $property_specification->id;
                        $property_specification_content->label = $data;
                        $property_specification_content->value = $request[$language->code . '_value'][$key];
                        $property_specification_content->save();
                    }
                }
            }
            $propertyContent = Content::where('property_id', $property->id)->select('title')->first();

            $this->mailToAdminForCreateProperty($propertyContent->title, Auth::guard('vendor')->user());
        });
        Session::flash('success', 'New Property added successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function updateStatus(Request $request)
    {
        $property = Property::findOrFail($request->propertyId);

        if ($request->status == 1) {
            $property->update(['status' => 1]);

            Session::flash('success', 'Property Active successfully!');
        } else {
            $property->update(['status' => 0]);

            Session::flash('success', 'Property Inactive successfully!');
        }

        return redirect()->back();
    }
    public function edit($id)
    {
        $property = Property::with('galleryImages')->where('vendor_id', Auth::guard('vendor')->user()->id)->findOrFail($id);
        $information['property'] = $property;
        $information['galleryImages'] = $property->galleryImages;
        $information['languages'] = Language::all();
        $language = Language::where('is_default', 1)->first();
        $information['propertyAmenities'] = PropertyAmenity::where('property_id', $property->id)->get();
        $information['amenities'] = Amenity::with(['amenityContent' => function ($q) use ($language) {
            $q->where('language_id', $language->id);
        }])->where('status', 1)->get();
        $information['propertyCategories'] = PropertyCategory::where([['type', $property->type], ['status', 1]])->with(['categoryContent' => function ($q) use ($language) {
            $q->where('language_id', $language->id);
        }])->get();
        $information['propertyCountries'] = Country::with(['countryContent' => function ($q) use ($language) {
            $q->where('language_id', $language->id);
        }])->get();
        $information['propertyStates'] = State::where('country_id', $property->country_id)->with(['stateContent' => function ($q) use ($language) {
            $q->where('language_id', $language->id);
        }])->get();

        $information['propertyCities'] = City::where('state_id', $property->state_id)->with(['cityContent' => function ($q) use ($language) {
            $q->where('language_id', $language->id);
        }])->get();
        $information['specifications'] = Spacification::where('property_id', $property->id)->get();
        $information['agents'] = Agent::where('vendor_id', Auth::guard('vendor')->user()->id)->get();
        $information['propertyUnities'] = PropertyUnit::where('property_id', $property->id)->get();
        $information['unitTypes']	= Unit::Where('status',1)->get();
        $information['propertyAreas'] = Area::where('city_id', $property->city_id)->get();
        $information['url'] = $this->routeName == 'vendor.property_inventory.properties' ? 'vendor.property_inventory.update_property' : 'vendor.property_management.update_property';
		$information['allAreas'] = Area::get();

        return view('vendors.property.edit', $information);
    }

    public function update(UpdateRequest $request, $id)
    {
        DB::transaction(
            function () use ($request, $id) {
                $languages = Language::all();

                $property = Property::where('vendor_id', Auth::guard('vendor')->user()->id)->findOrFail($request->property_id);

                $featuredImgName = $property->featured_image;
                $floorPlanningImage = $property->floor_planning_image;
                $videoImage = $property->video_image;

                if ($request->hasFile('featured_image')) {
                    $featuredImgName = UploadFile::update(public_path('assets/img/property/featureds'), $request->featured_image, $property->featured_image);
                }
                if ($request->hasFile('floor_planning_image')) {
                    $floorPlanningImage = UploadFile::update(public_path('assets/img/property/plannings'), $request->floor_planning_image, $property->floor_planning_image);
                }


                if ($request->hasFile('video_image')) {
                    $videoImage = UploadFile::update(public_path('assets/img/property/video/'), $request->video_image, $property->video_image);
                }


                $property->update([
                    'agent_id' => $request->agent_id,
                    'category_id' => $request->category_id,
                    'country_id' => $request->country_id,
                    'state_id' => $request->state_id,
                    'city_id' => $request->city_id,
                    'area_id' => $request->area_id,
                    'featured_image' => $featuredImgName,
                    'floor_planning_image' => $floorPlanningImage,
                    'video_image' => $videoImage,
                    'price' => $request->price,
                    'purpose' => $request->purpose,
                    'type' => $request->type,
                    'beds' => $request->beds,
                    'bath' => $request->bath,
                    'area' => $request->area,
                    'video_url' => $request->video_url,
                    'status' => $request->status,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'furnishing' => $request->furnishing,
                    'possession_date' => $request->possession_date
                ]);

                $d_property_specifications = Spacification::where('property_id', $request->property_id)->get();
                foreach ($d_property_specifications as $d_property_specification) {
                    $d_property_specification_contents = SpacificationCotent::where('property_spacification_id', $d_property_specification->id)->get();
                    foreach ($d_property_specification_contents as $d_property_specification_content) {
                        $d_property_specification_content->delete();
                    }
                    $d_property_specification->delete();
                }
                if ($request->has('amenities')) {
                    $property->proertyAmenities()->delete();
                    foreach ($request->amenities as $amenity) {
                        PropertyAmenity::create([
                            'property_id' => $property->id,
                            'amenity_id' => $amenity
                        ]);
                    }
                }

                if ($request->has('unit_type')) {
                  $property->proertyUnits()->delete();
                  foreach ($request->unit_type as $unit) {
                      PropertyUnit::create([
                          'property_id' => $property->id,
                          'unit_id' => $unit
                      ]);
                  }
              }

                foreach ($languages as $language) {
                    $propertyContent =  Content::where('property_id', $request->property_id)->where('language_id', $language->id)->first();
                    if (empty($propertyContent)) {
                        $propertyContent = new Content();
                    }
                    $propertyContent->language_id = $language->id;
                    $propertyContent->property_id = $property->id;
                    $propertyContent->title = $request[$language->code . '_title'];
                    $propertyContent->slug = createSlug($request[$language->code . '_title']);
                    $propertyContent->address = $request[$language->code . '_address'];
                    $propertyContent->description = Purifier::clean($request[$language->code . '_description'], 'youtube');
                    $propertyContent->meta_keyword = $request[$language->code . '_meta_keyword'];
                    $propertyContent->meta_description = $request[$language->code . '_meta_description'];
                    $propertyContent->save();

                    $label_datas = $request[$language->code . '_label'];
                    foreach ($label_datas as $key => $data) {
                        if (!empty($request[$language->code . '_value'][$key])) {
                            $property_specification = Spacification::where([['property_id', $property->id], ['key', $key]])->first();
                            if (is_null($property_specification)) {
                                $property_specification = new Spacification();
                                $property_specification->property_id = $property->id;
                                $property_specification->key  = $key;
                                $property_specification->save();
                            }
                            $property_specification_content = new SpacificationCotent();
                            $property_specification_content->language_id = $language->id;
                            $property_specification_content->property_spacification_id = $property_specification->id;
                            $property_specification_content->label = $data;
                            $property_specification_content->value = $request[$language->code . '_value'][$key];
                            $property_specification_content->save();
                        }
                    }
                }
            }
        );
        Session::flash('success', 'Property Updated successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function requestTofeature()
    {
        $data['featurePricing'] =  FeaturedPricing::where('status', 1)->get();
        return view('vendors.property.featured-request', $data);
    }


    public function specificationDelete(Request $request)
    {

        $d_project_specification = Spacification::find($request->spacificationId);

        $d_project_specification_contents = SpacificationCotent::where('property_spacification_id', $d_project_specification->id)->get();
        foreach ($d_project_specification_contents as $d_project_specification_content) {
            $d_project_specification_content->delete();
        }
        $d_project_specification->delete();

        return Response::json(['status' => 'success'], 200);
    }

    public function getStateCities(Request $request)
    {
        $language = Language::where('is_default', 1)->first();
        $states = State::where('country_id', $request->id)->with(['cities', 'stateContent' => function ($q) use ($language) {
            $q->where('language_id', $language->id);
        }])->get();
        $cities = City::where('country_id', $request->id)->with(['cityContent' => function ($q) use ($language) {
            $q->where('language_id', $language->id);
        }])->get();
        return Response::json(['states' => $states, 'cities' => $cities], 200);
    }
    public function getCities(Request $request)
    {
        $language = Language::where('is_default', 1)->first();
        $cities = City::where('state_id', $request->state_id)->with(['cityContent' => function ($q) use ($language) {
            $q->where('language_id', $language->id);
        }])->get();
        return Response::json(['cities' => $cities], 200);
    }

    public function delete(Request $request)
    {

        try {
            $this->deleteProperty($request->property_id);
        } catch (\Exception $e) {
            Session::flash('warning', 'Something went wrong!');

            return redirect()->back();
        }

        Session::flash('success', 'Property deleted successfully!');
        return redirect()->back();
    }

    public function deleteProperty($id)
    {

        $property = Property::find($id);

        if (!is_null($property->featured_image)) {
            @unlink(public_path('assets/img/property/featureds/' . $property->featured_image));
        }

        if (!is_null($property->floor_planning_image)) {
            @unlink(public_path('assets/img/property/plannings/' . $property->floor_planning_image));
        }
        if (!is_null($property->video_image)) {
            @unlink(public_path('assets/img/property/video/' . $property->video_image));
        }

        $propertySliderImages  = $property->galleryImages()->get();
        foreach ($propertySliderImages  as  $image) {

            @unlink(public_path('assets/img/property/slider-images/' . $image->image));
            $image->delete();
        }

        $property->proertyAmenities()->delete();
        $property->proertyUnits()->delete();

        $specifications = $property->specifications()->get();
        foreach ($specifications as $specification) {
            $specificationContents = $specification->specificationContents()->get();
            foreach ($specificationContents as $sContent) {

                $sContent->delete();
            }
            $specification->delete();
        }

        $propertyContents = $property->propertyContents()->get();

        foreach ($propertyContents as $content) {

            $content->delete();
        }
        $property->propertyMessages()->delete();
        $property->featuredProperties()->delete();
        // delete wishlists
        $property->wishlists()->delete();

        $property->delete();

        return;
    }

    public function bulkDelete(Request $request)
    {
        $propertyIds = $request->ids;
        try {
            foreach ($propertyIds as $id) {
                $this->deleteProperty($id);
            }
        } catch (\Exception $e) {
            Session::flash('warning', 'Something went wrong!');

            return redirect()->back();
        }
        Session::flash('success', 'Properties deleted successfully!');
        return response()->json(['status' => 'success'], 200);
    }

    public function unitTypeAdd()
    {
        $property_types = Unit::whereStatus(1)->get();
        $view = view('vendors.unit-type.add',compact('property_types'))->render();
        return response()->json(['status'=>'success','view'=>$view]);
    }

    public function unitTypeStore(Request $request)
    {

        $vendor_id = Auth::guard('vendor')->user()->id;

        $validator = Validator::make($request->all(), [
        'unit_name' => 'required'
        ]);

        $validator->after(function ($validator) use ($request) {
          if(Unit::whereUnit_name($request->unit_name)->exists())
          {
            $validator->errors()->add('unit_name', 'The unit name has already been taken');
          }
        });

        if ($validator->fails()) {
          return response()->json([
          'status' => 'validation',
          'errors' => $validator->errors()
          ]);
        }

        $data = $request->except('_token');
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['added_id'] = $vendor_id;

        try
        {
          DB::beginTransaction();

          $object = new Unit();
          Helper::saveData($object,$data);
          $id = $object->id;
          DB::commit();
          return response()->json(['status'=>'success','id'=>$id,'name'=>$request->unit_name,'msg'=>'The unit type has been successfully added.']);
        }
        catch (\Throwable $e)
        {
          DB::rollBack();
          if ($e instanceof \Illuminate\Database\QueryException && $e->errorInfo[1] === 1451) {
            $message = trans('custom.delete_msg');
            } else {
            $message = $e->getMessage();
          }
          return response()->json(['status' => 'error', 'msg' => $message]);
        }
    }

    public function view($id)
    {
        return view('vendors.property.view',compact('id'));
    }

    public function propertyFullWings($property_id)
    {
      $wings = PrtWing::where('property_id',$property_id)->get();
			$countmore = (count($wings) > 0)?(count($wings)+1):2;
			$view = view('vendors.property.full_property_wings',compact('property_id','wings','countmore'))->render();
			return response()->json(['status'=>'success','view'=>$view]);
    }

    public function propertyFullWingsStore(Request $request)
    {

			$user_id = Auth::guard('vendor')->user()->id;

			$property_id = $request->property_id;
			$wings = $request->wings;
			$wing_number = $request->wing_number;
			$wing_name = $request->wing_name;

			$data = $request->except('_token');
			$data['user_id'] = $user_id;
			$data['status'] = 1;

			try
			{
				DB::beginTransaction();
				if(isset($request->id) && !empty($request->id))
				{
					$data['updated_at'] = now();
					$wing_id = $request->id;
					$object = PrtWing::find($wing_id);
					Helper::saveData($object,$data);
				}
				else
				{
					$data['created_at'] = now();
					$data['updated_at'] = now();
					$object = new PrtWing();
					Helper::saveData($object,$data);
					$wing_id = $object->id;
				}
				DB::commit();
				$view = view('vendors.property.full_property.floors',compact('property_id','wing_id','wings','wing_number'))->render();
				return response()->json(['status'=>'success','view'=>$view,'wing_id'=>$wing_id,'wings'=>$wings,'wing_name'=>$wing_name]);
			}
			catch (\Throwable $e)
			{
				DB::rollBack();
				if ($e instanceof \Illuminate\Database\QueryException && $e->errorInfo[1] === 1451) {
					$message = trans('custom.delete_msg');
					} else {
					$message = $e->getMessage();
				}
				return response()->json(['status' => 'error', 'msg' => $message]);
			}
    }

    public function propertyFullWingsFloors(Request $request)
    {

        $property_id = $request->property_id;
        $wings = $request->wings;
        $wing_number = $request->wing_number;
        $wing_id = $request->wing_id;

        $view = view('vendors.property.full_property.floors',compact('property_id','wing_id','wings','wing_number'))->render();

        return response()->json(['status'=>'success','view'=>$view]);
    }

    public function propertyFullWingDelete(Request $request)
    {
      try
			{
				DB::beginTransaction();
				PrtWing::find($request->wing_id)->delete();

				$prtwings = PrtWing::where('property_id',$request->property_id)->orderBy('wing_number','asc')->get();
				if(count($prtwings) > 0)
				{
					foreach($prtwings as $key => $prtwing)
					{
						PrtWing::where('id',$prtwing->id)->update(['wing_number'=>($key + 1)]);
					}
				}
				DB::commit();
				return response()->json(['status'=>'success','msg'=>'The wings has been successfully deleted.']);
			}
			catch (\Throwable $e)
			{
				DB::rollBack();
				if ($e instanceof \Illuminate\Database\QueryException && $e->errorInfo[1] === 1451) {
					$message = trans('custom.delete_msg');
					} else {
					$message = $e->getMessage();
				}
				return response()->json(['status' => 'error', 'msg' => $message]);
			}
    }

    public function propertyFullFloorsStore(Request $request)
    {

			$user_id = Auth::guard('vendor')->user()->id;

			$property_id = $request->property_id;
			$floors = $request->floors;
			$floor_number = $request->floor_number;
			$wing_id = $request->wing_id;
			$floor_name = $request->floor_name;

			$data = $request->except('_token','id');
			$data['user_id'] = $user_id;
			$data['status'] = 1;
			$data['created_at'] = now();

			try
			{
				DB::beginTransaction();
				if(empty($request->id))
				{
					$data['updated_at'] = now();
					$object = new PrtFloor();
					Helper::saveData($object,$data);
					$floor_id = $object->id;
				}
				else
				{
					$floor_id = $request->id;
					$object = PrtFloor::find($floor_id);
					Helper::saveData($object,$data);
				}
				DB::commit();
				$flatcount = 0;
				$units = Unit::where('status', 1)->get();
				$budgets = [];
				$categories = [];
			  $partailsDetails = PrtUnit::where('wing_id', $wing_id)->where('floor_id', $floor_id)->get()->keyBy('flat_number');
				$view = view('vendors.property.full_property.property-details',compact('property_id','wing_id','floor_id','floors','floor_number','budgets','categories','flatcount','partailsDetails','units'))->render();

				return response()->json(['status'=>'success','view'=>$view,'floors'=>$floors,'floor_id'=>$floor_id,'floor_name'=>$floor_name]);
			}
			catch (\Throwable $e)
			{
        dd($e);
				DB::rollBack();
				if ($e instanceof \Illuminate\Database\QueryException && $e->errorInfo[1] === 1451) {
					$message = trans('custom.delete_msg');
					} else {
					$message = $e->getMessage();
				}
				return response()->json(['status' => 'error', 'msg' => $message]);
			}
    }

    public function propertyFullFloorsPartial(Request $request)
    {
      try{

        $user_id = Auth::guard('vendor')->user()->id;

        $property_id = $request->property_id;
        $floors = $request->floors;
        $floor_number = $request->floor_number;
        $wing_id = $request->wing_id;
        $floor_id = $request->floor_id;

        $units = Unit::where('status', 1)->get();
        $budgets = [];
        $categories = [];

        $flatcount = PrtUnit::where('wing_id',$wing_id)->where('floor_id',$floor_id)->count();
        $partailsDetails = PrtUnit::where('wing_id', $wing_id)->where('floor_id', $floor_id)->get()->keyBy('flat_number');

        $view = view('vendors.property.full_property.property-details',compact('property_id','wing_id','floor_id','floors','floor_number','units','budgets','categories','flatcount','partailsDetails'))->render();
        return response()->json(['status'=>'success','view'=>$view]);
      }catch (\Throwable $e)
			{
        dd($e);
      }
		}

    public function propertyFullFloorDelete(Request $request)
    {
      try
			{

				DB::beginTransaction();
				Prtfloor::find($request->floor_id)->delete();
				$prtfloors = PrtFloor::where('property_id',$request->property_id)->where('wing_id',$request->wing_id)->orderBy('floor_number','asc')->get();
				if(count($prtfloors) > 0)
				{
					foreach($prtfloors as $key => $prtfloor)
					{
						PrtFloor::where('id',$prtfloor->id)->update(['floor_number'=>($key + 1)]);
					}
				}

				$count = PrtFloor::where('wing_id',$request->wing_id)->count();
				PrtWing::where('id',$request->wing_id)->update(['wings'=>$count]);
				$prtwings = PrtWing::where('id',$request->wing_id)->first();
				DB::commit();
				return response()->json(['status'=>'success','msg'=>'The floors has been successfully deleted.','count'=>$count,'prtwings'=>$prtwings]);
			}
			catch (\Throwable $e)
			{
				DB::rollBack();
				if ($e instanceof \Illuminate\Database\QueryException && $e->errorInfo[1] === 1451) {
					$message = trans('custom.delete_msg');
					} else {
					$message = $e->getMessage();
				}
				return response()->json(['status' => 'error', 'msg' => $message]);
			}
    }

    public function propertyFloorCopy($id)
    {
      try
			{
				DB::beginTransaction();
				$floor = PrtFloor::with('wing:id,wing_name,wing_number,wings', 'units')->find($id);

				if($floor && count($floor->units) > 0)
				{
					$floors = PrtFloor::with('units')
					->select('id', 'wing_id', 'floors', 'floor_name', 'floor_number')
					->where('id', '!=', $id)
					->where('wing_id', $floor->wing_id)
					->get();

					$wing_number = $floor && $floor->wing ? ($floor->wing->wings - 1) - count($floors) : 0;
					if(count($floors) > 0)
					{
						foreach($floors as $row)
						{
							$object = PrtFloor::find($row->id);
							Helper::saveData($object,['floors'=>$floor->floors, 'updated_at'=>now()]);

							if(count($row->units) > 0)
							{
								foreach ($floor->units as $key => $unit)
								{
									$j = $key + 1;
									if($j <= 9)
									{
										$j = '0'.$j;
									}

									$flat_no = $row->floor_number.$j;
									$newUnit = $unit->replicate()->toArray();
									$newUnit['user_id'] = auth()->user()->id;
									$newUnit['wing_id'] = $row->wing_id;
									$newUnit['floor_id'] = $row->id;
									$newUnit['flat_no'] = $flat_no;
									$newUnit['updated_at'] = now();

									$object1 = PrtUnit::updateOrCreate(
										['id' => isset($row->units[$key]) ? $row->units[$key]->id : '','wing_id' => $row->wing_id,'floor_id' =>$row->id],
										$newUnit
									);

								}
							}
							else
							{
								foreach ($floor->units as $key => $unit)
								{
									$j = $key + 1;
									if($j <= 9)
									{
										$j = '0'.$j;
									}
									$newUnit = $unit->replicate()->toArray();
									$newUnit['user_id'] = auth()->user()->id;
									$newUnit['wing_id'] = $row->wing_id;
									$newUnit['floor_id'] = $row->id;
									$newUnit['flat_no'] = $row->floor_number.$j;
									$newUnit['created_at'] = now();
									$newUnit['updated_at'] = now();

									$object1 = new PrtUnit();
									Helper::saveData($object1,$newUnit);
								}
							}
						}
						if($wing_number > 0)
						{
							$this->floorStore($wing_number, $floor);
						}
					}
					else
					{
						if ($wing_number > 0) {
							for ($i = 1; $i <= $wing_number; $i++) {
								$floorNumber = $i + 1;

								$newFloor = $floor->replicate()->toArray();
								$newFloor['floor_number'] = $floorNumber;
								$newFloor['floor_name'] = "Floor{$floorNumber} / Row{$floorNumber}";
								$newFloor['created_at'] = now();
								$newFloor['updated_at'] = now();

								unset($newFloor['wing'], $newFloor['units']);

								$floorObject = new PrtFloor();
								Helper::saveData($floorObject, $newFloor);

								$flatCount = 1;

								foreach ($floor->units as $unit)
								{
									if($flatCount <= 9)
									{
										$flatCount = '0'.$flatCount;
									}
									$newUnit = $unit->replicate()->toArray();
									$newUnit['user_id']   = auth()->id();
									$newUnit['wing_id']   = $floorObject->wing_id;
									$newUnit['flat_no']   = (int) "{$floorNumber}{$flatCount}";
									$newUnit['floor_id']  = $floorObject->id;
									$newUnit['created_at'] = now();
									$newUnit['updated_at'] = now();

									$unitObject = new PrtUnit();
									Helper::saveData($unitObject, $newUnit);

									$flatCount++;
								}
							}
						}
					}
					DB::commit();
					return response()->json(['status' => 'success', 'msg' => 'The unit/floor details has been copied.','wing'=>$floor->wing]);
				}

				return response()->json(['status' => 'error', 'msg' => 'The unit/floor details not found.']);
			}
			catch (\Throwable $e)
			{
				DB::rollBack();
				if ($e instanceof \Illuminate\Database\QueryException && $e->errorInfo[1] === 1451) {
					$message = trans('custom.delete_msg');
					} else {
					$message = $e->getMessage();
				}
				return response()->json(['status' => 'error', 'msg' => $message]);
			}
    }

    public function propertyFullFlatStore(Request $request)
		{
			error_reporting(0);

			$user_id = auth()->user()->id;
			$flat_nos = $request->flat_no;

			$now = now();
			$insertData = [];
			$updateData = [];

			try {
				DB::beginTransaction();

				foreach ($flat_nos as $key => $flat_no) {
					$data = [
					'user_id' => $user_id,
					'wing_id' => $request->wing_id,
					'floor_id' => $request->floor_id,
					'property_id' => $request->property_id,
					'flat_no' => $flat_no,
					'flat_number' => $request->flat_number[$key],
					'category_id' => 'sell',
					'unit_type' => $request->unit_type[$key],
					'facing' => $request->facing[$key],
					'sqft' => $request->sqft[$key],
					'plot_size' => $request->plot_size[$key],
					'budget' => $request->budget[$key],
					'price' => $request->price[$key],
					'property_status' => 'Available',
					'created_at' => $now,
					'updated_at' => $now
					];

					if (empty($request->id[$key])) {
						$insertData[] = $data;
						} else {
						$updateData[$request->id[$key]] = $data;
					}
				}

				if (!empty($insertData)) {
					PrtUnit::insert($insertData);
					foreach ($insertData as $data) {
						$id = PrtUnit::where($data)->pluck('id')->first();
					}
				}

				foreach ($updateData as $id => $data) {
					$object = PrtUnit::find($id);
					if ($object) {
						$object->update($data);
					}
				}

				DB::commit();
				$prtwings = PrtWing::find($request->wing_id);
				return response()->json(['status' => 'success', 'msg' => 'Property details have been successfully added.', 'prtwings' => $prtwings]);
				} catch (\Throwable $e) {
          dd($e);
				DB::rollBack();
				if ($e instanceof \Illuminate\Database\QueryException && $e->errorInfo[1] === 1451) {
					$message = trans('custom.delete_msg');
					} else {
					$message = $e->getMessage();
				}
				return response()->json(['status' => 'error', 'msg' => $message]);
			}
		}

    public function propertyFullFlatDelete(Request $request)
    {
      try
			{
				DB::beginTransaction();
				PrtUnit::find($request->details_id)->delete();

				$prtunits = PrtUnit::where('property_id',$request->property_id)->where('wing_id',$request->wing_id)->where('floor_id',$request->floor_id)->orderBy('flat_no','asc')->get();
				if(count($prtunits) > 0)
				{
					foreach($prtunits as $key => $prtunit)
					{
						$object = PrtUnit::find($prtunit->id);
						Helper::saveData($object,['flat_number'=>($key + 1)]);
					}
				}

				$count = PrtUnit::where('wing_id',$request->wing_id)->where('floor_id',$request->floor_id)->count();
				PrtFloor::where('id',$request->floor_id)->update(['floors'=>$count]);
				$prtwings = PrtWing::where('id',$request->wing_id)->first();
				DB::commit();
				return response()->json(['status'=>'success','msg'=>'The property details has been successfully deleted.','count'=>$count,'prtwings'=>$prtwings]);
			}
			catch (\Throwable $e)
			{
				DB::rollBack();
				if ($e instanceof \Illuminate\Database\QueryException && $e->errorInfo[1] === 1451) {
					$message = trans('custom.delete_msg');
					} else {
					$message = $e->getMessage();
				}
				return response()->json(['status' => 'error', 'msg' => $message]);
			}
    }

    public function propertyFloorStatusChanges(Request $request)
    {
        $vendorId = Auth::guard('vendor')->user()->id;
        error_reporting(0);
        $property_id = $request->property_id;
        $wing_id = $request->wing_id;
        $floor_id = $request->floor_id;
        $unit_id = $request->unit_id;
        $property_status = $request->property_status;

        $user = Auth::user();

        $branch_id = $user->branch_id;


        $property = Content::select('property_id','title')->where('property_id',$property_id)->first();

        $wings = PrtWing::select('prt_wings.*')->where('prt_wings.property_id',$property_id)->join('prt_floors as prtf','prtf.wing_id','=','prt_wings.id')->orderBy('prt_wings.wing_number','asc')->get();

        $floors = PrtFloor::select('prt_floors.*')->where('prt_floors.wing_id',$request->wing_id)->orderBy('prt_floors.floor_number','asc')->get();

        $floor_details = PrtUnit::where('wing_id',$wing_id)->where('floor_id',$floor_id)->get();
        $customers = Customer::where('vendor_id',$vendorId)->get();

        $view = view('vendors.property.floor-status-change',compact('property','wings','floors','property_id','wing_id','floor_id','floor_details','unit_id','property_status','customers'))->render();
        return response()->json(['status'=>'success','view'=>$view]);
    }

    public function propertyWorkAssignStore(Request $request)
    {

        $data = $request->except('_token');

        $validator = Validator::make($request->all(), [
          'property_id' => 'required',
          'wing_id' => 'required',
          'floor_id' => 'required',
          'unit_id' => 'required'
        ]);

        if ($validator->fails()) {
          return response()->json([
          'status' => 'validation',
          'errors' => $validator->errors()
          ]);
        }

        try
        {

         DB::beginTransaction();

          // Update property status in PrtUnit table
          PrtUnit::where('id', $request->unit_id)
              ->update(['property_status' => $request->property_status]);

          // Check if a record already exists in PrtFloorStatus
          $object = PrtFloorStatus::where('property_id', $request->property_id)
              ->where('wing_id', $request->wing_id)
              ->where('floor_id', $request->floor_id)
              ->where('unit_id', $request->unit_id)
              ->first();

          if ($object) {
              // Update if record exists
              Helper::saveData($object, $data);
          } else {
              // Create new if record does not exist
              $object = new PrtFloorStatus();
              Helper::saveData($object, $data);
          }

          DB::commit();

          return response()->json([
              'status' => 'success',
              'msg' => 'Property Work Assigned Successfully'
          ]);
			  }
        catch (\Throwable $e)
        {
          DB::rollBack();
          if ($e instanceof \Illuminate\Database\QueryException && $e->errorInfo[1] === 1451) {
            $message = trans('custom.delete_msg');
            } else {
            $message = $e->getMessage();
          }
          return response()->json(['status' => 'error', 'msg' => $message]);
        }
    }

    public function addCustomer(Request $request)
    {

        $validator = Validator::make($request->all(), [
          'name' => 'required',
          'email' => 'required|email',
          'phone' => 'required'
        ]);

        $validator->after(function ($validator) use ($request) {
          if(Customer::whereemail($request->email)->exists())
          {
            $validator->errors()->add('email', 'The email has already been taken');
          }
        });

        if ($validator->fails()) {
          return response()->json([
          'status' => 'validation',
          'errors' => $validator->errors()
          ]);
        }

        $customer = Customer::create([
          'name'  => $request->name,
          'email' => $request->email,
          'phone_number' => $request->phone,
          'vendor_id' => Auth::guard('vendor')->user()->id
      ]);

      return response()->json([
          'success' => true,
          'customer' => $customer
      ]);
    }

    public function manageStatus(Request $request)
    {
      $properties = Property::with('propertyContent')->where('vendor_id', Auth::guard('vendor')->user()->id)->where('property_type', 'full')
        ->where('approve_status', 1)
        ->where('status', 1)
        ->get();

      return view('vendors.property.manage_status',compact('properties'));
    }

    public function propertyByPropertyWing($property_id)
    {
        // $wings = PrtWing::select('prt_wings.*')->where('prt_wings.property_id',$property_id)->join('prt_floors as prtf','prtf.wing_id','=','prt_wings.id')->orderBy('prt_wings.wing_number','asc')->groupBy('prtf.wing_id')->get();
        $wings = PrtWing::select('prt_wings.*')
        ->where('prt_wings.property_id', $property_id)
        ->join('prt_floors as prtf', 'prtf.wing_id', '=', 'prt_wings.id')
        ->distinct()
        ->orderBy('prt_wings.wing_number', 'asc')
        ->get();
        $output = '';
        foreach($wings as $key => $wing)
        {
          $output .= '<option value="'.$wing->id.'" selected>Wing - '.$wing->wing_number.'</option>';
        }
        return response()->json(['status'=>'success','output'=>$output]);
    }

    public function propertyByFloorDetails(Request $request)
		{
			error_reporting(0);
			if(!isset($request->wing_id))
			{
				return response()->json(['status'=>'error','view'=>'']);
			}

           $wings = PrtWing::select(
        'prt_wings.id',
        'prt_wings.wing_number',
        'prt_wings.wing_name'
      )
      ->whereIn('prt_wings.id', $request->wing_id)
      ->join('prt_floors as prtf', 'prtf.wing_id', '=', 'prt_wings.id')
      ->groupBy('prt_wings.id', 'prt_wings.wing_number', 'prt_wings.wing_name')
      ->orderBy('prt_wings.wing_number', 'asc')
      ->get();
			$floors = PrtFloor::select(
                DB::raw('MIN(prt_floors.id) as id'),
                'prt_floors.floor_number',
                DB::raw('GROUP_CONCAT(prt_floors.wing_id) as group_wing'),
                DB::raw('MAX(prt_floors.created_at) as created_at')
            )
            ->whereIn('prt_floors.wing_id', $request->wing_id)
            ->orderBy('prt_floors.floor_number', 'asc')
            ->groupBy('prt_floors.floor_number')
            ->get();

			$view = view('vendors.property.manage_status.floor-details',compact('floors','wings'))->render();
			return response()->json(['status'=>'success','view'=>$view]);
		}

    public function getAreas(Request $request)
    {
        $areas = Area::where('city_id',$request->city_id)->get();

        return Response::json(['areas' => $areas], 200);
    }

    public function convertedCustomer()
    {
        $vendorId = Auth::guard('vendor')->user()->id;
        $prtStatus = PrtFloorStatus::with('customer','property','wing','floors','Units','property.propertyContent')->whereNotNull('customer_id')
                  ->whereHas('customer', function ($query) use ($vendorId) {
                      $query->where('vendor_id', $vendorId);
                  })->get();

        return view('vendors.property.converted_customer', compact('prtStatus'));
    }

}
