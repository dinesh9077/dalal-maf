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
    PropertyCategoryContent, PropertyContact, State, StateContent
};
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
		$limit = $request->get('limit', 9); // default 9
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
        $misc = new MiscellaneousController();
        $language = $misc->getLanguage();
        $information['seoInfo'] = $language->seoInfo()->select('meta_keyword_properties', 'meta_description_properties')->first();

        if ($request->has('type') && ($request->type == 'commercial' || $request->type == 'residential')) {
            $information['categories'] = PropertyCategory::with(['categoryContent' => function ($q) use ($language) {
                $q->where('language_id', $language->id);
            }, 'properties'])->where([['status', 1], ['type', $request->type]])->get();
        } else {
            $information['categories'] = PropertyCategory::with(['categoryContent' => function ($q) use ($language) {
                $q->where('language_id', $language->id);
            }, 'properties'])->where('status', 1)->get();
        }
 
        $information['bgImg'] = $misc->getBreadcrumb();
        $information['pageHeading'] = $misc->getPageHeading($language);
        $information['amenities'] = Amenity::where('status', 1)->with(['amenityContent' => function ($q) use ($language) {
            $q->where('language_id', $language->id);
        }])->orderBy('serial_number')->get();

        $propertyCategory = null;
        $category = null;
        if ($request->filled('category') && $request->category != 'all') {
            $category = $request->category;
            $propertyCategory = PropertyCategoryContent::where([['language_id', $language->id], ['slug', $category]])->first();
        }

        $amenities = [];
        $amenityInContentId = [];
        if ($request->filled('amenities')) {
            $amenities = $request->amenities;
            foreach ($amenities as $amenity) {
                $amenConId = AmenityContent::where('name', $amenity)->where('language_id', $language->id)->pluck('amenity_id')->first();
                array_push($amenityInContentId, $amenConId);
            }
        }

        $amenityInContentId = array_unique($amenityInContentId);
        $type = null;
        if ($request->filled('type') && $request->type != 'all') {
            $type = $request->type;
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
                return $query->where('properties.type', $type);
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
            ->when($category && $propertyCategory, function ($query) use ($propertyCategory) {
                return $query->where('properties.category_id', $propertyCategory->category_id);
            })

            ->when(!empty($amenityInContentId), function ($query) use ($amenityInContentId) {
                $query->whereHas(
                    'proertyAmenities',
                    function ($q) use ($amenityInContentId) {
                        $q->whereIn('amenity_id', $amenityInContentId);
                    },
                    '=',
                    count($amenityInContentId)
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

        $min = Property::where([['status', 1], ['approve_status', 1]])->min('price');
        $max = Property::where([['status', 1], ['approve_status', 1]])->max('price');
        $information['min'] = intval($min);
        $information['max'] = intval($max);
        if ($request->ajax()) {
            $viewContent = View::make('frontend.property.property',  $information);
            $viewContent = $viewContent->render();

            return response()->json(['propertyContents' => $viewContent, 'properties' => $property_contents])->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        }

        return $this->successResponse($information);
    }
  
}