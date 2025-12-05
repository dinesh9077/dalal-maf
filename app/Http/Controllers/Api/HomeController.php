<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Models\Admin;
use App\Models\Agent;
use App\Models\Amenity;
use App\Models\AmenityContent;
use App\Models\BasicSettings\Basic;
use App\Models\CounterSection;
use App\Models\HomePage\{AboutSection, Banner, BrandSection, CategorySection, CitySection, Partner, ProjectSection, PropertySection, Section, VendorSection, WhyChooseUs};
use App\Models\Journal\{Blog, BlogCategory};
use App\Models\Package;
use App\Models\Project\Project;
use App\Models\Prominence\FeatureSection;
use App\Models\Property\{
    Area, City, CityContent, Content, Country, CountryContent,
    FeaturedProperty, Property, PropertyAmenity, PropertyCategory,
    PropertyCategoryContent, PropertyContact, State, StateContent,
	Wishlist
};
use App\Models\Project\ProjectContent;
use App\Models\User;
use App\Models\Vendor;
use App\Traits\ApiResponseTrait;
use Carbon\Carbon;
use Config, DB, Session, URL, Auth;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\{Cache, Mail, Response, Validator, View};
use PhpOffice\PhpSpreadsheet\Calculation\Category;
 
class HomeController extends Controller
{
	use ApiResponseTrait;
	
	public function index(Request $request)
	{
		$themeVersion = Basic::query()->pluck('theme_version')->first();
		
		$secInfo = Section::query()->first(); 
		$misc = new MiscellaneousController(); 
		$language = $misc->getLanguage();
		
		$queryResult['language'] = $language; 
		$queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_home', 'meta_description_home')->first();
		
		if ($secInfo->counter_section_status == 1) {
			$queryResult['counterSectionImage'] = Basic::query()->pluck('counter_section_image')->first();
			$queryResult['counterSectionInfo'] = CounterSection::where('language_id', $language->id)->first();
			$queryResult['counters'] = $language->counterInfo()->orderByDesc('id')->get();
		}
		
		$queryResult['currencyInfo'] = $this->getCurrencyInfo();
		
		$queryResult['secInfo'] = $secInfo;
		
		
		// for real estate query
		
		if ($themeVersion == 2) {
			$queryResult['sliderInfos'] = $language->sliderInfo()->orderByDesc('id')->get();
			$queryResult['packages'] = Package::where([['status', 1], ['is_featured', 1]])->get();
			$queryResult['pricingSecInfo'] = $language->pricingSection()->first();
		}
		
		if ($themeVersion != 2) {
			$queryResult['heroStatic'] = $language->heroStatic()->first();
			// $queryResult['heroImg'] = Basic::query()->pluck('hero_static_img')->first();
			$heroImg = Basic::query()->pluck('hero_static_img')->first();
			$queryResult['heroImg'] = $heroImg ? json_decode($heroImg, true) : [];
		}
		
		if ($secInfo->property_section_status == 1) {
			$queryResult['propertySecInfo'] = PropertySection::where('language_id', $language->id)->first();
		}
		
		
		if ($themeVersion != 3) {
			$queryResult['featuredSecInfo'] = FeatureSection::where('language_id', $language->id)->first();
		}
		if ($themeVersion == 2 || $themeVersion == 3) {
			$queryResult['catgorySecInfo'] = CategorySection::where('language_id', $language->id)->first();
		}
		if ($themeVersion == 1) {
			$queryResult['citySecInfo'] = CitySection::where('language_id', $language->id)->first();
		}
		
		if ($secInfo->testimonial_section_status == 1) {
			$queryResult['testimonialSecInfo'] = $language->testimonialSection()->first();
			$queryResult['testimonials'] = $language->testimonial()->orderByDesc('id')->get();
			$queryResult['testimonialSecImage'] = Basic::query()->pluck('testimonial_section_image')->first();
		}
		
		if ($themeVersion == 2 && $secInfo->call_to_action_section_status == 1) {
			$queryResult['callToActionSectionImage'] = Basic::query()->pluck('call_to_action_section_image')->first();
			$queryResult['callToActionSecInfo'] = $language->callToActionSection()->first();
		}
		
		if ($themeVersion == 1 && $secInfo->subscribe_section_status == 1) {
			$queryResult['subscribeSectionImage'] = Basic::query()->pluck('subscribe_section_img')->first();
			$queryResult['subscribeSecInfo'] = $language->subscribeSection()->first();
		}
		
		if ($secInfo->work_process_section_status == 1 && ($themeVersion == 2 || $themeVersion == 3)) {
			$queryResult['workProcessSecInfo'] = $language->workProcessSection()->first();
			$queryResult['processes'] = $language->workProcess()->orderBy('serial_number', 'asc')->get();
		}
		 
		$all_proeprty_categories = PropertyCategory::select('*', DB::raw("CONCAT('" . URL::to('/') . "/assets/img/property-category/', image) as image_url"))
		->where('status', 1)
		->with(['categoryContent' => function ($q) use ($language) {
			$q->where('language_id', $language->id);
		}])
		->withCount(['properties' => function ($query) {
			$query->where('status', 1)->where('approve_status', 1);
		}])
		->orderBy('serial_number', 'asc')
		->get();
		
		/* $allCities = City::where('status', 1)->with(['cityContent' => function ($q) use ($language) {
			$q->where('language_id', $language->id);
		}])->get();
		
		$queryResult['all_cities'] = $allCities;  */
	 
		$queryResult['all_proeprty_categories'] = $all_proeprty_categories;
		
		// Reusable base query builder for all property sections
		$baseQuery = Property::query()
			->join('property_contents', 'property_contents.property_id', '=', 'properties.id')
			->join('property_categories', 'property_categories.id', '=', 'properties.category_id')
			->leftJoin('memberships', function ($join) {
				$join->on('properties.vendor_id', '=', 'memberships.vendor_id')
					 ->where('memberships.status', 1)
					 ->whereDate('memberships.start_date', '<=', now())
					 ->whereDate('memberships.expire_date', '>=', now());
			})
			->leftJoin('vendors', function ($join) {
				$join->on('properties.vendor_id', '=', 'vendors.id')
					 ->where('vendors.status', 1);
			})
			->where([
				['properties.status', 1],
				['properties.approve_status', 1],
			])
			->where('property_contents.language_id', $language->id)
			->where(function ($query) {
				$query->whereNotNull('memberships.id')
					  ->orWhere('properties.vendor_id', 0);
			})
			->select(
				'properties.*',
				DB::raw("CONCAT('" . URL::to('/') . "/assets/img/property/featureds/', properties.featured_image) as featured_image_url"),
				'property_contents.slug',
				'property_contents.title',
				'property_contents.address',
				'property_contents.language_id'
			);

		// ------------------------------------------------------------
		// 1️⃣ Latest Properties
		// ------------------------------------------------------------
		$queryResult['properties'] = (clone $baseQuery)
			->latest('properties.created_at')
			->take(8)
			->get();

		// ------------------------------------------------------------
		// 2️⃣ Business for Sale
		// ------------------------------------------------------------
		$queryResult['business_for_sale'] = (clone $baseQuery)
			->where([
				['properties.purpose', 'business_for_sale'],
				['properties.property_type', 'partial']
			])
			->latest('properties.created_at')
			->get();

		// ------------------------------------------------------------
		// 3️⃣ Franchise
		// ------------------------------------------------------------
		$queryResult['franchiese'] = (clone $baseQuery)
			->where([
				['properties.purpose', 'franchiese'],
				['properties.property_type', 'partial']
			])
			->latest('properties.created_at')
			->get();

		// ------------------------------------------------------------
		// 4️⃣ Featured Properties
		// ------------------------------------------------------------
		$queryResult['featured_properties'] = (clone $baseQuery) 
			->where('properties.is_featured', 1)
			->select(
				'properties.*', 
				DB::raw("CONCAT('" . URL::to('/') . "/assets/img/property/featureds/', properties.featured_image) as featured_image_url"),
				'property_contents.slug',
				'property_contents.title',
				'property_contents.address',
				'property_contents.language_id'
			)
			->inRandomOrder()
			->take(10)
			->get();
			
		$queryResult['hotProperties'] = (clone $baseQuery) 
			->where('properties.is_hot', 1)
			->select(
				'properties.*',
				DB::raw("CONCAT('" . URL::to('/') . "/assets/img/property/featureds/', properties.featured_image) as featured_image_url"),
				'property_contents.slug',
				'property_contents.title',
				'property_contents.address',
				'property_contents.language_id'
			)
			->inRandomOrder()
			->take(10)
			->get();
			
		$queryResult['fastSellingProperties'] = (clone $baseQuery) 
			->where('properties.is_fast_selling', 1)
			->select(
				'properties.*', 
				DB::raw("CONCAT('" . URL::to('/') . "/assets/img/property/featureds/', properties.featured_image) as featured_image_url"),
				'property_contents.slug',
				'property_contents.title',
				'property_contents.address',
				'property_contents.language_id'
			)
			->inRandomOrder()
			->take(10)
			->get();
			
		$queryResult['recommendedProperties'] = (clone $baseQuery) 
			->where('properties.is_recommended', 1)
			->select(
				'properties.*', 
				DB::raw("CONCAT('" . URL::to('/') . "/assets/img/property/featureds/', properties.featured_image) as featured_image_url"),
				'property_contents.slug',
				'property_contents.title',
				'property_contents.address',
				'property_contents.language_id'
			)
			->inRandomOrder()
			->take(10)
			->get();

		
		if ($themeVersion == 1 && $secInfo->project_section_status == 1) {

			// --- Base query for projects ---
			$projectBaseQuery = Project::query()
				->join('project_contents', 'project_contents.project_id', '=', 'projects.id')
				->leftJoin('memberships', function ($join) {
					$join->on('projects.vendor_id', '=', 'memberships.vendor_id')
						->where('memberships.status', 1)
						->whereDate('memberships.start_date', '<=', now())
						->whereDate('memberships.expire_date', '>=', now());
				})
				->leftJoin('vendors', function ($join) {
					$join->on('projects.vendor_id', '=', 'vendors.id')
						->where('vendors.status', 1);
				})
				->where([
					['projects.approve_status', 1],
					['projects.featured', 1],
					['project_contents.language_id', $language->id],
				])
				->where(function ($query) {
					$query->whereNotNull('memberships.id')
						->orWhere('projects.vendor_id', 0);
				})
				->where(function ($query) {
					$query->whereNotNull('vendors.id')
						->orWhere('projects.vendor_id', 0);
				})
				->select(
					'projects.*',
					DB::raw("CONCAT('" . URL::to('/') . "/assets/img/project/featured/', projects.featured_image) as featured_image_url"),
					'project_contents.slug',
					'project_contents.title',
					'project_contents.address'
				)
				->inRandomOrder()
				->latest('projects.created_at')
				->take(8);

			$queryResult['projects'] = $projectBaseQuery->get();

			// --- Project section info (static, cached once) ---
			$queryResult['projectInfo'] = ProjectSection::where('language_id', $language->id)
				->first();

			// --- Blog section ---
			$queryResult['blogs'] = Blog::query()
				->join('blog_informations', 'blogs.id', '=', 'blog_informations.blog_id')
				->join('blog_categories', 'blog_categories.id', '=', 'blog_informations.blog_category_id')
				->where([
					['blogs.status', 1],
					['blog_informations.language_id', $language->id],
				])
				->select(
					'blogs.image',
				    DB::raw("CONCAT('" . URL::to('/') . "/assets/img/blogs/', blogs.image) as image_url"),
					'blog_categories.name as categoryName',
					'blog_categories.slug as categorySlug',
					'blog_informations.title',
					'blog_informations.slug',
					'blog_informations.author',
					'blogs.created_at',
					'blog_informations.content'
				)
				->orderBy('blogs.serial_number', 'asc')
				->limit(3)
				->get();
		}

		
		$queryResult['aboutImg'] = Basic::query()->select('about_section_image1', 'about_section_image2', 'about_section_video_link')->first();
		$queryResult['aboutInfo'] =  AboutSection::where('language_id', $language->id)->first();
		
		if ($themeVersion == 1 && $secInfo->vendor_section_status == 1) 
		{ 
			$queryResult['vendorInfo'] =  VendorSection::where('language_id', $language->id)->first(); 
			$queryResult['vendors'] = Vendor::join('memberships', 'memberships.vendor_id', 'vendors.id')
			->where([
			['memberships.status', 1],
			['memberships.start_date', '<=', Carbon::now()->format('Y-m-d')],
			['memberships.expire_date', '>=', Carbon::now()->format('Y-m-d')],
			])->where([['vendors.status', 1], ['vendors.id', '!=', 0]])
			->with(['properties' => function ($q) {
				$q->where([['approve_status', 1], ['status', 1]]);
				}, 'projects' => function ($q) {
				$q->where('approve_status', 1);
			}, 'agents'])
			->select('vendors.*', DB::raw("CASE 
                    WHEN photo IS NOT NULL AND photo != '' 
                    THEN CONCAT('" . URL::to('/') . "/assets/admin/img/vendor-photo/', photo)
                    ELSE '" . URL::to('/') . "/assets/img/blank-user.jpg'
                 END AS photo_url"))
			->inRandomOrder()->take(5)->get();
		}
		
		if ($themeVersion == 1 && $secInfo->why_choose_us_section_status == 1) {
			$queryResult['whyChooseUsImg'] = Basic::query()->select('why_choose_us_section_img1', 'why_choose_us_section_img2', 'why_choose_us_section_video_link')->first();
			$queryResult['whyChooseUsInfo'] =  WhyChooseUs::where('language_id', $language->id)->first();
		}
		
		
		if ($themeVersion == 1 && $secInfo->cities_section_status == 1) {
			$cities =  City::where([['status', 1], ['featured', 1]])->orderBy('serial_number', 'asc')->get();
			$cities->map(function ($city) use ($language) { 
				$city['image_url'] = asset('assets/img/property-city/' . $city->image);
				$city['propertyCount'] = $city->cityProperties()->count();
				$city['name'] = $city->getContent($language->id)->name;
			});
			
			$queryResult['cities'] =  $cities;
		}
		
		if (($themeVersion == 2 || $themeVersion == 3) && $secInfo->brand_section_status == 1) {
			$queryResult['brands'] = BrandSection::get();
		}
		
		// --- Property min & max ---
		$propertyStats = Property::query()
			->where('status', 1)
			->where('approve_status', 1)
			->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
			->first();

		$queryResult['min'] = (int) $propertyStats->min_price;
		$queryResult['max'] = (int) $propertyStats->max_price;

		// --- Counters (can be cached) ---
		$queryResult['cityCount'] = Cache::remember('city_count', 600, fn() => City::count());
		$queryResult['userCount'] = Cache::remember('user_count', 600, fn() => User::count());
		$queryResult['vendorCount'] = Cache::remember('vendor_count', 600, fn() => Vendor::count());
		 
		return $this->successResponse($queryResult); 
	}
	
	public function viewAllProperties($type)
	{
		$property_contents = Property::query()
		->select('*', DB::raw("CONCAT('" . URL::to('/') . "/assets/img/property/featureds/', properties.featured_image) as featured_image_url"))
		->where([['properties.status', 1], ['properties.approve_status', 1]])
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
		return $this->successResponse(compact('title', 'property_contents'));  
	}
	
	public function viewAllPartners(Request $request)
	{
		$limit = $request->get('limit', 10); // default 10
		$offset = $request->get('offset', 0); // default 0

		$vendors = Vendor::join('memberships', 'memberships.vendor_id', '=', 'vendors.id')
			->where([
				['memberships.status', 1],
				['memberships.start_date', '<=', Carbon::now()->format('Y-m-d')],
				['memberships.expire_date', '>=', Carbon::now()->format('Y-m-d')],
			])
			->where('vendors.status', 1)
			->where('vendors.id', '!=', 0)
			->with(['properties', 'projects', 'agents'])
			->select(
				'vendors.*',
				DB::raw("CASE 
					WHEN vendors.photo IS NOT NULL AND vendors.photo != '' 
					THEN CONCAT('" . URL::to('/') . "/assets/admin/img/vendor-photo/', vendors.photo)
					ELSE '" . URL::to('/') . "/assets/img/blank-user.jpg'
				 END AS photo_url")
			)
			->orderBy('vendors.id', 'ASC')
			->offset($offset)
			->limit($limit)
			->get();

		return $this->successResponse($vendors);
	}

	public function viewAllBlogs(Request $request)
	{
		$limit = $request->get('limit', 10);  
		$offset = $request->get('offset', 0); 
		
		$blogTitle = $blogCategory = null; 
		if ($request->filled('title')) {
		  $blogTitle = $request['title'];
		}
		if ($request->filled('category')) {
		  $blogCategory = $request['category'];
		}
	
		$misc = new MiscellaneousController(); 
		$language = $misc->getLanguage(); 

		$blogs = Blog::where('blogs.status', 1)
			->join('blog_informations', 'blogs.id', '=', 'blog_informations.blog_id')
			->join('blog_categories', 'blog_categories.id', '=', 'blog_informations.blog_category_id')
			->where('blog_informations.language_id', '=', $language->id)
			->when($blogTitle, function ($query, $blogTitle) {
				return $query->where('blog_informations.title', 'like', '%' . $blogTitle . '%');
			})
			->when($blogCategory, function ($query, $blogCategory) {
				$categoryId = BlogCategory::query()->where('slug', '=', $blogCategory)->pluck('id')->first();
				return $query->where('blog_informations.blog_category_id', '=', $categoryId);
			})
			->select(
				'blogs.image',
				DB::raw("CASE 
					WHEN blogs.image IS NOT NULL AND blogs.image != '' 
					THEN CONCAT('" . URL::to('/') . "/assets/img/blogs/', blogs.image)
					ELSE '" . URL::to('/') . "/assets/img/blogs/default.jpg'
				 END AS image_url"),
				'blog_categories.name as categoryName',
				'blog_categories.slug as categorySlug',
				'blog_informations.title',
				'blog_informations.slug',
				'blog_informations.author',
				'blogs.created_at',
				'blog_informations.content'
			)
			->orderBy('blogs.serial_number', 'asc')
			->offset($offset)
			->limit($limit)
			->get();

		return $this->successResponse($blogs);
	}
	
	public function blogDetails($slug)
	{
		$misc = new MiscellaneousController(); 
		$language = $misc->getLanguage();
  
		$details = Blog::where('blogs.status', '=', 1)
		  ->join('blog_informations', 'blogs.id', '=', 'blog_informations.blog_id')
		  ->join('blog_categories', 'blog_categories.id', '=', 'blog_informations.blog_category_id')
		  ->where('blog_informations.language_id', '=', $language->id)
		  ->where('blog_informations.slug', '=', $slug)
		  ->select('blogs.id', 'blogs.image', DB::raw("CASE 
				WHEN blogs.image IS NOT NULL AND blogs.image != '' 
				THEN CONCAT('" . URL::to('/') . "/assets/img/blogs/', blogs.image)
				ELSE '" . URL::to('/') . "/assets/img/blogs/default.jpg'
			 END AS image_url"), 'blogs.created_at', 'blog_informations.title', 'blog_informations.content', 'blog_informations.meta_keywords', 'blog_informations.meta_description', 'blog_informations.author', 'blog_categories.name as categoryName', 'blog_categories.slug as categorySlug')
		  ->firstOrFail();

		$queryResult['details'] = $details;

		$queryResult['recent_blogs'] = Blog::where('blogs.status', '=', 1)
		  ->join('blog_informations', 'blogs.id', '=', 'blog_informations.blog_id')
		  ->where('blog_informations.language_id', '=', $language->id)
		  ->where('blogs.id', '!=', $details->id)
		  ->select('blogs.image', DB::raw("CASE 
			WHEN blogs.image IS NOT NULL AND blogs.image != '' 
			THEN CONCAT('" . URL::to('/') . "/assets/img/blogs/', blogs.image)
			ELSE '" . URL::to('/') . "/assets/img/blogs/default.jpg'
		 END AS image_url"),  'blog_informations.title', 'blog_informations.slug', 'blog_informations.author', 'blogs.created_at', 'blog_informations.content')
		  ->orderBy('blogs.serial_number', 'asc')
		  ->limit(3)->get();

		$queryResult['disqusInfo'] = Basic::select('disqus_status', 'disqus_short_name')->firstOrFail(); 
		$queryResult['categories'] = $this->getCategories($language);   
		return $this->successResponse($queryResult);
	}
	
	public function getCategories($language)
	{
		$categories = $language->blogCategory()->where('status', 1)->orderBy('serial_number', 'asc')->get();

		$categories->map(function ($category) {
		  $category['blogCount'] = $category->blogInfo()->count();
		});

		return $categories;
	}
	
	public function properties(Request $request)
    { 
		$limit = $request->get('limit', 10);  
		$offset = $request->get('offset', 0);
		
        $misc = new MiscellaneousController();
        $language = $misc->getLanguage();

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

        if ($request->filled('sort')) {
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

		$property_contents = Property::where('property_type', 'partial')->where([['properties.status', 1], ['properties.approve_status', 1]])
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
		->when($purpose, function ($query) use ($purpose) {
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

			$areaName = $parts[0] ?? null; // e.g. "Pasodara Patiya" or "Surat"
			$cityName = $parts[1] ?? null; // e.g. "Surat"
			$stateName = $parts[2] ?? null; // e.g. "Gujarat"
			$countryName = $parts[3] ?? null;

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
		->with([
			'categoryContent' => function ($q) use ($language) {
				$q->where('language_id', $language->id);
			}
		])

		->select(
			'properties.*',
			'property_categories.id as categoryId',
			'property_contents.title',
			'property_contents.slug',
			'property_contents.address',
			'property_contents.description',
			'property_contents.language_id',
			'areas.name as area_name',
			'property_city_contents.name as city_name',
			'property_state_contents.name as state_name',
			'property_country_contents.name as country_name'
		)
		->orderBy($order_by_column, $order)
		->offset($offset)
		->limit($limit)
		->get();

        $information['property_contents'] = $property_contents; 
 
        $min = Property::where([['status', 1], ['approve_status', 1]])->min('price');
        $max = Property::where([['status', 1], ['approve_status', 1]])->max('price');
        $information['min'] = intval($min);
        $information['max'] = intval($max);
         
        return $this->successResponse($information);
    }
	
	public function cities()
	{
		$misc = new MiscellaneousController();
        $language = $misc->getLanguage();
		
		$allCities = City::where('status', 1)->with(['cityContent' => function ($q) use ($language) {
            $q->where('language_id', $language->id);
        }])->get();
		
		return $this->successResponse($allCities);
	}
	
	public function areas()
	{ 
		$allAreas = Area::where('status', 1)->get(); 
		return $this->successResponse($allAreas);
	}
	
	public function states()
	{ 
		$misc = new MiscellaneousController();
        $language = $misc->getLanguage();
		
		$allStates = State::with(['stateContent' => function ($q) use ($language) {
            $q->where('language_id', $language->id);
        }])->get(); 
		
		return $this->successResponse($allStates);
	}
	
	public function countries()
	{  
		$misc = new MiscellaneousController();
        $language = $misc->getLanguage();
		
        $allCountries = Country::with(['countryContent' => function ($q) use ($language) {
            $q->where('language_id', $language->id);
        }])->get();
		
		return $this->successResponse($allCountries);
	}
	
	public function categories()
	{  
		$misc = new MiscellaneousController();
        $language = $misc->getLanguage();
		
        $categories = PropertyCategory::with(['categoryContent' => function ($q) use ($language) {
			$q->where('language_id', $language->id);
		}, 'properties'])->where('status', 1)->get();
		
		return $this->successResponse($categories);
	}
	
	public function amenities()
	{  
		$misc = new MiscellaneousController();
        $language = $misc->getLanguage();
		
        $amenities = Amenity::where('status', 1)->with(['amenityContent' => function ($q) use ($language) {
            $q->where('language_id', $language->id);
        }])->orderBy('serial_number')->get();
		
		return $this->successResponse($amenities);
	}
	
	public function propertyDetails($slug)
	{
		$misc = new MiscellaneousController();
		$language = $misc->getLanguage();  
		$propertyContent = Content::where('slug', $slug)->firstOrFail(); 

		$property = Content::query()
		->where('property_contents.language_id', $language->id)
		->where('property_contents.property_id', $propertyContent->property_id)
		->leftJoin('properties', 'property_contents.property_id', 'properties.id')
		->where([['properties.status', 1]])
		->when('properties.vendor_id' != 0, function ($query) {

			$query->leftJoin('memberships', 'properties.vendor_id', '=', 'memberships.vendor_id')
				->where(function ($query) {
					$query->where([
						['memberships.status', '=', 1],
						['memberships.start_date', '<=', now()->format('Y-m-d')],
						['memberships.expire_date', '>=', now()->format('Y-m-d')],
					])->orWhere('properties.vendor_id', '=', 0);
				});
		})
		->when('properties.vendor_id' != 0, function ($query) {
			return $query->leftJoin('vendors', 'properties.vendor_id', '=', 'vendors.id')
				->where(function ($query) {
					$query->where('vendors.status', '=', 1)->orWhere('properties.vendor_id', '=', 0);
				});
		}) 
		->with(['propertySpacifications', 'galleryImages'])
		->select('properties.*','property_contents.*', 'properties.id as propertyId', 'property_contents.id as contentId')->firstOrFail();

		$information['propertyContent'] = $property;
		$information['sliders'] = $property->galleryImages;
		$information['amenities'] = PropertyAmenity::with([
			'amenityContent' => function ($q) use ($language) {
				$q->where('language_id', $language->id);
			}
		])->where('property_id', $property->property_id)->get();
		$information['agent'] = Agent::with([
			'agent_info' => function ($q) use ($language) {
				$q->where('language_id', $language->id);
			}
		])->find($property->agent_id);


		$information['vendor'] = Vendor::with([
			'vendor_info' => function ($q) use ($language) {
				$q->where('language_id', $language->id);
			}
		])->find($property->vendor_id);

		$information['admin'] = Admin::where('role_id', null)->first();

   
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
 
		return $this->successResponse($information);
	}

	public function propertyEnquiry(Request $request)
	{ 
		$validator = Validator::make($request->all(), [
			'auth_type' => 'required|in:user,agent,vendor',
			"auth_id" => 'required|integer',
			'name' => 'required|string|max:255',
			'email' => 'required|email|max:255',
			'phone' => 'required|string|max:255',
			'message' => 'required|string',
			'property_id' => 'required|integer|exists:properties,id',
		]);

		if ($validator->fails()) {
			return $this->errorResponse($validator->errors()->first(), 422);
		}

		$authType = $request->auth_type;
		$guard = $authType == 'vendor' ? 'vendor_api' : ($authType === "agent" ? 'agent_api' : 'api'); 
		$authUser = Auth::guard($guard)->user(); 
		if (!$authUser) {
			return $this->errorResponse('Please login first');
		}

		$request['to_mail'] = $authUser->email ?? (Admin::where('role_id', null)->first()->email ?? null);
		   
		try {
			PropertyContact::create([
				'vendor_id' => $authType == "vendor" ? $authUser->id : null,
				'agent_id' => $authType == "agent" ? $authUser->id : null,
				'property_id' => $request->property_id,
				'inquiry_by_user' => $authType == "user" ? $authUser->id : null,
				'inquiry_by_vendor' => $authType == "vendor" ? $authUser->id : null,
				'inquiry_by_agent' => $authType == "agent" ? $authUser->id : null,
				'is_new' => '0',
				'name' => $request->name,
				'email' => $request->email,
				'phone' => $request->phone,
				'message' => $request->message,
				'status' => 'in-progress', 
			]);

			if($request['to_mail'])
			{
				$this->sendMail($request);
			}
			return $this->successResponse([],'Your inquiry has been sent successfully.');
		} catch (\Exception $e) {
			return $this->errorResponse('Something went wrong. Please try again.'.$e->getMessage()); 
		} 
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
			} catch (\Exception $e) {  
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
			return;
		}
	}

	public function addToWishlist(Request $request)
	{
		try {
			// ✅ Step 1: Validate Request
			$validator = Validator::make($request->all(), [
				'auth_type' => 'required|in:user,vendor,agent',
				'property_id' => 'required|integer|min:1',
			]);

			if ($validator->fails()) {
				return $this->errorResponse($validator->errors()->first());
			}

			// ✅ Step 2: Map auth type → guard + column
			$map = [
				'user' => ['guard' => 'api', 'column' => 'user_id'],
				'vendor' => ['guard' => 'vendor_api', 'column' => 'vendor_id'],
				'agent' => ['guard' => 'agent_api', 'column' => 'agent_id'],
			];

			$authType = $request->input('auth_type');
			$guard = $map[$authType]['guard'];
			$column = $map[$authType]['column'];

			// ✅ Step 3: Check Authentication
			if (!Auth::guard($guard)->check()) {
				return $this->errorResponse('Please login to add items to wishlist.');
			}

			$userId = Auth::guard($guard)->id();
			$propertyId = (int) $request->input('property_id');

			// ✅ Step 4: Create Wishlist Entry (idempotent)
			$wishlist = Wishlist::firstOrCreate(
				['property_id' => $propertyId, $column => $userId],
				[] // No extra attributes
			);

			// ✅ Step 5: Count Wishlist Items
			$count = Wishlist::where($column, $userId)->count();

			// ✅ Step 6: Build Response
			$message = $wishlist->wasRecentlyCreated
				? 'Added to your wishlist successfully!'
				: 'You already added this property to your wishlist!';

			return $this->successResponse(['count' => $count], $message);

		} catch (\Throwable $e) {
			return $this->errorResponse('Something went wrong: ' . $e->getMessage());
		}
	} 
	public function removeToWishlist(Request $request)
	{
		try {
			// 1) Validate input
			$validator = Validator::make($request->all(), [
				'auth_type' => 'required|in:user,vendor,agent',
				'property_id' => 'required|integer|min:1',
			]);

			if ($validator->fails()) {
				return $this->errorResponse($validator->errors()->first());
			}

			// 2) Map auth_type -> guard + column
			$map = [
				'user' => ['guard' => 'api', 'column' => 'user_id'],
				'vendor' => ['guard' => 'vendor_api', 'column' => 'vendor_id'],
				'agent' => ['guard' => 'agent_api', 'column' => 'agent_id'],
			];

			$authType = $request->input('auth_type');
			$guard = $map[$authType]['guard'];
			$column = $map[$authType]['column'];

			// 3) Must be authenticated for that guard
			if (!Auth::guard($guard)->check()) {
				return $this->errorResponse('Please login to remove items from wishlist.');
			}

			$userId = Auth::guard($guard)->id();
			$propertyId = (int) $request->input('property_id');

			// 4) Single-query delete (fast + safe)
			$deleted = Wishlist::where('property_id', $propertyId)
				->where($column, $userId)
				->delete();

			if (!$deleted) {
				return $this->errorResponse('Item not found in wishlist.');
			}

			// 5) Return updated count (helps update UI badges instantly)
			$count = Wishlist::where($column, $userId)->count();

			return $this->successResponse(['count' => $count], 'Removed from wishlist successfully!');
		} catch (\Throwable $e) {
			return $this->errorResponse('Something went wrong: ' . $e->getMessage());
		}
	} 

	public function wishlistCount(Request $request)
	{
		// Map incoming auth_type -> guard + wishlist column
		$map = [
			'user' => ['guard' => 'api', 'column' => 'user_id'],
			'vendor' => ['guard' => 'vendor_api', 'column' => 'vendor_id'],
			'agent' => ['guard' => 'agent_api', 'column' => 'agent_id'],
		];

		$authType = $request->string('auth_type')->lower()->toString(); // 'user' | 'vendor' | 'agent'
		if (!isset($map[$authType])) {
			return $this->errorResponse('Invalid auth type.');
		}

		$guard = $map[$authType]['guard'];
		$column = $map[$authType]['column'];

		// Must be authenticated on that guard
		if (!Auth::guard($guard)->check()) {
			return $this->errorResponse('Please login to view wishlist count.');
		}

		$id = Auth::guard($guard)->id(); 
		$count = Wishlist::where($column, $id)->count();

		return $this->successResponse(['count' => $count]);
	}

	public function projects(Request $request)
	{
		$limit = $request->get('limit', 10);  
		$offset = $request->get('offset', 0);
		
		$misc = new MiscellaneousController();
		$language = $misc->getLanguage();
		   
		$title = $location = $vendorId = null;
		if ($request->filled('title') && $request->filled('title')) {
			$title = $request->title;
		}
		if ($request->filled('location') && $request->filled('location')) {
			$location = $request->location;
		}
		if ($request->filled('vendor_id') && $request->filled('vendor_id')) {
			$vendorId = $request->vendor_id;
		}
		if ($request->filled('sort')) {
			if ($request['sort'] == 'new') {
				$order_by_column = 'projects.id';
				$order = 'desc';
			} elseif ($request['sort'] == 'old') {
				$order_by_column = 'projects.id';
				$order = 'asc';
			} elseif ($request['sort'] == 'high-to-low') {
				$order_by_column = 'projects.min_price';
				$order = 'desc';
			} elseif ($request['sort'] == 'low-to-high') {
				$order_by_column = 'projects.min_price';
				$order = 'asc';
			} else {
				$order_by_column = 'projects.id';
				$order = 'desc';
			}
		} else {
			$order_by_column = 'projects.id';
			$order = 'desc';
		}

		$projects = Project::where('projects.approve_status', 1)
			->join('project_contents', 'projects.id', 'project_contents.project_id')
			->when('projects.vendor_id' != 0, function ($query) {

				$query->leftJoin('memberships', 'projects.vendor_id', '=', 'memberships.vendor_id')
					->where(function ($query) {
						$query->where([
							['memberships.status', '=', 1],
							['memberships.start_date', '<=', now()->format('Y-m-d')],
							['memberships.expire_date', '>=', now()->format('Y-m-d')],
						])->orWhere('projects.vendor_id', '=', 0);
					});
			})
			->when('projects.vendor_id' != 0, function ($query) {
				return $query->leftJoin('vendors', 'projects.vendor_id', '=', 'vendors.id')
					->where(function ($query) {
						$query->where('vendors.status', '=', 1)->orWhere('projects.vendor_id', '=', 0);
					});
			})
			->where('project_contents.language_id', $language->id)
			->when($title, function ($query) use ($title) {
				return $query->where('project_contents.title', 'LIKE', '%' . $title . '%');
			})
			->when($vendorId, function ($query) use ($vendorId) {
				return $query->where('projects.vendor_id', $vendorId);
			})
			->when($location, function ($query) use ($location) {
				return $query->where('project_contents.address', 'LIKE', '%' . $location . '%');
			})
			->with(['vendor:id,username,email,photo'])
			->select('projects.*', 'project_contents.title', 'project_contents.slug', 'project_contents.address') 
			->orderBy($order_by_column, $order)
			->offset($offset)
			->limit($limit)
			->get();;

		$information['projects'] = $projects;  

		return $this->successResponse($information);
	}

	public function projectDetails($slug)
	{
		$misc = new MiscellaneousController();
		$language = $misc->getLanguage(); 

		$projectContent = ProjectContent::where('slug', $slug)->firstOrFail();
		$project = Project::query()
			//->where('projects.approve_status', 1)
			->where('projects.id', $projectContent->project_id)
			->where('project_contents.language_id', $language->id)
			->join('project_contents', 'projects.id', 'project_contents.project_id')
			->when('projects.vendor_id' != 0, function ($query) {

				$query->leftJoin('memberships', 'projects.vendor_id', '=', 'memberships.vendor_id')
					->where(function ($query) {
						$query->where([
							['memberships.status', '=', 1],
							['memberships.start_date', '<=', now()->format('Y-m-d')],
							['memberships.expire_date', '>=', now()->format('Y-m-d')],
						])->orWhere('projects.vendor_id', '=', 0);
					});
			})
			->when('projects.vendor_id' != 0, function ($query) {
				return $query->leftJoin('vendors', 'projects.vendor_id', '=', 'vendors.id')
					->where(function ($query) {
						$query->where('vendors.status', '=', 1)->orWhere('projects.vendor_id', '=', 0);
					});
			})

			->select('projects.*', 'project_contents.id as contentId', 'project_contents.title', 'project_contents.slug', 'project_contents.address', 'project_contents.language_id', 'project_contents.description', 'project_contents.meta_keyword', 'project_contents.meta_description')

			->with([
				'projectTypes',
				'galleryImages',
				'projectTypeContents' => function ($q) use ($language) {
					$q->where('language_id', $language->id);
				},
				'floorplanImages',
				'specifications.specificationContents'
			])
			->firstOrFail();
			
		if ($project->vendor_id == 0) { 
			$vendor = Admin::where('role_id', null)->select('username')->first();
			$information['username'] = $vendor->username;
		}
		$information['project'] = $project;
		$information['floorPlanImages'] = $information['project']->floorplanImages;
		$information['galleryImages'] = $information['project']->galleryImages;
 
		return $this->successResponse($information);
	}

	public function vendors()
	{    
		$vendors = Vendor::where([['id', '!=', 0], ['status', 1]])->get();
		return $this->successResponse($vendors);
	}
}