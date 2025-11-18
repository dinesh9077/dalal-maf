<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Agent;
use App\Models\Amenity;
use App\Models\AmenityContent;
use App\Models\HomePage\Section;
use App\Models\HomePage\PropertySection;
use App\Models\CounterSection;
use App\Models\Prominence\FeatureSection;
use App\Models\HomePage\CategorySection;
use App\Models\HomePage\CitySection;
use App\Models\BasicSettings\Basic;
use App\Models\Property\Area;
use App\Models\Property\City;
use App\Models\Property\CityContent;
use App\Models\Property\Content;
use App\Models\Property\Country;
use App\Models\Property\CountryContent;
use App\Models\Property\Property;
use App\Models\Property\PropertyAmenity;
use App\Models\Property\PropertyCategory;
use App\Models\Property\PropertyCategoryContent;
use App\Models\Property\PropertyContact;
use App\Models\Property\State;
use App\Models\Property\StateContent;
use App\Models\Vendor;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Config;
use Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Calculation\Category;
use View;

class PropertyController extends Controller
{
    public function index(Request $request)
    { 
        $misc = new MiscellaneousController();
        $language = $misc->getLanguage();
        $information['seoInfo'] = $language->seoInfo()->select('meta_keyword_properties', 'meta_description_properties')->first();

        $information['categories'] = PropertyCategory::with(['categoryContent' => function ($q) use ($language) {
            $q->where('language_id', $language->id);
        }, 'properties'])
        ->where('status', 1)->get();

        // if ($request->has('type')) 
        // {

        //     $information['categories'] = PropertyCategory::with(['categoryContent' => function ($q) use ($language) {
        //         $q->where('language_id', $language->id);
        //     }, 'properties'])
        //     ->where([['status', 1]])
        //     ->whereIn('type', $request->type)->get(); 
        // } else {
        //     $information['categories'] = PropertyCategory::with(['categoryContent' => function ($q) use ($language) {
        //         $q->where('language_id', $language->id);
        //     }, 'properties'])
        //     ->where('status', 1)->get();
        // }
 
        $information['bgImg'] = $misc->getBreadcrumb();
        $information['pageHeading'] = $misc->getPageHeading($language);
        $information['amenities'] = Amenity::where('status', 1)->with(['amenityContent' => function ($q) use ($language) {
            $q->where('language_id', $language->id);
        }])->orderBy('serial_number')->get();

        // Categories: request 'category' is an array of slugs -> fetch category_ids in one query
        $categoryIds = [];
        if ($request->filled('category')) {
            $slugs = array_filter((array) $request->input('category', []));   // e.g. ['residential','villa']
            if (!empty($slugs)) {
                $categoryIds = PropertyCategoryContent::query()
                    ->where('language_id', $language->id)
                    ->whereIn('slug', $slugs)
                    ->pluck('category_id')
                    ->filter()
                    ->unique()
                    ->values()
                    ->all();
            }
        }
         
        // Amenities: request 'amenities' is an array of names -> fetch amenity_ids in one query
        $amenityIds = [];
        if ($request->filled('amenities')) {
            $names = array_filter((array) $request->input('amenities', []));  // e.g. ['Gym','Pool']
            if (!empty($names)) {
                $amenityIds = AmenityContent::query()
                    ->where('language_id', $language->id)
                    ->whereIn('name', $names)
                    ->pluck('amenity_id')
                    ->filter()
                    ->unique()
                    ->values()
                    ->all();
            }
        }
 
        $type = [];
        if ($request->filled('type')) {
            $type = $request->type ?? [];
        }

        $unitTypes = [];
        if ($request->filled('unit_type')) {
            $unitTypes = $request->unit_type ?? [];
        }

        $price = null;
        if ($request->filled('price') && $request->price != 'all') {
            $price = $request->price;
        }

        $purpose = ['rent', 'sell', 'buy', 'lease'];
        if ($request->filled('purpose') && $request->purpose == 'franchiese') {
            $purpose = ['franchiese'];
        } 
		if ($request->filled('purpose') && $request->purpose == 'business_for_sale') {
            $purpose = ['business_for_sale'];
        } 
        if ($request->filled('purpose') && $request->purpose == 'buy') {
            $purpose = ['sell', 'buy'];
        }

        $min = $max = null;
        if ($request->filled('min') && $request->filled('max')) {
            $min = intval($request->min);
            $max = intval(($request->max));
        }

        $title = $location = $beds = $baths = $area = $countryId = $stateId = $cityId = $listAreaId = null;
        if ($request->filled('country') && $request->filled('country') && $request->country != 'all') {

            $country = CountryContent::where([['name', $request->country], ['language_id', $language->id]])->first();
            if ($country) {
                $countryId = $country->country_id;
            }
        }
        if ($request->filled('state') && $request->filled('state') && $request->state != 'all') {

            $state = StateContent::where([['name', $request->state], ['language_id', $language->id]])->first();
            if ($state) {
                $stateId = $state->state_id;
            }
        }
        if ($request->filled('city') && $request->filled('city') && $request->city != 'all') {

            $city = CityContent::where([['name', $request->city], ['language_id', $language->id]])->first();
            if ($city) {
                $cityId = $city->city_id;
            }
        }
        if ($request->filled('title') && $request->filled('title')) {
            $title =  $request->title;
        }

        if ($request->filled('location') && $request->filled('location')) {
            $location =  $request->location;
        }
        if ($request->filled('beds') && $request->filled('beds')) {
            $beds =  $request->beds;
        }
        if ($request->filled('baths') && $request->filled('baths')) {
            $baths =  $request->baths;
        }
        if ($request->filled('area') && $request->filled('area')) {
            $area =  $request->area;
        }
        if ($request->filled('listArea') && $request->filled('listArea') && $request->listArea != 'all') {
            $getArea = Area::where('name', $request->listArea)->first();
            $listAreaId =  $getArea->id;
        }

        if ($request->filled('sort')) 
        {
            if ($request['sort'] == 'new') {
                $order_by_column = 'properties.id';
                $order = 'desc';
            } elseif ($request['sort'] == 'old') {
                $order_by_column = 'properties.id';
                $order = 'asc';
            } elseif ($request['sort'] == 'high-to-low') {
                $order_by_column = 'properties.price';
                $order = 'desc';
            } elseif ($request['sort'] == 'low-to-high') {
                $order_by_column = 'properties.price';
                $order = 'asc';
            } else {
                $order_by_column = 'properties.id';
                $order = 'desc';
            }
        } else {
            $order_by_column = 'properties.id';
            $order = 'desc';
        }

        $property_contents = Property::where('property_type','partial')->where([['properties.status', 1], ['properties.approve_status', 1]])
            ->join('property_contents', 'properties.id', 'property_contents.property_id')
            ->join('property_categories', 'property_categories.id', 'properties.category_id')
            ->where('property_contents.language_id', $language->id)
            ->leftJoin('vendors', 'properties.vendor_id', '=', 'vendors.id')
            ->leftJoin('areas', 'properties.area_id', '=', 'areas.id')
            ->leftJoin('property_city_contents', function ($join) use ($language) {
                $join->on('properties.city_id', '=', 'property_city_contents.city_id')
                    ->where('property_city_contents.language_id', $language->id);
            })
            ->leftJoin('property_state_contents', function ($join) use ($language) {
                $join->on('properties.state_id', '=', 'property_state_contents.state_id')
                    ->where('property_state_contents.language_id', $language->id);
            })
            ->leftJoin('property_country_contents', function ($join) use ($language) {
                $join->on('properties.country_id', '=', 'property_country_contents.country_id')
                    ->where('property_country_contents.language_id', $language->id);
            })
            ->leftJoin('memberships', function ($join) {
                $join->on('properties.vendor_id', '=', 'memberships.vendor_id')
                    ->where('memberships.status', '=', 1)
                    ->where('memberships.start_date', '<=', Carbon::now()->format('Y-m-d'))
                    ->where('memberships.expire_date', '>=', Carbon::now()->format('Y-m-d'));
            })

            ->where(function ($query) {
                $query->where('properties.vendor_id', '=', 0)
                    ->orWhere(function ($query) {
                        $query->where('vendors.status', '=', 1)->whereNotNull('memberships.id');
                    });
            })

            ->when($type, function ($query) use ($type) {
                return $query->whereIn('properties.type', $type);
            })
            ->when($purpose, function ($query) use ($purpose) 
			{
                return $query->whereIn('properties.purpose', $purpose);
            })
            ->when($countryId, function ($query) use ($countryId) {
                return $query->where('properties.country_id', $countryId);
            })
            ->when($stateId, function ($query) use ($stateId) {
                return $query->where('properties.state_id', $stateId);
            })
            ->when($cityId, function ($query) use ($cityId) {
                return $query->where('properties.city_id', $cityId);
            })
            ->when($listAreaId, function ($query) use ($listAreaId) {
                return $query->where('properties.area_id', $listAreaId);
            }) 
            ->when(!empty($categoryIds), function ($query) use ($categoryIds) {
                return $query->whereIn('properties.category_id', $categoryIds);
            })
            ->when(!empty($amenityIds), function ($query) use ($amenityIds) {
                $query->whereHas(
                    'proertyAmenities',
                    function ($q) use ($amenityIds) {
                        $q->whereIn('amenity_id', $amenityIds);
                    },
                    '=',
                    count($amenityIds)
                );
            })
           ->when(!empty($unitTypes), function ($query) use ($unitTypes) {
                $unitTypes = array_values(array_unique($unitTypes)); 
                $query->whereHas('proertyUnits', fn($q) => $q->whereIn('unit_id', $unitTypes));
            })
            ->when($price, function ($query) use ($price) {
                if ($price == 'negotiable') {
                    return $query->where('properties.price', null);
                } elseif ($price == 'fixed') {

                    return $query->where('properties.price', '!=', null);
                } else {
                    return $query;
                }
            })

            ->when($min, function ($query) use ($min, $max, $price) {
                if ($price == 'fixed' || empty($price)) {
                    return $query->where('properties.price', '>=', $min)
                        ->where('properties.price', '<=', $max);
                } else {
                    return $query;
                }
            })
            ->when($beds, function ($query) use ($beds) {
                return $query->where('properties.beds', $beds);
            })
            ->when($baths, function ($query) use ($baths) {
                return $query->where('properties.bath', $baths);
            })
            ->when($area, function ($query) use ($area) {
                return $query->where('properties.area', $area);
            })
            ->when($title, function ($query) use ($title) {
                return $query->where('property_contents.title', 'LIKE', '%' . $title . '%');
            })
            ->when($location, function ($query) use ($location) {
                // Split by comma
                $parts = array_map('trim', explode(',', $location));

                $areaName   = $parts[0] ?? null; // e.g. "Pasodara Patiya" or "Surat"
                $cityName   = $parts[1] ?? null; // e.g. "Surat"
                $stateName  = $parts[2] ?? null; // e.g. "Gujarat"
                $countryName= $parts[3] ?? null;

                return $query->where(function ($q) use ($areaName, $cityName, $stateName, $countryName) {
                    if ($areaName && $cityName) {
                        // user searched Area + City → restrict by area only
                        $q->where('areas.name', 'LIKE', "%{$areaName}%");
                    } elseif ($cityName) {
                        // only city searched
                        $q->where('property_city_contents.name', 'LIKE', "%{$cityName}%");
                    } elseif ($stateName) {
                        $q->where('property_state_contents.name', 'LIKE', "%{$stateName}%");
                    } elseif ($countryName) {
                        $q->where('property_country_contents.name', 'LIKE', "%{$countryName}%");
                    } else {
                        // fallback: search anywhere
                        $q->where('areas.name', 'LIKE', "%{$areaName}%")
                          ->orWhere('property_city_contents.name', 'LIKE', "%{$areaName}%")
                          ->orWhere('property_state_contents.name', 'LIKE', "%{$areaName}%")
                          ->orWhere('property_country_contents.name', 'LIKE', "%{$areaName}%");
                    }
                });
            })
            ->with(['categoryContent' => function ($q) use ($language) {
                $q->where('language_id', $language->id);
            }])

            ->select('properties.*', 'property_categories.id as categoryId', 'property_contents.title', 'property_contents.slug', 'property_contents.address', 'property_contents.description', 'property_contents.language_id',
                    'areas.name as area_name',
                    'property_city_contents.name as city_name',
                    'property_state_contents.name as state_name',
                    'property_country_contents.name as country_name')
            ->orderBy($order_by_column, $order)
            ->paginate(12);

        $information['property_contents'] = $property_contents;
        $information['contents'] = $property_contents;

        $information['all_areas'] = Area::where('status', 1)->get();
        $information['all_cities'] = City::where('status', 1)->with(['cityContent' => function ($q) use ($language) {
            $q->where('language_id', $language->id);
        }])->get();
        $information['all_states'] = State::with(['stateContent' => function ($q) use ($language) {
            $q->where('language_id', $language->id);
        }])->get();
        $information['all_countries'] = Country::with(['countryContent' => function ($q) use ($language) {
            $q->where('language_id', $language->id);
        }])->get();

        $information['units'] = DB::table('units')->whereStatus(1)->get();

        $min = Property::where([['status', 1], ['approve_status', 1]])->min('price');
        $max = Property::where([['status', 1], ['approve_status', 1]])->max('price');
        $information['min'] = intval($min);
        $information['max'] = intval($max);
        if ($request->ajax()) {
            $viewContent = View::make('frontend.property.property',  $information);
            $viewContent = $viewContent->render();

            return response()->json(['propertyContents' => $viewContent, 'properties' => $property_contents])->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        } 
        return view('frontend.property.index', $information);
    }

    public function loadCategoryAmenitiesTypes(Request $request)
    {
        // Read types from request; ensure it's always an array
        $types = $request->input('type', []);
        if (!is_array($types)) {
            $types = [$types];
        }

        $misc = new MiscellaneousController();
        $language = $misc->getLanguage();

        // Build categories query
        $categoriesQuery = PropertyCategory::with([
            'categoryContent' => function ($q) use ($language) {
                $q->where('language_id', $language->id);
            },
            'properties'
        ])->where('status', 1);

        if (!empty($types)) {
            $categoriesQuery->whereIn('type', $types);
        }

        $categories = $categoriesQuery->get();

        // Build amenities query
        $amenitiesQuery = Amenity::where('status', 1)
            ->with([
                'amenityContent' => function ($q) use ($language) {
                    $q->where('language_id', $language->id);
                }
            ])
            ->orderBy('serial_number');

        if (!empty($types)) {
            if (!empty($types)) {
                $amenitiesQuery->where(function ($q) use ($types) {
                    foreach ($types as $type) {
                        // orWhereJsonContains available in Laravel 8+. If not, use orWhereRaw with JSON_CONTAINS
                        $q->orWhereJsonContains('types', $type);
                    }
                });
            }
        }

        $amenities = $amenitiesQuery->get();

        // Render blade partials to HTML strings
        $categoriesHtml = view('frontend.property.categories-list', compact('categories'))->render();
        $amenitiesHtml = view('frontend.property.amenities-list', compact('amenities'))->render();

        // Return HTML (and optionally raw data)
        return response()->json([
            'status' => 'success',
            'categories_html' => $categoriesHtml,
            'amenities_html' => $amenitiesHtml,
            'categories' => $categories,   // optional / for debugging
            'amenities' => $amenities,     // optional / for debugging
        ]);
    }


    public function featuredAll($type)
    { 
        $property_contents = Property::where([['properties.status', 1], ['properties.approve_status', 1]])
		->join('property_contents', 'properties.id', 'property_contents.property_id')
		->where($type, 1)
		->get(); 
		
		$title = match ($type) {
			'is_featured' => 'Featured Properties',
			'is_hot' => 'Hot Properties',
			'is_recommended' => 'Recommended Properties',
			'is_fast_selling' => 'Fast Selling Properties',
			default => 'Properties',
		};
        return view('frontend.property.featured',compact('property_contents', 'title')); 
    }
     
    public function details($slug)
    {
		
        $misc = new MiscellaneousController();
        $language = $misc->getLanguage();
        $information['language'] = $language;

        $information['bgImg'] = $misc->getBreadcrumb();
        $information['pageHeading'] = $misc->getPageHeading($language);
        $propertyContent = Content::where('slug', $slug)->firstOrFail();
		 
        $baseQuery = Content::query()
        ->where('property_contents.language_id', $language->id)
        ->where('property_contents.property_id', $propertyContent->property_id)
        ->leftJoin('properties', 'property_contents.property_id', 'properties.id')
        ->where([['properties.status', 1]]);

        if (!auth()->guard('admin')->check()){
            $baseQuery->when('properties.vendor_id' != 0, function ($query) { 
                $query->leftJoin('memberships', 'properties.vendor_id', '=', 'memberships.vendor_id')
                ->where(function ($query) {
                    $query->where([
                        ['memberships.status', '=', 1],
                        ['memberships.start_date', '<=', now()->format('Y-m-d')],
                        ['memberships.expire_date', '>=', now()->format('Y-m-d')],
                    ])->orWhere('properties.vendor_id', '=', 0);
                });
            });

            $baseQuery->when('properties.vendor_id' != 0, function ($query) {
                return $query->leftJoin('vendors', 'properties.vendor_id', '=', 'vendors.id')
                    ->where(function ($query) {
                        $query->where('vendors.status', '=', 1)->orWhere('properties.vendor_id', '=', 0);
                    });
            });
        }

        $property = $baseQuery->with(['propertySpacifications', 'galleryImages'])
        ->select('properties.*', 'property_contents.*', 'properties.id as propertyId', 'property_contents.id as contentId')->firstOrFail();
 
        $information['propertyContent'] = $property;
        $information['sliders'] =  $property->galleryImages;
        $information['amenities'] = PropertyAmenity::with(['amenityContent' => function ($q) use ($language) {
            $q->where('language_id', $language->id);
        }])->where('property_id', $property->property_id)->get();
        $information['agent'] = Agent::with(['agent_info' => function ($q) use ($language) {
            $q->where('language_id', $language->id);
        }])->find($property->agent_id);


        $information['vendor'] = Vendor::with(['vendor_info' => function ($q) use ($language) {
            $q->where('language_id', $language->id);
        }])->find($property->vendor_id);

        $information['admin']  = Admin::where('role_id', null)->first();
 
 
        $information['relatedProperty'] = Property::where([['properties.status', 1], ['properties.approve_status', 1]])->leftJoin('property_contents', 'properties.id', 'property_contents.property_id')
            ->leftJoin('vendors', 'properties.vendor_id', '=', 'vendors.id')
            ->leftJoin('memberships', function ($join) {
                $join->on('properties.vendor_id', '=', 'memberships.vendor_id')
                    ->where('memberships.status', '=', 1)
                    ->where('memberships.start_date', '<=', Carbon::now()->format('Y-m-d'))
                    ->where('memberships.expire_date', '>=', Carbon::now()->format('Y-m-d'));
            })
            ->where(function ($query) {
                $query->where('properties.vendor_id', '=', 0)
                    ->orWhere(function ($query) {
                        $query->where([
                            ['vendors.status', '=', 1],

                        ]);
                    });
            })->where([['properties.id', '!=', $property->property_id], ['properties.category_id', $property->category_id]])
            ->where('property_contents.language_id', $language->id)->latest('properties.created_at')
            ->select('properties.*', 'property_contents.title', 'property_contents.slug', 'property_contents.address', 'property_contents.language_id')
            ->take(5)->get();
			
        $information['info'] = Basic::select('google_recaptcha_status')->first();
		
        return view('frontend.property.details', $information);
    }

    public function contact(Request $request)
    {  
        if (!Auth::guard('web')->check() && !Auth::guard('vendor')->check() && !Auth::guard('agent')->check()) {
            // if neither web nor vendor is logged in
            return redirect()->route('user.login')->with('error', 'Please login first');
        }
        $rules = [
            'name' => 'required',
            'email' => 'required|email:rfc,dns',
            'phone' => 'required|numeric',
            'message' => 'required'
        ];
        $info = Basic::select('google_recaptcha_status')->first();
        if ($info->google_recaptcha_status == 1) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        $messages = [];

        if ($info->google_recaptcha_status == 1) {
            $messages['g-recaptcha-response.required'] = 'Please verify that you are not a robot.';
            $messages['g-recaptcha-response.captcha'] = 'Captcha error! try again later or contact site admin.';
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        if ($request->vendor_id != 0) {

            if ($request->vendor_id) {
                $vendor = Vendor::find($request->vendor_id);

                if (empty($vendor)) {

                    return back()->with('error', 'Something went wrong!');
                }
                $request['to_mail'] = $vendor->email;
            }
            if ($request->agent_id) {
                $agent = Agent::find($request->agent_id);
                if (empty($agent)) {
                    return back()->with('error', 'Something went wrong!');
                }
                $request['to_mail'] = $agent->email;
            }
        } elseif ($request->vendor_id == 0 && !empty($request->agent_id)) {
            $agent = Agent::find($request->agent_id);
            if (empty($agent)) {
                return back()->with('error', 'Something went wrong!');
            }
            $request['to_mail'] = $agent->email;
        } else {

            $admin = Admin::where('role_id', null)->first();
            $request['to_mail'] = $admin->email;
        }

        try {
            PropertyContact::create([
                'vendor_id' => $request->vendor_id,
                'agent_id' => $request->agent_id,
                'property_id' => $request->property_id,
                'inquiry_by_user'   => Auth::guard('web')->check() ? Auth::guard('web')->id() : null,
                'inquiry_by_vendor' => Auth::guard('vendor')->check() ? Auth::guard('vendor')->id() : null,
                'inquiry_by_agent' => Auth::guard('agent')->check() ? Auth::guard('agent')->id() : null,
                'is_new' => '0',
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'message' => $request->message,
                'status' => 'in-progress',

            ]);
            $this->sendMail($request);
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong!');
        }



        return back()->with('success', 'Inquiry sent successfully');
    }
    public function contactUser(Request $request)
    { 
        $rules = [
            'name' => 'required',
            'email' => 'required|email:rfc,dns',
            'phone' => 'required|numeric',
            'message' => 'required'
        ];
        $info = Basic::select('google_recaptcha_status')->first();
        if ($info->google_recaptcha_status == 1) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        $messages = [];

        if ($info->google_recaptcha_status == 1) {
            $messages['g-recaptcha-response.required'] = 'Please verify that you are not a robot.';
            $messages['g-recaptcha-response.captcha'] = 'Captcha error! try again later or contact site admin.';
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        if ($request->vendor_id != 0) {

            if ($request->vendor_id) {
                $vendor = Vendor::find($request->vendor_id);

                if (empty($vendor)) {

                    return back()->with('error', 'Something went wrong!');
                }
                $request['to_mail'] = $vendor->email;
            }
        } else {
            $admin = Admin::where('role_id', null)->first();
            $request['to_mail'] = $admin->email;
        }
        if (!empty($request->agent_id)) {
            $agent = Agent::find($request->agent_id);
            if (empty($agent)) {
                return back()->with('error', 'Something went wrong!');
            }
            $request['to_mail'] = $agent->email;
        }

        try {
            $this->sendMail($request);
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong!');
        }



        return back()->with('success', 'Message sent successfully');
    }
    public function sendMail($request)
    {

        $info = DB::table('basic_settings')
            ->select('website_title', 'smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name', 'to_mail')
            ->first();
        $name = $request->name;
        $to = $request->to_mail;

        $subject = 'Contact for property';

        $message = '<p>A new message has been sent.<br/><strong>Client Name: </strong>' . $name . '<br/><strong>Client Mail: </strong>' . $request->email . '<br/><strong>Client Phone: </strong>' . $request->phone . '</p><p>Message : ' . $request->message . '</p>';

        if ($info->smtp_status == 1) {
            try {
                $smtp = [
                    'transport' => 'smtp',
                    'host' => $info->smtp_host,
                    'port' => $info->smtp_port,
                    'encryption' => $info->encryption,
                    'username' => $info->smtp_username,
                    'password' => $info->smtp_password,
                    'timeout' => null,
                    'auth_mode' => null,
                ];
                Config::set('mail.mailers.smtp', $smtp);
            } catch (\Exception $e) {
                Session::flash('error', $e->getMessage());

                return;
            }
        }
        $data = [
            'to' => $to,
            'subject' => $subject,
            'message' => $message,
        ];
        try {
            Mail::send([], [], function (Message $message) use ($data, $info) {
                $fromMail = $info->from_mail;
                $fromName = $info->from_name;
                $message->to($data['to'])
                    ->subject($data['subject'])
                    ->from($fromMail, $fromName)
                    ->html($data['message'], 'text/html');
            });
        } catch (\Exception $e) {
            Session::flash('error', 'Something went wrong.');
            return;
        }
    }

    public function getStateCities(Request $request)
    {
        $misc = new MiscellaneousController();
        $language = $misc->getLanguage();
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
        $misc = new MiscellaneousController();
        $language = $misc->getLanguage();
        $cities = City::where('state_id', $request->state_id)->with(['cityContent' => function ($q) use ($language) {
            $q->where('language_id', $language->id);
        }])->get();
        return Response::json(['cities' => $cities], 200);
    }

    public function getAreas(Request $request)
    {
        $areas = Area::where('city_id', $request->city_id)->get();
        return Response::json(['areas' => $areas], 200);
    }

    public function getCategories(Request $request)
    {

        $misc = new MiscellaneousController();
        $language = $misc->getLanguage();
        if ($request->type != 'all') {
            $categories = PropertyCategory::where([['type', $request->type], ['status', 1]])->with(['categoryContent' => function ($q) use ($language) {
                $q->where('language_id', $language->id);
            }])->get();
        } else {
            $categories = PropertyCategory::where('status', 1)->with(['categoryContent' => function ($q) use ($language) {
                $q->where('language_id', $language->id);
            }])->get();
        }

        return Response::json(['categories' => $categories], 200);
    }

    public function locationSearch(Request $request)
    {
        $term = $request->get('term');

        $results = DB::table('areas')
            ->leftJoin('property_city_contents', function ($join) {
                $join->on('areas.city_id', '=', 'property_city_contents.city_id')
                    ->where('property_city_contents.language_id', 20);
            })
            ->leftJoin('property_state_contents', function ($join) {
                $join->on('areas.state_id', '=', 'property_state_contents.state_id')
                    ->where('property_state_contents.language_id', 20);
            })
            ->leftJoin('property_country_contents', function ($join) {
                $join->on('areas.country_id', '=', 'property_country_contents.country_id')
                    ->where('property_country_contents.language_id', 20);
            })
            ->select(
                'areas.id as area_id',
                'areas.name as area_name',
                'property_city_contents.name as city_name',
                'property_state_contents.name as state_name',
                'property_country_contents.name as country_name'
            )
            ->where(function ($q) use ($term) {
                $q->where('areas.name', 'LIKE', "%{$term}%")
                  ->orWhere('property_city_contents.name', 'LIKE', "%{$term}%")
                  ->orWhere('property_state_contents.name', 'LIKE', "%{$term}%")
                  ->orWhere('property_country_contents.name', 'LIKE', "%{$term}%");
            })
            ->limit(10)
            ->get();

        $suggestions = [];
        foreach ($results as $row) {
            $suggestions[] = [
                'id'   => $row->area_name,
                'text' => trim(
                    $row->area_name . ', ' .
                    ($row->city_name ?? '') . ', ' .
                    ($row->state_name ?? '') . ', ' .
                    ($row->country_name ?? '')
                , ', ')
            ];
        }

        return response()->json($suggestions); 
    } 
    public function filters(Request $request)
	{
			$themeVersion = Basic::query()->pluck('theme_version')->first();
			
			$secInfo = Section::query()->first();
			
			$misc = new MiscellaneousController();
			
			$language = $misc->getLanguage();
			
			$queryResult['language'] = $language;
			
		  
			$all_proeprty_categories = PropertyCategory::where('status', 1)
			->with(['categoryContent' => function ($q) use ($language) {
				$q->where('language_id', $language->id);
			}])
			->withCount(['properties' => function ($query) {
				$query->where('status', 1)->where('approve_status', 1);
			}])
			->orderBy('serial_number', 'asc')
			->get();
			 
			$queryResult['all_proeprty_categories'] = $all_proeprty_categories;
			
			   
			if ($themeVersion == 1 && $secInfo->cities_section_status == 1) {
				$cities =  City::where([['status', 1], ['featured', 1]])->orderBy('serial_number', 'asc')->get();
				$cities->map(function ($city) use ($language) {
					$city['propertyCount'] = $city->cityProperties()->count();
					$city['name'] = $city->getContent($language->id)->name;
				});
				
				$queryResult['cities'] =  $cities;
			} 
			
			// --- Property min & max ---
			$propertyStats = Property::query()
				->where('status', 1)
				->where('approve_status', 1)
				->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
				->first();

			$queryResult['min'] = (int) $propertyStats->min_price;
			$queryResult['max'] = (int) $propertyStats->max_price;
 
        return view('frontend.property.filter', $queryResult);
    } 
}
