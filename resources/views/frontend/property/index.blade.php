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
.animits-div-tab:active {
    border: 1px solid #a3daff !important;
    background: #f0f9ff !important;
}

input[type="checkbox"]:checked+label .animits-div-tab {
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

.btn-check:checked+.propertyType-div-tab {
    border: 1px solid #a3daff !important;
    background: #f0f9ff !important;
    color: #000000ff !important;
}

.purpose-div-tab {
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

.btn-check:checked+.purpose-div-tab {
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

@media (max-width: 576px) {
    .product-sort-area .row.justify-content-sm-end {
        display: flex;
        align-items: center;
        /* justify-content: space-between; */
        flex-wrap: nowrap;
    }

    .product-sort-area .col-sm-5,
    .product-sort-area .col-sm-7 {
        flex: 0 0 50%;
        max-width: 50%;
    }

    .product-sort-area .col-sm-5 button {
        width: 100%;
        padding: 4px 8px;
        font-size: 13px;
    }

    .product-sort-area .product-sort-list select {
        font-size: 13px;
        padding: 4px 6px;
    }
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
<div class="listing-grid header-next pb-10" style="margin-top: 100px; " data-aos="fade-up">
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
                            @if(!request()->has('purpose') || request()->has('purpose') && !in_array(request('purpose'),
                            ['franchiese', 'business_for_sale']))
                            <div class="widget widget-select  mb-30">
                                <h3 class="title">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#type" aria-expanded="true" aria-controls="type">
                                        {{ __('Property Type') }}
                                    </button>
                                </h3>
                                <div id="type" class="collapse show">
                                    <div class="accordion-body">
                                        <div class=" custom-checkbox new-animitis-divs">
                                            @php
                                            if (!empty(request()->input('type'))) {
                                            $selectedTypes = [];
                                            if (is_array(request()->input('type'))) {
                                            $selectedTypes = request()->input('type');
                                            } else {
                                            array_push($selectedTypes, request()->input('type'));
                                            }
                                            } else {
                                            $selectedTypes = [];
                                            }
                                            @endphp

                                            @foreach (['residential', 'commercial', 'industrial'] as $type)
                                            <div>
                                                <input class="input-checkbox" type="checkbox" name="category[]"
                                                    id="checkbox{{ $type }}" value="{{ $type }}"
                                                    {{ in_array($type, $selectedTypes) ? 'checked' : '' }}
                                                    onchange="updateAmenities('type[]={{ $type }}',this)">
                                                <label for="checkbox{{ $type }}"><span class="animits-div-tab">
                                                        {{ ucwords($type) }}</span></label>
                                            </div>
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <form action="{{ route('frontend.properties') }}" method="get" id="searchForm"
                                class="w-100">
                                @if(!request()->has('purpose') || request()->has('purpose') &&
                                !in_array(request('purpose'), ['franchiese', 'business_for_sale']))
                                <div class="widget widget-select  mb-30">
                                    <h3 class="title">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#select" aria-expanded="true" aria-controls="select">
                                            {{ __('Property Info') }}
                                        </button>
                                    </h3>
                                    <div id="select" class="collapse show">
                                        <div class="accordion-body">
                                            <div class="form-group mb-20">
                                                <!-- <label class="mb-10">{{ __('Title') }}</label> -->
                                                <input type="text" class="form-control filter-input" name="title"
                                                    placeholder="{{ __('Enter title') }}" style="box-shadow : none"
                                                    value="{{ request()->input('title') }}">
                                            </div>

                                            <div class="form-group mb-20">
                                                <!-- <label class="mb-10">{{ __('Country') }}</label> -->
                                                <select name="country" id="" class="form-control country form-select "
                                                    style="box-shadow : none"
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

                                            <div class="form-group mb-20 state" style="display: none;">
                                                <!-- <label class="mb-10">{{ __('State') }}</label> -->
                                                <select name="state_id" id=""
                                                    class="form-control form-select  state_id states"
                                                    style="box-shadow : none"
                                                    onchange="updateURL('state='+$(this).val());getCities(this)">
                                                    <option>{{ __('Select State') }}</option>
                                                    @if ($basicInfo->property_country_status != 1 &&
                                                    $basicInfo->property_state_status == 1)
                                                    @foreach ($all_states as $state)
                                                    <option data-id="{{ $state->id }}"
                                                        value="{{ $state->stateContent?->name }}">
                                                        {{ $state->stateContent?->name }}
                                                    </option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group mb-20 city" style="display: none;">
                                                <!-- <label class="mb-10">{{ __('City') }}</label> -->
                                                <select name="city_id" id="" class="form-control form-select  city_id"
                                                    style="box-shadow : none"
                                                    onchange="updateURL('city='+$(this).val());getAreas(this)">
                                                    <option>{{ __('Select City') }}</option>
                                                    @if ($basicInfo->property_country_status != 1 &&
                                                    $basicInfo->property_state_status != 1)
                                                    @foreach ($all_cities as $city)
                                                    <option data-id="{{ $city->id }}"
                                                        value="{{ $city->cityContent?->name }}">
                                                        {{ $city->cityContent?->name }}</option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group mb-20 area">
                                                <select name="area_id" id="" class="form-control form-select area_id"
                                                    style="box-shadow : none"
                                                    onchange="updateURL('listArea='+$(this).val())">
                                                    <option>{{ __('Select Area') }}</option>
                                                    @foreach ($all_areas as $area)
                                                    <option data-id="{{ $area->id }}" value="{{ $area->name }}">
                                                        {{ $area->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group mb-20">
                                                <input type="text" class="form-control filter-input" name="location"
                                                    placeholder="{{ __('Enter location') }}" style="box-shadow : none"
                                                    value="{{ request()->input('location') }}">
                                            </div>
                                            <div class="row">
                                                {{-- <div class="col-lg-6">
                                                            <div class="form-group mb-20">
                                                                <input type="text" class="form-control filter-input"
                                                                    name="beds" placeholder="{{ __('No. of bed') }}"
                                                style="box-shadow : none" value="{{ request()->input('beds') }}">
                                            </div>
                                        </div> --}}
                                        <div class="col-lg-12">
                                            <div class="form-group mb-20">
                                                <input type="text" class="form-control filter-input" name="baths"
                                                    placeholder="{{ __('No. of bath') }}" style="box-shadow : none"
                                                    value="{{ request()->input('baths') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-20">
                                        <input type="text" class="form-control filter-input" style="box-shadow : none"
                                            placeholder="{{ __('Enter area Sqft') }}" name="area"
                                            value="{{ request()->input('area') }}">
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
                        <div class="accordion-body">
                            <div class=" custom-checkbox new-animitis-divs">
                                @php
                                if (!empty(request()->input('category'))) {
                                $selectedCategories = [];
                                if (is_array(request()->input('category'))) {
                                $selectedCategories = request()->input('category');
                                } else {
                                array_push(
                                $selectedCategories,
                                request()->input('category'),
                                );
                                }
                                } else {
                                $selectedCategories = [];
                                }
                                @endphp

                                @foreach ($categories as $category)
                                @if ($category->categoryContent)
                                <div>
                                    <input class="input-checkbox" type="checkbox" name="category[]"
                                        id="checkbox{{ $category->id }}" value="{{ $category->id }}"
                                        {{ in_array($category->categoryContent?->slug, $selectedCategories) ? 'checked' : '' }}
                                        onchange="updateAmenities('category[]={{ $category->categoryContent?->slug }}',this)">
                                    <label for="checkbox{{ $category->id }}"><span class="animits-div-tab">
                                            {{ $category->categoryContent?->name }}</span></label>
                                </div>
                                @endif
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>

                <div class="widget widget-amenities mb-30">
                    <h3 class="title">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#amenities" aria-expanded="true" aria-controls="amenities">
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
                                    <input class="input-checkbox" type="checkbox" name="amenities[]"
                                        id="checkbox{{ $amenity->id }}" value="{{ $amenity->id }}"
                                        {{ in_array($amenity->amenityContent?->name, $selected_amenities) ? 'checked' : '' }}
                                        onchange="updateAmenities('amenities[]={{ $amenity->amenityContent?->name }}',this)">

                                    <label for="checkbox{{ $amenity->id }}"><span
                                            class="animits-div-tab">{{ $amenity->amenityContent?->name }}</span></label>
                                    <!-- <label class="form-check-label new-lable-check-animitis" for="checkbox{{ $amenity->id }}"><span class="animits-div-tab">{{ $amenity->amenityContent?->name }}</span></label> -->
                                </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="widget widget-price mb-30">
                    <h3 class="title">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#price"
                            aria-expanded="true" aria-controls="price">
                            {{ __('Pricing Filter') }}
                        </button>
                    </h3>

                    {{-- Hidden fields used by JS / server --}}
                    <input type="hidden" class="form-control" name="min" id="min"
                        value="{{ request()->filled('min') ? (int) request()->input('min') : (int) $min }}">
                    <input type="hidden" class="form-control" name="max" id="max"
                        value="{{ request()->filled('max') ? (int) request()->input('max') : (int) $max }}">
                    <input type="hidden" class="form-control" id="o_min" value="{{ (int) $min }}">
                    <input type="hidden" class="form-control" id="o_max" value="{{ (int) $max }}">
                    <input type="hidden" id="currency_symbol" value="{{ $basicInfo->base_currency_symbol }}">

                    <div id="price" class="collapse show">
                        <div class="accordion-body">
                            <div class="price-item">
                                {{-- Slider target with data attributes for clarity --}}
                                <div data-range-slider="priceSlider" data-range-min="{{ (int) $min }}"
                                    data-range-max="{{ (int) $max }}"
                                    data-start-min="{{ request()->filled('min') ? (int) request()->input('min') : (int) $min }}"
                                    data-start-max="{{ request()->filled('max') ? (int) request()->input('max') : (int) $max }}">
                                </div>

                                <div class="price-value">
                                    <span style="color:#6c603c;">
                                        {{ __('Price :') }}
                                        <span data-range-value="priceSliderValue">
                                            {{ symbolPrice($min) }} - {{ symbolPrice($max) }}
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="cta">

                    <div class="row">
                        <div class="col-sm-12">
                            <button onclick="resetURL()" type="button" class="btn-text  icon-start mt-10"
                                style="color: white;"><i class="fal fa-redo"></i>{{ __('Reset Search') }}</button>
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
                        data-bs-toggle="offcanvas" data-bs-target="#widgetOffcanvas" aria-controls="widgetOffcanvas">
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
            <x-property :property="$property_content" :animate="false" class="col-lg-4 col-md-6 mb-30" />
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
var purpose = @json(request('purpose') ?? '');
</script>
<!-- Leaflet Map JS -->
<script src="{{ asset('/assets/front/js/vendors/leaflet.js') }}"></script>
<script src="{{ asset('/assets/front/js/vendors/leaflet.markercluster.js') }}"></script>
<!-- Map JS -->
{{-- <script src="{{ asset('/assets/front/js/map.js') }}"></script> --}}
<script src="{{ asset('/assets/front/js/properties.js') }}"></script>

<script>
// Debounce search input
let debounceTimer;
$('.filter-input').keyup(function(e) {
    const param = $(this).attr('name');
    const val = $(this).val().trim();
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        updateURL(`${param}=${encodeURIComponent(val)}`);
    }, 400);
});

document.addEventListener('DOMContentLoaded', function() {
    const sliderEl = document.querySelector("[data-range-slider='priceSlider']");
    if (!sliderEl) return;

    if (!sliderEl.noUiSlider) {
        noUiSlider.create(sliderEl, {
            start: [Number($('#min').val()) || 0, Number($('#max').val()) || 0],
            connect: true,
            range: {
                min: Number($('#o_min').val()) || 0,
                max: Number($('#o_max').val()) || 0
            },
            step: 1
        });
    }

    const inputs = [
        document.getElementById('min') || null,
        document.getElementById('max') || null
    ];

    sliderEl.noUiSlider.on('update', function(values, handle) {
        const val = Math.round(Number(values[handle]) || 0);
        const input = inputs[handle];
        if (input) input.value = val; // ✅ only write if present
    });

    // If you also update a label:
    const $label = $("[data-range-value='priceSliderValue']");
    const currency = $('#currency_symbol').val() || '';
    const fmt = new Intl.NumberFormat(undefined, {
        maximumFractionDigits: 0
    });

    sliderEl.noUiSlider.on('update', function(values) {
        const minV = Math.round(Number(values[0]) || 0);
        const maxV = Math.round(Number(values[1]) || 0);
        if ($label.length) $label.text(
            `${currency}${fmt.format(minV)} - ${currency}${fmt.format(maxV)}`);
    });

    // when user releases handles, push via your updateURL(min=...), updateURL(max=...)
    let debounceTimer1;
    sliderEl.noUiSlider.on('change', function(values) {
        const minV = Math.round(+values[0] || 0);
        const maxV = Math.round(+values[1] || 0);

        clearTimeout(debounceTimer1);
        debounceTimer = setTimeout(() => {
            updateURL(`min=${encodeURIComponent(minV)}`);
            updateURL(`max=${encodeURIComponent(maxV)}`);
        }, 400);
    });
});
</script>
@endsection