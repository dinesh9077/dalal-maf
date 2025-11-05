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
        /* width: 90% !important; */
    }

    .ui-widget.ui-widget-content .ui-menu-item .ui-menu-item-wrapper:hover {
        background: white !important;
        border: none !important;
        color: black !important;
        outline: none !important;

    }

    .ui-widget.ui-widget-content .ui-menu-item {
        background: white !important;
        border: none !important;
        outline: none !important;
        color: black !important;
    }

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
<section id="heroBanner" class="home-banner home-banner-1 relative" data-aos="fade-up"
    style="background: url('{{ asset('assets/img/hero/static/' . $firstHeroImg) }}'); background-size: cover; background-position: center;">

    <div class="container">
        <div class="row align-items-center">
            <div class="col-xxl-10">
                <div class="content mb-40" data-aos="fade-up">
                    <h1 class="title title-colors">{{ $heroStatic->title }}</h1>
                    <!-- <h1 class="title">{{ $heroStatic->title }}</h1> -->
                    <p class="text title-colors">
                        <!-- <p class="text"> -->
                        {{ $heroStatic->text }}
                    </p>
                </div>

            </div>
        </div>
    </div>

    <div class="banner-filter-form" data-aos="fade-up">
        <ul class="nav nav-tabs">

            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#rent"
                    type="button">{{ __('Rent') }}</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#sale"
                    type="button">{{ __('Sale') }}</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#buy"
                    type="button">{{ __('Buy') }}</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#lease"
                    type="button">{{ __('Lease') }}</button>
            </li>
            <!-- <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#franchiese"
                                type="button">{{ __('Franchiese') }}</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#businessSell"
                                type="button">{{ __('Business Sell') }}</button>
                        </li> -->
        </ul>
        <div class="tab-content form-wrapper">
            <input type="hidden" value="{{ $min }}" id="min">
            <input type="hidden" value="{{ $max }}" id="max">

            <input type="hidden" id="currency_symbol" value="{{ $basicInfo->base_currency_symbol }}">
            <input class="form-control" type="hidden" value="{{ $min }}" id="o_min">
            <input class="form-control" type="hidden" value="{{ $max }}" id="o_max">

            <div class="tab-pane fade active show" id="rent">
                <form action="{{ route('frontend.properties') }}" method="get">
                    <input type="hidden" name="purposre" value="rent">
                    <input type="hidden" name="min" value="{{ $min }}" id="min1">
                    <input type="hidden" name="max" value="{{ $max }}" id="max1">
                    <div class="grid">
                        <div class="grid-item home-des-border">
                            <div class="form-group">
                                <label for="search1">{{ __('Location') }}</label>
                                <input type="text" id="search1" name="location" class="form-control"
                                    placeholder="{{ __('Enter Location') }}">
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
                                style="background-color: rgba(0, 0, 0, 1) !important;">
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
            <div class="tab-pane fade" id="sale">
                <form action="{{ route('frontend.properties') }}" method="get">
                    <input type="hidden" name="purposre" value="sale">
                    <input type="hidden" name="min" value="{{ $min }}" id="min2">
                    <input type="hidden" name="max" value="{{ $max }}" id="max2">
                    <div class="grid">
                        <div class="grid-item home-des-border">
                            <div class="form-group">
                                <label for="search1">{{ __('Location') }}</label>
                                <input type="text" id="search1" name="location"
                                    class="form-control" placeholder="{{ __('Enter Location') }}">
                            </div>
                        </div>
                        <div class="grid-item home-des-border">
                            <div class="form-group">
                                <label for="type1" class="icon-end">{{ __('Property Type') }}</label>
                                <select aria-label="#" name="type" class="form-control select2 type"
                                    id="type1">
                                    <option selected disabled value="">{{ __('Select Property') }}
                                    </option>
                                    <option value="all">{{ __('All') }}</option>
                                    <option value="residential">{{ __('Residential') }}</option>
                                    <option value="commercial">{{ __('Commercial') }}</option>

                                </select>
                            </div>
                        </div>
                        <div class="grid-item home-des-border">
                            <div class="form-group">
                                <label for="category1" class="icon-end">{{ __('Categories') }}</label>
                                <select aria-label="#" class="form-control select2 bringCategory"
                                    id="category1" name="category">
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

                        <div class="grid-item home-des-border city">
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
                        </div>
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
                                style="background-color: rgba(0, 0, 0, 1) !important;">
                                <img src="{{ asset('assets/front/images/new-images/search.png') }}" alt="Search" class="new-icons-search">
                            </button>
                            <!-- <button type="submit"
                                                class="btn btn-lg btn-primary bg-secondary icon-start w-100">
                                                {{ __('Search') }}
                                            </button> -->
                        </div>
                    </div>
                </form>
            </div>
            <div class="tab-pane fade" id="buy">
                <form action="{{ route('frontend.properties') }}" method="get">
                    <input type="hidden" name="purposre" value="sale">
                    <input type="hidden" name="min" value="{{ $min }}" id="min2">
                    <input type="hidden" name="max" value="{{ $max }}" id="max2">
                    <div class="grid">
                        <div class="grid-item home-des-border">
                            <div class="form-group">
                                <label for="search1">{{ __('Location') }}</label>
                                <input type="text" id="search1" name="location"
                                    class="form-control" placeholder="{{ __('Enter Location') }}">
                            </div>
                        </div>
                        <div class="grid-item home-des-border">
                            <div class="form-group">
                                <label for="type1" class="icon-end">{{ __('Property Type') }}</label>
                                <select aria-label="#" name="type" class="form-control select2 type"
                                    id="type1">
                                    <option selected disabled value="">{{ __('Select Property') }}
                                    </option>
                                    <option value="all">{{ __('All') }}</option>
                                    <option value="residential">{{ __('Residential') }}</option>
                                    <option value="commercial">{{ __('Commercial') }}</option>

                                </select>
                            </div>
                        </div>
                        <div class="grid-item home-des-border">
                            <div class="form-group">
                                <label for="category1" class="icon-end">{{ __('Categories') }}</label>
                                <select aria-label="#" class="form-control select2 bringCategory"
                                    id="category1" name="category">
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

                        <div class="grid-item home-des-border city">
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
                        </div>
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
                                style="background-color: rgba(0, 0, 0, 1) !important;">
                                <img src="{{ asset('assets/front/images/new-images/search.png') }}" alt="Search" class="new-icons-search">
                            </button>
                            <!-- <button type="submit"
                                                class="btn btn-lg btn-primary bg-secondary icon-start w-100">
                                                {{ __('Search') }}
                                            </button> -->
                        </div>
                    </div>
                </form>
            </div>
            <div class="tab-pane fade" id="lease">
                <form action="{{ route('frontend.properties') }}" method="get">
                    <input type="hidden" name="purposre" value="sale">
                    <input type="hidden" name="min" value="{{ $min }}" id="min2">
                    <input type="hidden" name="max" value="{{ $max }}" id="max2">
                    <div class="grid">
                        <div class="grid-item home-des-border">
                            <div class="form-group">
                                <label for="search1">{{ __('Location') }}</label>
                                <input type="text" id="search1" name="location"
                                    class="form-control" placeholder="{{ __('Enter Location') }}">
                            </div>
                        </div>
                        <div class="grid-item home-des-border">
                            <div class="form-group">
                                <label for="type1" class="icon-end">{{ __('Property Type') }}</label>
                                <select aria-label="#" name="type" class="form-control select2 type"
                                    id="type1">
                                    <option selected disabled value="">{{ __('Select Property') }}
                                    </option>
                                    <option value="all">{{ __('All') }}</option>
                                    <option value="residential">{{ __('Residential') }}</option>
                                    <option value="commercial">{{ __('Commercial') }}</option>

                                </select>
                            </div>
                        </div>
                        <div class="grid-item home-des-border">
                            <div class="form-group">
                                <label for="category1" class="icon-end">{{ __('Categories') }}</label>
                                <select aria-label="#" class="form-control select2 bringCategory"
                                    id="category1" name="category">
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

                        <div class="grid-item home-des-border city">
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
                        </div>
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
                                style="background-color: rgba(0, 0, 0, 1) !important;">
                                <img src="{{ asset('assets/front/images/new-images/search.png') }}" alt="Search" class="new-icons-search">
                            </button>
                            <!-- <button type="submit"
                                                class="btn btn-lg btn-primary bg-secondary icon-start w-100">
                                                {{ __('Search') }}
                                            </button> -->
                        </div>
                    </div>
                </form>
            </div>
            <div class="tab-pane fade" id="franchiese">
                <form action="{{ route('frontend.properties') }}" method="get">
                    <input type="hidden" name="purposre" value="sale">
                    <input type="hidden" name="min" value="{{ $min }}" id="min2">
                    <input type="hidden" name="max" value="{{ $max }}" id="max2">
                    <div class="grid">
                        <div class="grid-item home-des-border">
                            <div class="form-group">
                                <label for="search1">{{ __('Location') }}</label>
                                <input type="text" id="search1" name="location"
                                    class="form-control" placeholder="{{ __('Enter Location') }}">
                            </div>
                        </div>
                        <div class="grid-item home-des-border">
                            <div class="form-group">
                                <label for="type1" class="icon-end">{{ __('Property Type') }}</label>
                                <select aria-label="#" name="type" class="form-control select2 type"
                                    id="type1">
                                    <option selected disabled value="">{{ __('Select Property') }}
                                    </option>
                                    <option value="all">{{ __('All') }}</option>
                                    <option value="residential">{{ __('Residential') }}</option>
                                    <option value="commercial">{{ __('Commercial') }}</option>

                                </select>
                            </div>
                        </div>
                        <div class="grid-item home-des-border">
                            <div class="form-group">
                                <label for="category1" class="icon-end">{{ __('Categories') }}</label>
                                <select aria-label="#" class="form-control select2 bringCategory"
                                    id="category1" name="category">
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

                        <div class="grid-item home-des-border city">
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
                        </div>
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
                                style="background-color: rgba(0, 0, 0, 1) !important;">
                                <img src="{{ asset('assets/front/images/new-images/search.png') }}" alt="Search" class="new-icons-search">
                            </button>
                            <!-- <button type="submit"
                                                class="btn btn-lg btn-primary bg-secondary icon-start w-100">
                                                {{ __('Search') }}
                                            </button> -->
                        </div>
                    </div>
                </form>
            </div>
            <div class="tab-pane fade" id="businessSell">
                <form action="{{ route('frontend.properties') }}" method="get">
                    <input type="hidden" name="purposre" value="sale">
                    <input type="hidden" name="min" value="{{ $min }}" id="min2">
                    <input type="hidden" name="max" value="{{ $max }}" id="max2">
                    <div class="grid">
                        <div class="grid-item home-des-border">
                            <div class="form-group">
                                <label for="search1">{{ __('Location') }}</label>
                                <input type="text" id="search1" name="location"
                                    class="form-control" placeholder="{{ __('Enter Location') }}">
                            </div>
                        </div>
                        <div class="grid-item home-des-border">
                            <div class="form-group">
                                <label for="type1" class="icon-end">{{ __('Property Type') }}</label>
                                <select aria-label="#" name="type" class="form-control select2 type"
                                    id="type1">
                                    <option selected disabled value="">{{ __('Select Property') }}
                                    </option>
                                    <option value="all">{{ __('All') }}</option>
                                    <option value="residential">{{ __('Residential') }}</option>
                                    <option value="commercial">{{ __('Commercial') }}</option>

                                </select>
                            </div>
                        </div>
                        <div class="grid-item home-des-border">
                            <div class="form-group">
                                <label for="category1" class="icon-end">{{ __('Categories') }}</label>
                                <select aria-label="#" class="form-control select2 bringCategory"
                                    id="category1" name="category">
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

                        <div class="grid-item home-des-border city">
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
                        </div>
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
                                style="background-color: rgba(0, 0, 0, 1) !important;">
                                <img src="{{ asset('assets/front/images/new-images/search.png') }}" alt="Search" class="new-icons-search">
                            </button>
                            <!-- <button type="submit"
                                                class="btn btn-lg btn-primary bg-secondary icon-start w-100">
                                                {{ __('Search') }}
                                            </button> -->
                        </div>
                    </div>
                </form>
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

@if ($secInfo->featured_properties_section_status == 1)
<section class="product-area featured-product pt-70 pb-70">
    <div class="container-fulid">
        <div class="row">
            <div class="container">
                <div class="col-12">
                    <div class="section-title text-center new-padding-des-res mb-40 new-titles" data-aos="fade-up">
                        <h2 class="title" style="text-align : center;">{{ $featuredSecInfo->title }}</h2>
                    </div>
                </div>
            </div>

            <div class="row align-items-stretch">
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 d-flex" style="margin-bottom : 20px; " data-aos="fade-up">
                    <div class="custom-card w-100">
                        <img src="{{ asset('assets/front/images/new-images/citykrugy-about.png') }}" alt="citykrugy-about">
                        <h3 class="card-title">Best Seller In Surat</h3>
                        <p class="card-text">
                            We are Surat’s most trusted and best-selling real estate agency, specializing in luxury residences, premium commercial spaces, and profitable investment properties. With years of expertise, a wide property portfolio, and strong market knowledge, we’ve helped countless families and investors find exactly what they’re looking for.
                        </p>
                        <a href="{{ route('frontend.properties.featured.all') }}" class="vs-btn">View All</a>

                    </div>
                </div>
                <div class="col-xl-9 col-lg-8 col-md-6 col-sm-6 d-flex new-my-div" data-aos="fade-up">
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

                        <!-- Slider pagination -->
                        <div class="swiper-pagination position-static mb-30" id="product-slider-pagination"></div>

                        <!-- Navigation buttons -->
                        <div class="swiper-button-prev custom-swiper-btn"></div>
                        <div class="swiper-button-next custom-swiper-btn"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endif

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




@if ($secInfo->property_section_status == 1)
<section class="product-area popular-product product-1 pt-70 pb-70 relative" style="background : white;">
    <img src="{{ asset('assets/front/images/new-images/new-primume-properties.png') }}" alt="" class="new-primume-prop-img">
    <div class="container" style="margin-top: 50px;">
        <div class="row">
            <div class="col-12">
                <div class="section-title text-center mb-40" data-aos="fade-up">
                    <h2 class="title">{{ $propertySecInfo->title }}</h2>

                </div>
            </div>
            <div class="col-12">
                <div class="tab-content" data-aos="fade-up">
                    <div class="tab-pane fade show active" id="forAll">
                        <div class="row">

                            @forelse ($properties as $property)
                            {{-- property component --}}
                            <x-property :property="$property" class="col-lg-4 col-xxl-3 col-md-6" />
                            @empty
                            <div class="p-3 text-center mb-30">
                                <h3 class="mb-0"> {{ __('No Properties Found') }}</h3>
                            </div>
                            @endforelse

                        </div>
                    </div>
                    <div class="tab-pane fade" id="forRent">
                        <div class="row">
                            @forelse ($properties as $property)
                            @if ($property->purpose == 'rent')
                            {{-- property component --}}
                            <x-property :property="$property" class="col-lg-4 col-xxl-3 col-md-6" />
                            @endif
                            @empty
                            <div class="p-3 text-center mb-30">
                                <h3 class="mb-0"> {{ __('No Properties Found') }}</h3>
                            </div>
                            @endforelse

                        </div>
                    </div>
                    <div class="tab-pane fade" id="forSell">
                        <div class="row">
                            @forelse ($properties as $property)
                            @if ($property->purpose == 'sale')
                            {{-- property component --}}
                            <x-property :property="$property" class="col-lg-4 col-xxl-3 col-md-6" />
                            @endif
                            @empty
                            <div class="p-3 text-center mb-30">
                                <h3 class="mb-0"> {{ __('No Properties Found') }}</h3>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif


<section class=" pt-70 pb-70 relative" style="background : #ffffffff;">
    <!-- <img src="http://127.0.0.1:8000/assets/front/images/new-images/new-primume-properties.png" alt="" class="new-primume-prop-img"> -->
    <div class="container" style="margin-top: 50px;">
        <div class="row">
            <div class="col-12">
                <div class="section-title text-center mb-40 aos-init aos-animate" data-aos="fade-up">
                    <h2 class="title">Business For Sale </h2>

                </div>
            </div>
            <div class="col-12">
                <div class="tab-content aos-init aos-animate" data-aos="fade-up">
                    <div class="tab-pane fade show active" id="forAll">
                        <div class="row">

                       @forelse ($business_for_sale as $sale)
                              {{-- property component --}}
                              <x-property :property="$sale" class="col-lg-4 col-xxl-3 col-md-6" />
                            @empty
                              <div class="p-3 text-center mb-30">
                                  <h3 class="mb-0"> {{ __('No Properties Found') }}</h3>
                              </div>

                            @endforelse

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="product-area popular-product product-1 pt-70 pb-70 relative" style="background : #ffffffff;">
    <div class="container" style="margin-top: 50px;">
        <div class="row">
            <div class="col-12">
                <div class="section-title text-center mb-40 aos-init aos-animate" data-aos="fade-up">
                    <h2 class="title">Franchiese </h2>

                </div>
            </div>
           <div class="col-12">
                <div class="tab-content aos-init aos-animate" data-aos="fade-up">
                    <div class="tab-pane fade show active" id="forAll">
                        <div class="row">
                           @forelse ($franchiese as $franchie)
                                  {{-- property component --}}
                                  <x-property :property="$franchie" class="col-lg-4 col-xxl-3 col-md-6" />

                                  @empty
                                  <div class="p-3 text-center mb-30">
                                      <h3 class="mb-0"> {{ __('No Properties Found') }}</h3>
                                  </div>
                                @endforelse
                        </div>
                    </div>
                </div>
        </div>
    </div>
</section>




@if ($secInfo->why_choose_us_section_status == 1)
<!-- <section class="choose-area pt-70 pb-70">
    <div class="container">
        <div class="row gx-xl-5">
            <div class="col-lg-7">
                <div class="img-content mb-30 image-right" data-aos="fade-up">
                    <div class="img-1">
                        <img class="lazyload blur-up"
                            data-src="{{ asset('assets/img/why-choose-us/' . $whyChooseUsImg->why_choose_us_section_img1) }} "
                            alt="Image">
                        @if (!empty($whyChooseUsImg->why_choose_us_section_video_link))
                        <a href="{{ $whyChooseUsImg->why_choose_us_section_video_link }}"
                            class="video-btn youtube-popup p-absolute">
                            <i class="fas fa-play"></i>
                        </a>
                        @endif
                    </div>
                    <div class="img-2">
                        <img class="lazyload blur-up"
                            data-src="  {{ asset('assets/img/why-choose-us/' . $whyChooseUsImg->why_choose_us_section_img2) }} "
                            alt="Image">
                    </div>
                </div>
            </div>
            <div class="col-lg-5 order-lg-first">
                <div class="content" data-aos="fade-up">
                    <div class="content-title">
                        <span class="subtitle"><span class="line"></span>{{ $whyChooseUsInfo->title }}</span>
                        <h2>{{ $whyChooseUsInfo?->sub_title }}</h2>
                    </div>
                    <div class="text">{!! $whyChooseUsInfo?->description !!}</div>
                </div>
            </div>
        </div>
    </div>
</section> -->

<section class="new-aps-sections pt-70 pb-70">
    <div class="container">
        <div class="section-title title-inline mb-40 aos-init aos-animate d-flex" data-aos="fade-up">
            <h2 class="title">Explore Apartment Types</h2>
        </div>

        <div class="aps-slide-wrapper " style="margin-top : 50px;" data-aos="fade-up">
            <!-- Left Arrow -->
            <button class="arrow-button arrow-left" id="arrowLeft"><img src="{{ asset('assets/front/images/new-images/left.png') }}" alt=""></button>

            <!-- Slider -->
            <div class="aps-slider ">
                @foreach ($all_proeprty_categories as $category)
                <div class="new-aps-titles-bag">
                    <img src="{{ asset('assets/img/property-category/' . $category->image) }}" alt="" class="new-images-aps-type">
                    <div class="new-type-title">
                        <h6 class="aps-type">{{ @$category->categoryContent->name }}</h6>
                        <h6 class="aps-type-property">{{ $category->properties_count }} properties</h6>
                    </div>
                </div>
                @endforeach

            </div>

            <!-- Right Arrow -->
            <button class="arrow-button arrow-right" id="arrowRight"><img src="{{ asset('assets/front/images/new-images/Right.png') }}" alt=""></button>
        </div>

    </div>
</section>
@endif



@if ($secInfo->vendor_section_status == 1)
<section class="pt-100 pb-100">
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
                            <!-- <p class="new-fonts-property">{{ count($vendor->agents) }} {{ __('Agents') }}</p> -->
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
                class="btn btn-lg btn-primary  mb-30" style="background-color: black;">{{ $vendorInfo->btn_name }}</a>
        </div>
        @endif
    </div>
</section>
@endif

@if ($secInfo->cities_section_status == 1)

<section class="new-gellary-area pt-100 pb-100 relative ">

    <img src="http://127.0.0.1:8000/assets/front/images/new-images/new-primume-properties.png" alt="" class="exp-img">

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title title-center title-inline mb-40" data-aos="fade-up">
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



<div class="container my-5 upcoming-projects" data-aos="fade-up">

    <div class="row">
        <div class="col-12">
            <div class="#6c603c aos-init aos-animate" data-aos="fade-up">
                <h2 class="title">Upcoming Project</h2>
            </div>
        </div>
    </div>

    <div class="up-comming-slide-wrapper mt-4" data-aos="fade-up">

        <!-- Left Arrow -->
        <button class="arrow-button-pro arrow-left-pro" id="up-comming-prev">
            <img src="{{ asset('assets/front/images/new-images/left.png') }}" alt="Prev">
        </button>

        <!-- Slider -->
        <div class="up-comming-project">
            @foreach($projects as $project)
            <div class="new-up-cards">
                <img src="{{ asset('assets/img/project/featured/' . $project->featured_image) }}" alt="Project 1" class="upcoming-projects-img">
                <div class="upcomming-card-body">
                    {{-- <img src="{{ asset('assets/front/images/new-images/company-1.png') }}" alt="" style="width: 50%; height: 50%;"> --}}
                    <h5>{{ $project->title }}</h5>
                    <p class="text-muted">{{ $project->address }}</p>
                    <h6 class="up-price"> {{ symbolPrice($project->min_price) }} <small class="text-muted">Starting</small></h6>
                </div>
            </div>

            @endforeach


        </div>

        <!-- Right Arrow -->
        <button class="arrow-button-pro arrow-right-pro" id="up-comming-next">
            <img src="{{ asset('assets/front/images/new-images/Right.png') }}" alt="Next">
        </button>
    </div>

</div>


<div class="main-blog" data-aos="fade-up">
    <div class="container" style="padding-top: 100px; padding-bottom: 100px; ">

        <div class="row">
            <div class="col-12">
                <div class="#6c603c aos-init aos-animate" data-aos="fade-up">
                    <h2 class="title">Our Blogs</h2>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-4">

            @foreach ($blogs as $blog)
            <div class="col-md-4">
                <div class="blog-card">
                    <img src="https://images.unsplash.com/photo-1526779259212-939e64788e3c?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NHx8ZnJlZSUyMGltYWdlc3xlbnwwfHwwfHx8MA%3D%3D" class="blog-img" alt="Blog Image">
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
                class="btn btn-lg btn-primary  mb-30" style="background-color: black;">All Blogs</a>
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
            slidesToShow: 4,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 4000,
            dots: false,
            arrows: false,
            responsive: [{
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 3
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

        // Show/hide arrows if not scrollable
        function updateArrows() {
            const slickObj = $slider.slick('getSlick');
            const showArrows = slickObj.slideCount > slickObj.options.slidesToShow;
            $('.arrow-button').toggleClass('hidden', !showArrows);
        }

        $slider.on('init reInit afterChange', updateArrows);
        updateArrows();
    });





    $(document).ready(function() {
        const $slider = $('.up-comming-project');
        $slider.slick({
            slidesToShow: 3, // 3 cards visible
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 2500,
            dots: false,
            arrows: false, // using custom arrows
            responsive: [{
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
        $('#up-comming-prev').on('click', function() {
            $slider.slick('slickPrev');
        });

        $('#up-comming-next').on('click', function() {
            $slider.slick('slickNext');
        });

        // Show/hide arrows if not scrollable
        function updateArrows() {
            const slickObj = $slider.slick('getSlick');
            const showArrows = slickObj.slideCount > slickObj.options.slidesToShow;
            $('.arrow-button').toggleClass('hidden', !showArrows);
        }

        $slider.on('init reInit afterChange', updateArrows);
        updateArrows();
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
        $("#search1").autocomplete({
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

    var productSwiper = new Swiper(".product-slider", {
        loop: true,
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
                slidesPerView: 1
            },
            991: {
                slidesPerView: 2
            },
            1200: {
                slidesPerView: 2
            },
            1400: {
                slidesPerView: 3
            },
            1600: {
                slidesPerView: 4
            }
        }
    });




    // ===============



    var bussinessForSaleSwiper = new Swiper(".bussiness-for-sale-slider", {
        loop: false,
        slidesPerView: 4,
        spaceBetween: 20,
        autoplay: {
            delay: 4000, // 5 seconds
            disableOnInteraction: false,
             pauseOnMouseEnter: true,
        },
        navigation: {
            nextEl: ".bussiness-for-sale-button .swiper-button-next",
            prevEl: ".bussiness-for-sale-button .swiper-button-prev",
        },
         allowTouchMove: false,
    simulateTouch: false,
        breakpoints: {
            1200: {
                slidesPerView: 4
            },
            992: {
                slidesPerView: 3
            },
            768: {
                slidesPerView: 2
            },
            576: {
                slidesPerView: 1
            },
        }
    });


    // =======================



</script>




@endsection
