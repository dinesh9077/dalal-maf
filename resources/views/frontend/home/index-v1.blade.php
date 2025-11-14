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



    .mobile-search {
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 90%;
        z-index: 10;
    }

    .mobile-search-bar {
        position: relative;
        width: 100%;
    }

    .mobile-search-input {
        width: 100%;
        padding: 10px 40px 10px 15px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 16px;
        outline: none;
    }

    .mobile-search-bar i {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #000;
        font-size: 18px;
        pointer-events: none;
    }

    .tabs-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 1px solid #f4f5f7;
    }

    .tabs-wrapper .nav-tabs {
        border: none;
        display: flex;
        align-items: center;
        margin-bottom: 0;
    }

    .tabs-wrapper .nav-tabs .nav-link {
        border: none;
        font-weight: 500;
        color: #333;
        background: none;
    }

    .tabs-wrapper .nav-tabs .nav-link.active {
        color: #6c603c;
        border-bottom: 2px solid #6c603c;
    }

    .post-property-btn {
        border-left: 1px solid #f4f5f7;
        padding-left: 5px;
        margin-left: 5px;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
    }

    .style__postContainerTab {
        background-color: #6c603c;
        border: none;
        color: #fff;
        padding: 8px 18px;
        border-radius: 6px;
        font-size: 14px;
        transition: 0.3s ease;
    }

    .style__postContainerTab:hover {
        background-color: #5a5234;
        color: #fff;
    }

    @media (max-width: 576px) {
        .tabs-wrapper {
            flex-direction: column;
            align-items: stretch;
        }

        .post-property-btn {
            border-left: none;
            margin-top: 10px;
            text-align: left;
            padding-left: 0;
        }

        .style__postContainerTab {
            width: 100%;
            text-align: center;
        }
    }

    .select2-results__options {
        max-height: 150px !important;
        /* Dropdown ki max height */
        overflow-y: auto !important;
        /* Scrollbar enable */
    }

    /* (Optional) Scrollbar styling */
    .select2-results__options::-webkit-scrollbar {
        width: 6px !important;
    }

    .select2-results__options::-webkit-scrollbar-thumb {
        background: #aaa !important;
        border-radius: 3px !important;
    }

    .select2-results__options::-webkit-scrollbar-thumb:hover {
        background: #888 !important;
    }

    .main-navbar.navbar-transparent {
        background: linear-gradient(180deg, rgba(0, 0, 0, 0.9), rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0)) !important;
        transition: background 0.3s ease;
        height: 110px;
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
    <div class="hero-image" id="heroBanner"
        style="background: url('{{ asset('assets/img/hero/static/' . $firstHeroImg) }}'); background-size: cover; background-position: center;">

        <div class="container">
            <div class="row align-items-center">
                <div class="col-xxl-10">
                    <div class="content mb-30" data-aos="fade-up">
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

        

        @php
        $tabs = [
        'buy' => 'Buy',
        // 'sale' => 'Sale',
        'rent' => 'Rent',
        'lease' => 'Lease',
        ];

        $user = Auth::guard('web')->user();
        $vendor = Auth::guard('vendor')->user();
        $agent = Auth::guard('agent')->user();

        // Determine auth type
        if ($vendor) {
        $authType = 'vendor';
        $authUser = $vendor;
        $dashboardRoute = route('vendor.dashboard');
        $logoutRoute = route('vendor.logout');
        } elseif ($user) {
        $authType = 'user';
        $authUser = $user;
        $dashboardRoute = route('user.dashboard');
        $logoutRoute = route('user.logout');
        } elseif ($agent) {
        $authType = 'agent';
        $authUser = $agent;
        $dashboardRoute = route('agent.dashboard');
        $logoutRoute = route('agent.logout');
        } else {
        $authType = 'guest';
        $authUser = null;
        }

        // First letter for avatar
        $initial = $authUser ? strtoupper(substr($authUser->username ?? 'U', 0, 1)) : null;

        // Post Property route
        if ($authType === 'vendor' && $vendor->email) {
        $postPropertyRoute = route('vendor.property_management.type');
        } elseif ($authType === 'user' && $user->email) {
        $postPropertyRoute = route('user.property_management.type');
        } elseif ($authType === 'agent' && $agent->email) {
        $postPropertyRoute = route('agent.property_management.type');
        } else {
        $postPropertyRoute = route('user.signup');
        }
        @endphp

        <div class="banner-filter-form new-banner-filters-width" data-aos="fade-up">
            <div class="tab-content form-wrapper">

                {{-- Tabs --}}
                <div class="tabs-wrapper">
                    <ul class="nav nav-tabs mb-0" >
                        @foreach($tabs as $key => $label)
                        <li class="nav-item">
                            <button class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab"
                                data-bs-target="#{{ $key }}" type="button">
                                {{ __($label) }}
                            </button>
                        </li>
                        @endforeach
                    </ul>

                    {{-- Post Property Button --}}
                    <div class="post-property-btn" >
                        @if ($authType === 'guest')
                        <button type="button" class="style__postContainerTab" data-bs-toggle="modal"
                            style="border:none; " data-bs-target="#customerPhoneModal" data-action="post_property">
                            <span class="style__postTab">{{ __('Post Property') }}</span>
                        </button>
                        @else
                        <a class="style__postContainerTab border-0" href="{{ $postPropertyRoute }}">
                            <span class="style__postTab">{{ __('Post Property') }}</span>
                        </a>
                        @endif
                    </div>
                </div>


                {{-- Common Hidden Fields --}}
                <input type="hidden" id="currency_symbol" value="{{ $basicInfo->base_currency_symbol }}">
                <input class="form-control" type="hidden" value="{{ $min }}" id="o_min">
                <input class="form-control" type="hidden" value="{{ $max }}" id="o_max">

                {{-- Tab Content --}}
                @foreach($tabs as $key => $label)
                <div class="tab-pane fade mt-3 {{ $loop->first ? 'active show' : '' }}" id="{{ $key }}">
                    <form action="{{ route('frontend.properties') }}" method="get" id="{{ $key }}Form">
                        <input type="hidden" name="purpose" value="{{ strtolower($label) }}">
                        <input type="hidden" name="min" value="{{ $min }}" id="min_{{ $key }}">
                        <input type="hidden" name="max" value="{{ $max }}" id="max_{{ $key }}">

                        <div class="grid mt-2">
                            {{-- City --}}
                            <div class="grid-item home-des-border">
                                <div class="form-group">
                                    <label for="city_{{ $key }}" class="icon-end">City</label>
                                    <select name="city" id="city_{{ $key }}" class="form-control select2" >
                                        <option value="" >{{ __('Select City') }}</option>
                                        @foreach ($cities as $city)
                                        <option value="{{ $city->name }}">{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Location --}}
                            <div class="grid-item home-des-border">
                                <div class="form-group">
                                    <label for="search_{{ $key }}">{{ __('Location') }}</label>
                                    <input type="text" id="search_{{ $key }}" name="location"
                                        class="form-control searchBar" placeholder="{{ __('Enter Location') }}"
                                        style="box-shadow: none;     border: 1px solid #e4e4e4;
    padding: 7.5px  10px;
    border-radius: 10px;">
                                </div>
                            </div>

                            {{-- Property Type --}}
                            <div class="grid-item home-des-border">
                                <div class="form-group">
                                    <label for="type_{{ $key }}" class="icon-end">{{ __('Property Type') }}</label>
                                    <select name="type[]" id="type_{{ $key }}" class="form-control select2">
                                        <option selected disabled value="">{{ __('Select Property') }}</option>
                                        <option value="all">{{ __('All') }}</option>
                                        <option value="residential">{{ __('Residential') }}</option>
                                        <option value="commercial">{{ __('Commercial') }}</option>
                                        <option value="industrial">{{ __('Industrial') }}</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Categories --}}
                            <div class="grid-item home-des-border">
                                <div class="form-group">
                                    <label for="category_{{ $key }}" class="icon-end">{{ __('Categories') }}</label>
                                    <select name="category[]" id="category_{{ $key }}"
                                        class="form-control select2 bringCategory">
                                        <option selected disabled value="">{{ __('Select Category') }}</option>
                                        <option value="all">{{ __('All') }}</option>
                                        @foreach ($all_proeprty_categories as $category)
                                        <option value="{{ @$category->categoryContent->slug }}">
                                            {{ @$category->categoryContent->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Price Slider --}}
                            <div class="grid-item home-des-border">
                                <label style="margin-bottom: 6px;">{{ __('Price') }}:</label>

                                <div data-range-value="filterPriceSlider_{{ $key }}_value" style="margin-bottom: 5px;color: black;font-size: 14px;">
                                        {{ symbolPrice($min) }} - {{ symbolPrice($max) }}
                                </div>

                                <div data-range-slider="filterPriceSlider_{{ $key }}"></div>
                            </div>

                            {{-- Submit Button --}}
                            <div>
                                <button type="submit" class="btn btn-primary bg-secondary icon-start new-search-btn"
                                    style="background-color:#6c603c !important;">
                                    <img src="{{ asset('assets/front/images/new-images/search.png') }}" alt="Search"
                                        class="new-icons-search">
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                @endforeach
            </div>
        </div>
        <!-- Mobile Search Bar (visible only on mobile) -->
        <div class="mobile-search">
            <div class="mobile-search-bar">
                <a href="{{ route('frontend.properties.filter')}}">
                    <input type="text" class="mobile-search-input" placeholder="Search properties...">
                    <i class="fas fa-search"></i>
                </a>
            </div>
        </div>
    </div>
</section>

@if($featured_properties->isNotEmpty())
<section class="product-area featured-product pt-100 pb-20">
    <div class="container" style="border-top:none !important;">
        <div class="row">
            <div class="container">
                <div class="col-12 ">
                    <div class="section-title mb-10 new-titles position-relative" data-aos="fade-up">
                        <h2 class="title text-center">Featured Properties</h2>
                        <!-- <p class="mt-1" style="font-size:13px; line-height : 1.2;">
                            Handpicked and premium listings showcased for you.
                            Explore top-rated homes, offices, and commercial spaces that stand out.
                        </p>  -->
                  
                        <a href="{{ url('properties/is_featured/all') }}" class="vs-btn vs-new-set-btn view-all-desktop"
                            style="padding: 10px 20px;">View All</a>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="row align-items-stretch new-ww-mb">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 d-flex position-relative px-0" data-aos="fade-up">
                        <div class="swiper product-slider w-100">
                            <div class="swiper-wrapper">
                                @forelse ($featured_properties as $property)
                                <div class="swiper-slide">
                                    <x-property :property="$property" />
                                </div>
                                @empty
                                <div class="p-3 text-center w-100">
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

                <div class="text-center">
                    <a href="{{ url('properties/is_featured/all') }}" class="vs-btn vs-new-set-btn view-all-mobile"
                        style="padding: 10px 20px;">View All</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif


@if($hotProperties->isNotEmpty())
<section class="product-area featured-product pt-20 pb-10">
    <div class="container">
        <div class="row">
            <div class="container">
                <div class="col-12">
                    <div class="section-title mb-10 new-titles" data-aos="fade-up" style="position: relative;">
                        <h2 class="title" style="text-align : center;">Hot Properties</h2>
                        <!-- <p class="mt-1" style="font-size:13px; line-height : 1.2;">
                            Handpicked and premium listings showcased for you.
                            Explore top-rated homes, offices, and commercial spaces that stand out.
                        </p> -->
                        <a href="{{ url('properties/is_hot/all') }}" class="vs-btn vs-new-set-btn view-all-desktop"
                            style="padding: 10px 20px;">
                            View All
                        </a>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="row align-items-stretch">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 d-flex" style="position: relative;"
                        data-aos="fade-up">
                        <div class="swiper product-slider w-100">
                            <div class="swiper-wrapper">
                                @forelse ($hotProperties as $property)
                                <div class="swiper-slide">
                                    <x-hot-property :property="$property" />
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

                <div class="text-center mt-2 mt-sm-2">
                    <a href="{{ url('properties/is_hot/all') }}" class="vs-btn vs-new-set-btn view-all-mobile"
                        style="padding: 10px 20px;">
                        View All
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif


@if($recommendedProperties->isNotEmpty())
<section class="product-area featured-product pt-20 pb-20">
    <div class="container">
        <div class="row">
            <div class="container">
                <div class="col-12">
                    <div class="section-title  mb-30 new-titles" data-aos="fade-up" style="position: relative;">
                        <h2 class="title" style="text-align : center;">Recommended Properties</h2>
                        <p class="mt-1" style="font-size:13px; line-height : 1.2;">Handpicked and premium listings showcased for you.
                            Explore top-rated homes, offices, and commercial spaces that stand out.</p>
                        <a href="{{ url('properties/is_recommended/all') }}" class="vs-btn vs-new-set-btn"
                            style="padding: 10px 20px;">View All</a>

                    </div>
                </div>
            </div>

            <div class="container ">
                <div class="row align-items-stretch ">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 d-flex " style="position: relative;"
                        data-aos="fade-up">
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
    </div>
</section>
@endif

@if($fastSellingProperties->isNotEmpty())
<section class="product-area featured-product pt-20 pb-20">
    <div class="container">
        <div class="row">
            <div class="container">
                <div class="col-12">
                    <div class="section-title mb-10 new-titles" data-aos="fade-up" style="position: relative;">
                        <h2 class="title" style="text-align : center;">Fast Selling Properties</h2>
                        <p class="mt-1" style="font-size:13px; line-height : 1.2;">
                            Handpicked and premium listings showcased for you.
                            Explore top-rated homes, offices, and commercial spaces that stand out.
                        </p>
                        <a href="{{ url('properties/is_fast_selling/all') }}"
                            class="vs-btn vs-new-set-btn view-all-desktop" style="padding: 10px 20px;">
                            View All
                        </a>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="row align-items-stretch">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 d-flex" style="position: relative;"
                        data-aos="fade-up">
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

                <div class="text-center">
                    <a href="{{ url('properties/is_fast_selling/all') }}" class="vs-btn vs-new-set-btn view-all-mobile"
                        style="padding: 10px 20px;">
                        View All
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif


@if ($secInfo->property_section_status == 1)
<section class="product-area popular-product product-1 relative" >
    <img src="{{ asset('assets/front/images/new-images/new-primume-properties.png') }}" alt=""
        class="new-primume-prop-img">
    <div class="container pt-30 pb-30">
        <div class="row">
            <div class="col-12">
                <div class="section-title prop-pad" data-aos="fade-up">
                    <h2 class="title">{{ $propertySecInfo->title }}</h2>
                </div>
            </div>
            <div class="col-12">
                <div class="tab-content" data-aos="fade-up">
                    <div class="row new-padding-width-res" style="position: relative; margin-top : 15px;">
                        <!-- Slider wrapper -->
                        <div class="swiper LP-new-slider">
                            <div class="swiper-wrapper">
                                @forelse ($properties as $property)
                                @if($property->property_type == 'partial')
                                <div class="swiper-slide">
                                    <x-latest-property :property="$property"  />
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



<section class="product-area popular-product product-1 relative">
    <div class="container pt-30 pb-30 ">
        <div class="row">
            <div class="col-12">
                <div class="section-title  aos-init aos-animate" data-aos="fade-up">
                    <h2 class="title">Business For Sale</h2>
                    <!-- <p class="mt-1" style="font-size:13px; line-height : 1.2;">Explore a wide range of businesses up for sale. From retail
                        outlets to commercial setups, find the right opportunity to invest and grow.
                    </p> -->
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


<section class="product-area popular-product product-1 relative" style="background:#F8F7F1; overflow:visible;">
    <div class="container pt-30 pb-30">
        <div class="row" style="position: relative;">
            <div class="col-12">
                <div class="section-title mb-10 aos-init aos-animate" data-aos="fade-up">
                    <h2 class="title">Franchiese</h2>
                    <!-- <p class="mt-1" style="font-size:13px; line-height : 1.2;">Join hands with Dala Maaf and become a part of a growing
                        network. Unlock business opportunities with our easy-to-start franchise model.</p> -->
                </div>
            </div>


            <div class="col-12">
                <!-- Swiper Slider Wrapper -->
                <div class="swiper fren-new-slider">
                    <div class="swiper-wrapper">
                        @forelse ($franchiese as $franchie)
                        <div class="swiper-slide">
                            <x-hot-property :property="$franchie" />
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


<div class="container pt-30 pb-30 upcoming-projects" data-aos="fade-up">

    <div class="row">
        <div class="col-12">
            <div class="section-title" data-aos="fade-up">
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

                    <img src="{{ asset('assets/img/project/featured/' . $project->featured_image) }}" alt="Project"
                        class="upcoming-projects-img">

                    <div class="upcomming-card-body">
                        <h5>{{ $project->title }}</h5>
                        <p class="text-muted">{{ \Illuminate\Support\Str::limit($project->address, 35) }}</p>
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
            <h6>First-time buyer, investor, or renter? Dala Maaf connects you to trusted properties in prime
                locations—your real estate partner.</h6>
            <p>Looking for your dream home, the right investment, or the best rental deal? Dala Maaf makes it simple.
                With thousands of verified listings, expert insights, and user-friendly search options, you can buy,
                sell, or rent properties with complete confidence.</p>
            <p>Whether you’re a first-time buyer, an investor, or someone searching for the perfect rental, Dala Maaf
                connects you to trusted property options across prime locations. It’s more than a platform—it’s your
                partner in real estate.</p>
            <div style="margin-top: 30px;">
                <a href="{{ route('frontend.properties') }}" class="find-your-btn">Find Your Perfect Property with Dala
                    Maaf</a>
            </div>
        </div>
    </div>
</section>

@if ($secInfo->why_choose_us_section_status == 1)
<section class="new-aps-sections  pt-30 pb-30">
    <div class="container">
        <div class="section-title title-inline    aos-init aos-animate d-flex" data-aos="fade-up">
            <h2 class="title">Explore Property Types</h2>
        </div>

        <div class="aps-slide-wrapper " style="margin-top : 30px;" data-aos="fade-up">
            <!-- Left Arrow -->
            <button class="arrow-button arrow-left" id="arrowLeft"><img
                    src="{{ asset('assets/front/images/new-images/left.png') }}" alt=""></button>

            <!-- Slider -->
            <div class="aps-slider ">
                @foreach ($all_proeprty_categories as $category)
                <div class="new-aps-titles-bag">
                    <a href="{{ route('frontend.properties',['category'=>$category->categoryContent->name]) }}">
                        <img src="{{ asset('assets/img/property-category/' . $category->image) }}" alt=""
                            class="new-images-aps-type mx-auto">
                        <div class="new-type-title">
                            <h6 class="aps-type">{{ @$category->categoryContent->name }}</h6>
                            <h6 class="aps-type-property">{{ $category->properties_count }} properties</h6>
                        </div>
                    </a>
                </div>
                @endforeach

            </div>

            <!-- Right Arrow -->
            <button class="arrow-button arrow-right" id="arrowRight"><img
                    src="{{ asset('assets/front/images/new-images/Right.png') }}" alt=""></button>
        </div>



    </div>
</section>
@endif




<section class="buy-rent-sale-section pt-30 pb-30">
    <div class="container">

        <div class="section-title title-inline mb-3 aos-init aos-animate d-flex" data-aos="fade-up">
            <h2 class="title">For all your luxury real estate needs, We have got you covered!</h2>
            <!-- <p style="font-size: 13px; line-height : 1.2;">From finding your dream home with all the luxury amenities to seamless transactions, trust us to handle
                every detail with care and expertise.</p> -->
        </div>

        <div class="row">
            <div class="col-lg-4 b-s-r-div-main">
                <div class="b-s-r-div ">
                    <img src="{{ asset('assets/front/images/acrs-imag/buy.png') }}" alt="" class="b-s-r-img">
                    <h5 class="but-title">Buy</h5>
                    <p class="but-title-p">Discover thousands of verified listings across residential and commercial
                        spaces. Find your dream home or perfect investment with our easy-to-use search and trusted
                        property details.</p>
                </div>
            </div>
            <div class="col-lg-4 b-s-r-div-main">
                <div class="b-s-r-div ">
                    <img src="{{ asset('assets/front/images/acrs-imag/sale.png') }}" alt="" class="b-s-r-img">
                    <h5 class="but-title">Sale</h5>
                    <p class="but-title-p">List your property in minutes and connect with serious buyers. With
                        DalalMaf’s wide reach and trusted network, selling your property has never been faster or
                        easier.</p>

                </div>
            </div>
            <div class="col-lg-4 b-s-r-div-main">
                <div class="b-s-r-div ">
                    <img src="{{ asset('assets/front/images/acrs-imag/rent.png') }}" alt="" class="b-s-r-img">
                    <h5 class="but-title">Rent</h5>
                    <p class="but-title-p">Looking for a home or office on rent? Explore verified rental options that
                        fit your budget and lifestyle. Hassle-free rentals with trusted landlords, all in one place.</p>

                </div>
            </div>
        </div>
    </div>
</section>

<section class="why-dlal-dection pt-30 pb-30">
    <div class="container">
        <div class="section-title title-inline  aos-init aos-animate d-flex " style="flex-direction: column;"
            data-aos="fade-up">
            <h2 class="title">Why DalalMaf ?</h2>
            <!-- <p style="font-size: 13px;  line-height : 1.2;">For Your Buying, Selling & Renting in Real Estate</p> -->
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="why-main-div">
                    <h6>Best Deals at Your Fingertips</h6>
                    <p>Find verified properties at the most competitive prices. Whether buying, selling, or renting,
                        DalalMaf ensures you get the best value for your money.</p>
                    <div class="icons">
                        <img src="{{ asset('assets/front/images/acrs-imag/why-1.png') }}" alt="">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="why-main-div">
                    <h6>Verified & Trusted Listings </h6>
                    <p>Every property listed on DalalMaf goes through a strict verification process, ensuring that you
                        connect only with genuine sellers, buyers, and renters.</p>
                    <div class="icons">
                        <img src="{{ asset('assets/front/images/acrs-imag/why-2.png') }}" alt="">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="why-main-div">
                    <h6>Expert Support Anytime </h6>
                    <p>From searching properties to closing the deal, our dedicated support team is here to guide you at
                        every step, making your journey smooth and hassle-free.</p>
                    <div class="icons">
                        <img src="{{ asset('assets/front/images/acrs-imag/why-3.png') }}" alt="">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="why-main-div">
                    <h6>Wide Network & Easy Access </h6>
                    <p>With thousands of listings across residential, commercial, and rental properties, DalalMaf gives
                        you a wide choice and helps you find the perfect property quickly.</p>
                    <div class="icons">
                        <img src="{{ asset('assets/front/images/acrs-imag/why-4.png') }}" alt="">
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<section class="about-section-new pt-40 pb-40">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 about-lrft-sections-new">
                <img src="{{ asset('assets/front/images/acrs-imag/about-right.png') }}" alt="" class="about-llrs-img">
            </div>
            <div class="col-lg-6">
                <h1 class="ab-le-title">About The Story Behind Us</h1>
                <p class="ab-le-par">Welcome to DalalMaf, your trusted platform for buying, selling, and renting
                    properties.
                    We are committed to making real estate simple, transparent, and accessible for everyone. Whether you
                    are a first-time buyer, a property investor, or someone looking to rent a home, DalalMaf connects
                    you with verified listings and trusted partners.
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
<section class="pt-30 pb-30">
    <div class="container">

        <div class="row">
            <div class="col-12 button-res">
                <div class="section-title mb-20" data-aos="fade-up">
                    <h2 class="title">{{ $vendorInfo?->subtitle }}</h2>
                </div>

                @if (count($vendors) > 0)
                <div class="text-center view-all-desktop">
                    <a href="{{ route('frontend.vendors') }}" class="vs-btn" style="padding: 11px 20px;">{{ $vendorInfo->btn_name }}</a>
                </div>
                @endif
            </div>

            @forelse ($vendors as $vendor)
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="new-company-card" data-aos="fade-up">
                    <div class="logo-container">
                        <img class="lazyload"
                            data-src="{{ $vendor->photo ? asset('assets/admin/img/vendor-photo/' . $vendor->photo) : asset('assets/img/blank-user.jpg') }}">
                    </div>
                    <div class="company-details">
                        <h3>{{ $vendor->vendor_info['name'] ?? 'Not Available'}}</h3>
                        <div>
                            <p class="new-fonts-property">{{ count($vendor->properties) }} {{ __('Properties') }}</p>
                            <p class="new-fonts-property">{{ count($vendor->agents) }} {{ __('Staffs') }}</p>
                            <p class="new-fonts-property">{{ count($vendor->projects) }} {{ __('Projects') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-3 text-center mb-30 w-100">
                <h3 class="mb-0">{{ __('No Partners Found') }}</h3>
            </div>
            @endforelse

            @if (count($vendors) > 0)
            <div class="text-center mt-4 view-all-mobile">
                <a href="{{ route('frontend.vendors') }}" class="btn btn-lg btn-primary mb-30"
                    style="background-color:#6c603c;">{{ $vendorInfo->btn_name }}</a>
            </div>
            @endif
        </div>

    </div>
</section>
@endif


@if ($secInfo->cities_section_status == 1)

<section class="new-gellary-area pt-30 pb-30 relative ">

    <!-- <img src="http://127.0.0.1:8000/assets/front/images/new-images/new-primume-properties.png" alt="" class="exp-img"> -->

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title" data-aos="fade-up">
                    <h2 class="title">{{ $citySecInfo?->subtitle }}</h2>
                </div>
            </div>

        </div>
        <div class="row ">
            <!-- City Card Start -->
            @forelse ($cities as $city)
            <div class="custom-col-5 mt-3" data-aos="fade-up">
                <a href="{{ route('frontend.properties', ['city' => $city->name]) }}" class="text-center"
                    style="display: block; text-decoration: none; color: inherit;">
                    <div class="city-card text-center">
                        <img class="lazyload blur-up"
                            data-src="{{ asset('assets/img/property-city/' . $city->image) }}">
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
	<section class="testimonial-area pt-100 pb-20">
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
	<div class="swiper-slide pb-20" data-aos="fade-up">
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
	<section class="newsletter-area pb-200" data-aos="fade-up">
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




<div class="main-blog pt-30 pb-40" data-aos="fade-up">
    <div class="container">

        <div class="row">
            <div class="col-12 button-res">
                <div class="section-title " data-aos="fade-up">
                    <h2 class="title">Our Blogs</h2>
                </div>

                <!-- Desktop Button -->
                <div class="text-center view-all-desktop">
                    <a href="{{ route('blog') }}" class="vs-btn mb-30"
                        style="padding: 11px 20px;">All Blogs</a>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-1">
            @foreach ($blogs as $blog)
            <div class="col-md-4">
                <div class="blog-card">
                    <img src="{{ asset('assets/img/blogs/' . $blog->image) }}" class="blog-img" alt="Blog Image">
                    <div class="blog-body">
                        <h5 class="blog-title">{{ $blog->title }}</h5>
                        <p class="blog-text">{{ \Illuminate\Support\Str::limit(strip_tags($blog->content), 90) }}</p>
                        <a href="{{ route('blog_details', ['slug' => $blog->slug]) }}" class="show-more">Show More →</a>
                    </div>
                    <div class="blog-footer">
                        <img src="https://i.pravatar.cc/50" class="author-img" alt="Author">
                        <div class="author-info">
                            <span>{{ $blog->author }}</span>
                            <small>{{ $blog->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Mobile Button -->
        <div class="text-center mt-4 view-all-mobile">
            <a href="{{ route('blog') }}" class="btn btn-lg btn-primary mb-30" style="background-color:#6c603c;">All
                Blogs</a>
        </div>
    </div>
</div>
<div class="mobile-bottom-menu">
    <a href="{{ route('index') }}" class="menu-item active">
        <i class="fas fa-home"></i>
        <span>Home</span>
    </a>

    <a href="{{ route('frontend.properties') }}" class="menu-item">
        <i class="fas fa-lightbulb"></i>
        <span>Properties</span>
    </a>

    <a href="{{ route('frontend.projects') }}" class="menu-item">
        <i class="fas fa-building"></i>
        <span>Projects</span>
    </a>

    <a href="{{ route('frontend.properties',['purpose'=>'franchiese']) }}" class="menu-item">
        <i class="fas fa-heart"></i>
        <span>Franchiese</span>
    </a>

    <a href="{{ route('frontend.properties', ['purpose' => 'business_for_sale']) }}" class="menu-item">
        <i class="fas fa-user"></i>
        <span>Business for Sale</span>
    </a>
</div>


<a href="javascript:;" class="floating-plus-btn" data-bs-toggle="modal"
    data-bs-target="#customerPhoneModal" data-action="post_property">
    <i class="fas fa-plus"></i>
</a>



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
                    breakpoint: 767,
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
                                value: item.id, // what fills input
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

    var min = @json($min);
    var max = @json($max);
    ['buy', 'sale', 'rent', 'lease'].forEach(key => {
        const slider = document.querySelector(`[data-range-slider="filterPriceSlider_${key}"]`);
        const valueEl = document.querySelector(`[data-range-value="filterPriceSlider_${key}_value"]`);
        const minInput = document.getElementById(`min_${key}`);
        const maxInput = document.getElementById(`max_${key}`);
        if (slider && valueEl) {
            // Initialize your price range slider logic here
            // Example (if using noUiSlider or similar):
            noUiSlider.create(slider, {
                start: [min, max],
                connect: true,
                range: {
                    min: min,
                    max: max
                }
            });

            slider.noUiSlider.on('update', (values) => {
                minInput.value = Math.round(values[0]);
                maxInput.value = Math.round(values[1]);
                valueEl.textContent = `${values[0]} - ${values[1]}`;
            });
        }
    });

    document.getElementById("sellRentBtn1").addEventListener("click", function(e) {
        e.preventDefault();
        const modal = document.getElementById("customerPhoneModal");
        if (modal) {
            const modalTrigger = new bootstrap.Modal(modal);
            modalTrigger.show();
        }
    });
</script>





@endsection