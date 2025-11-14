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
<div class="listing-grid header-next pb-10" style="margin-top: 100px;" data-aos="fade-up">
    <div class="container">
        <div class="row gx-xl-5">

            <!-- SIDEBAR LEFT START -->
            <div class="col-xl-3 ">

                <div class="widget-offcanvas offcanvas offcanvas-start" tabindex="-1" id="widgetOffcanvas"
                    aria-labelledby="widgetOffcanvas">
                    <div class="offcanvas-header px-20" style="
    justify-content: space-between;
">
                        <h4 class="offcanvas-title">{{ __('Filter') }}</h4>
                        <button type="button"  data-bs-dismiss="offcanvas"
                            data-bs-target="#widgetOffcanvas" aria-label="Close" style="
    width: fit-content;
    background: transparent;
    padding: 0px;
">
<i class="fas fa-close" style="    color: black;
    font-size: 22px;
"></i>
                        </button>
                    </div>

                    <div class="offcanvas-body p-3 p-xl-0">
                        <aside class="sidebar-widget-area new-color-ngs-property" data-aos="fade-up">

                            @if(!request()->has('purpose') || request()->has('purpose') && !in_array(request('purpose'),
                            ['franchiese', 'business_for_sale']))

                            <!-- Property Type -->
                            <div class="widget widget-select mb-30">
                                <h3 class="title">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#type" aria-expanded="true" aria-controls="type">
                                        {{ __('Property Type') }}
                                    </button>
                                </h3>

                                <div id="type" class="collapse show">
                                    <div class="accordion-body">
                                        <div class="custom-checkbox new-animitis-divs">

                                            @php
                                            $selectedTypes = request()->input('type', []);
                                            if(!is_array($selectedTypes)) $selectedTypes = [$selectedTypes];
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

                            <!-- PROPERTY INFO -->
                            <form action="{{ route('frontend.properties') }}" method="get" id="searchForm"
                                class="w-100">

                                @if(!request()->has('purpose') || request()->has('purpose') &&
                                !in_array(request('purpose'), ['franchiese', 'business_for_sale']))

                                <div class="widget widget-select mb-30">
                                    <h3 class="title">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#select" aria-expanded="true" aria-controls="select">
                                            {{ __('Property Info') }}
                                        </button>
                                    </h3>

                                    <div id="select" class="collapse show">
                                        <div class="accordion-body">

                                            <!-- Title -->
                                            <div class="form-group mb-20">
                                                <input type="text" class="form-control filter-input" name="title"
                                                    placeholder="{{ __('Enter Properties Name') }}"
                                                    value="{{ request()->input('title') }}" style="box-shadow:none;">
                                            </div>

                                            <!-- Area Select -->
                                            <div class="form-group mb-20 area">
                                                <select name="area_id" class="form-control form-select area_id"
                                                    style="box-shadow:none;"
                                                    onchange="updateURL('listArea='+$(this).val())">
                                                    <option>{{ __('Select Area') }}</option>
                                                    @foreach ($all_areas as $area)
                                                    <option value="{{ $area->name }}">{{ $area->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <!-- <div class="form-group mb-20">
                                                <input type="text" class="form-control filter-input" name="location"
                                                    placeholder="{{ __('Enter location') }}" style="box-shadow : none"
                                                    value="{{ request()->input('location') }}">
                                            </div> -->
                                            <div class="row">
                                                {{-- <div class="col-lg-6">
                                                            <div class="form-group mb-20">
                                                                <input type="text" class="form-control filter-input"
                                                                    name="beds" placeholder="{{ __('No. of bed') }}"
                                                style="box-shadow : none" value="{{ request()->input('beds') }}">
                                            </div>
                                        </div> --}}
                                        <!-- <div class="col-lg-12">
                                            <div class="form-group mb-20">
                                                <input type="text" class="form-control filter-input" name="baths"
                                                    placeholder="{{ __('No. of bath') }}" style="box-shadow : none"
                                                    value="{{ request()->input('baths') }}">
                                            </div>
                                        </div> -->
                                    </div>
                                    <!-- <div class="form-group mb-20">
                                        <input type="text" class="form-control filter-input" style="box-shadow : none"
                                            placeholder="{{ __('Enter area Sqft') }}" name="area"
                                            value="{{ request()->input('area') }}">
                                    </div> -->
                                    <div class="collapse show">
                                        <div >
                                            @php
                                                if (!empty(request()->input('unit_type')))
                                                {
                                                    $selectedUnitTypes = [];
                                                    if (is_array(request()->input('unit_type'))) {
                                                        $selectedUnitTypes = request()->input('unit_type');
                                                    } else {
                                                        array_push($selectedUnitTypes, request()->input('unit_type'));
                                                    }
                                                } else {
                                                    $selectedUnitTypes = [];
                                                }
                                            @endphp
                                            <div class=" custom-checkbox new-animitis-divs"> 
                                                @foreach($units as $unit)
                                                    <div>
                                                        <input class="input-checkbox" type="checkbox" name="unit_type[]"
                                                            id="checkbox{{ $unit->id }}" value="{{ $unit->id }}"
                                                            {{ in_array($unit->id, $selectedUnitTypes) ? 'checked' : '' }}
                                                            onchange="updateAmenities('unit_type[]={{ $unit->id }}',this)">
                                                        <label for="checkbox{{ $unit->id }}"><span class="animits-div-tab">
                                                                {{ ucwords($unit->unit_name) }}</span></label>
                                                    </div>
                                                @endforeach
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Categories -->
                                <div class="widget widget-categories mb-30">
                                    <h3 class="title">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#categories" aria-expanded="true"
                                            aria-controls="categories">
                                            {{ __('Categories') }}
                                        </button>
                                    </h3>
                                    <div id="categories" class="collapse show">
                                        <div class="accordion-body">
                                            <div class="custom-checkbox new-animitis-divs">

                                                @php
                                                $selectedCategories = request()->input('category', []);
                                                if(!is_array($selectedCategories)) $selectedCategories =
                                                [$selectedCategories];
                                                @endphp

                                                @foreach ($categories as $category)
                                                @if ($category->categoryContent)
                                                <div>
                                                    <input class="input-checkbox" type="checkbox" name="category[]"
                                                        id="checkbox_cat{{ $category->id }}"
                                                        value="{{ $category->categoryContent->slug }}"
                                                        {{ in_array($category->categoryContent->slug, $selectedCategories) ? 'checked' : '' }}
                                                        onchange="updateAmenities('category[]={{ $category->categoryContent->slug }}',this)">
                                                    <label for="checkbox_cat{{ $category->id }}"><span
                                                            class="animits-div-tab">{{ $category->categoryContent->name }}</span></label>
                                                </div>
                                                @endif
                                                @endforeach

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Amenities -->
                                <div class="widget widget-amenities mb-30">
                                    <h3 class="title">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#amenities" aria-expanded="true" aria-controls="amenities">
                                            {{ __('Amenities') }}
                                        </button>
                                    </h3>

                                    <div id="amenities" class="collapse show">
                                        <div class="accordion-body">
                                            <div class="custom-checkbox new-animitis-divs">

                                                @php
                                                $selected_amenities = request()->input('amenities', []);
                                                if(!is_array($selected_amenities)) $selected_amenities =
                                                [$selected_amenities];
                                                @endphp

                                                @foreach ($amenities as $amenity)
                                                @if ($amenity->amenityContent)
                                                <div>
                                                    <input class="input-checkbox" type="checkbox" name="amenities[]"
                                                        id="checkbox_am{{ $amenity->id }}"
                                                        value="{{ $amenity->amenityContent->name }}"
                                                        {{ in_array($amenity->amenityContent->name, $selected_amenities) ? 'checked' : '' }}
                                                        onchange="updateAmenities('amenities[]={{ $amenity->amenityContent->name }}',this)">
                                                    <label for="checkbox_am{{ $amenity->id }}"><span
                                                            class="animits-div-tab">{{ $amenity->amenityContent->name }}</span></label>
                                                </div>
                                                @endif
                                                @endforeach

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pricing Filter -->
                                <div class="widget widget-price mb-30">
                                    <h3 class="title">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#price" aria-expanded="true" aria-controls="price">
                                            {{ __('Pricing Filter') }}
                                        </button>
                                    </h3>

                                    <input type="hidden" name="min" id="min" value="{{ request('min', $min) }}">
                                    <input type="hidden" name="max" id="max" value="{{ request('max', $max) }}">
                                    <input type="hidden" id="o_min" value="{{ $min }}">
                                    <input type="hidden" id="o_max" value="{{ $max }}">
                                    <input type="hidden" id="currency_symbol"
                                        value="{{ $basicInfo->base_currency_symbol }}">

                                    <div id="price" class="collapse show">
                                        <div class="accordion-body">
                                            <div class="price-item">

                                                <div data-range-slider="priceSlider" data-range-min="{{ $min }}"
                                                    data-range-max="{{ $max }}"
                                                    data-start-min="{{ request('min', $min) }}"
                                                    data-start-max="{{ request('max', $max) }}">
                                                </div>

                                                <div class="price-value">
                                                    <span style="color:#6c603c;">
                                                        {{ __('Price :') }}
                                                        <span data-range-value="priceSliderValue">
                                                            {{ symbolPrice($min) }} -
                                                            {{ symbolPrice($max) }}
                                                        </span>
                                                    </span>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reset Button -->
                                <div class="cta">
                                    <button onclick="resetURL()" type="button" class="btn-text icon-start mt-10"
                                        style="color:white;">
                                        <i class="fal fa-redo"></i>{{ __('Reset Search') }}
                                    </button>
                                </div>

                            </form>
                        </aside>
                    </div>
                </div>

            </div>
            <!-- SIDEBAR LEFT END -->
            <div class="col-xl-12">

                <div class="filter-top-row d-flex align-items-center d-none d-md-flex  gap-3">

                    <input type="text" class="form-control filter-input" name="title"
                        placeholder="Enter Properties Name" value="{{ request()->input('title') }}"
                        style="box-shadow:none;">

                    <select name="area_id" class="form-control form-select area_id" style="box-shadow:none; line-height:45px;"
                        onchange="updateURL('listArea='+$(this).val())">
                        <option>{{ __('Select Area') }}</option>
                        @foreach ($all_areas as $area)
                        <option value="{{ $area->name }}">{{ $area->name }}</option>
                        @endforeach
                    </select>

                    <select class="form-select sort-select" name="sort" onchange="updateURL('sort='+$(this).val())">
                        <option value="new" {{ request('sort')=='new' ? 'selected' : '' }}>Newest</option>
                        <option value="old" {{ request('sort')=='old' ? 'selected' : '' }}>Oldest</option>
                        <option value="low-to-high" {{ request('sort')=='low-to-high' ? 'selected' : '' }}>Price: Low to
                            High</option>
                        <option value="high-to-low" {{ request('sort')=='high-to-low' ? 'selected' : '' }}>Price: High
                            to Low</option>
                    </select>

                    <button type="button" class="filter-btn" data-bs-toggle="offcanvas"
                        data-bs-target="#widgetOffcanvas">
                        <i class="fa-solid fa-sliders filt-ico" ></i>
                    </button>

                </div>


                <div class="product-sort-area mb-10" data-aos="fade-up">
                    <div class="row justify-content-sm-end">

                        <div class="col-sm-5 d-md-none">
                            <button class="btn btn-sm btn-outline icon-end radius-sm mb-15" type="button"
                                data-bs-toggle="offcanvas" data-bs-target="#widgetOffcanvas">
                                {{ __('Filter') }} <i class="fal fa-filter"></i>
                            </button>
                        </div>

                        <div class="col-sm-7">
                            <ul class="product-sort-list text-sm-end list-unstyled mb-15 d-block d-md-none">
                                <li class="item">
                                    <div class="sort-item d-flex align-items-center">
                                        <label class="color-dark me-2 font-sm flex-auto">{{ __('Sort By') }} :</label>

                                        <select class="form-select form_control" name="sort"
                                            onchange="updateURL('sort='+$(this).val())">
                                            <option value="new" {{ request('sort')=='new' ? 'selected' : '' }}>Newest
                                            </option>
                                            <option value="old" {{ request('sort')=='old' ? 'selected' : '' }}>Oldest
                                            </option>
                                            <option value="low-to-high"
                                                {{ request('sort')=='low-to-high' ? 'selected' : '' }}>Price : Low to
                                                High</option>
                                            <option value="high-to-low"
                                                {{ request('sort')=='high-to-low' ? 'selected' : '' }}>Price : High to
                                                Low</option>
                                        </select>

                                    </div>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>

                <!-- PROPERTIES LIST -->
                <div class="row properties">

                    @forelse ($property_contents as $property_content)
                    <x-property :property="$property_content" :animate="false" class="col-lg-3 col-md-6 mt-4" />
                    @empty
                    <div class="col-lg-12">
                        <h3 class="text-center mt-5">{{ __('NO PROPERTY FOUND') }}!</h3>
                    </div>
                    @endforelse

                    <div class="col-lg-12 pagination justify-content-center customPaginagte">
                        {{ $property_contents->links() }}
                    </div>

                </div>

            </div>
            <!-- RIGHT SIDE END -->

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