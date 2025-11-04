@php
$version = $basicInfo->theme_version;
@endphp
@extends("frontend.layouts.layout-v$version")

@section('pageHeading')
{{ !empty($pageHeading) ? $pageHeading->property_page_title : __('Property') }}
@endsection

@section('metaKeywords')
@if (!empty($seoInfo))
{{ $seoInfo->meta_keyword_properties }}
@endif
@endsection

@section('metaDescription')
@if (!empty($seoInfo))
{{ $seoInfo->meta_description_properties }}
@endif
@endsection
@section('style')
<meta http-equiv="Cache-Control" content="no-store" />
@endsection
@section('content')

<style>
.new-main-navbar {
    background-color: #6c603c;
}


    .new-round-category-des {
        border: 1px solid #dcdcdc;
         padding: 4px 16px;
        margin: 3px;
        font-size: 13px;
        font-weight: 500;
        border-radius: 50px;
        height: 20px;
        background: white;
        line-height: 2;
        cursor: pointer;
        transition: all 0.3s ease;
    }

     

.new-round-category-des:hover,
.new-round-category-des:active,
.new-round-category-des.active {
  border: 1px solid #a3daff !important;
  background: #f0f9ff !important;
}
       

    .new-animitis-divs {
        display: flex;
        flex-wrap: wrap;
    }



    .animits-div-tab {
        border: 1px solid #dcdcdc;
            padding: 5px 16px;
        margin: 4px;
        font-size: 13px;
        font-weight: 500;
        border-radius: 50px;
        height: 20px;
        background: white;
        line-height: 2.5;
        color: #2b3138;
        cursor: pointer;
    }


    .animits-div-tab:hover,
.animits-div-tab:active{
  border: 1px solid #a3daff !important;
  background: #f0f9ff !important;
}
    


/* When checkbox is checked */
input[type="checkbox"]:checked + label .animits-div-tab {
  border: 1px solid #a3daff !important;
  background: #f0f9ff !important;
}

    .new-items-pricelist {
        padding: 5px 40px 2px 15px;
        width: fit-content;
        border-radius: 8px;
        background: white;
        border: 1px solid #dcdcdc;
        color: #2b3138;
    }

    .propertyType-div-tab {
        border: 1px solid #dcdcdc;
       padding: 1px 17px;
        margin: 4px;
        font-size: 13px;
        font-weight: 500;
        border-radius: 50px;
        background: white;
        line-height: 2.1;
        color: #2b3138;
        cursor: pointer;
    }

       
       .propertyType-div-tab:hover {
            border: 1px solid #a3daff !important;
            background: #f0f9ff !important;
        }

        .btn-check:checked + .propertyType-div-tab {
  border: 1px solid #a3daff !important;
  background: #f0f9ff !important;
  color: #000000ff !important;
}

    .purpose-div-tab{
         border: 1px solid #dcdcdc;
           padding: 1px 16px;
    margin: 4px;
    font-size: 13px;
    font-weight: 500;
    border-radius: 36px;
        background: white;
        line-height: 2.1;
        color: #2b3138;
        cursor: pointer;
    }

           .purpose-div-tab:hover {
            border: 1px solid #a3daff !important;
            background: #f0f9ff !important;
        }

        .btn-check:checked + .purpose-div-tab {
  border: 1px solid #a3daff !important;
  background: #f0f9ff !important;
  color: #000000ff !important;
}


        .sidebar-widget-area .widget {
            padding: 0px;
                border: none;
                border-bottom: 1px solid #dcdcdc;
    padding-bottom: 14px;
        }
 


</style>


<a href="https://wa.me/9925133440" target="_blank">
    <div class="whatsapp-btn" data-aos="fade-up">
        <img src="{{ asset('assets/front/images/new-images/whatsapp.png') }}" alt="WhatsApp">
    </div>
</a>

<!-- Map Start-->
<div class="map-area border-top header-next pt-30">

    <!-- <div class="container">
            <div class="lazy-container radius-md ratio border">
                <div id="main-map"></div>
            </div>
        </div> -->
</div>
<!-- Map End-->



<!-- Listing Start -->
<div class="listing-grid header-next pb-70" style="margin-top: 100px; " data-aos="fade-up">
    <div class="container">
        <div class="row gx-xl-5">
            <div class="col-xl-3">
                <div class="widget-offcanvas offcanvas-xl offcanvas-start" tabindex="-1" id="widgetOffcanvas"
                    aria-labelledby="widgetOffcanvas">
                    <div class="offcanvas-header px-20">
                        <h4 class="offcanvas-title">{{ __('Filter') }}</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                            data-bs-target="#widgetOffcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body p-3 p-xl-0">

                        <aside class="sidebar-widget-area new-color-ngs-property" data-aos="fade-up">
                            <div class="widget widget-select  mb-30">
                                <h3 class="title">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#type" aria-expanded="true" aria-controls="type">
                                        {{ __('Property Type') }}
                                    </button>
                                </h3>
                                <div id="type" class="collapse show">
                                    <!-- <div class="accordion-body"> <select name="type" id="" class="form-control form-select mb-20" onchange="updateURL('type='+$(this).val())">
                                            <option selected disabled>{{ __('Select Type') }}</option>
                                            <option value="all" {{ request()->filled('type') && request()->input('type') == 'all' ? 'selected' : '' }}> {{ __('All') }}</option>
                                            <option value="residential" {{ request()->filled('type') && request()->input('type') == 'residential' ? 'selected' : '' }}> {{ __('Residential') }}</option>
                                            <option value="commercial" {{ request()->filled('type') && request()->input('type') == 'commercial' ? 'selected' : '' }}> {{ __('Commercial') }}</option>
                                            <option value="industrial" {{ request()->filled('type') && request()->input('type') == 'industrial' ? 'selected' : '' }}> {{ __('Industrial') }}</option>
                                        </select>
                                    </div> -->
                                    <div class="accordion-body d-flex flex-wrap">
                                        <input type="radio" class="btn-check" name="type" id="type-all" value="all"
                                            onchange="updateURL('type='+this.value)"
                                            {{ request()->filled('type') && request()->input('type') == 'all' ? 'checked' : '' }}>
                                        <label class="btn propertyType-div-tab" for="type-all">{{ __('All') }}</label>
                                        <input type="radio" class="btn-check" name="type" id="type-residential" value="residential"
                                            onchange="updateURL('type='+this.value)"
                                            {{ request()->filled('type') && request()->input('type') == 'residential' ? 'checked' : '' }}>
                                        <label class="btn  propertyType-div-tab" for="type-residential">{{ __('Residential') }}</label>
                                        <input type="radio" class="btn-check" name="type" id="type-commercial" value="commercial"
                                            onchange="updateURL('type='+this.value)"
                                            {{ request()->filled('type') && request()->input('type') == 'commercial' ? 'checked' : '' }}>
                                        <label class="btn  propertyType-div-tab" for="type-commercial">{{ __('Commercial') }}</label>
                                        <input type="radio" class="btn-check" name="type" id="type-industrial" value="industrial"
                                            onchange="updateURL('type='+this.value)"
                                            {{ request()->filled('type') && request()->input('type') == 'industrial' ? 'checked' : '' }}>
                                        <label class="btn propertyType-div-tab" for="type-industrial">{{ __('Industrial') }}</label>

                                    </div>
                                </div>
                            </div>

                            <form action="{{ route('frontend.properties') }}" method="get" id="searchForm"
                                class="w-100">
 
                                <!--<div class="widget widget-select  mb-30">
                                    <h3 class="title">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#purpose" aria-expanded="true" aria-controls="purpose">
                                            {{ __('Purpose') }}
                                    </h3>
                                    <div id="purpose" class="collapse show">
                                        <!{1}** <div class="accordion-body">
                                            <select name="purpose" onchange="updateURL('purpose='+$(this).val())"
                                                id="" class="form-control form-select mb-20">
                                                <option selected disabled>{{ __('Select Purpose') }}</option>
                                                <option value="all"
                                                    {{ request()->filled('purpose') && request()->input('purpose') == 'all' ? 'selected' : '' }}>
                                                    {{ __('All') }}
                                                </option>
                                                <option value="rent"
                                                    {{ request()->filled('purpose') && request()->input('purpose') == 'rent' ? 'selected' : '' }}>
                                                    {{ __('Rent') }}
                                                </option>
                                                <option value="sell"
                                                    {{ request()->filled('purpose') && request()->input('purpose') == 'sell' ? 'selected' : '' }}>
                                                    {{ __('Sell') }}
                                                </option>
                                                <option value="buy"
                                                    {{ request()->filled('purpose') && request()->input('purpose') == 'buy' ? 'selected' : '' }}>
                                                    {{ __('Buy') }}
                                                </option>
                                                <option value="lease"
                                                    {{ request()->filled('purpose') && request()->input('purpose') == 'lease' ? 'selected' : '' }}>
                                                    {{ __('Lease') }}
                                                </option>
                                                <option value="franchiese" {{ request()->filled('franchiese') && request()->input('franchiese') == 'franchiese' ? 'selected' : '' }}>
                                                    {{ __('Franchiese') }}
                                                </option>
                                                <option value="business_for_sale" {{ request()->filled('business_for_sale') && request()->input('business_for_sale') == 'business_for_sale' ? 'selected' : '' }}>
                                                    {{ __('Business For Sale') }}
                                                </option>
                                            </select>
                                        </div> **{1}>
                                        <!{1}**<div class="accordion-body d-flex flex-wrap">

                                        <input type="radio" class="btn-check" name="purpose" id="purpose-all" value="all"
                                            onchange="updateURL('purpose='+this.value)"
                                            {{ request()->filled('purpose') && request()->input('purpose') == 'all' ? 'checked' : '' }}>
                                        <label class="btn purpose-div-tab" for="purpose-all">{{ __('All') }}</label>

                                        <input type="radio" class="btn-check" name="purpose" id="purpose-rent" value="rent"
                                            onchange="updateURL('purpose='+this.value)"
                                            {{ request()->filled('purpose') && request()->input('purpose') == 'rent' ? 'checked' : '' }}>
                                        <label class="btn purpose-div-tab" for="purpose-rent">{{ __('Rent') }}</label>

                                        <input type="radio" class="btn-check" name="purpose" id="purpose-sell" value="sell"
                                            onchange="updateURL('purpose='+this.value)"
                                            {{ request()->filled('purpose') && request()->input('purpose') == 'sell' ? 'checked' : '' }}>
                                        <label class="btn purpose-div-tab" for="purpose-sell">{{ __('Sell') }}</label>

                                        <input type="radio" class="btn-check" name="purpose" id="purpose-buy" value="buy"
                                            onchange="updateURL('purpose='+this.value)"
                                            {{ request()->filled('purpose') && request()->input('purpose') == 'buy' ? 'checked' : '' }}>
                                        <label class="btn purpose-div-tab" for="purpose-buy">{{ __('Buy') }}</label>

                                        <input type="radio" class="btn-check" name="purpose" id="purpose-lease" value="lease"
                                            onchange="updateURL('purpose='+this.value)"
                                            {{ request()->filled('purpose') && request()->input('purpose') == 'lease' ? 'checked' : '' }}>
                                        <label class="btn purpose-div-tab" for="purpose-lease">{{ __('Lease') }}</label>

                                        <input type="radio" class="btn-check" name="purpose" id="purpose-franchiese" value="franchiese"
                                            onchange="updateURL('purpose='+this.value)"
                                            {{ request()->filled('purpose') && request()->input('purpose') == 'franchiese' ? 'checked' : '' }}>
                                        <label class="btn purpose-div-tab" for="purpose-franchiese">{{ __('Franchiese') }}</label>

                                        <input type="radio" class="btn-check" name="purpose" id="purpose-business-for-sale" value="business_for_sale"
                                            onchange="updateURL('purpose='+this.value)"
                                            {{ request()->filled('purpose') && request()->input('purpose') == 'business_for_sale' ? 'checked' : '' }}>
                                        <label class="btn purpose-div-tab" for="purpose-business-for-sale">{{ __('Business For Sale') }}</label>

                                    </div>**{1}>

                                    </div>
                                </div>-->

                                <div class="widget widget-select  mb-30">
                                    <h3 class="title">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#select" aria-expanded="true" aria-controls="select">
                                            {{ __('Property Info') }}
                                        </button>
                                    </h3>
                                    <div id="select" class="collapse show">
                                        <div class="accordion-body">
                                            <div class="form-group mb-20" >
                                                <!-- <label class="mb-10">{{ __('Title') }}</label> -->
                                                <input type="text" class="form-control" name="title"
                                                    placeholder="{{ __('Enter title') }}" style="box-shadow : none"
                                                    onkeydown="if (event.keyCode == 13) updateURL('title='+$(this).val())">
                                            </div>
                                            @if ($basicInfo->property_country_status == 1)
                                            <div class="form-group mb-20" >
                                                <!-- <label class="mb-10">{{ __('Country') }}</label> -->
                                                <select name="country" id=""
                                                    class="form-control country form-select " style="box-shadow : none"
                                                    onchange="updateURL('country='+$(this).val())">
                                                    <option selected disabled>{{ __('Select Country') }}</option>
                                                    <option value="all" data-id="0">{{ __('All') }}
                                                    </option>
                                                    @foreach ($all_countries as $country)
                                                    <option data-id="{{ $country->id }}"
                                                        value="{{ $country->countryContent?->name }}">
                                                        {{ $country->countryContent?->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @endif
                                            @if ($basicInfo->property_state_status == 1)
                                            <div class="form-group mb-20 state" >
                                                <!-- <label class="mb-10">{{ __('State') }}</label> -->
                                                <select name="state_id" id=""
                                                    class="form-control form-select  state_id states" style="box-shadow : none"
                                                    onchange="updateURL('state='+$(this).val());getCities(this)">
                                                    <option>{{ __('Select State') }}</option>
                                                    @if ($basicInfo->property_country_status != 1 && $basicInfo->property_state_status == 1)
                                                    @foreach ($all_states as $state)
                                                    <option data-id="{{ $state->id }}"
                                                        value="{{ $state->stateContent?->name }}">
                                                        {{ $state->stateContent?->name }}
                                                    </option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            @endif
                                            <div class="form-group mb-20 city" >
                                                <!-- <label class="mb-10">{{ __('City') }}</label> -->
                                                <select name="city_id" id=""
                                                    class="form-control form-select  city_id" style="box-shadow : none"
                                                    onchange="updateURL('city='+$(this).val());getAreas(this)">
                                                    <option>{{ __('Select City') }}</option>
                                                    @if ($basicInfo->property_country_status != 1 && $basicInfo->property_state_status != 1)
                                                        @foreach ($all_cities as $city)
                                                            <option data-id="{{ $city->id }}"
                                                                value="{{ $city->cityContent?->name }}">
                                                                {{ $city->cityContent?->name }}</option>
                                                        @endforeach
                                                    @endif
                                                 </select>
                                            </div>
                                            <div class="form-group mb-20 area" >
                                                <select name="area_id" id=""
                                                    class="form-control form-select area_id" style="box-shadow : none"
                                                    onchange="updateURL('listArea='+$(this).val())">
                                                    <option>{{ __('Select Area') }}</option>
                                                    @if ($basicInfo->property_country_status != 1 && $basicInfo->property_state_status != 1)
                                                        @foreach ($all_areas as $area)
                                                            <option data-id="{{ $area->id }}"
                                                                value="{{ $area->name }}">
                                                                {{ $area->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group mb-20"  >
                                                <!-- <label class="mb-10">{{ __('Location') }}</label> -->
                                                <input type="text" class="form-control" name="location"
                                                    placeholder="{{ __('Enter location') }}" style="box-shadow : none"
                                                    onkeydown="if (event.keyCode == 13) updateURL('location='+$(this).val())">
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-group mb-20"  >
                                                        <!-- <label class="mb-10"> {{ __('Beds') }}</label> -->
                                                        <input type="text" class="form-control" name="beds"
                                                            placeholder="{{ __('No. of bed') }}" style="box-shadow : none"
                                                            onkeydown="if (event.keyCode == 13) updateURL('beds='+$(this).val())">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group mb-20"  >
                                                        <!-- <label class="mb-10"> {{ __('Baths') }}</label> -->
                                                        <input type="text" class="form-control" name="baths"
                                                            placeholder="{{ __('No. of bath') }}" style="box-shadow : none"
                                                            onkeydown="if (event.keyCode == 13) updateURL('baths='+$(this).val())">

                                                    </div>
                                                </div>
                                            </div>


                                            <div class="form-group mb-20"  >
                                                <!-- <label class="mb-10"> {{ __('Area') }}
                                                    ({{ __('Sqft') }}.)</label> -->
                                                <input type="text" class="form-control" style="box-shadow : none"
                                                    placeholder="{{ __('Enter area Sqft') }}"
                                                    onkeydown="if (event.keyCode == 13) updateURL('area='+$(this).val())">
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="widget widget-categories  mb-30">
                                    <h3 class="title">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#categories" aria-expanded="true" aria-controls="categories">
                                            {{ __('Categories') }}
                                        </button>
                                    </h3>
                                    <div id="categories" class="collapse show">
                                        <div class="accordion-body" style="line-height: 2;">
                                            <a class="new-round-category-des {{ request()->filled('category') && request()->input('category') == 'all' ? 'active' : '' }}"
                                                onclick="updateURL('category=all')">
                                                {{ __('All') }} </a>
                                            @foreach ($categories as $category)
												@if ($category->categoryContent)


												<a class="new-round-category-des {{ request()->filled('category') && request()->input('category') == $category->categoryContent?->slug ? 'active' : '' }}"
													onclick="updateURL('category={{ $category->categoryContent?->slug }}');">
													{{ $category->categoryContent?->name }}</a>

												@endif
                                            @endforeach
                                            <!-- <ul class="list-group">

                                                    <li class="list-item">

                                                        <a class="{{ request()->filled('category') && request()->input('category') == 'all' ? 'active' : '' }}"
                                                            onclick="updateURL('category=all')">
                                                            {{ __('All') }} </a>
                                                    </li>
                                                    <div id="catogoryul">
                                                        @foreach ($categories as $category)
                                                            @if ($category->categoryContent)
                                                                <li class="list-item">

                                                                    <a class="{{ request()->filled('category') && request()->input('category') == $category->categoryContent?->slug ? 'active' : '' }}"
                                                                        onclick="updateURL('category={{ $category->categoryContent?->slug }}');">
                                                                        {{ $category->categoryContent?->name }}</a>
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </ul> -->
                                        </div>
                                    </div>
                                </div>


                                <div class="widget widget-amenities mb-30">
                                    <h3 class="title">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#amenities" aria-expanded="true"
                                            aria-controls="amenities">
                                            {{ __('Amenities') }}
                                        </button>
                                    </h3>
                                    <div id="amenities" class="collapse show">
                                        <div class="accordion-body">
                                            <div class=" custom-checkbox new-animitis-divs">
                                                @php
                                                if (!empty(request()->input('amenities'))) {
                                                $selected_amenities = [];
                                                if (is_array(request()->input('amenities'))) {
                                                $selected_amenities = request()->input('amenities');
                                                } else {
                                                array_push(
                                                $selected_amenities,
                                                request()->input('amenities'),
                                                );
                                                }
                                                } else {
                                                $selected_amenities = [];
                                                }
                                                @endphp
                                                @foreach ($amenities as $amenity)
                                                @if ($amenity->amenityContent)
                                                <div>
                                                    <input class="input-checkbox" type="checkbox"
                                                        name="amenities[]" id="checkbox{{ $amenity->id }}"
                                                        value="{{ $amenity->id }}"
                                                        {{ in_array($amenity->amenityContent?->name, $selected_amenities) ? 'checked' : '' }}
                                                        onchange="updateAmenities('amenities[]={{ $amenity->amenityContent?->name }}',this)">

                                                    <label for="checkbox{{ $amenity->id }}"><span class="animits-div-tab">{{ $amenity->amenityContent?->name }}</span></label>
                                                    <!-- <label class="form-check-label new-lable-check-animitis" for="checkbox{{ $amenity->id }}"><span class="animits-div-tab">{{ $amenity->amenityContent?->name }}</span></label> -->
                                                </div>
                                                @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="widget widget-type radius-md mb-30">
                                    <h3 class="title">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#pricetype" aria-expanded="true" aria-controls="type">
                                            {{ __('Pricing Type') }}
                                        </button>
                                    </h3>
                                    <div id="pricetype" class="collapse show">
                                        <div class="accordion-body">
                                            <ul class="list-group">
                                                <li class="list-item new-items-pricelist">
                                                    <div class="form-check">
                                                        <input class="form-check-input  " type="radio"
                                                            name="price"
                                                            {{ request()->input('price') == 'all' ? 'checked' : '' }}
                                                            onchange="updateURL('price=all',this)" id="exampleRadios"
                                                            value="all" checked>
                                                        <label class="form-check-label" for="exampleRadios">
                                                            {{ __('All') }}
                                                        </label>
                                                    </div>
                                                </li>

                                                <li class="list-item new-items-pricelist">
                                                    <div class="form-check">
                                                        <input class="form-check-input  " type="radio"
                                                            name="price"
                                                            {{ request()->input('price') == 'fixed' ? 'checked' : '' }}
                                                            onchange="updateURL('price=fixed',this)"
                                                            id="exampleRadios1" value="fixed">
                                                        <label class="form-check-label" for="exampleRadios1">
                                                            {{ __('Fixed Price') }}
                                                        </label>
                                                    </div>
                                                </li>

                                                <li class="list-item new-items-pricelist">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="price"
                                                            {{ request()->input('price') == 'negotiable' ? 'checked' : '' }}
                                                            onchange="updateURL('price=negotiable',this)"
                                                            id="exampleRadios2" value="negotiable">
                                                        <label class="form-check-label" for="exampleRadios2">
                                                            {{ __('Negotiable') }}
                                                        </label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div> --}}


                                <div class="widget widget-price mb-30">
                                    <h3 class="title">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#price" aria-expanded="true" aria-controls="price">
                                            {{ __('Pricing Filter') }}
                                        </button>
                                    </h3>
                                    <input class="form-control" type="hidden"
                                        value="{{ request()->filled('min') ? request()->input('min') : $min }}"
                                        name="min" id="min">
                                    <input class="form-control" type="hidden" value="{{ $min }}"
                                        id="o_min">
                                    <input class="form-control" type="hidden" value="{{ $max }}"
                                        id="o_max">

                                    <input class="form-control"
                                        value="{{ request()->filled('max') ? request()->input('max') : $max }}"
                                        type="hidden" name="max" id="max">
                                    <input type="hidden" id="currency_symbol"
                                        value="{{ $basicInfo->base_currency_symbol }}">

                                    <div id="price" class="collapse show">
                                        <div class="accordion-body">
                                            <div class="price-item">
                                                <div data-range-slider='priceSlider'></div>
                                                <div class="price-value">
                                                    <span style="color: #6c603c;">{{ __('Price :') }} <span
                                                            data-range-value="priceSliderValue">
                                                            {{ symbolPrice($min) }}
                                                            -
                                                            {{ symbolPrice($max) }}
                                                        </span></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="cta">

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <button onclick="resetURL()" type="button"
                                                class="btn-text  icon-start mt-10" style="color: white;"><i
                                                    class="fal fa-redo"></i>{{ __('Reset Search') }}</button>
                                        </div>

                                    </div>
                                </div>
                            </form>



                        </aside>

                    </div>
                </div>
            </div>
            <div class="col-xl-9">
                <div class="product-sort-area mb-10" data-aos="fade-up">
                    <div class="row justify-content-sm-end">
                        <div class="col-sm-5 d-xl-none">
                            <button class="btn btn-sm btn-outline icon-end radius-sm mb-15" type="button"
                                data-bs-toggle="offcanvas" data-bs-target="#widgetOffcanvas"
                                aria-controls="widgetOffcanvas">
                                {{ __('Filter') }} <i class="fal fa-filter"></i>
                            </button>
                        </div>
                        <div class="col-sm-7">
                            <ul class="product-sort-list text-sm-end list-unstyled mb-15">
                                <li class="item">
                                    <div class="sort-item d-flex align-items-center">
                                        <label class="color-dark me-2 font-sm flex-auto">{{ __('Sort By') }} :</label>
                                        <select class="form-select form_control" name="sort"
                                            onchange="updateURL('sort='+$(this).val())">
                                            <option
                                                {{ request()->filled('sort') && request()->input('sort') == 'new' ? 'selected' : '' }}
                                                value="new">{{ __('Newest') }}</option>
                                            <option
                                                {{ request()->filled('sort') && request()->input('sort') == 'old' ? 'selected' : '' }}
                                                value="old">{{ __('Oldest') }}</option>
                                            <option
                                                {{ request()->filled('sort') && request()->input('sort') == 'low-to-high' ? 'selected' : '' }}
                                                value="low-to-high">
                                                {{ __('Price : Low to High') }}
                                            </option>
                                            <option
                                                {{ request()->filled('sort') && request()->input('sort') == 'high-to-low' ? 'selected' : '' }}
                                                value="high-to-low">{{ __('Price : High to Low') }}</option>
                                        </select>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row properties">
                    @forelse ($property_contents as $property_content)
                    <x-property :property="$property_content" :animate="false" class="col-lg-4 col-md-6" />
                    @empty
                    <div class="col-lg-12">
                        <h3 class="text-center mt-5">{{ __('NO PROPERTY FOUND') . '!' }}</h3>
                    </div>
                    @endforelse
                    <div class="row">
                        <div class="col-lg-12 pagination justify-content-center customPaginagte">
                            {{ $property_contents->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Listing End -->
@endsection

@section('script')
<script>
    'use strict';
    var property_contents = @json($property_contents);
    var properties = property_contents.data;
</script>
<!-- Leaflet Map JS -->
<script src="{{ asset('/assets/front/js/vendors/leaflet.js') }}"></script>
<script src="{{ asset('/assets/front/js/vendors/leaflet.markercluster.js') }}"></script>
<!-- Map JS -->
{{-- <script src="{{ asset('/assets/front/js/map.js') }}"></script> --}}
<script src="{{ asset('/assets/front/js/properties.js') }}"></script>
@endsection
