<?php
	
	namespace App\Http\Controllers\BackEnd\Property;
	
	use App\Helpers\Helper;
	use App\Http\Controllers\Controller;
	use App\Http\Helpers\UploadFile;
	use App\Http\Helpers\VendorPermissionHelper;
	use App\Http\Requests\Property\PropertyStoreRequest;
	use App\Http\Requests\Property\PropertyUpdateRequest;
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
	use App\Models\Property\PropertyContact;
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
	use Auth;
	use Carbon\Carbon;
	use DB;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Log;
	use Illuminate\Support\Facades\Session;
	use Purifier;
	use Response;
	use Validator;
	use App\Exports\PropertiesExport;
	use Maatwebsite\Excel\Facades\Excel;
	
	class PropertyController extends Controller
	{
		protected $routeName;
		
		public function __construct(Request $request)
		{
			$this->routeName = $request->route()->getName();
		}
		
		public function getAgent(Request $request)
		{
			$agents = Agent::where('vendor_id', $request->vendor_id)->where('status', 1)->get();
			if (!empty($agents)) {
				return Response::json(['agents' => $agents], 200);
				} else {
				return Response::json('error',   404);
			}
		}
		public function type(Request $request)
		{
			$data['commertialCount'] = Property::where('type', 'commercial')->count();
			$data['residentialCount'] = Property::where('type', 'residential')->count();
			$data['industrialCount'] = Property::where('type', 'industrial')->count();
			if ($this->routeName === 'admin.property_inventory.type') {
				$data['url'] = 'admin.property_inventory.create_property';
				} else {
				$data['url'] = 'admin.property_management.create_property';
			}
			
			return view('backend.property.type', $data);
		}
		
		public function settings()
		{
			$content = Basic::select('property_country_status', 'property_state_status')->first();
			return view('backend.property.settings', compact('content'));
		}
		public function propertSettings()
		{
			$content = Basic::select('property_approval_status')->first();
			return view('backend.property.property-settings', compact('content'));
		}
		
		//update_setting
		public function update_settings(Request $request)
		{
			$status = Basic::first();
			$status->property_country_status = $request->property_country_status ?? $status->property_country_status;
			$status->property_state_status = $request->property_state_status ?? $status->property_state_status;
			$status->property_approval_status = $request->property_approval_status ?? $status->property_approval_status;
			$status->save();
			Session::flash('success', 'Property Settings Updated Successfully!');
			return back();
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
			if ($this->routeName === 'admin.property_inventory.properties') {
				Property::where('property_type','full')->where('is_new', '0')->update(['is_new' => '1']);
				}else {
				Property::where('property_type','partial')->where('is_new', '0')->update(['is_new' => '1']);
			}
			
			$language_id = $language->id;
			$vendor_id = $title = $purpose = $category = $city = null;
			if (request()->filled('vendor_id')) {
				$vendor_id = $request->vendor_id;
			}
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
			
			$query = Property::join('property_contents', 'properties.id', 'property_contents.property_id')
            ->leftJoin('property_category_contents', 'properties.category_id', '=', 'property_category_contents.category_id')
            ->leftJoin('property_city_contents', 'properties.city_id', '=', 'property_city_contents.city_id')
            ->with([
			'propertyContents' => function ($q) use ($language_id) {
				$q->where('language_id', $language_id);
			},
			'vendor',
			'cityContent' => function ($q) use ($language) {
				$q->where('language_id', $language->id);
			}
            ]);
			
			// ✅ Only for inventory route
			if ($this->routeName === 'admin.property_inventory.properties') {
				$query->where('property_type', 'full');
				} else {
				$query->where('property_type', 'partial');
			}
			
			// ✅ Vendor filter
			$query->when($vendor_id, function ($query) use ($vendor_id) {
				if ($vendor_id === 'admin') {
					return $query->where('properties.vendor_id', '0');
					} else {
					return $query->where('properties.vendor_id', $vendor_id);
				}
			});
			
			// ✅ Title filter
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
			
			if ($request->has('export') && $request->export == '1') {
				$properties = $query->where('property_contents.language_id', $language_id)->select('properties.*')->orderBy('properties.id', 'desc')->get();
				return Excel::download(new PropertiesExport($properties), 'properties.xlsx');
			}
			
			$data['properties'] = $query
            ->where('property_contents.language_id', $language_id)
            ->select('properties.*')
            ->orderBy('properties.id', 'desc')
            ->paginate(10);
			
			
			$data['vendors'] = Vendor::where('id', '!=', 0)->get();
			
			$data['featurePricing'] =  FeaturedPricing::where('status', 1)->get();
			$data['onlineGateways'] = OnlineGateway::query()->where('status', '=', 1)->get();
			$data['offlineGateways'] = OfflineGateway::query()->where('status', '=', 1)->orderBy('serial_number', 'asc')->get();
			$data['categotyConetnt'] = PropertyCategoryContent::orderBy('id', 'asc')->get();
			$data['cities'] = CityContent::orderBy('id', 'asc')->get();
			
			$data['url'] = $this->routeName == 'admin.property_inventory.properties' ? 'admin.property_inventory.edit' : 'admin.property_management.edit';
			$data['search_url'] = $this->routeName == 'admin.property_inventory.properties' ? 'admin.property_inventory.properties' : 'admin.property_management.properties';
			
			return view('backend.property.index', $data);
		}
		public function create(Request $request)
		{
			$information = [];
			$language = Language::where('is_default', 1)->first();
			$languages = Language::get();
			$information['languages'] = $languages;
			$information['vendors'] = Vendor::where('id', '!=', 0)->where('status', 1)->get();
			$information['propertyCategories'] = PropertyCategory::where([['type', $request->type], ['status', 1]])->with(['categoryContent' => function ($q) use ($language) {
				$q->where('language_id', $language->id);
			}])->get();
			$information['propertyCountries'] = Country::with(['countryContent' => function ($q) use ($language) {
				$q->where('language_id', $language->id);
			}])->get();
			$information['amenities'] = Amenity::with(['amenityContent' => function ($q) use ($language) {
				$q->where('language_id', $language->id);
			}])->where('status', 1)->get();
			
			$information['propertySettings'] = Basic::select('property_state_status', 'property_country_status')->first();
			$information['states'] = State::with(['stateContent' => function ($q) use ($language) {
				$q->where('language_id', $language->id);
			}])->get();
			$information['cities'] = City::where('status', 1)->with(['cityContent' => function ($q) use ($language) {
				$q->where('language_id', $language->id);
			}])->get();
			$information['areas'] = Area::where('status', 1)->get();
			
			if($this->routeName == 'admin.property_inventory.create_property') {
				$information['url'] = 'admin.property_inventory.store_property';
				} else {
				$information['url'] = 'admin.property_management.store_property';
			}
			$information['unitTypes'] = Unit::get();
			return view('backend.property.create', $information);
		}
		
		public function updateFeatured(Request $request)
		{
			/* $property = FeaturedProperty::findOrFail($request->requestId);
			$property->delete();
			Session::flash('success', 'Property remove from featured successfully!');
			// if ($request->status == 1) {
			//     $property->update(['status' => 1]);
			//     Session::flash('success', 'Property featured successfully!');
			// } else {
			//     $property->update(['status' => 0]);
			//     Session::flash('success', 'Property remove from featured successfully!');
			// }
			
			return redirect()->back(); */
			
			$validated = $request->validate([
				'property_id' => 'required|integer',
				'field' => 'required|string|in:is_featured,is_recommended,is_hot,is_fast_selling',
				'status' => 'required|in:0,1',
			]);

			$property = Property::findOrFail($validated['property_id']);
			$property->{$validated['field']} = $validated['status'];
			$property->save();

			return response()->json([
				'status' => 'success',
				'message' => ucfirst($validated['field']).' status updated successfully!',
			]);
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
			// dd($validator);
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
		public function videoImgrmv(Request $request)
		{
			$pi = Property::select('video_image', 'id')->findOrFail($request->fileid);
			
			if (!empty($pi->video_image)) {
				@unlink(public_path('assets/img/property/video/') . $pi->video_image);
				$pi->video_image = null;
				$pi->save();
				return 'success';
				} else {
				return 'false';
			}
		}
		
		public function floorImgrmv(Request $request)
		{
			$pi = Property::select('floor_planning_image', 'id')->findOrFail($request->fileid);
			
			if (!empty($pi->floor_planning_image)) {
				@unlink(public_path('assets/img/property/plannings/') . $pi->floor_planning_image);
				$pi->floor_planning_image = null;
				$pi->save();
				return 'success';
				} else {
				return 'false';
			}
		}
		public function store(PropertyStoreRequest $request)
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
				
				$property = Property::create([
					'property_type' => $this->routeName == 'admin.property_inventory.store_property' ? 'full' : 'partial',
					'vendor_id' => $request->vendor_id ?? 0,
					'agent_id' => $request->agent_id,
					'category_id' => $request->category_id,
					'country_id' => $request->country_id,
					'area_id' => $request->area_id,
					'state_id' => $request->state_id,
					'city_id' => $request->city_id,
					'featured_image' => $featuredImgName,
					'floor_planning_image' => $floorPlanningImage,
					'video_image' => $videoImage,
					'price' => $request->price,
					'purpose' => $request->purpose,
					'type' => $request->type,
					'address' => $request->address,
					'notes' => $request->notes ?? '',
					'beds' => $request->beds ?? 0,
					'bath' => $request->bath ?? 0,
					'area' => $request->area ?? 0,
					'video_url' => $request->video_url,
					'status' => $request->status,
					'latitude' => $request->latitude,
					'longitude' => $request->longitude,
					'approve_status' => 1,
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
					$propertyContent->address = $request->address;
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
				
				Session::flash('success', 'Property Inactive  successfully!');
			}
			
			return redirect()->back();
		}
		
		public function approveStatus(Request $request)
		{
			$property = Property::findOrFail($request->property);
			
			if ($request->approve_status == 1) {
				$property->update(['approve_status' => 1]);
				
				Session::flash('success', 'Property Approved Successfully!');
				} else {
				$property->update(['approve_status' => 2]);
				
				Session::flash('success', 'Property Reject Successfully!');
			}
			
			return redirect()->back();
		}
		public function edit($id)
		{
			$property = Property::with('galleryImages')->findOrFail($id);
			$information['property'] = $property;
			$information['galleryImages'] = $property->galleryImages;
			$language = Language::where('is_default', 1)->first();
			$information['languages'] = Language::all();
			$information['vendors'] = Vendor::with('agents')->where('status', 1)->get();
			$information['propertyAmenities'] = PropertyAmenity::where('property_id', $property->id)->get();
			$information['propertyUnities'] = PropertyUnit::where('property_id', $property->id)->get();
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
			
			$information['propertyAreas'] = Area::where('city_id', $property->city_id)->get();
			$information['allAreas'] = Area::get();
			$information['propertySettings'] = Basic::select('property_state_status', 'property_country_status')->first();
			$information['specifications'] = Spacification::where('property_id', $property->id)->get();
			
			if ($property->vendor_id != 0) {
				$package = VendorPermissionHelper::currentPackagePermission($property->vendor_id);
				$uploadGImg = $package->number_of_property_gallery_images -  count($information['galleryImages']);
				} else {
				$uploadGImg = 999999;
			}
			$information['uploadGImg'] = $uploadGImg;
			$information['url'] = $this->routeName == 'admin.property_inventory.properties' ? 'admin.property_inventory.update_property' : 'admin.property_management.update_property';
			$information['unitTypes']	= Unit::Where('status',1)->get();
			return view('backend.property.edit', $information);
		}
		
		public function update(PropertyUpdateRequest $request, $id)
		{
			
			DB::transaction(function () use ($request, $id) {
				$languages = Language::all();
				
				$property = Property::findOrFail($request->property_id);
				
				$featuredImgName = $property->featured_image;
				$floorPlanningImage = $property->floor_planning_image;
				$videoImage = $property->video_image;
				if ($request->hasFile('featured_image')) {
					$featuredImgName = UploadFile::update(public_path('assets/img/property/featureds/'), $request->featured_image, $property->featured_image);
				}
				if ($request->hasFile('floor_planning_image')) {
					$floorPlanningImage = UploadFile::update(public_path('assets/img/property/plannings/'), $request->floor_planning_image, $property->floor_planning_image);
				}
				if ($request->hasFile('video_image')) {
					$videoImage = UploadFile::update(public_path('assets/img/property/video/'), $request->video_image, $property->video_image);
				}
				$property->update([
                'vendor_id' => $request->vendor_id ?? 0,
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
				'address' => $request->address,
                'type' => $request->type,
				'notes' => $request->notes ?? '',
				'beds' => $request->beds ?? 0,
				'bath' => $request->bath ?? 0,
				'area' => $request->area ?? 0,
                'video_url' => $request->video_url,
                'status' => $request->status,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'furnishing' => $request->furnishing,
                'possession_date' => $request->possession_date
				]);
				// if ($request->has('amenities')) {
				//     $property->proertyAmenities()->delete();
				//     foreach ($request->amenities as $amenity) {
				//         PropertyAmenity::create([
				//             'property_id' => $property->id,
				//             'amenity_id' => $amenity
				//         ]);
				//     }
				// }
				$property->amenities()->sync($request->input('amenities', []));
				$property->updateunits()->sync($request->input('unit_type', []));
				// if ($request->has('unit_type')) {
				//     $property->proertyUnits()->delete();
				//     foreach ($request->unit_type as $unit) {
				//         PropertyUnit::create([
				//             'property_id' => $property->id,
				//             'unit_id' => $unit
				//         ]);
				//     }
				// }
				
				$d_property_specifications = Spacification::where('property_id', $request->property_id)->get();
				foreach ($d_property_specifications as $d_property_specification) {
					$d_property_specification_contents = SpacificationCotent::where('property_spacification_id', $d_property_specification->id)->get();
					foreach ($d_property_specification_contents as $d_property_specification_content) {
						$d_property_specification_content->delete();
					}
					$d_property_specification->delete();
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
					
					$propertyContent->address = $request->address;
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
			});
			Session::flash('success', 'Property Updated successfully!');
			
			return Response::json(['status' => 'success'], 200);
		}
		
		
		public function featuredPayment(Request $request)
		{
			
			// $featuredPricing = FeaturedPricing::findOrFail($request->featured_pricing_id);
			// $request['amount'] = $featuredPricing->price;
			// $request['number_of_days'] = $featuredPricing->number_of_days;
			// $request['gateway'] == 'flutterwave';
			// $bs = Basic::select('timezone')->first();
			// $property = Property::findOrFail($request->property_id);
			
			FeaturedProperty::create([
            // 'featured_pricing_id' => $featuredPricing->id,
            'property_id' =>  $request->property_id,
            'status' => 1,
            // 'vendor_id' =>  $property->vendor_id,
            // 'number_of_days' => $featuredPricing->number_of_days,
            // 'amount' => $featuredPricing->price,
            // 'transaction_id' => VendorPermissionHelper::uniqidReal(8),
            // 'transaction_details' => 'from admin',
            // 'payment_method' => 'from admin',
            // 'gateway_type' => $request->gateway,
            // 'payment_status' => 'complete',
            // 'attachment' => $request->attachmen ?? null,
            // 'start_date' => Carbon::now()->timezone($bs->timezone)->format('Y-m-d H:i:s'),
            // 'end_date' => Carbon::now()->timezone($bs->timezone)->addDays($featuredPricing->number_of_days)->format('Y-m-d H:i:s'),
			]);
			
			Session::flash('success', 'Porperty featured sucessfully.');
			return redirect()->back();
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
			$property->proertyAmenities()->delete();
			$property->proertyUnits()->delete();
			$propertySliderImages  = $property->galleryImages()->get();
			foreach ($propertySliderImages  as  $image) {
				
				@unlink(public_path('assets/img/property/slider-images/' . $image->image));
				$image->delete();
			}
			
			
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
			
			PrtFloor::where('property_id',$id)->delete();
			PrtFloorStatus::where('property_id',$id)->delete();
			PrtUnit::where('property_id',$id)->delete();
			PrtWing::where('property_id',$id)->delete();
			
			$property->delete(); 
			return;
		}
		
		public function bulkDelete(Request $request)
		{
			$propertyIds = $request->ids;
		 
			if (empty($propertyIds) || !is_array($propertyIds)) {
				return response()->json([
				'status' => 'error',
				'message' => 'No property IDs provided.'
				]);
			} 
			
			DB::beginTransaction(); 
			try
			{
				foreach ($propertyIds as $id) {
					$this->deleteProperty($id); // Assuming this safely deletes the property and its relations
				}
				DB::commit();
				return response()->json([
					'status' => 'success',
					'message' => 'Properties deleted successfully!'
				]); 
			} 
			catch (\Exception $e)
			{ 
				DB::rollBack();
				return response()->json([
				'status' => 'error',
				'message' => 'Something went wrong while deleting properties.'.$e->getMessage()
				]);
			}
		}
		
		public function view($id)
		{
			// $view = view('backend.property.view',compact('id'))->render();
			return view('backend.property.view',compact('id'));
		}
		
		public function propertyFullWings($property_id)
		{
			$wings = PrtWing::where('property_id',$property_id)->get();
			$countmore = (count($wings) > 0)?(count($wings)+1):2;
			$view = view('backend.property.full_property_wings',compact('property_id','wings','countmore'))->render();
			return response()->json(['status'=>'success','view'=>$view]);
		}
		
		public function propertyFullWingsStore(Request $request)
		{
			
			$user_id = auth()->user()->id;
			
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
				$view = view('backend.property.full_property.floors',compact('property_id','wing_id','wings','wing_number'))->render();
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
			$bz_id = auth()->user()->bz_id;
			$user_id = auth()->user()->id;
			
			
			$property_id = $request->property_id;
			$wings = $request->wings;
			$wing_number = $request->wing_number;
			$wing_id = $request->wing_id;
			
			$view = view('backend.property.full_property.floors',compact('property_id','wing_id','wings','wing_number'))->render();
			
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
			
			$bz_id = auth()->user()->bz_id;
			$user_id = auth()->user()->id;
			
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
				$view = view('backend.property.full_property.property-details',compact('property_id','wing_id','floor_id','floors','floor_number','budgets','categories','flatcount','partailsDetails','units'))->render();
				
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
				$bz_id = auth()->user()->bz_id;
				$user_id = auth()->user()->id;
				
				
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
				
				$view = view('backend.property.full_property.property-details',compact('property_id','wing_id','floor_id','floors','floor_number','units','budgets','categories','flatcount','partailsDetails'))->render();
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
		
		public function unitTypeAdd()
		{
			$property_types = Unit::whereStatus(1)->get();
			$view = view('backend.property.unit-type.add',compact('property_types'))->render();
			return response()->json(['status'=>'success','view'=>$view]);
		}
		
		public function unitTypeStore(Request $request)
		{
			$user = Auth::user();
			
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
			$data['added_id'] = $user->id;
			
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
		
		public function propertyFloorStatusChanges(Request $request)
		{
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
			$customers = Customer::get();
			
			$view = view('backend.property.floor-status-change',compact('property','wings','floors','property_id','wing_id','floor_id','floor_details','unit_id','property_status','customers'))->render();
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
			]);
			
			return response()->json([
			'success' => true,
			'customer' => $customer
			]);
		}
		
		public function manageStatus(Request $request)
		{
			
			$properties = Property::with('propertyContent')->where('property_type', 'full')
			->where('approve_status', 1)
			->where('status', 1)
			->get();
			
			return view('backend.property.manage_status',compact('properties'));
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
			
			$view = view('backend.property.manage_status.floor-details',compact('floors','wings'))->render();
			return response()->json(['status'=>'success','view'=>$view]);
		}
		
		public function convertedCustomer()
		{
			$prtStatus = PrtFloorStatus::with('customer','property','wing','floors','Units','property.propertyContent')->whereNotNull('customer_id')->get();
			
			return view('backend.property.converted_customer', compact('prtStatus'));
		}
		
	}
