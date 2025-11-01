@php
$version = $basicInfo->theme_version;
@endphp
@extends('frontend.layouts.layout-v' . $version)
@section('pageHeading')
{{ __('Home') }}
@endsection

@section('metaKeywords')
@if (!empty($seoInfo))
{{ $seoInfo->meta_keyword_home }}
@endif
@endsection

@section('metaDescription')
@if (!empty($seoInfo))
{{ $seoInfo->meta_description_home }}
@endif
@endsection
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />

<style>
    .ui-widget.ui-widget-content {
	background: white !important;
	border: 1px solid #dcdcdc !important;
	font-size: 14px !important;
	padding: 2px !important;
	color: #2b3138 !important;
	text-transform: capitalize !important;
	border-radius: 5px !important;
    }
	
    .ui-widget.ui-widget-content .ui-menu-item .ui-menu-item-wrapper:hover {
	background: black !important;
	border: none !important;
	color: white !important;
	outline: none !important;
	border-radius: 5px !important;
	
	
    }
	
    .ui-widget.ui-widget-content .ui-menu-item {
	background: white !important;
	border: none !important;
	outline: none !important;
	color: black !important;
    }
	
    .new-main-navbar {
	
	background-color: transparent !important;
    }
	
    /* ==== */
</style>

@section('content')

<a href="https://wa.me/9925133440" target="_blank">
    <div class="whatsapp-btn" data-aos="fade-up">
        <img src="{{ asset('assets/front/images/new-images/whatsapp.png') }}" alt="WhatsApp">
	</div>
</a>
@php
$firstHeroImg = !empty($heroImg) && is_array($heroImg) ? $heroImg[0] : 'noimage.jpg';
@endphp
<!-- <section class="home-banner home-banner-1 relative new-home-hero-color" data-aos="fade-up"> -->
<section class="home-banner home-banner-1 relative" data-aos="fade-up">
    <div class="hero-image" id="heroBanner" style="background: url('{{ asset('assets/img/hero/static/' . $firstHeroImg) }}'); background-size: cover; background-position: center;">
		
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xxl-10">
                    <div class="content mb-40" data-aos="fade-up">
                        <!-- <h1 class="title title-colors">{{ $heroStatic->title }}</h1> -->
                        <!-- <h1 class="title">{{ $heroStatic->title }}</h1> -->
                        <!-- <p class="text title-colors">
							
                            {{ $heroStatic->text }}
						</p> -->
                        <!-- <div style="  margin-top: 40px;">
							
                            <a href="{{ route('frontend.properties') }}" class="home-hero-inq-btn">Make an Inquiry</a>
						</div> -->
					</div>
					
				</div>
			</div>
		</div>
		
        <!-- <img src="{{ asset('assets/front/images/acrs-imag/HOUSE-1.png') }}" class="home-hero-imahes-new" alt=""> -->
		
        <div class="banner-filter-form new-banner-filters-width" data-aos="fade-up">
			
            <div class="tab-content form-wrapper">
                <div style="
				border-bottom: 1px solid #dcdcdc;">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#buy"
							type="button">{{ __('Buy') }}</button>
						</li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#sale"
							type="button">{{ __('Sale') }}</button>
						</li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#rent"
							type="button">{{ __('Rent') }}</button>
						</li>
						
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#lease"
							type="button">{{ __('Lease') }}</button>
						</li>
					</ul>
					
				</div>
				
				
                <input type="hidden" value="{{ $min }}" id="min">
                <input type="hidden" value="{{ $max }}" id="max">
				
                <input type="hidden" id="currency_symbol" value="{{ $basicInfo->base_currency_symbol }}">
                <input class="form-control" type="hidden" value="{{ $min }}" id="o_min">
                <input class="form-control" type="hidden" value="{{ $max }}" id="o_max">
				
                <div class="tab-pane fade mt-3 active show" id="buy">
                    <form action="{{ route('frontend.properties') }}" method="get">
                        <input type="hidden" name="purposre" value="rent">
                        <input type="hidden" name="min" value="{{ $min }}" id="min1">
                        <input type="hidden" name="max" value="{{ $max }}" id="max1">
                        <div class="grid">
                            <div class="grid-item home-des-border">
                                <div class="form-group">
                                    <label for="search1">{{ __('Location') }}</label>
                                    <input type="text" id="search1" name="location" class="form-control searchBar"
									placeholder="{{ __('Enter Location') }}" style="box-shadow : none;">
								</div>
							</div>
                            <div class="grid-item home-des-border">
                                <div class="form-group">
                                    <label for="type" class="icon-end">City</label>
                                    <select aria-label="#" name="type" class="form-control select2 type select2-hidden-accessible" id="type" data-select2-id="select2-data-type" tabindex="-1" aria-hidden="true">
                                        <option selected="" disabled="" value="" data-select2-id="select2-data-2-00xq">Select City
										</option>
                                        <option value="all" data-select2-id="select2-data-21-5nn2">All</option>
                                        <option value="residential" data-select2-id="select2-data-22-xtxj">Surat</option>
                                        <option value="commercial" data-select2-id="select2-data-23-xppr">Ahmedabad</option>
                                        <option value="industrial" data-select2-id="select2-data-24-6p4y">Baroda</option>
									</select>
								</div>
							</div>
                            <div class="grid-item home-des-border">
                                <div class="form-group">
                                    <label for="type" class="icon-end">{{ __('Property Type') }}</label>
                                    <select aria-label="#" name="type" class="form-control select2 type"
									id="type">
                                        <option selected disabled value="">{{ __('Select Property') }}
										</option>
                                        <option value="all">{{ __('All') }}</option>
                                        <option value="residential">{{ __('Residential') }}</option>
                                        <option value="commercial">{{ __('Commercial') }}</option>
                                        <option value="industrial">{{ __('Industrial') }}</option>
									</select>
								</div>
							</div>
                            <div class="grid-item home-des-border">
                                <div class="form-group">
                                    <label for="category" class="icon-end">{{ __('Categories') }}</label>
                                    <select aria-label="#" class="form-control select2 bringCategory"
									id="category" name="category">
                                        <option selected disabled value="">{{ __('Select Category') }}
										</option>
                                        <option value="all">{{ __('All') }}</option>
                                        @foreach ($all_proeprty_categories as $category)
                                        <option value="{{ @$category->categoryContent->slug }}">
                                            {{ @$category->categoryContent->name }}
										</option>
                                        @endforeach
										
									</select>
								</div>
							</div>
                            <div class="grid-item home-des-border">
                                <label class="price-value">{{ __('Price') }}: <br>
                                    <span data-range-value="filterPriceSliderValue" style="margin-top : 10px;">{{ symbolPrice($min) }}
                                        -
									{{ symbolPrice($max) }}</span>
								</label>
                                <div data-range-slider="filterPriceSlider"></div>
							</div>
                            <div class="">
                                <button type="submit"
								class="btn btn-primary bg-secondary icon-start new-search-btn"
								style="background-color: #6c603c !important;">
                                    <img src="{{ asset('assets/front/images/new-images/search.png') }}" alt="Search" class="new-icons-search">
								</button>
                                <!-- ==== P -->
                                <!-- <button type="submit"
									class="btn btn-lg btn-primary bg-secondary icon-start w-100">
									{{ __('Search') }}
								</button> -->
							</div>
						</div>
					</form>
				</div>
                <div class="tab-pane fade mt-3" id="sale">
                    <form action="{{ route('frontend.properties') }}" method="get">
                        <input type="hidden" name="purposre" value="sale">
                        <input type="hidden" name="min" value="{{ $min }}" id="min2">
                        <input type="hidden" name="max" value="{{ $max }}" id="max2">
                        <div class="grid">
                            <div class="grid-item home-des-border">
                                <div class="form-group">
                                    <label for="search1">{{ __('Location') }}</label>
                                    <input type="text" id="search1" name="location"
									class="form-control searchBar" placeholder="{{ __('Enter Location') }}">
								</div>
							</div>
							
                            <div class="grid-item home-des-border">
                                <div class="form-group">
                                    <label for="type" class="icon-end">City</label>
                                    <select aria-label="#" name="type" class="form-control select2 type select2-hidden-accessible" id="type" data-select2-id="select2-data-type" tabindex="-1" aria-hidden="true">
                                        <option selected="" disabled="" value="" data-select2-id="select2-data-2-00xq">Select City
										</option>
                                        <option value="all" data-select2-id="select2-data-21-5nn2">All</option>
                                        <option value="residential" data-select2-id="select2-data-22-xtxj">Surat</option>
                                        <option value="commercial" data-select2-id="select2-data-23-xppr">Ahmedabad</option>
                                        <option value="industrial" data-select2-id="select2-data-24-6p4y">Baroda</option>
									</select>
								</div>
							</div>
							
							
                            <div class="grid-item home-des-border">
                                <div class="form-group">
                                    <label for="type_sale" class="icon-end">{{ __('Property Type') }}</label>
                                    <select aria-label="#" name="type" class="form-control select2 type"
									id="type_sale">
                                        <option selected disabled value="">{{ __('Select Property') }}
										</option>
                                        <option value="all">{{ __('All') }}</option>
                                        <option value="residential">{{ __('Residential') }}</option>
                                        <option value="commercial">{{ __('Commercial') }}</option>
                                        <option value="industrial">{{ __('Industrial') }}</option>
										
									</select>
								</div>
							</div>
                            <div class="grid-item home-des-border">
                                <div class="form-group">
                                    <label for="category_sale" class="icon-end">{{ __('Categories') }}</label>
                                    <select aria-label="#" class="form-control select2 bringCategory"
									id="category_sale" name="category">
                                        <option selected disabled value="">{{ __('Select Category') }}
										</option>
                                        <option value="all">{{ __('All') }}</option>
                                        @foreach ($all_proeprty_categories as $category)
                                        <option value="{{ @$category->categoryContent->slug }}">
                                            {{ @$category->categoryContent->name }}
										</option>
                                        @endforeach
										
									</select>
								</div>
							</div>
							
                            {{-- <div class="grid-item home-des-border city">
                                <div class="form-group">
                                    <label for="city1" class="icon-end">{{ __('City') }}</label>
									<select aria-label="#" name="city"
									class="form-control select2 city_id" id="city1">
										<option selected disabled value="">{{ __('Select City') }}
										</option>
										<option value="all">{{ __('All') }}</option>
										
										@foreach ($all_cities as $city)
										<option data-id="{{ $city->id }}"
										value="{{ @$city->cityContent->name }}">
											{{ @$city->cityContent->name }}
										</option>
										@endforeach
										
									</select>
								</div>
							</div> --}}
							<div class="grid-item home-des-border">
								<label class="price-value">{{ __('Price') }}: <br>
									<span data-range-value="filterPriceSlider2Value">{{ symbolPrice($min) }}
										-
									{{ symbolPrice($max) }}</span>
								</label>
								<div data-range-slider="filterPriceSlider2"></div>
							</div>
							<div class="">
								<button type="submit"
								class="btn btn-primary bg-secondary icon-start new-search-btn"
								style="background-color:#6c603c !important;">
									<img src="{{ asset('assets/front/images/new-images/search.png') }}" alt="Search" class="new-icons-search">
								</button>
							</div>
						</div>
					</form>
				</div>
				<div class="tab-pane fade mt-3" id="rent">
					<form action="{{ route('frontend.properties') }}" method="get">
						<input type="hidden" name="purposre" value="sale">
						<input type="hidden" name="min" value="{{ $min }}" id="min2">
						<input type="hidden" name="max" value="{{ $max }}" id="max2">
						<div class="grid">
							<div class="grid-item home-des-border">
								<div class="form-group">
									<label for="search1">{{ __('Location') }}</label>
									<input type="text" id="search1" name="location"
									class="form-control searchBar" placeholder="{{ __('Enter Location') }}">
								</div>
							</div>
							
							
							<div class="grid-item home-des-border">
								<div class="form-group">
									<label for="type" class="icon-end">City</label>
									<select aria-label="#" name="type" class="form-control select2 type select2-hidden-accessible" id="type" data-select2-id="select2-data-type" tabindex="-1" aria-hidden="true">
										<option selected="" disabled="" value="" data-select2-id="select2-data-2-00xq">Select City
										</option>
										<option value="all" data-select2-id="select2-data-21-5nn2">All</option>
										<option value="residential" data-select2-id="select2-data-22-xtxj">Surat</option>
										<option value="commercial" data-select2-id="select2-data-23-xppr">Ahmedabad</option>
										<option value="industrial" data-select2-id="select2-data-24-6p4y">Baroda</option>
									</select>
								</div>
							</div>
							
							
							<div class="grid-item home-des-border">
								<div class="form-group">
									<label for="type_rent" class="icon-end">{{ __('Property Type') }}</label>
									<select aria-label="#" name="type" class="form-control select2 type"
									id="type_rent">
										<option selected disabled value="">{{ __('Select Property') }}
										</option>
										<option value="all">{{ __('All') }}</option>
										<option value="residential">{{ __('Residential') }}</option>
										<option value="commercial">{{ __('Commercial') }}</option>
										<option value="industrial">{{ __('Industrial') }}</option>
										
									</select>
								</div>
							</div>
							<div class="grid-item home-des-border">
								<div class="form-group">
									<label for="category_rent" class="icon-end">{{ __('Categories') }}</label>
									<select aria-label="#" class="form-control select2 bringCategory"
									id="category_rent" name="category">
										<option selected disabled value="">{{ __('Select Category') }}
										</option>
										<option value="all">{{ __('All') }}</option>
										@foreach ($all_proeprty_categories as $category)
										<option value="{{ @$category->categoryContent->slug }}">
											{{ @$category->categoryContent->name }}
										</option>
										@endforeach
										
									</select>
								</div>
							</div>
							
							{{-- <div class="grid-item home-des-border city">
                                <div class="form-group">
                                    <label for="city1" class="icon-end">{{ __('City') }}</label>
									<select aria-label="#" name="city"
									class="form-control select2 city_id" id="city1">
										<option selected disabled value="">{{ __('Select City') }}
										</option>
										<option value="all">{{ __('All') }}</option>
										
										@foreach ($all_cities as $city)
										<option data-id="{{ $city->id }}"
										value="{{ @$city->cityContent->name }}">
											{{ @$city->cityContent->name }}
										</option>
										@endforeach
										
									</select>
								</div>
							</div> --}}
							<div class="grid-item home-des-border">
								<label class="price-value">{{ __('Price') }}: <br>
									<span data-range-value="filterPriceSlider2Value">{{ symbolPrice($min) }}
										-
									{{ symbolPrice($max) }}</span>
								</label>
								<div data-range-slider="filterPriceSlider2"></div>
							</div>
							<div class="">
								<button type="submit"
								class="btn btn-primary bg-secondary icon-start new-search-btn"
								style="background-color:#6c603c  !important;">
									<img src="{{ asset('assets/front/images/new-images/search.png') }}" alt="Search" class="new-icons-search">
								</button>
							</div>
						</div>
					</form>
				</div>
				<div class="tab-pane fade mt-3" id="lease">
					<form action="{{ route('frontend.properties') }}" method="get">
						<input type="hidden" name="purposre" value="sale">
						<input type="hidden" name="min" value="{{ $min }}" id="min2">
						<input type="hidden" name="max" value="{{ $max }}" id="max2">
						<div class="grid">
							<div class="grid-item home-des-border">
								<div class="form-group">
									<label for="search1">{{ __('Location') }}</label>
									<input type="text" id="search1" name="location"
									class="form-control searchBar" placeholder="{{ __('Enter Location') }}">
								</div>
							</div>
							
							<div class="grid-item home-des-border">
								<div class="form-group">
									<label for="type" class="icon-end">City</label>
									<select aria-label="#" name="type" class="form-control select2 type select2-hidden-accessible" id="type" data-select2-id="select2-data-type" tabindex="-1" aria-hidden="true">
										<option selected="" disabled="" value="" data-select2-id="select2-data-2-00xq">Select City
										</option>
										<option value="all" data-select2-id="select2-data-21-5nn2">All</option>
										<option value="residential" data-select2-id="select2-data-22-xtxj">Surat</option>
										<option value="commercial" data-select2-id="select2-data-23-xppr">Ahmedabad</option>
										<option value="industrial" data-select2-id="select2-data-24-6p4y">Baroda</option>
									</select>
								</div>
							</div>
							
							
							<div class="grid-item home-des-border">
								<div class="form-group">
									<label for="type_lease" class="icon-end">{{ __('Property Type') }}</label>
									<select aria-label="#" name="type" class="form-control select2 type"
									id="type_lease">
										<option selected disabled value="">{{ __('Select Property') }}
										</option>
										<option value="all">{{ __('All') }}</option>
										<option value="residential">{{ __('Residential') }}</option>
										<option value="commercial">{{ __('Commercial') }}</option>
										<option value="industrial">{{ __('Industrial') }}</option>
										
									</select>
								</div>
							</div>
							<div class="grid-item home-des-border">
								<div class="form-group">
									<label for="category_lease" class="icon-end">{{ __('Categories') }}</label>
									<select aria-label="#" class="form-control select2 bringCategory"
									id="category_lease" name="category">
										<option selected disabled value="">{{ __('Select Category') }}
										</option>
										<option value="all">{{ __('All') }}</option>
										@foreach ($all_proeprty_categories as $category)
										<option value="{{ @$category->categoryContent->slug }}">
											{{ @$category->categoryContent->name }}
										</option>
										@endforeach
									</select>
								</div>
							</div>
							
							{{-- <div class="grid-item home-des-border city">
                                <div class="form-group">
                                    <label for="city1" class="icon-end">{{ __('City') }}</label>
									<select aria-label="#" name="city"
									class="form-control select2 city_id" id="city1">
										<option selected disabled value="">{{ __('Select City') }}
										</option>
										<option value="all">{{ __('All') }}</option>
										
										@foreach ($all_cities as $city)
										<option data-id="{{ $city->id }}"
										value="{{ @$city->cityContent->name }}">
											{{ @$city->cityContent->name }}
										</option>
										@endforeach
										
									</select>
								</div>
							</div> --}}
							<div class="grid-item home-des-border">
								<label class="price-value">{{ __('Price') }}: <br>
									<span data-range-value="filterPriceSlider2Value">{{ symbolPrice($min) }}
										-
									{{ symbolPrice($max) }}</span>
								</label>
								<div data-range-slider="filterPriceSlider2"></div>
							</div>
							<div class="">
								<button type="submit"
								class="btn btn-primary bg-secondary icon-start new-search-btn"
								style="background-color:#6c603c  !important;">
									<img src="{{ asset('assets/front/images/new-images/search.png') }}" alt="Search" class="new-icons-search">
								</button>
							</div>
						</div>
					</form>
				</div>
				
			</div>
		</div>
		
	</div>
</section>
 
@if($featured_properties->isNotEmpty()) 
	<section class="product-area featured-product pt-100 pb-70">
		<div class="container-fulid new-padding-des-res">
			<div class="row">
				<div class="container" >
					<div class="col-12">
						<div class="section-title text-center mb-40 new-titles" data-aos="fade-up"  style="position: relative;">
							<h2 class="title" style="text-align : center;">Featured Properties</h2>
							<p class="mt-4">Handpicked and premium listings showcased for you. Explore top-rated homes, offices, and commercial spaces that stand out.</p>
							<a href="{{ route('frontend.properties.featured.all') }}" class="vs-btn vs-new-set-btn" style="padding: 10px 20px;">View All</a>

						</div>
					</div>
				</div>
				
                <div class="container ">

              
                    <div class="row align-items-stretch new-my-div">
                        <!-- <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 d-flex" style="margin-bottom : 20px; " data-aos="fade-up">
                            <div class="custom-card w-100">
                                <img src="{{ asset('assets/front/images/new-images/citykrugy-about.png') }}" alt="citykrugy-about">
                                <h3 class="card-title"> Featured Properties In Surat</h3>
                                <p class="card-text">
                                    We are Surat’s most trusted and best-selling real estate agency, specializing in luxury residences, premium commercial spaces, and profitable investment properties. With years of expertise, a wide property portfolio, and strong market knowledge, we’ve helped countless families and investors find exactly what they’re looking for.
                                </p>
                                <a href="{{ route('frontend.properties.featured.all') }}" class="vs-btn" style="padding: 10px 20px;">View All</a>
                                
                            </div>
                        </div> -->
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 d-flex " style="position: relative;" data-aos="fade-up">
                            <div class="swiper product-slider w-100">
                                <div class="swiper-wrapper">
                                    @forelse ($featured_properties as $property)
                                    <div class="swiper-slide">
                                        <x-property :property="$property" />
                                    </div>
                                    @empty
                                    <div class="p-3 text-center mb-30 w-100">
                                        <h3 class="mb-0">{{ __('No Featured Property Found') }}</h3>
                                    </div>
                                    @endforelse
                                </div>
                                <div class="swiper-pagination position-static mb-30" id="product-slider-pagination"></div>
                            </div>
                            
                            <div class="swiper-button-prev first-left custom-swiper-btn"></div>
                            <div class="swiper-button-next first-right custom-swiper-btn"></div>
                        </div>
                    </div>

                  </div>
			</div>
		</div>
	</section> 
@endif

@if($hotProperties->isNotEmpty())
	<section class="product-area featured-product pt-100 pb-70">
		<div class="container-fulid ">
			<div class="row">
				<div class="container">
					<div class="col-12">
						<div class="section-title text-center mb-40 new-titles" data-aos="fade-up">
							<h2 class="title" style="text-align : center;">Hot Properties</h2>
							<p class="mt-4">Handpicked and premium listings showcased for you. Explore top-rated homes, offices, and commercial spaces that stand out.</p>
						</div>
					</div>
				</div>
				
				<div class="row align-items-stretch">
					<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 d-flex" style="margin-bottom : 20px; " data-aos="fade-up">
						<div class="custom-card w-100">
							<img src="{{ asset('assets/front/images/new-images/citykrugy-about.png') }}" alt="citykrugy-about">
							<h3 class="card-title"> Hot Properties In Surat</h3>
							<p class="card-text">
								We are Surat’s most trusted and best-selling real estate agency, specializing in luxury residences, premium commercial spaces, and profitable investment properties. With years of expertise, a wide property portfolio, and strong market knowledge, we’ve helped countless families and investors find exactly what they’re looking for.
							</p>
							<a href="{{ route('frontend.properties.featured.all') }}" class="vs-btn" style="padding: 10px 20px;">View All</a>
							
						</div>
					</div>
					<div class="col-xl-9 col-lg-8 col-md-6 col-sm-6 d-flex new-my-div" style="position: relative;" data-aos="fade-up">
						<div class="swiper product-slider w-100">
							<div class="swiper-wrapper">
								@forelse ($hotProperties as $property)
								<div class="swiper-slide">
									<x-property :property="$property" />
								</div>
								@empty
								<div class="p-3 text-center mb-30 w-100">
									<h3 class="mb-0">{{ __('No Hot Property Found') }}</h3>
								</div>
								@endforelse
							</div>
							<div class="swiper-pagination position-static mb-30" id="product-slider-pagination"></div>
						</div>
						
						<div class="swiper-button-prev first-left custom-swiper-btn"></div>
						<div class="swiper-button-next first-right custom-swiper-btn"></div>
					</div>
				</div>
			</div>
		</div>
	</section>
@endif

@if($recommendedProperties->isNotEmpty())
	<section class="product-area featured-product pt-100 pb-70">
		<div class="container-fulid">
			<div class="row">
				<div class="container">
					<div class="col-12">
						<div class="section-title text-center mb-40 new-titles" data-aos="fade-up">
							<h2 class="title" style="text-align : center;">Recommended Properties</h2>
							<p class="mt-4">Handpicked and premium listings showcased for you. Explore top-rated homes, offices, and commercial spaces that stand out.</p>
						</div>
					</div>
				</div>
				
				<div class="row align-items-stretch">
					<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 d-flex" style="margin-bottom : 20px; " data-aos="fade-up">
						<div class="custom-card w-100">
							<img src="{{ asset('assets/front/images/new-images/citykrugy-about.png') }}" alt="citykrugy-about">
							<h3 class="card-title"> Recommended Properties In Surat</h3>
							<p class="card-text">
								We are Surat’s most trusted and best-selling real estate agency, specializing in luxury residences, premium commercial spaces, and profitable investment properties. With years of expertise, a wide property portfolio, and strong market knowledge, we’ve helped countless families and investors find exactly what they’re looking for.
							</p>
							<a href="{{ route('frontend.properties.featured.all') }}" class="vs-btn" style="padding: 10px 20px;">View All</a>
							
						</div>
					</div>
					<div class="col-xl-9 col-lg-8 col-md-6 col-sm-6 d-flex new-my-div" style="position: relative;" data-aos="fade-up">
						<div class="swiper product-slider w-100">
							<div class="swiper-wrapper">
								@forelse ($recommendedProperties as $property)
								<div class="swiper-slide">
									<x-property :property="$property" />
								</div>
								@empty
								<div class="p-3 text-center mb-30 w-100">
									<h3 class="mb-0">{{ __('No Recommended Property Found') }}</h3>
								</div>
								@endforelse
							</div>
							<div class="swiper-pagination position-static mb-30" id="product-slider-pagination"></div>
						</div>
						
						<div class="swiper-button-prev first-left custom-swiper-btn"></div>
						<div class="swiper-button-next first-right custom-swiper-btn"></div>
					</div>
				</div>
			</div>
		</div>
	</section> 
@endif

@if($fastSellingProperties->isNotEmpty())
	<section class="product-area featured-product pt-100 pb-70">
		<div class="container-fulid">
			<div class="row">
				<div class="container">
					<div class="col-12">
						<div class="section-title text-center mb-40 new-titles" data-aos="fade-up">
							<h2 class="title" style="text-align : center;">Fast Selling Properties</h2>
							<p class="mt-4">Handpicked and premium listings showcased for you. Explore top-rated homes, offices, and commercial spaces that stand out.</p>
						</div>
					</div>
				</div>
				
				<div class="row align-items-stretch">
					<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 d-flex" style="margin-bottom : 20px; " data-aos="fade-up">
						<div class="custom-card w-100">
							<img src="{{ asset('assets/front/images/new-images/citykrugy-about.png') }}" alt="citykrugy-about">
							<h3 class="card-title"> Fast Selling Properties In Surat</h3>
							<p class="card-text">
								We are Surat’s most trusted and best-selling real estate agency, specializing in luxury residences, premium commercial spaces, and profitable investment properties. With years of expertise, a wide property portfolio, and strong market knowledge, we’ve helped countless families and investors find exactly what they’re looking for.
							</p>
							<a href="{{ route('frontend.properties.featured.all') }}" class="vs-btn" style="padding: 10px 20px;">View All</a>
							
						</div>
					</div>
					<div class="col-xl-9 col-lg-8 col-md-6 col-sm-6 d-flex new-my-div" style="position: relative;" data-aos="fade-up">
						<div class="swiper product-slider w-100">
							<div class="swiper-wrapper">
								@forelse ($fastSellingProperties as $property)
								<div class="swiper-slide">
									<x-property :property="$property" />
								</div>
								@empty
								<div class="p-3 text-center mb-30 w-100">
									<h3 class="mb-0">{{ __('No Fast Selling Property Found') }}</h3>
								</div>
								@endforelse
							</div>
							<div class="swiper-pagination position-static mb-30" id="product-slider-pagination"></div>
						</div>
						
						<div class="swiper-button-prev first-left custom-swiper-btn"></div>
						<div class="swiper-button-next first-right custom-swiper-btn"></div>
					</div>
				</div>
			</div>
		</div>
	</section> 
@endif

@if ($secInfo->property_section_status == 1)
	<section class="product-area popular-product product-1 pt-70 pb-70 relative" style="background : #F8F7F1;">
		<img src="{{ asset('assets/front/images/new-images/new-primume-properties.png') }}" alt="" class="new-primume-prop-img">
		<div class="container" style="margin-top: 50px;">
			<div class="row">
				<div class="col-12">
					<div class="section-title text-center mb-40" data-aos="fade-up">
						<h2 class="title">{{ $propertySecInfo->title }}</h2>
						<p class="mt-4">Stay updated with the newest property listings. Discover fresh options for buying, selling, or renting in just a few clicks.
						</p>
						
					</div>
				</div>
				<div class="col-12">
					<div class="tab-content" data-aos="fade-up">
						<div class="row new-padding-width-res" style="position: relative;">
							<!-- Slider wrapper -->
							<div class="swiper LP-new-slider">
								<div class="swiper-wrapper">
									@forelse ($properties as $property)
										@if($property->property_type == 'partial')
											<div class="swiper-slide">
												<x-property :property="$property" class="col-12" />
											</div>
										@endif
									@empty
										<div class="p-3 text-center mb-30">
											<h3 class="mb-0">{{ __('No Properties Found') }}</h3>
										</div>
									@endforelse
								</div>
							</div>
							
							<!-- Navigation arrows -->
							<div class="LP-new-left-btn">
								<img src="{{ asset('assets/front/images/new-images/left.png') }}" alt="">
							</div>
							<div class="LP-new-right-btn">
								<img src="{{ asset('assets/front/images/new-images/Right.png') }}" alt="">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
@endif



<section class="product-area popular-product product-1 pt-70 pb-70 relative">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title text-center mb-40 aos-init aos-animate" data-aos="fade-up">
                    <h2 class="title">Verified property </h2>
                    <p class="mt-4 desss">Explore a diverse collection of verified real estate listings. From residential spaces to commercial investments, find trusted properties that meet your goals with complete peace of mind.
					</p>
				</div>
				
				
			</div>
		</div>
		
        <div class="row new-padding-width-res" style="position: relative;">
            <!-- Slider wrapper -->
            <div class="swiper verify-f-s-slider">
                <div class="swiper-wrapper">
                    @forelse ($business_for_sale as $sale)
                    <div class="swiper-slide">
                        <x-property :property="$sale" class="col-12" />
					</div>
                    @empty
                    <div class="p-3 text-center mb-30">
                        <h3 class="mb-0">{{ __('No Properties Found') }}</h3>
					</div>
                    @endforelse
				</div>
			</div>
			
            <!-- Navigation arrows -->
            <div class="verify-f-s-left-btn">
                <img src="{{ asset('assets/front/images/new-images/left.png') }}" alt="">
			</div>
            <div class="verify-f-s-right-btn">
                <img src="{{ asset('assets/front/images/new-images/Right.png') }}" alt="">
			</div>
		</div>
		
		
	</div>
</section>


<section class="product-area popular-product product-1 pt-70 pb-70 relative">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title text-center mb-40 aos-init aos-animate" data-aos="fade-up">
                    <h2 class="title">Business For Sale</h2>
                    <p class="mt-4">Explore a wide range of businesses up for sale. From retail outlets to commercial setups, find the right opportunity to invest and grow.
					</p>
				</div>
			</div>
		</div>
		
        <div class="row  new-padding-width-res" style="position : relative;">
            <!-- Slider wrapper -->
            <div class="swiper bussiness-f-s-slider">
                <div class="swiper-wrapper">
                    @forelse ($business_for_sale as $sale)
                    <div class="swiper-slide">
                        <x-property :property="$sale" class="col-12" />
					</div>
                    @empty
                    <div class="p-3 text-center mb-30">
                        <h3 class="mb-0"> {{ __('No Properties Found') }}</h3>
					</div>
                    @endforelse
				</div>
			</div>
			
            <!-- Navigation arrows -->
            <div class="bussiness-f-s-left-btn">
                <img src="{{ asset('assets/front/images/new-images/left.png') }}" alt="">
			</div>
            <div class="bussiness-f-s-right-btn">
                <img src="{{ asset('assets/front/images/new-images/Right.png') }}" alt="">
			</div>
		</div>
	</div>
</section>




<section class="product-area popular-product product-1 pt-70 pb-70 relative" style="background:#F8F7F1;">
    <div class="container" style="margin-top: 50px;">
        <div class="row" style="position: relative;">
            <div class="col-12">
                <div class="section-title text-center mb-40 aos-init aos-animate" data-aos="fade-up">
                    <h2 class="title">Franchiese</h2>
                    <p class="mt-4">Join hands with Dala Maaf and become a part of a growing network. Unlock business opportunities with our easy-to-start franchise model.</p>
				</div>
			</div>
			
			
            <div class="col-12">
                <!-- Swiper Slider Wrapper -->
                <div class="swiper fren-new-slider">
                    <div class="swiper-wrapper">
                        @forelse ($franchiese as $franchie)
                        <div class="swiper-slide">
                            {{-- property component --}}
                            <x-property :property="$franchie" />
						</div>
                        @empty
                        <div class="p-3 text-center mb-30">
                            <h3 class="mb-0"> {{ __('No Properties Found') }}</h3>
						</div>
                        @endforelse
					</div>
					
					
				</div>
				
                <!-- Navigation Buttons -->
                <div class="fren-new-left-btn">
                    <img src="{{ asset('assets/front/images/new-images/left.png') }}" alt="">
				</div>
                <div class="fren-new-right-btn">
                    <img src="{{ asset('assets/front/images/new-images/Right.png') }}" alt="">
				</div>
			</div>
		</div>
	</div>
</section>


<div class="container my-5 upcoming-projects" data-aos="fade-up">

    <div class="row">
        <div class="col-12">
            <div class="section-title title-center mb-40" data-aos="fade-up">
                <h2 class="title">Upcoming Project</h2>
            </div>
        </div>
    </div>

    <!-- Slider Wrapper -->
    <div class="up-comming-slide-wrapper mt-4" data-aos="fade-up">

        <!-- Left Arrow -->
        <button class="arrow-button-pro arrow-left-pro hidden" id="up-comming-prev">
            <img src="{{ asset('assets/front/images/new-images/left.png') }}" alt="Prev">
        </button>

        <!-- Slider -->
        <div class="up-comming-project" style="height: fit-content;">
            @foreach($projects as $project)
            <a href="{{ route('frontend.projects.details', ['slug' => $project->slug]) }}" style="height : auto;">
                <div class="new-up-cards">

                    <img src="{{ asset('assets/img/project/featured/' . $project->featured_image) }}" alt="Project" class="upcoming-projects-img">

                    <div class="upcomming-card-body">
                        <h5>{{ $project->title }}</h5>
                        <p class="text-muted">{{ $project->address }}</p>
                        <h6 class="up-price">
                            {{ symbolPrice($project->min_price) }}
                            <small class="text-muted">Starting</small>
                        </h6>
                    </div>

                </div>

            </a>

            @endforeach
        </div>

        <!-- Right Arrow -->
        <button class="arrow-button-pro arrow-right-pro hidden" id="up-comming-next">
            <img src="{{ asset('assets/front/images/new-images/Right.png') }}" alt="Next">
        </button>
    </div>


</div>

<section class="city-section">
    <img src="{{ asset('assets/front/images/acrs-imag/building-vector.png') }}" alt="" class="building-vector">
    <div class="container">
        <div class="city-details">
            <h6>First-time buyer, investor, or renter? Dala Maaf connects you to trusted properties in prime locations—your real estate partner.</h6>
            <p>Looking for your dream home, the right investment, or the best rental deal? Dala Maaf makes it simple. With thousands of verified listings, expert insights, and user-friendly search options, you can buy, sell, or rent properties with complete confidence.</p>
            <p>Whether you’re a first-time buyer, an investor, or someone searching for the perfect rental, Dala Maaf connects you to trusted property options across prime locations. It’s more than a platform—it’s your partner in real estate.</p>
            <div style="margin-top: 30px;">
                <a href="{{ route('frontend.properties') }}" class="find-your-btn">Find Your Perfect Property with Dala Maaf</a>
			</div>
		</div>
	</div>
</section>

@if ($secInfo->why_choose_us_section_status == 1)
<section class="new-aps-sections  pt-70 pb-70">
    <div class="container new-padding-des-res">
        <div class="section-title title-inline mb-40 aos-init aos-animate d-flex justify-content-center" data-aos="fade-up">
            <h2 class="title">Explore Property Types</h2>
		</div>
		
        <div class="aps-slide-wrapper " style="margin-top : 50px;" data-aos="fade-up">
            <!-- Left Arrow -->
            <button class="arrow-button arrow-left" id="arrowLeft"><img src="{{ asset('assets/front/images/new-images/left.png') }}" alt=""></button>
			
            <!-- Slider -->
            <div class="aps-slider ">
                @foreach ($all_proeprty_categories as $category)
                <div class="new-aps-titles-bag">
                    <a href="{{ route('frontend.properties',['category'=>$category->categoryContent->name]) }}">
                        <img src="{{ asset('assets/img/property-category/' . $category->image) }}" alt="" class="new-images-aps-type">
                        <div class="new-type-title">
                            <h6 class="aps-type">{{ @$category->categoryContent->name }}</h6>
                            <h6 class="aps-type-property">{{ $category->properties_count }} properties</h6>
						</div>
					</a>
				</div>
                @endforeach
				
			</div>
			
            <!-- Right Arrow -->
            <button class="arrow-button arrow-right" id="arrowRight"><img src="{{ asset('assets/front/images/new-images/Right.png') }}" alt=""></button>
		</div>
		
		
		
	</div>
</section>
@endif




<section class="buy-rent-sale-section">
    <div class="container">
		
		
		
        <div class="section-title title-inline mb-40 aos-init aos-animate d-flex justify-content-center" data-aos="fade-up">
            <h2 class="title">For all your luxury real estate needs, We have got you covered!</h2>
            <p>From finding your dream home with all the luxury amenities to seamless transactions, trust us to handle every detail with care and expertise.</p>
		</div>
		
        <div class="row">
            <div class="col-lg-4 b-s-r-div-main">
                <div class="b-s-r-div ">
                    <img src="{{ asset('assets/front/images/acrs-imag/buy.png') }}" alt="" class="b-s-r-img">
                    <h5 class="but-title">Buy</h5>
                    <p class="but-title-p">Discover thousands of verified listings across residential and commercial spaces. Find your dream home or perfect investment with our easy-to-use search and trusted property details.</p>
				</div>
			</div>
            <div class="col-lg-4 b-s-r-div-main">
                <div class="b-s-r-div ">
                    <img src="{{ asset('assets/front/images/acrs-imag/sale.png') }}" alt="" class="b-s-r-img">
                    <h5 class="but-title">Sell</h5>
                    <p class="but-title-p">List your property in minutes and connect with serious buyers. With DalalMaf’s wide reach and trusted network, selling your property has never been faster or easier.</p>
					
				</div>
			</div>
            <div class="col-lg-4 b-s-r-div-main">
                <div class="b-s-r-div ">
                    <img src="{{ asset('assets/front/images/acrs-imag/rent.png') }}" alt="" class="b-s-r-img">
                    <h5 class="but-title">Rent</h5>
                    <p class="but-title-p">Looking for a home or office on rent? Explore verified rental options that fit your budget and lifestyle. Hassle-free rentals with trusted landlords, all in one place.</p>
					
				</div>
			</div>
		</div>
		
		
	</div>
</section>

<!-- @if ($secInfo->counter_section_status == 1)
	<div class="counter-area pt-100 pb-70">
	<div class="container">
	<div class="row gx-xl-5" data-aos="fade-up">
	@forelse ($counters as $counter)
	<div class="col-sm-6 col-lg-3">
	<div class="card mb-30">
	<div class="d-flex align-items-center justify-content-center mb-10">
	<div class="card-icon me-2 color-secondary"><i class="{{ $counter->icon }}"></i>
	</div>
	<h2 class="m-0 color-secondary"><span class="counter">{{ $counter->amount }}</span>+
	</h2>
	</div>
	<p class="card-text text-center">{{ $counter->title }}</p>
	</div>
	</div>
	@empty
	<div class="col-12">
	<h3 class="text-center mt-20"> {{ __('No Counter Information Found') }} </h3>
	</div>
	@endforelse
	</div>
	</div>
	</div>
@endif -->



<!-- @if ($secInfo->about_section_status == 1)
	<section class="about-area pb-70 pt-30">
    <div class="container">
	<div class="row gx-xl-5">
	<div class="col-lg-6">
	<div class="img-content mb-30" data-aos="fade-up">
	<div class="image">
	<img class="lazyload blur-up"
	data-src="{{ asset('assets/img/about_section/' . $aboutImg->about_section_image1) }}">
	
	<img class="lazyload blur-up"
	data-src="{{ asset('assets/img/about_section/' . $aboutImg->about_section_image2) }}">
	</div>
	<div class="absolute-text bg-secondary">
	<div class="center-text">
	<span class="h2 color-primary">{{ $aboutInfo?->years_of_expricence }}+</span>
	<span>{{ __('Years') }}</span>
	</div>
	<div id="curveText">{{ __('We are highly experience') }}</div>
	</div>
	</div>
	</div>
	<div class="col-lg-6">
	<div class="content mb-30" data-aos="fade-up">
	<div class="content-title">
	<span class="subtitle"><span class="line"></span>
	{{ $aboutInfo->title }}</span>
	<h2>{{ $aboutInfo?->sub_title }}</h2>
	</div>
	<div class="text summernote-content">{!! $aboutInfo?->description !!}</div>
	
	<div class="d-flex align-items-center flex-wrap gap-15">
	@if (!empty($aboutInfo->btn_url))
	<a href="{{ $aboutInfo->btn_url }}"
	class="btn btn-lg btn-primary bg-secondary">{{ $aboutInfo?->btn_name }}</a>
	@endif
	@if (!empty($aboutInfo->client_text))
	<div class="clients d-flex align-items-center flex-wrap gap-2">
	<div class="client-img">
	<img class="lazyload"
	data-src="  {{ asset('assets/front/images/client/client-1.jpg') }}">
	<img class="lazyload"
	data-src="  {{ asset('assets/front/images/client/client-2.jpg') }}">
	<img class="lazyload"
	data-src="  {{ asset('assets/front/images/client/client-3.jpg') }}">
	<img class="lazyload"
	data-src="  {{ asset('assets/front/images/client/client-4.jpg') }}">
	</div>
	@endif
	<span>{{ $aboutInfo?->client_text }}</span>
	</div>
	</div>
	</div>
	</div>
	</div>
    </div>
	</section>
@endif -->








<section class="why-dlal-dection">
    <div class="container">
        <div class="section-title title-inline mb-40 aos-init aos-animate d-flex justify-content-center" style="flex-direction: column;" data-aos="fade-up">
            <h2 class="title">Why DalalMaf ?</h2>
            <p>For Your Buying, Selling & Renting in Real Estate</p>
		</div>
		
        <div class="row">
            <div class="col-lg-6">
                <div class="why-main-div">
                    <h6>Best Deals at Your Fingertips</h6>
                    <p>Find verified properties at the most competitive prices. Whether buying, selling, or renting, DalalMaf ensures you get the best value for your money.</p>
                    <div class="icons">
                        <img src="{{ asset('assets/front/images/acrs-imag/why-1.png') }}" alt="">
					</div>
				</div>
			</div>
            <div class="col-lg-6">
                <div class="why-main-div">
                    <h6>Verified & Trusted Listings </h6>
                    <p>Every property listed on DalalMaf goes through a strict verification process, ensuring that you connect only with genuine sellers, buyers, and renters.</p>
                    <div class="icons">
                        <img src="{{ asset('assets/front/images/acrs-imag/why-2.png') }}" alt="">
					</div>
				</div>
			</div>
            <div class="col-lg-6">
                <div class="why-main-div">
                    <h6>Expert Support Anytime </h6>
                    <p>From searching properties to closing the deal, our dedicated support team is here to guide you at every step, making your journey smooth and hassle-free.</p>
                    <div class="icons">
                        <img src="{{ asset('assets/front/images/acrs-imag/why-3.png') }}" alt="">
					</div>
				</div>
			</div>
            <div class="col-lg-6">
                <div class="why-main-div">
                    <h6>Wide Network & Easy Access </h6>
                    <p>With thousands of listings across residential, commercial, and rental properties, DalalMaf gives you a wide choice and helps you find the perfect property quickly.</p>
                    <div class="icons">
                        <img src="{{ asset('assets/front/images/acrs-imag/why-4.png') }}" alt="">
					</div>
				</div>
			</div>
		</div>
		
	</div>
</section>

<section class="about-section-new">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 about-lrft-sections-new">
                <!-- <div class="new-ace-about-section-property-card">
					<img src="https://images.pexels.com/photos/674010/pexels-photo-674010.jpeg?cs=srgb&dl=pexels-anjana-c-169994-674010.jpg&fm=jpg" alt="Main Property" class="new-ace-about-section-property-main-img">
					
					
					<div class="new-ace-about-section-property-video-thumb">
					<img src="https://thumbs.dreamstime.com/b/environment-earth-day-hands-trees-growing-seedlings-bokeh-green-background-female-hand-holding-tree-nature-field-118143566.jpg" alt="Video Preview">
					</div>
				</div>-->
                <img src="{{ asset('assets/front/images/acrs-imag/about-right.png') }}" alt="" class="about-llrs-img">
				
				
			</div>
            <div class="col-lg-6">
                <h1 class="ab-le-title">About The Story Behind Us</h1>
                <p class="ab-le-par">Welcome to DalalMaf, your trusted platform for buying, selling, and renting properties.
                    We are committed to making real estate simple, transparent, and accessible for everyone. Whether you are a first-time buyer, a property investor, or someone looking to rent a home, DalalMaf connects you with verified listings and trusted partners.
				</p>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="about-right-pd">
                            <div class="abs-r-i-des-div">
                                <h6>{{ $cityCount }}</h6>
                                <h5>Cities Covered</h5>
							</div>
                            <div class="abt-r-i-div">
                                <img src="{{ asset('assets/front/images/acrs-imag/abs-01.png') }}" alt="">
							</div>
						</div>
					</div>
                    <div class="col-lg-6">
                        <div class="about-right-pd">
                            <div class="abs-r-i-des-div">
                                <h6>{{ $userCount }}</h6>
                                <h5>Happy Customers</h5>
							</div>
                            <div class="abt-r-i-div">
                                <img src="{{ asset('assets/front/images/acrs-imag/abs-02.png') }}" alt="">
							</div>
						</div>
					</div>
                    <div class="col-lg-6">
                        <div class="about-right-pd">
                            <div class="abs-r-i-des-div">
                                <h6>24/7</h6>
                                <h5>Customer Support</h5>
							</div>
                            <div class="abt-r-i-div">
                                <img src="{{ asset('assets/front/images/acrs-imag/abs-03.png') }}" alt="">
							</div>
						</div>
					</div>
                    <div class="col-lg-6">
                        <div class="about-right-pd">
                            <div class="abs-r-i-des-div">
                                <h6>{{ $vendorCount }}</h6>
                                <h5>Trusted Partners</h5>
							</div>
                            <div class="abt-r-i-div">
                                <img src="{{ asset('assets/front/images/acrs-imag/abs-04.png') }}" alt="">
							</div>
						</div>
					</div>
				</div>
                <div style="margin-top: 30px;">
                    <a href="{{ route('about_us') }}" class="btn-cus-header">Know More About Us</a>
				</div>
			</div>
		</div>
	</div>
</section>




{{-- <section class="ready-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="re-left-div">
                    <h5 style="text-transform: capitalize;">
                        We’re here to help you with all your real estate needs.
					</h5>
                    <p>
						
                        Whether you’re buying, selling, renting, or just exploring options — our team is ready to guide you every step of the way.
					</p>
				</div>
			</div>
            <div class="col-lg-6">
                <div>
                    <form class="contact-form">
                        <h1 class="visit0class">Schudeal a Visit</h1>
                        <div class="form-group">
                            <input type="text" placeholder="Your Name*" required>
                            <span class="icon fa fa-user"></span>
						</div>
                        <div class="form-group">
                            <input type="email" placeholder="Your Email*" required>
                            <span class="icon fa fa-envelope"></span>
						</div>
                        <div class="form-group">
                            <select required>
                                <option value="">Real Estate*</option>
                                <option value="buy">Buy</option>
                                <option value="sell">Sell</option>
                                <option value="rent">Rent</option>
							</select>
                            <span class="icon dropdown"></span>
						</div>
                        <div class="form-group">
                            <textarea placeholder="Type Your Message*" required></textarea>
                            <span class="icon fa fa-envelope"></span>
						</div>
                        <button type="submit">Submit Message</button>
					</form>
					
				</div>
			</div>
		</div>
	</div>
</section> --}}







@if ($secInfo->vendor_section_status == 1)
<section class="pt-70">
    <div class="container">
		
        <div class="row">
			
            <div class="col-12">
                <div class="section-title title-center mb-40" data-aos="fade-up">
                    <h2 class="title">{{ $vendorInfo?->subtitle }}</h2>
				</div>
			</div>
			
            @forelse ($vendors as $vendor)
			
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="new-company-card " data-aos="fade-up">
                    <div class="logo-container">
                        <img class="lazyload" data-src="{{ $vendor->photo ? asset('assets/admin/img/vendor-photo/' . $vendor->photo) : asset('assets/img/blank-user.jpg') }}">
					</div>
                    <div class="company-details">
						
                        <h3>{{ $vendor->vendor_info['name'] ?? 'Not Available'}}</h3>
                        <div>
                            @php
                            $agents = App\Models\Agent::where('vendor_id', $vendor->vendorId)->get();
                            @endphp
							
                            <p class="new-fonts-property">{{ count($vendor->properties) }} {{ __('Properties') }}</p>
                            <p class="new-fonts-property">{{ count($vendor->agents) }} {{ __('Staffs') }}</p>
                            <p class="new-fonts-property">{{ count($vendor->projects) }} {{ __('Projects') }}</p>
						</div>
                        <!-- <div class="mt-3">
                            <a href="{{ route('frontend.vendor.details', ['username' => $vendor->username]) }}"
							class="btn-text view-profile">{{ __('View Profile') }}</a>
						</div> -->
					</div>
				</div>
			</div>
            @empty
            <div class="p-3 text-center mb-30 w-100">
                <h3 class="mb-0"> {{ __('No Partners Found') }}</h3>
			</div>
            @endforelse
		</div>
        @if (count($vendors) > 0)
        <div class="text-center mt-4">
            <a href="{{ route('frontend.vendors') }}"
			class="btn btn-lg btn-primary  mb-30" style="background-color:  #6c603c;">{{ $vendorInfo->btn_name }}</a>
		</div>
        @endif
	</div>
</section>
@endif

@if ($secInfo->cities_section_status == 1)

<section class="new-gellary-area pt-70 pb-70 relative ">
	
    <img src="http://127.0.0.1:8000/assets/front/images/new-images/new-primume-properties.png" alt="" class="exp-img">
	
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title title-center mb-40" data-aos="fade-up">
                    <h2 class="title">{{ $citySecInfo?->subtitle }}</h2>
				</div>
			</div>
			
		</div>
        <div class="row justify-content-center new-city-design">
            <!-- City Card Start -->
            @forelse ($cities as $city)
            <div class="custom-col-5 mt-4" data-aos="fade-up">
                <a href="{{ route('frontend.properties', ['city' => $city->name]) }}" class="text-center" style="display: block; text-decoration: none; color: inherit;">
                    <div class="city-card text-center">
                        <img class="lazyload blur-up" data-src="{{ asset('assets/img/property-city/' . $city->image) }}">
                        <p>{{ $city->name }}</p>
                        <p>
                            {{ $city->propertyCount }}
                            @if ($city->propertyCount > 0)
                            {{ __('Properties') }}
                            @else
                            {{ __('Property') }}
                            @endif
						</p>
					</div>
				</a>
			</div>
            @empty
            <div class="p-3 text-center mb-30 w-100">
                <h3 class="mb-0">{{ __('No Cities Found') }}</h3>
			</div>
            @endforelse
			
            {{-- <div class="mt-3" data-aos="fade-up">
                <div class="city-tag-wrapper" id="cityTagWrapper">
                    <span class="city-tag">Surat</span>
                    <span class="city-tag">Thane</span>
                    <span class="city-tag">New Mumbai</span>
                    <span class="city-tag">Noida</span>
                    <span class="city-tag">Nagpur</span>
                    <span class="city-tag">Varanasi</span>
                    <span class="city-tag">Bhiwandi</span>
                    <span class="city-tag">Agra</span>
                    <span class="city-tag">Ajmer</span>
                    <span class="city-tag">Akola</span>
                    <span class="city-tag">Almora</span>
                    <span class="city-tag">Bharuch</span>
                    <span class="city-tag">Baroda</span>
                    <span class="city-tag">Rajkot</span>
                    <span class="city-tag">Bhavnagar</span>
				</div>
				
			</div> --}}
			
			
		</div>
        {{-- <div class="text-center" style="margin-top : 30px;">
            <a href="http://127.0.0.1:8000/vendors" class="btn btn-lg btn-primary  mb-30" style="background : black;">See All</a>
		</div> --}}
		
	</div>
	
    <img src="{{ asset('assets/front/images/new-images/left-img.png') }}" alt="" class="city-left">
    <img src="{{ asset('assets/front/images/new-images/right-img.png') }}" alt="" class="city-right">
</section>
@endif

<!-- @if ($secInfo->testimonial_section_status == 1)
	<section class="testimonial-area pt-100 pb-70">
    <div class="overlay-bg d-none d-lg-block">
	<img class="lazyload blur-up" data-src="{{ asset('assets/img/' . $testimonialSecImage) }}">
    </div>
    <div class="container">
	<div class="row align-items-center">
	<div class="col-lg-4">
	<div class="content mb-30" data-aos="fade-up">
	<div class="content-title">
	<span class="subtitle"><span
	class="line"></span>{{ $testimonialSecInfo->title }}</span>
	<h2 class="title">
	{{ $testimonialSecInfo?->subtitle }}
	</h2>
	</div>
	<p class="text mb-30">
	{{ $testimonialSecInfo?->content }}
	</p>
	
	<div class="slider-navigation scroll-animate">
	<button type="button" title="Slide prev" class="slider-btn slider-btn-prev">
	<i class="fal fa-angle-left"></i>
	</button>
	<button type="button" title="Slide next" class="slider-btn slider-btn-next">
	<i class="fal fa-angle-right"></i>
	</button>
	</div>
	</div>
	</div>
	<div class="col-lg-8" data-aos="fade-up">
	<div class="swiper" id="testimonial-slider-1">
	<div class="swiper-wrapper">
	@forelse ($testimonials as $testimonial)
	<div class="swiper-slide pb-30" data-aos="fade-up">
	<div class="slider-item">
	<div class="client-img">
	<div class="lazy-container ratio ratio-1-1">
	@if (is_null($testimonial->image))
	<img data-src="{{ asset('assets/img/profile.jpg') }}"
	class="lazyload">
	@else
	<img class="lazyload"
	data-src="{{ asset('assets/img/clients/' . $testimonial->image) }}">
	@endif
	
	
	</div>
	</div>
	<div class="client-content mt-30">
	<div class="quote">
	<p class="text">{{ $testimonial->comment }}</p>
	</div>
	<div
	class="client-info d-flex flex-wrap gap-10 align-items-center justify-content-between">
	<div class="content">
	<h6 class="name">{{ $testimonial->name }}</h6>
	<span class="designation">{{ $testimonial->occupation }}</span>
	</div>
	<div class="ratings">
	
	<div class="rate">
	<div class="rating-icon"
	style="width: {{ $testimonial->rating * 20 }}%"></div>
	</div>
	<span class="ratings-total">({{ $testimonial->rating }}) </span>
	</div>
	</div>
	</div>
	</div>
	</div>
	@empty
	<div class="bg-light p-3 text-center mb-30 w-100">
	<h3 class="mb-0"> {{ __('No Testimonials Found') }}</h3>
	</div>
	@endforelse
	</div>
	</div>
	</div>
	</div>
    </div>
	</section>
@endif -->

<!-- @if ($secInfo->subscribe_section_status == 1)
	<section class="newsletter-area pb-100" data-aos="fade-up">
    <div class="container">
	<div class="newsletter-inner px-4">
	<img class="lazyload bg-img" src="{{ asset('assets/img/' . $subscribeSectionImage) }}">
	<div class="row justify-content-center text-center" data-aos="fade-up">
	<div class="col-lg-6 col-xxl-5">
	<div class="content mb-30">
	<span class="subtitle color-white mb-10 d-block">{{ $subscribeSecInfo->title }}</span>
	<h2 class="color-white">{{ $subscribeSecInfo?->subtitle }}</h2>
	</div>
	<form id="newsletterForm" class="subscription-form newsletter-form"
	action="{{ route('store_subscriber') }}" method="POST">
	@csrf
	<div class="input-group radius-md">
	<input class="form-control" placeholder="{{ __('Enter Your Email') }}"
	type="email" name="email_id" required>
	<button class="btn btn-lg btn-primary" type="submit">
	{{ $subscribeSecInfo->btn_name ?? __('Start Now') }}</button>
	</div>
	</form>
	</div>
	</div>
	</div>
    </div>
	</section>
@endif -->





<div class="main-blog" data-aos="fade-up">
    <div class="container" style="padding-top: 100px; padding-bottom: 100px; ">
		
        <div class="row">
            <div class="col-12">
                <div class="section-title title-center mb-40" data-aos="fade-up">
                    <h2 class="title">Our Blogs</h2>
				</div>
			</div>
		</div>
		
        <div class="row g-4 mt-4">
			
            @foreach ($blogs as $blog)
            <div class="col-md-4">
                <div class="blog-card">
                    <img src="{{ asset('assets/img/blogs/' . $blog->image) }}" class="blog-img" alt="Blog Image">
                    <div class="blog-body">
                        <h5 class="blog-title">{{$blog->title}}</h5>
                        <p class="blog-text">{{ \Illuminate\Support\Str::limit(strip_tags($blog->content), 90) }}
						</p>
                        <a href="{{ route('blog_details', ['slug' => $blog->slug]) }}" class="show-more">Show More →</a>
					</div>
                    <div class="blog-footer">
                        <img src="https://i.pravatar.cc/50" class="author-img" alt="Author">
                        <div class="author-info">
                            <span>{{$blog->author}}</span>
                            <small>{{ $blog->created_at->diffForHumans() }}</small>
						</div>
					</div>
				</div>
			</div>
            @endforeach
			
		</div>
        <div class="text-center mt-4">
            <a href="{{ route('blog') }}"
			class="btn btn-lg btn-primary  mb-30" style="background-color:  #6c603c;">All Blogs</a>
		</div>
	</div>
</div>
@php
$imagePaths = is_array($heroImg) ? array_map(function($img) {
return asset('assets/img/hero/static/' . $img);
}, $heroImg) : [asset('assets/img/noimage.jpg')];
@endphp
@endsection

@section('script')
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script>
    // apartment slide ===============
    $(document).ready(function() {
        const $slider = $('.aps-slider');
		
        $slider.slick({
            slidesToShow: 6,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 4000,
            dots: false,
            arrows: false,
            pauseOnHover: true, // autoplay hover पर रुक जाएगा
            responsive: [{
				breakpoint: 1200,
				settings: {
					slidesToShow: 3
				}
			},
			{
				breakpoint: 992,
				settings: {
					slidesToShow: 2
				}
			},
			{
				breakpoint: 576,
				settings: {
					slidesToShow: 1
				}
			}
            ]
		});
		
        // Custom arrows
        $('#arrowLeft').on('click', function() {
            $slider.slick('slickPrev');
		});
		
        $('#arrowRight').on('click', function() {
            $slider.slick('slickNext');
		});
		
        // जब mouse slider पर जाए → drag/swipe disable
        $slider.on('mouseenter', function() {
            $slider.slick('slickSetOption', 'swipe', false, false);
            $slider.slick('slickSetOption', 'draggable', false, false);
		});
		
        // जब mouse slider से हटे → drag/swipe enable
        $slider.on('mouseleave', function() {
            $slider.slick('slickSetOption', 'swipe', true, false);
            $slider.slick('slickSetOption', 'draggable', true, false);
		});
		
        // Function to update arrow visibility
        function updateArrows(event, slick, currentSlide) {
            if (slick.slideCount <= slick.options.slidesToShow) {
                $('#arrowLeft, #arrowRight').addClass('hidden');
                return;
			}
            if (slick.currentSlide === 0) {
                $('#arrowLeft').addClass('hidden');
				} else {
                $('#arrowLeft').removeClass('hidden');
			}
            if (slick.currentSlide >= slick.slideCount - slick.options.slidesToShow) {
                $('#arrowRight').addClass('hidden');
				} else {
                $('#arrowRight').removeClass('hidden');
			}
		}
		
        $slider.on('init reInit afterChange', updateArrows);
		
        $slider.on('init', function(event, slick) {
            updateArrows(event, slick, 0);
		});
		
        $slider.slick('setPosition');
	});
	
	
	
	
	
    $(document).ready(function() {
        const $slider = $('.up-comming-project');
        const $prev = $('#up-comming-prev');
        const $next = $('#up-comming-next');
		
        // Init slick
        $slider.slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 3000,
            dots: false,
            arrows: false,
            infinite: false,
            pauseOnHover: true,
            swipe: false, // Disable swipe/drag
            draggable: false, // Disable mouse drag
            touchMove: false, // Disable touch drag
            responsive: [{
				breakpoint: 1100,
				settings: {
					slidesToShow: 2
				}
			},
			{
				breakpoint: 576,
				settings: {
					slidesToShow: 1
				}
			}
            ]
		});
		
        // Custom arrows
        $prev.on('click', function() {
            $slider.slick('slickPrev');
		});
        $next.on('click', function() {
            $slider.slick('slickNext');
		});
		
        // Arrow visibility
        function updateArrows(event, slick) {
            const current = slick.currentSlide;
            const maxSlides = slick.slideCount - slick.options.slidesToShow;
			
            if (slick.slideCount <= slick.options.slidesToShow) {
                $prev.addClass('hidden');
                $next.addClass('hidden');
                return;
			}
			
            $prev.toggleClass('hidden', current === 0);
            $next.toggleClass('hidden', current >= maxSlides);
		}
		
        $slider.on('init reInit afterChange', updateArrows);
        $slider.slick('slickGoTo', 0);
		
        // Stop slider autoplay on hover
        $slider.on('mouseenter', function() {
            $slider.slick('slickPause');
		});
        $slider.on('mouseleave', function() {
            $slider.slick('slickPlay');
		});
		
		
	});
	
	
	
    // Hero Section  Swiper====================
	
    document.addEventListener("DOMContentLoaded", function() {
        const images = @json($imagePaths);
		
        let index = 0;
        const hero = document.getElementById("heroBanner");
		
        if (images.length > 0) {
            hero.style.backgroundImage = `url('${images[0]}')`;
			
            setInterval(() => {
                index = (index + 1) % images.length;
                hero.style.backgroundImage = `url('${images[index]}')`;
			}, 5000); // change every 5 sec
		}
	});
    $(function() {
        $(".searchBar").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('frontend.location.search') }}",
                    data: {
                        term: request.term
					},
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                label: item.text, // what to show
                                value: item.text, // what fills input
                                id: item.id // area id
							};
						}));
					}
				});
			},
            minLength: 2,
            select: function(event, ui) {
                console.log("Selected Area ID: " + ui.item.id);
			}
		});
	});
	
	
    // product-2 ================================
	
	
    document.addEventListener('DOMContentLoaded', function() {
        // query the buttons inside this slider container
        const prevBtn = document.querySelector('.product-slider .swiper-button-prev');
        const nextBtn = document.querySelector('.product-slider .swiper-button-next');
		
        var productSwiper = new Swiper(".product-slider", {
            loop: false,
            spaceBetween: 20,
            pagination: {
                el: "#product-slider-pagination",
                clickable: true,
			},
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
			},
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
			},
            breakpoints: {
                320: {
                    slidesPerView: 1
				},
                576: {
                    slidesPerView: 2
				},
                991: {
                    slidesPerView: 3
				},
                1200: {
                    slidesPerView: 4
				},
                1400: {
                    slidesPerView: 4
				},
                1600: {
                    slidesPerView: 4
				}
			},
			
            // keep Swiper aware of DOM changes (useful if your slides are rendered after load)
            observer: true,
            observeParents: true,
			
            // attach events to update nav visibility
            on: {
                init: function() {
                    updateNav(this);
				},
                slideChange: function() {
                    updateNav(this);
				},
                resize: function() {
                    updateNav(this);
				},
                breakpoint: function() {
                    updateNav(this);
				},
                observerUpdate: function() {
                    updateNav(this);
				}
			}
		});
		
        // function to hide/show nav buttons
        function updateNav(swiper) {
            // safety: if buttons don't exist, do nothing
            if (!prevBtn || !nextBtn) return;
			
            // If slider is "locked" (not enough slides for current view) hide both
            // swiper.isLocked is true when no sliding possible
            const notEnoughSlides = swiper.isLocked || (swiper.slides.length <= (swiper.params.slidesPerView || 1));
            if (notEnoughSlides) {
                prevBtn.classList.add('hidden');
                nextBtn.classList.add('hidden');
                return;
			}
			
            // show/hide based on position
            if (swiper.isBeginning) prevBtn.classList.add('hidden');
            else prevBtn.classList.remove('hidden');
            if (swiper.isEnd) nextBtn.classList.add('hidden');
            else nextBtn.classList.remove('hidden');
		}
	});
	
	
	
	
	
    // ===============
	
	
    document.addEventListener('DOMContentLoaded', function() {
        const prevBtnFS = document.querySelector('.bussiness-f-s-left-btn');
        const nextBtnFS = document.querySelector('.bussiness-f-s-right-btn');
        const sliderEl = document.querySelector('.bussiness-f-s-slider');
		
        if (!sliderEl) return;
		
        var businessFSSlider = new Swiper(sliderEl, {
            loop: false,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
			},
            spaceBetween: 20,
            slidesPerView: 4,
            simulateTouch: false, // 👈 drag disable
            allowTouchMove: false, // 👈 swipe disable
            navigation: {
                nextEl: nextBtnFS,
                prevEl: prevBtnFS,
			},
            breakpoints: {
                0: {
                    slidesPerView: 1
				},
                576: {
                    slidesPerView: 2
				},
                768: {
                    slidesPerView: 3
				},
                1200: {
                    slidesPerView: 4
				},
			},
            on: {
                init(swiper) {
                    updateNavFS(swiper);
				},
                slideChange(swiper) {
                    updateNavFS(swiper);
				},
                resize(swiper) {
                    updateNavFS(swiper);
				},
                breakpoint(swiper) {
                    updateNavFS(swiper);
				},
			}
		});
		
        // Hover → autoplay stop/start
        sliderEl.addEventListener("mouseenter", () => businessFSSlider.autoplay.stop());
        sliderEl.addEventListener("mouseleave", () => businessFSSlider.autoplay.start());
		
        // Update navigation buttons
        function updateNavFS(swiper) {
            if (!prevBtnFS || !nextBtnFS) return;
			
            const notEnoughSlides =
			swiper.isLocked || swiper.slides.length <= swiper.params.slidesPerView;
			
            if (notEnoughSlides) {
                prevBtnFS.classList.add('hidden');
                nextBtnFS.classList.add('hidden');
                return;
			}
			
            swiper.isBeginning ?
			prevBtnFS.classList.add('hidden') :
			prevBtnFS.classList.remove('hidden');
			
            swiper.isEnd ?
			nextBtnFS.classList.add('hidden') :
			nextBtnFS.classList.remove('hidden');
		}
	});
	
    // =======================
	
    document.addEventListener('DOMContentLoaded', function() {
        const sliderEl = document.querySelector('.fren-new-slider');
		
        var frenNewSlider = new Swiper(".fren-new-slider", {
            loop: false,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
			},
            spaceBetween: 20,
            slidesPerView: 4,
			
            // 🔒 Cursor/Swipe movement disable
            simulateTouch: false,
            allowTouchMove: false,
			
            navigation: {
                nextEl: ".fren-new-right-btn",
                prevEl: ".fren-new-left-btn",
			},
            breakpoints: {
                0: {
                    slidesPerView: 1
				},
                576: {
                    slidesPerView: 2
				},
                768: {
                    slidesPerView: 3
				},
                1200: {
                    slidesPerView: 4
				},
			},
            on: {
                init: function() {
                    toggleNavButtons(this);
				},
                slideChange: function() {
                    toggleNavButtons(this);
				},
			},
		});
		
        // 👉 Hover पर autoplay stop / leave पर start
        sliderEl.addEventListener("mouseenter", () => frenNewSlider.autoplay.stop());
        sliderEl.addEventListener("mouseleave", () => frenNewSlider.autoplay.start());
		
        function toggleNavButtons(swiper) {
            const prevBtn = document.querySelector('.fren-new-left-btn');
            const nextBtn = document.querySelector('.fren-new-right-btn');
			
            // Left button disable on first slide
            prevBtn.classList.toggle('swiper-button-disabled', swiper.isBeginning);
			
            // Right button disable on last slide
            nextBtn.classList.toggle('swiper-button-disabled', swiper.isEnd);
		}
	});
	
    // ===================================
	
    document.addEventListener('DOMContentLoaded', function() {
		const prevBtnFS = document.querySelector('.verify-f-s-left-btn');
		const nextBtnFS = document.querySelector('.verify-f-s-right-btn');
		const sliderEl = document.querySelector('.verify-f-s-slider');
		
		if (!sliderEl) return;
		
		var verifyFSSlider = new Swiper(sliderEl, {
			loop: false,
			autoplay: {
				delay: 3000,
				disableOnInteraction: false,
			},
			spaceBetween: 20,
			slidesPerView: 4,
			simulateTouch: false, // 👈 drag disable
			allowTouchMove: false, // 👈 swipe disable
			navigation: {
				nextEl: nextBtnFS,
				prevEl: prevBtnFS,
			},
			breakpoints: {
				0: {
					slidesPerView: 1
				},
				576: {
					slidesPerView: 2
				},
				768: {
					slidesPerView: 3
				},
				1200: {
					slidesPerView: 4
				},
			},
			on: {
				init(swiper) {
					updateNavFS(swiper);
				},
				slideChange(swiper) {
					updateNavFS(swiper);
				},
				resize(swiper) {
					updateNavFS(swiper);
				},
				breakpoint(swiper) {
					updateNavFS(swiper);
				},
			}
		});
		
		// Hover → autoplay stop/start
		sliderEl.addEventListener("mouseenter", () => verifyFSSlider.autoplay.stop());
		sliderEl.addEventListener("mouseleave", () => verifyFSSlider.autoplay.start());
		
		// Update navigation buttons
		function updateNavFS(swiper) {
			if (!prevBtnFS || !nextBtnFS) return;
			
			const notEnoughSlides =
            swiper.isLocked || swiper.slides.length <= swiper.params.slidesPerView;
			
			if (notEnoughSlides) {
				prevBtnFS.classList.add('hidden');
				nextBtnFS.classList.add('hidden');
				return;
			}
			
			swiper.isBeginning ?
            prevBtnFS.classList.add('hidden') :
            prevBtnFS.classList.remove('hidden');
			
			swiper.isEnd ?
            nextBtnFS.classList.add('hidden') :
            nextBtnFS.classList.remove('hidden');
		}
	});
	
	
	
	document.addEventListener('DOMContentLoaded', function() {
		const prevBtnLP = document.querySelector('.LP-new-left-btn');
		const nextBtnLP = document.querySelector('.LP-new-right-btn');
		const sliderEl = document.querySelector('.LP-new-slider');
		
		if (!sliderEl) return;
		
		var LPNewSlider = new Swiper(sliderEl, {
			loop: false,
			autoplay: {
				delay: 3000,
				disableOnInteraction: false,
			},
			spaceBetween: 20,
			slidesPerView: 4,
			simulateTouch: false, // 👈 drag disable
			allowTouchMove: false, // 👈 swipe disable
			navigation: {
				nextEl: nextBtnLP,
				prevEl: prevBtnLP,
			},
			breakpoints: {
				0: {
					slidesPerView: 1
				},
				576: {
					slidesPerView: 2
				},
				768: {
					slidesPerView: 3
				},
				1200: {
					slidesPerView: 4
				},
			},
			on: {
				init(swiper) {
					updateNavLP(swiper);
				},
				slideChange(swiper) {
					updateNavLP(swiper);
				},
				resize(swiper) {
					updateNavLP(swiper);
				},
				breakpoint(swiper) {
					updateNavLP(swiper);
				},
			}
		});
		
		// Hover → autoplay stop/start
		sliderEl.addEventListener("mouseenter", () => LPNewSlider.autoplay.stop());
		sliderEl.addEventListener("mouseleave", () => LPNewSlider.autoplay.start());
		
		// Update navigation buttons
		function updateNavLP(swiper) {
			if (!prevBtnLP || !nextBtnLP) return;
			
			const notEnoughSlides =
            swiper.isLocked || swiper.slides.length <= swiper.params.slidesPerView;
			
			if (notEnoughSlides) {
				prevBtnLP.classList.add('hidden');
				nextBtnLP.classList.add('hidden');
				return;
			}
			
			swiper.isBeginning ?
            prevBtnLP.classList.add('hidden') :
            prevBtnLP.classList.remove('hidden');
			
			swiper.isEnd ?
            nextBtnLP.classList.add('hidden') :
            nextBtnLP.classList.remove('hidden');
		}
	});
</script>




@endsection