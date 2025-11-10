<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Property Banner</title>

    {{-- Bootstrap 5 CSS (optional if already included in layout) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/assets/front/css/vendors/nouislider.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/front/css/vendors/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/front/css/vendors/select2.min.css') }}">


    <style>
    body {
        margin: 0;
        background: #f9f9f9;
        font-family: 'Open Sans', system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
    }

    .home-banner {
        position: relative;
        overflow: visible;
        padding: 12px 0 24px;
    }

    .banner-filter-form {
        margin: 0 auto;
        width: min(1200px, 95%);
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, .08);
        padding: 16px;
    }

    .tabs-wrapper {
        border-bottom: 1px solid #f4f5f7;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .nav-tabs {
        border: 0;
        gap: 6px;
    }

    .nav-tabs .nav-link {
        border: 0;
        color: #6c603c;
        padding: 10px 12px;
    }

    .nav-tabs .nav-link.active {
        border-bottom: 2px solid #6c603c;
        font-weight: 600;
        color: #6c603c;
        background: transparent;
    }

    .style__postContainerTab {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border-radius: 8px;
        background: #6c603c;
        color: #fff;
        text-decoration: none;
        border: none;
    }

    .style__postContainerTab:hover {
        background: #5a5231;
        color: #fff;
    }

    .form-wrapper .grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 12px;
    }

    .grid-item.home-des-border {
        /* border: 1px solid #e5e5e5; */
        border-radius: 8px;
        /* padding: 12px; */
        /* background: #fff; */
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .form-group label {
        font-weight: 600;
        color: #6c603c;
        font-size: 14px;
    }

    /* Price */
    .price-value {
        font-weight: 600;
        color: #6c603c;
        font-size: 14px;
        display: inline-block;
        margin-bottom: 8px;
    }

    .noUi-connect {
        background-color: #6c603c;
    }

    [data-range-slider] {
        position: relative;
        width: 100%;
        height: 5px;
        /* margin-top: 10px; */
    }

    [data-range-slider] .slider-track-base {
        position: absolute;
        left: 0;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        height: 3px;
        background: #bfb8a2;
        border-radius: 2px;
    }

    [data-range-slider] .slider-active {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        height: 3px;
        background: #6c603c;
        border-radius: 2px;
    }

    [data-range-slider] input[type="range"] {
        position: absolute;
        left: 0;
        bottom: 0;
        width: 100%;
        pointer-events: none;
        -webkit-appearance: none;
        background: transparent;
        border: none;
    }

    [data-range-slider] input[type="range"]::-webkit-slider-runnable-track {
        height: 3px;
        background: transparent;
    }

    [data-range-slider] input[type="range"]::-webkit-slider-thumb {
        pointer-events: all;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #6c603c;
        cursor: pointer;
        -webkit-appearance: none;
        margin-top: -5px;
        transition: all 0.2s ease;
    }

    [data-range-slider] input[type="range"]::-webkit-slider-thumb:hover {
        background: #6c603c;
        border-color: #6c603c;
    }

    [data-range-slider] input[type="range"]::-moz-range-thumb {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #6c603c;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    [data-range-slider] input[type="range"]::-moz-range-thumb:hover {
        background: #6c603c;
        border-color: #6c603c;
    }

    .new-search-btn {
        background-color: #6c603c !important;
        border-color: #6c603c !important;
        border-radius: 8px;
        padding: 10px 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .new-icons-search {
        width: 18px;
        height: 18px;
        display: block;
    }

    @media (max-width: 1199px) {
        .form-wrapper .grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 767px) {
        .tabs-wrapper {
            flex-wrap: wrap;
            gap: 8px;
        }

        .post-property-btn {
            width: 100%;
            display: flex;
            justify-content: flex-end;
        }

        .form-wrapper .grid {
            grid-template-columns: 1fr;
        }

        .banner-filter-form {
            width: min(100%, 100%);
        }
    }

    .select2-container {
        width: 100% !important;
        height: 100% !important;
    }

    .banner-close-btn {
        position: absolute;
        top: 16px;
        right: 3px;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #fff;
        border: 1px solid #ccc;
        color: #333;
        font-size: 30px;
        line-height: 20px;
        text-align: center;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease;
        z-index: 10;
        padding-top: 0;
    }

    .banner-close-btn:hover {
        background: #6c603c;
        color: #fff;
        border-color: #6c603c;
    }

    .noUi-horizontal .noUi-handle {
        width: 16px;
        height: 16px;
        right: -8px;
        top: -6px;
        border-radius: 50%;
    }

    .noUi-handle {
        background: transparent;
        border: 3px solid #6c603c;
        border-radius: 50%;
        cursor: pointer;
        box-shadow: none;
        transition: all 0.2s ease;
    }

    .noUi-handle:before,
    .noUi-handle:after {
        display: none !important;
    }

    .noUi-handle:hover {
        background: #6c603c;
        border-color: #6c603c;
    }
    </style>
</head>

<body>

    @php
    $tabs = [
    'buy' => 'Buy',
    'rent' => 'Rent',
    'lease' => 'Lease',
    ];

    $user = Auth::guard('web')->user();
    $vendor = Auth::guard('vendor')->user();
    $agent = Auth::guard('agent')->user();

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

    $initial = $authUser ? strtoupper(substr($authUser->username ?? 'U', 0, 1)) : null;

    if ($authType === 'vendor' && $vendor->email) {
    $postPropertyRoute = route('vendor.property_management.type');
    } elseif ($authType === 'user' && $user?->email) {
    $postPropertyRoute = route('user.property_management.type');
    } elseif ($authType === 'agent' && $agent?->email) {
    $postPropertyRoute = route('agent.property_management.type');
    } else {
    $postPropertyRoute = route('user.signup');
    }
    @endphp

    <section class="home-banner">
        <div class="banner-filter-form new-banner-filters-width" data-aos="fade-up">
            <button type="button" class="banner-close-btn" aria-label="Close">&times;</button>

            <div class="tab-content form-wrapper">

                {{-- Tabs header --}}
                <div class="tabs-wrapper">
                    <ul class="nav nav-tabs mb-0" role="tablist">
                        @foreach($tabs as $key => $label)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab"
                                data-bs-target="#{{ $key }}" type="button" role="tab" aria-controls="{{ $key }}"
                                aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                {{ __($label) }}
                            </button>
                        </li>
                        @endforeach
                    </ul>

                    {{-- Post Property --}}
                    <div class="post-property-btn">
                        {{-- @if ($authType === 'guest')
            <button type="button"
                    class="style__postContainerTab"
                    data-bs-toggle="modal"
                    data-bs-target="#customerPhoneModal"
                    data-action="post_property">
              <span class="style__postTab">{{ __('Post Property') }}</span>
                        </button>
                        @else
                        <a class="style__postContainerTab" href="{{ $postPropertyRoute }}">
                            <span class="style__postTab">{{ __('Post Property') }}</span>
                        </a>
                        @endif --}}
                    </div>
                </div>

                {{-- Common hidden (currency + global min/max) --}}
                <input type="hidden" id="currency_symbol" value="{{ $basicInfo->base_currency_symbol }}">
                <input type="hidden" id="o_min" value="{{ $min }}">
                <input type="hidden" id="o_max" value="{{ $max }}">

                {{-- Tab panes --}}
                @foreach($tabs as $key => $label)
                <div class="tab-pane fade mt-3 {{ $loop->first ? 'show active' : '' }}" id="{{ $key }}" role="tabpanel">
                    <form action="{{ route('frontend.properties') }}" method="get" id="{{ $key }}Form">
                        <input type="hidden" name="purpose" value="{{ strtolower($label) }}">
                        <input type="hidden" name="min" id="min_{{ $key }}" value="{{ $min }}">
                        <input type="hidden" name="max" id="max_{{ $key }}" value="{{ $max }}">

                        <div class="grid">
                            {{-- Location --}}
                            <div class="grid-item home-des-border">
                                <div class="form-group">
                                    <label for="search_{{ $key }}">{{ __('Location') }}</label>
                                    <input type="text" id="search_{{ $key }}" name="location"
                                        class="form-control searchBar" placeholder="{{ __('Enter Location') }}"
                                        style="box-shadow:none;">
                                </div>
                            </div>

                            {{-- City --}}
                            <div class="grid-item home-des-border">
                                <div class="form-group">
                                    <label for="city_{{ $key }}" class="icon-end">{{ __('City') }}</label>
                                    <select name="city" id="city_{{ $key }}" class="form-control select2">
                                        <option value="">{{ __('Select City') }}</option>
                                        @foreach ($cities as $city)
                                        <option value="{{ $city->name }}">{{ $city->name }}</option>
                                        @endforeach
                                    </select>
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
                                <label class="price-value">
                                    {{ __('Price') }}:<br>
                                    <span data-range-value="filterPriceSlider_{{ $key }}_value">
                                        {{ symbolPrice($min) }} - {{ symbolPrice($max) }}
                                    </span>
                                </label>
                                <div data-range-slider="filterPriceSlider_{{ $key }}"></div>
                            </div>

                            {{-- Submit --}}
                            <div class="d-flex justify-content-end align-items-end">
                                <button type="submit" class="btn btn-primary new-search-btn">
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
    </section>

    {{-- Bootstrap 5 JS (optional if already included in layout) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Jquery JS -->
    <script src="{{ asset('/assets/front/js/vendors/jquery.min.js') }}"></script>
    <script src="{{ asset('/assets/front/js/vendors/datatables.min.js') }}"></script>
    <script src="{{ asset('/assets/front/js/vendors/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('/assets/front/js/vendors/select2.min.js') }}"></script>
    <script src="{{ asset('/assets/front/js/vendors/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('/assets/front/js/vendors/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('/assets/front/js/vendors/jquery.waypoints.js') }}"></script>
    <script src="{{ asset('/assets/front/js/vendors/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('/assets/front/js/vendors/lazysizes.min.js') }}"></script>
    <script src="{{ asset('/assets/front/js/vendors/nouislider.min.js') }}"></script>
    <script src="{{ asset('/assets/front/js/vendors/aos.min.js') }}"></script>
    <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
    <script src="{{ asset('/assets/front/js/vendors/masonry.pkgd.js') }}"></script>
    <script src="{{ asset('/assets/front/js/vendors/imagesloaded.pkgd.js') }}"></script>
    <script src="{{ asset('/assets/js/floating-whatsapp.js') }}"></script>
    <script src="{{ asset('/assets/front/js/script.js') }}"></script>

    <script src="{{ asset('assets/js/jquery-syotimer.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <script>
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
    document.querySelector('.banner-close-btn').addEventListener('click', function() {
        window.history.back();
    });
    </script>


</body>

</html>