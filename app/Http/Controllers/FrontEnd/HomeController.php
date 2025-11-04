<?php
	
	namespace App\Http\Controllers\FrontEnd;
	
	use App\Http\Controllers\Controller;
	use App\Http\Controllers\FrontEnd\MiscellaneousController;
	use App\Models\BasicSettings\Basic;
	use App\Models\CounterSection;
	use App\Models\HomePage\AboutSection;
	use App\Models\HomePage\Banner;
	use App\Models\HomePage\BrandSection;
	use App\Models\HomePage\CategorySection;
	use App\Models\HomePage\CitySection;
	use App\Models\HomePage\Section;
	use App\Models\Journal\Blog;
	use App\Models\HomePage\Partner;
	use App\Models\HomePage\ProjectSection;
	use App\Models\HomePage\PropertySection;
	use App\Models\HomePage\VendorSection;
	use App\Models\HomePage\WhyChooseUs;
	use App\Models\Package;
	use App\Models\Project\Project;
	use App\Models\Prominence\FeatureSection;
	use App\Models\Property\City;
	use App\Models\Property\Country;
	use App\Models\Property\FeaturedProperty;
	use App\Models\Property\Property;
	use App\Models\Property\PropertyCategory;
	use App\Models\Property\State;
	use App\Models\User;
	use App\Models\Vendor;
	use Carbon\Carbon;
	use Illuminate\Http\Request;
	use Session;
	use Illuminate\Support\Facades\Cache;
	
	class HomeController extends Controller
	{
		
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
			 
			$all_proeprty_categories = PropertyCategory::where('status', 1)
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
			/* $queryResult['all_states'] = State::with(['stateContent' => function ($q) use ($language) {
				$q->where('language_id', $language->id);
			}])->get();
			$queryResult['all_countries'] = Country::with(['countryContent' => function ($q) use ($language) {
				$q->where('language_id', $language->id);
			}])->get();  */
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
			
			if ($themeVersion == 1 && $secInfo->vendor_section_status == 1) {
				
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
				->select('vendors.*')->inRandomOrder()->take(5)->get();
			}
			
			if ($themeVersion == 1 && $secInfo->why_choose_us_section_status == 1) {
				$queryResult['whyChooseUsImg'] = Basic::query()->select('why_choose_us_section_img1', 'why_choose_us_section_img2', 'why_choose_us_section_video_link')->first();
				$queryResult['whyChooseUsInfo'] =  WhyChooseUs::where('language_id', $language->id)->first();
			}
			
			
			if ($themeVersion == 1 && $secInfo->cities_section_status == 1) {
				$cities =  City::where([['status', 1], ['featured', 1]])->orderBy('serial_number', 'asc')->get();
				$cities->map(function ($city) use ($language) {
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
			 
			if ($themeVersion == 1) {
				return view('frontend.home.index-v1', $queryResult);
				} elseif ($themeVersion == 2) {
				return view('frontend.home.index-v2', $queryResult);
				} elseif ($themeVersion == 3) {
				return view('frontend.home.index-v3', $queryResult);
			}
		}
		 
		//about
		public function about()
		{
			$misc = new MiscellaneousController();
			
			$language = $misc->getLanguage();
			
			$queryResult['seoInfo'] = $language->seoInfo()->select('meta_keywords_about_page', 'meta_description_about_page')->first();
			
			$queryResult['pageHeading'] = $misc->getPageHeading($language);
			
			$queryResult['bgImg'] = $misc->getBreadcrumb();
			$secInfo = Section::query()->first();
			$queryResult['secInfo'] = $secInfo;
			
			
			if ($secInfo->about_section_status == 1) {
				$queryResult['aboutImg'] = Basic::query()->select('about_section_image1', 'about_section_image2', 'about_section_video_link')->first();
				$queryResult['aboutInfo'] =  AboutSection::where('language_id', $language->id)->first();
			}
			if ($secInfo->property_section_status == 1) {
				$queryResult['whyChooseUsImg'] = Basic::query()->select('why_choose_us_section_img1', 'why_choose_us_section_img2', 'why_choose_us_section_video_link')->first();
				$queryResult['whyChooseUsInfo'] =  WhyChooseUs::where('language_id', $language->id)->first();
			}
			
			if ($secInfo->work_process_section_status == 1) {
				$queryResult['workProcessSecInfo'] = $language->workProcessSection()->first();
				$queryResult['processes'] = $language->workProcess()->orderBy('serial_number', 'asc')->get();
			}
			
			
			if ($secInfo->testimonial_section_status == 1) {
				$queryResult['testimonialSecInfo'] = $language->testimonialSection()->first();
				$queryResult['testimonials'] = $language->testimonial()->orderByDesc('id')->get();
				$queryResult['testimonialSecImage'] = Basic::query()->pluck('testimonial_section_image')->first();
			}
			
			return view('frontend.about', $queryResult);
		}
		public function pricing()
		{
			$misc = new MiscellaneousController();
			
			$language = $misc->getLanguage();
			
			$queryResult['seoInfo'] = $language->seoInfo()->select('meta_keywords_pricing_page', 'meta_description_pricing_page')->first();
			
			$queryResult['pageHeading'] = $misc->getPageHeading($language);
			
			$queryResult['bgImg'] = $misc->getBreadcrumb();
			$queryResult['packages'] = Package::where('status', 1)->get();
			return view('frontend.pricing', $queryResult);
		}
		//offline
		public function offline()
		{
			return view('frontend.offline');
		}
	}
