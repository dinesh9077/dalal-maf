@php
$version = $basicInfo->theme_version;
@endphp
@extends("frontend.layouts.layout-v$version")

@section('pageHeading')
{{ $propertyContent->title }}
@endsection

@section('metaKeywords')
@if ($propertyContent)
{{ $propertyContent->meta_keyword }}
@endif
@endsection

@section('metaDescription')
@if ($propertyContent)
{{ $propertyContent->meta_description }}
@endif
@endsection


@section('og:tag')
<meta property="og:title" content="{{ $propertyContent->title }}">
<meta property="og:image" content="{{ asset('assets/img/property/featureds/' . $propertyContent->featured_image) }}">
<meta property="og:url" content="{{ route('frontend.property.details', $propertyContent->slug) }}">
@endsection

<style>
.new-main-navbar {
    background-color: #6c603c;
}

.new-hover {
    box-shadow: none;
}

.new-hover:hover {
    box-shadow: none !important;
}

.hover-btn {
    background: #6c603c;
    color: white;
    border: none;
    padding: 10px 18px;
    border-radius: 6px;
    cursor: pointer;
    margin: 10px 0;
    width: fit-content;
}

.modal-content {
    box-shadow: 0px 6px 20px rgba(0, 0, 0, 0.2);
    background: #e7e3d1;
    border-radius: 12px;
    width: 44%;
    margin: auto;
    position: relative;
}

.custom-close-btn {
    position: absolute;
    top: -11px;
    right: -10px;
    padding: 5px;
    background: #ffffff;
    color: #000;
    border: none;
    border-radius: 50%;
    width: 28px;
    height: 28px;
    font-size: 16px;
    font-weight: bold;
    line-height: 1;
    cursor: pointer;
    transition: background 0.3s ease;
    z-index: 10;
}

.custom-close-btn:hover {
    background: #ddd;
}

.hover-btn {
    background: #6c603c;
    color: #fff;
    border: none;
    padding: 10px 18px;
    border-radius: 6px;
    cursor: pointer;
    margin-top: 10px;
}

.model-width {
    background: #e7e3d1 !important;
    border-radius: 12px !important;
    width: 44% !important;
    margin: auto !important;
    position: relative !important;
}
@media(min-width:320px) and (max-width:576px){
    .model-width{
        width:100% !important;
    }
}
@media(min-width:567px) and (max-width:768px){
    .model-width{
        width:70% !important;
    }
}
</style>

@section('content')
<div class="product-single header-next" style="    margin-top: 100px;">

    <a href="https://wa.me/9925133440" target="_blank">
        <div class="whatsapp-btn" data-aos="fade-up">
            <img src="{{ asset('assets/front/images/new-images/whatsapp.png') }}" alt="WhatsApp">
        </div>
    </a>


    <div class="container">
        <div class="row gx-xl-5" data-aos="fade-up">

            <div>
                <div class="mb-5">
                    <div class="row g-3 new-pro-images-grid">
                        <div class="col-md-8">
                            <img src="{{ asset('assets/img/property/featureds/' . $propertyContent->featured_image) }}"
                                data-img="{{ asset('assets/img/property/featureds/' . $propertyContent->featured_image) }}">
                        </div>
                        <div class="col-md-4 d-grid gap-3">
                            <div class="row g-3">
                                @php
                                $count = $sliders->count();
                                @endphp
                                @if ($count == 2)
                                @foreach ($sliders->take(2) as $index => $slider)
                                <div class="col-12 {{ $loop->last ? 'overlay-container position-relative' : '' }}">
                                    <img src="{{ asset('assets/img/property/slider-images/' . $slider->image) }}"
                                        data-img="{{ asset('assets/img/property/slider-images/' . $slider->image) }}"
                                        alt="Slider Image {{ $index + 1 }}">
                                    @if ($loop->last)
                                    <div class="new-pro-overlay" data-bs-toggle="modal"
                                        data-bs-target="#newProImagesSliderModal">View All</div>
                                    @endif
                                </div>
                                @endforeach
                                @endif
                                @if ($count == 3)
                                @foreach ($sliders->take(3) as $index => $slider)
                                <div class="{{ $loop->last ? 'col-12 overlay-container position-relative' : 'col-6' }}">
                                    <img src="{{ asset('assets/img/property/slider-images/' . $slider->image) }}"
                                        data-img="{{ asset('assets/img/property/slider-images/' . $slider->image) }}"
                                        alt="Slider Image {{ $index + 1 }}">
                                    @if ($loop->last)
                                    <div class="new-pro-overlay" data-bs-toggle="modal"
                                        data-bs-target="#newProImagesSliderModal">View All</div>
                                    @endif
                                </div>
                                @endforeach
                                @endif
                                @if ($count >= 4)
                                @foreach ($sliders->take(4) as $index => $slider)
                                <div class="col-6 {{ $loop->last ? 'overlay-container position-relative' : '' }}">
                                    <img src="{{ asset('assets/img/property/slider-images/' . $slider->image) }}"
                                        data-img="{{ asset('assets/img/property/slider-images/' . $slider->image) }}"
                                        alt="Slider Image {{ $index + 1 }}">
                                    @if ($loop->last)
                                    <div class="new-pro-overlay" data-bs-toggle="modal"
                                        data-bs-target="#newProImagesSliderModal">View All</div>
                                    @endif
                                </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Slider Popup Modal -->
                <div class="modal fade" id="newProImagesSliderModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl">
                        <div class="modal-content bg-dark">
                            <div id="newProImagesCarousel" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner" style="object-fit: cover; height : 500px;">
                                    @foreach ($sliders as $index => $slider)
                                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                        <img src="{{ asset('assets/img/property/slider-images/' . $slider->image) }}"
                                            class="d-block w-100 rounded" alt="Slider Image {{ $index + 1 }}">
                                    </div>
                                    @endforeach
                                </div>
                                <!-- Controls -->
                                <button class="carousel-control-prev" type="button"
                                    data-bs-target="#newProImagesCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                </button>
                                <button class="carousel-control-next" type="button"
                                    data-bs-target="#newProImagesCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

            </div>


            <!-- <div class="col-12">
                     <div class="product-single-gallery mb-40">

                        <div class="slider-navigation">
                            <button type="button" title="Slide prev" class="slider-btn slider-btn-prev">
                                <i class="fal fa-angle-left"></i>
                            </button>
                            <button type="button" title="Slide next" class="slider-btn slider-btn-next">
                                <i class="fal fa-angle-right"></i>
                            </button>
                        </div>
                        <div class="swiper product-single-slider">
                            <div class="swiper-wrapper">
                                @foreach ($sliders as $slider)
    <div class="swiper-slide">
                                    <figure class="radius-lg lazy-container ratio ratio-16-11">
                                        <a href="{{ asset('assets/img/property/slider-images/' . $slider->image) }}"
                                            class="lightbox-single">
                                            <img class="lazyload" src="assets/images/placeholder.png"
                                                data-src="{{ asset('assets/img/property/slider-images/' . $slider->image) }}">
                                        </a>
                                    </figure>
                                </div>
    @endforeach

                            </div>
                        </div>

                        <div class="swiper slider-thumbnails">
                            <div class="swiper-wrapper">
                                @foreach ($sliders as $slider)
    <div class="swiper-slide">
                                    <div class="thumbnail-img lazy-container radius-md ratio ratio-16-11">
                                        <img class="lazyload" src="assets/images/placeholder.png"
                                            data-src="{{ asset('assets/img/property/slider-images/' . $slider->image) }}">
                                    </div>
                                </div>
    @endforeach
                            </div>
                        </div>
                    </div>
                </div> -->
            <div class="col-12 product-single-details">
                <div class="new-property-details">
                    <div class="new-pp-s-des">
                        <div class="new-photo-div">
                            <div class="user-img">
                                <div class=" new-images-lazy ratio ratio-1-1 rounded-pill">
                                    <img class="lazyload" src="{{ asset('assets/img/blank-user.jpg') }}" data-src="@if (!empty($agent)) {{ $agent->image ? asset('assets/img/agents/' . $agent->image) : asset('assets/img/blank-user.jpg') }}
                                            @elseif(!empty($vendor))
                                                {{ $vendor->photo ? asset('assets/admin/img/vendor-photo/' . $vendor->photo) : asset('assets/img/blank-user.jpg') }}
                                                @else
                                                 {{ asset('assets/img/admins/' . $admin->image) }} @endif">
                                </div>
                            </div>
                        </div>
                        <div style="padding: 3px 0px;">
                            <h3 class="product-title d-flex gap-3" style="margin-bottom:0px;">
                                <a
                                    href="{{ route('frontend.properties', ['category' => $propertyContent->categoryContent?->slug]) }}">{{ $propertyContent->title }}
                                    <span class="product-category new-pdc-s">
                                        ({{ $propertyContent->categoryContent?->name }})</span> </a>
                                <ul class="share-link list-unstyled">
                                    <li>
                                        <a class="btn l-s-btn" href="#" data-bs-toggle="modal"
                                            data-bs-target="#socialMediaModal">
                                            <i class="fal fa-share-alt"></i>
                                        </a>
                                    </li>

                                    <li>
                                        @if (Auth::guard('web')->check())
                                        @php
                                        $user_id = Auth::guard('web')->user()->id;
                                        $checkWishList = checkWishList(
                                        $propertyContent->propertyId,
                                        $user_id,
                                        );
                                        @endphp
                                        @elseif(Auth::guard('vendor')->check())
                                        @php
                                        $user_id = Auth::guard('vendor')->user()->id;
                                        $checkWishList = checkWishList(
                                        $propertyContent->propertyId,
                                        $user_id,
                                        'vendor',
                                        );
                                        @endphp

                                        @elseif(Auth::guard('agent')->check())
                                        @php
                                        $user_id = Auth::guard('agent')->user()->id;
                                        $checkWishList = checkWishList(
                                        $propertyContent->propertyId,
                                        $user_id,
                                        'agent',
                                        );
                                        @endphp
                                        @else
                                        @php
                                        $checkWishList = false;
                                        @endphp
                                        @endif
                                        @if (!Auth::guard('vendor')->check() && !Auth::guard('web')->check() &&
                                        !Auth::guard('agent')->check())
                                        <a type="button" class="btn l-s-btn " data-bs-toggle="modal"
                                            data-bs-target="#customerPhoneModal" data-action="login">
                                            <i class="fal fa-heart"></i>
                                        </a>
                                        @else
                                        <a href="javascript:void(0);"
                                            class="btn-wishlist {{ $checkWishList ? 'wishlist-active' : '' }}"
                                            data-id="{{ $propertyContent->propertyId }}"
                                            data-action="{{ $checkWishList ? 'remove' : 'add' }}"
                                            data-add-url="{{ route('addto.wishlist', $propertyContent->propertyId) }}"
                                            data-remove-url="{{ route('remove.wishlist', $propertyContent->propertyId) }}"
                                            data-url="{{ $checkWishList ? route('remove.wishlist', $propertyContent->propertyId) : route('addto.wishlist', $propertyContent->propertyId) }}"
                                            title="{{ $checkWishList ? __('Saved') : __('Add to Wishlist') }}">
                                            <i class="fal fa-heart"></i>
                                        </a>
                                        @endif
                                    </li>
                                </ul>
                            </h3>

                            <!-- <a @if (!empty($agent)) href="{{ route('frontend.agent.details', ['username' => $agent->username]) }}">
                                @elseif(!empty($vendor))
                                href="{{ route('frontend.vendor.details', ['username' => $vendor->username]) }}">
                                @else
                                href="{{ route('frontend.vendor.details', ['username' => $admin->username, 'admin' => 'true']) }}"> @endif -->
                            <div>
                                <div class="user-info">
                                    <h5 class="m-0">
                                        <span style="color: #585858; font-weight : 500;">By</span>
                                        @if (!empty($agent))
                                        {{ $agent->agent_info?->first_name . ' ' . $agent->agent_info?->last_name }}
                                        @elseif(!empty($vendor))
                                        {{ $vendor->vendor_info?->name }}
                                        @else
                                        {{ $admin->first_name . ' ' . $admin->last_name }}
                                        @endif
                                    </h5>

                                </div>
                            </div>
                            <!-- </a> -->


                            <div class="product-location icon-start">
                                <i class="fal fa-map-marker-alt"></i>
                                <span style="color: black;">
                                    {{ $propertyContent->address }}
                                </span>
                                {{-- <span style="color: black;">
                                        {{ $propertyContent->property->city?->getContent($propertyContent->language_id)?->name }}
                                {{ $propertyContent->property->isStateActive ? ', ' . $propertyContent->property->state?->getContent($propertyContent->language_id)?->name : '' }}
                                {{ $propertyContent->property->isCountryActive ? ', ' . $propertyContent->property->country?->getContent($propertyContent->language_id)?->name : '' }}
                                </span> --}}
                            </div>
                            <!-- <ul class="product-info p-0 list-unstyled d-flex align-items-center mt-10">
                                    <li class="icon-start" data-tooltip="tooltip" data-bs-placement="top"
                                        title="{{ __('Area') }}">
                                        <i class="fal fa-vector-square"></i>
                                        <span>{{ $propertyContent->area }} {{ __('Sqft') }}</span>
                                    </li>
                                    @if ($propertyContent->type == 'residential')
                                    <li class="icon-start" data-tooltip="tooltip" data-bs-placement="top"
                                        title="{{ __('Beds') }}">
                                        <i class="fal fa-bed"></i>
                                        <span>{{ $propertyContent->beds }} {{ __('Beds') }}</span>
                                    </li>
                                    <li class="icon-start" data-tooltip="tooltip" data-bs-placement="top"
                                        title="{{ __('Baths') }}">
                                        <i class="fal fa-bath"></i>
                                        <span>{{ $propertyContent->bath }} {{ __('Baths') }}</span>
                                    </li>
                                    @endif
                                </ul> -->
                            <button type="button" class="hover-btn mt-3" data-bs-toggle="modal"
                                data-bs-target="#contactSellerModal">
                                Contact Seller
                            </button>
                            <div class="modal fade" id="contactSellerModal" tabindex="-1"
                                aria-labelledby="contactSellerModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content model-width">
                                        <button type="button" class="custom-close-btn" data-bs-dismiss="modal"
                                            aria-label="Close">✕</button>

                                        <div class="modal-body" style="padding: 25px;">

                                            <div class="right-new-user-details">
                                                <div>
                                                    <div class="image-with-border mx-auto" style="width:73%;">
                                                        <img src="{{ asset('assets/img/image1.png') }}" alt="Image"
                                                            class="img-fluid">
                                                    </div>
                                                    <div class="form-image-right vector-building">
                                                        <img src="{{ asset('assets/img/vector-building (2).png') }}"
                                                            alt="Building Image" class="building-img">
                                                    </div>
                                                </div>

                                                <h1 class="rigt-calss-cs mt-3 mb-0">Contact Seller</h1>
                                                <p class="right-em-ph mt-1">Your Dream Home Is Just a Call Away!</p>

                                                <div class="contact-user text-start mt-3">
                                                    <p class="d-flex align-items-center mb-0 right-em-ph">
                                                        <img src="{{ asset('assets/img/home (3).png') }}"
                                                            alt="Home Icon" style="margin-right:6px;">
                                                        Instantly reach genuine sellers & builders
                                                    </p>
                                                    <p class="d-flex align-items-center mb-0 right-em-ph">
                                                        <img src="{{ asset('assets/img/verified (2).png') }}"
                                                            alt="Verify Icon" style="margin-right:6px;">
                                                        Verify property info directly from sellers
                                                    </p>
                                                    <p class="d-flex align-items-center mb-0 right-em-ph">
                                                        <img src="{{ asset('assets/img/discount.png') }}"
                                                            alt="Offer Icon" style="margin-right:6px;">
                                                        Get exclusive offers before others do
                                                    </p>
                                                    <p class="d-flex align-items-center mb-0 right-em-ph">
                                                        <img src="{{ asset('assets/img/question (2).png') }}"
                                                            alt="Chat Icon" style="margin-right:6px;">
                                                        Your questions answered in minutes
                                                    </p>
                                                </div>
                                            </div>

                                            @php
                                            $authUser = Auth::guard('vendor')->user()
                                            ?? Auth::guard('agent')->user()
                                            ?? Auth::guard('web')->user();
                                            @endphp


                                            <form action="{{ route('property_contact') }}" method="POST" class="mt-4">
                                                @csrf
                                                @if (!empty($agent))
                                                <input type="hidden" name="vendor_id" value="{{ $agent->vendor_id }}">
                                                <input type="hidden" name="agent_id" value="{{ $agent->id ?? '' }}">
                                                @elseif(!empty($vendor) && empty($agent))
                                                <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">
                                                @else
                                                <input type="hidden" name="vendor_id" value="0">
                                                @endif

                                                <input type="hidden" name="property_id"
                                                    value="{{ $propertyContent->propertyId }}">

                                                <div class="form-group mb-15">
                                                    <input type="text"
                                                        class="form-control new-right-form-control fc-new" name="name"
                                                        placeholder="{{ __('Name') }}*" required
                                                        value="{{ $authUser->username ?? '' }}" style="height:36px;">
                                                    @error('name') <p class="text-danger">{{ $message }}</p> @enderror
                                                </div>

                                                <div class="form-group mb-15">
                                                    <input type="email"
                                                        class="form-control new-right-form-control fc-new" name="email"
                                                        placeholder="{{ __('Email Address') }}*" required
                                                        value="{{ $authUser->email ?? '' }}" style="height:36px;">
                                                    @error('email') <p class="text-danger">{{ $message }}</p> @enderror
                                                </div>

                                                <div class="form-group mb-15">
                                                    <input type="number"
                                                        class="form-control new-right-form-control fc-new" name="phone"
                                                        placeholder="{{ __('Phone Number') }}*" required
                                                        value="{{ $authUser->phone ?? '' }}" style="height:36px;">
                                                    @error('phone') <p class="text-danger">{{ $message }}</p> @enderror
                                                </div>

                                                <div class="form-group mb-15">
                                                    <textarea name="message" id="message"
                                                        class="form-control new-right-form-control fc-new"
                                                        placeholder="{{ __('Description') }}..." required
                                                        style="min-height:100px !important;">{{ old('message') }}</textarea>
                                                    @error('message') <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                @if ($info->google_recaptcha_status == 1)
                                                <div class="form-group mb-30">
                                                    {!! NoCaptcha::renderJs() !!}
                                                    {!! NoCaptcha::display() !!}
                                                    @error('g-recaptcha-response') <p class="mt-1 text-danger">
                                                        {{ $message }}</p>
                                                    @enderror
                                                </div>
                                                @endif

                                                @if (!Auth::guard('vendor')->check() && !Auth::guard('web')->check())
                                                <button type="button" class="btn btn-md w-100"
                                                    style="background:#6c603c; color:white;" data-bs-toggle="modal"
                                                    data-bs-target="#customerPhoneModal" data-action="login">
                                                    {{ __('Send inquiry') }}
                                                </button>
                                                @else
                                                <button type="submit" class="btn btn-md w-100"
                                                    style="background:#6c603c; color:white;">
                                                    {{ __('Send inquiry') }}
                                                </button>
                                                @endif
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="product-price mb-10">
                            <span class="new-price">{{ __('Price:') }}
                                {{ $propertyContent->price ? symbolPrice($propertyContent->price) : __('Negotiable') }}</span>
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </div>

    <div class="new-property-details-down">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-xl-9 mt-3">

                    <div class="product-single-details" data-aos="fade-up">


                        <div class="new-details-bg-white">

                            <div class="col-12">
                                <h3 class="mb-40 new-title-pps"> {{ __('Property Overview ') }}</h3>
                            </div>

                            <div class="pro-over-main">
                                <div class="pro-over-detail-box">
                                    <strong class="pro-over-title">Purpose</strong>
                                    <span
                                        class="new-future-tit-ans">{{ ucwords(str_replace('_', ' ', $propertyContent->purpose)) }}
                                    </span>
                                </div>
                                <div class="pro-over-detail-box">
                                    <strong class="pro-over-title">Category</strong>
                                    <span
                                        class="new-future-tit-ans">{{ $propertyContent->categoryContent?->name }}</span>
                                </div>
                                @if ($propertyContent->property->country)
                                <div class="pro-over-detail-box">
                                    <strong class="pro-over-title">Country</strong>
                                    <span
                                        class="new-future-tit-ans">{{ $propertyContent->property->country?->getContent($propertyContent->language_id)?->name }}</span>
                                </div>
                                @endif
                                @if ($propertyContent->property->state)
                                <div class="pro-over-detail-box">
                                    <strong class="pro-over-title">State</strong>
                                    <span
                                        class="new-future-tit-ans">{{ $propertyContent->property->state?->getContent($propertyContent->language_id)?->name }}</span>
                                </div>
                                @endif
                                @if ($propertyContent->property->city)
                                <div class="pro-over-detail-box">
                                    <strong class="pro-over-title">City</strong>
                                    <span
                                        class="new-future-tit-ans">{{ $propertyContent->property->city?->getContent($propertyContent->language_id)?->name }}</span>
                                </div>
                                @endif
                                @if ($propertyContent->property->areaContent)
                                <div class="pro-over-detail-box">
                                    <strong class="pro-over-title">Area</strong>
                                    <span
                                        class="new-future-tit-ans">{{ $propertyContent->property->areaContent?->name }}</span>
                                </div>
                                @endif
                                <div class="pro-over-detail-box">
                                    <strong class="pro-over-title">Area (sqft)</strong>
                                    <span class="new-future-tit-ans">{{ $propertyContent->area }}</span>
                                </div>
                                @if ($propertyContent->beds)
                                <div class="pro-over-detail-box">
                                    <strong class="pro-over-title">Beds</strong>
                                    <span class="new-future-tit-ans">{{ $propertyContent->beds }}</span>
                                </div>
                                @endif
                                @if ($propertyContent->bath)
                                <div class="pro-over-detail-box">
                                    <strong class="pro-over-title">Baths</strong>
                                    <span class="new-future-tit-ans">{{ $propertyContent->bath }}</span>
                                </div>
                                @endif
                                @if ($propertyContent->units)
                                <div class="pro-over-detail-box">
                                    <strong class="pro-over-title">Unit Type</strong>
                                    @foreach ($propertyContent->units as $unit)
                                    <span class="new-future-tit-ans">{{ $unit }}</span>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>



                        <div class="new-details-bg-white mt-5">
                            @if (count($propertyContent->propertySpacifications) > 0)
                            <div class="row" class="mb-20">
                                <div class="col-12">
                                    <h3 class="mb-40 new-title-pps"> {{ __('Additional Features ') }}</h3>
                                </div>

                                @foreach ($propertyContent->propertySpacifications as $specification)
                                @php
                                $property_specification_content = App\Models\Property\SpacificationCotent::where(
                                [
                                ['property_spacification_id', $specification->id],
                                ['language_id', $language->id],
                                ],
                                )->first();
                                @endphp
                                <div class="col-lg-3 col-sm-6 col-md-4 mb-20">
                                    <strong
                                        class="mb-1  d-block new-future-tit">{{ $property_specification_content?->label }}</strong>
                                    <span
                                        class="new-future-tit-ans">{{ $property_specification_content?->value }}</span>
                                </div>
                                @endforeach
                            </div>
                            <div class="pb-20"></div>
                            @endif


                            <div class="product-desc">
                                <h3 class="mb-20 mt-20 new-title-pps">{{ __('Property Description') }}</h3>
                                <p class="summernote-content">{!! $propertyContent->description !!}</p>
                            </div>
                        </div>

                        @if (count($amenities) > 0)
                        <div class="new-details-bg-white mt-5">
                            <div class="product-featured ">
                                <h3 class="mb-20  new-title-pps">{{ __('Amenities') }}</h3>
                                <ul class="featured-list list-unstyled p-0 mt-20">
                                    @foreach ($amenities as $amenity)
                                    <li class="d-inline-flex flex-column align-items-center me-3" style="width: 80px;">
                                        <i class="{{ $amenity->amenity->icon }} mb-1 amenities-icon"></i>
                                        <span class="amenities-title">{{ $amenity->amenityContent?->name }}</span>
                                    </li>
                                    @endforeach

                                </ul>
                            </div>
                        </div>
                        @endif

                        @if (!empty($propertyContent->video_url))
                        <div class="new-details-bg-white mt-5">
                            <div class="product-video">
                                <h3 class="mb-20 new-title-pps"> {{ __('Video') }}</h3>
                                <div class="lazy-container radius-lg ratio ratio-16-11" style="width: 70%;">
                                    <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                                        data-src="{{ $propertyContent->video_image ? asset('assets/img/property/video/' . $propertyContent->video_image) : asset('assets/front/images/placeholder.png') }}">
                                    <a href="{{ $propertyContent->video_url }}"
                                        class="video-btn youtube-popup p-absolute">
                                        <i class="fas fa-play" style="color: black;"></i>
                                    </a>
                                </div>
                            </div>

                        </div>
                        @endif

                        @if (!empty($propertyContent->floor_planning_image))
                        <div class="new-details-bg-white mt-5">
                            <div class="product-planning mb-40">
                                <h3 class="mb-20 new-title-pps">{{ __('Floor Planning') }}</h3>
                                <div class="lazy-container radius-lg ratio ratio-16-11 border">
                                    <img class="lazyload" src="assets/images/placeholder.png"
                                        data-src="{{ asset('assets/img/property/plannings/' . $propertyContent->floor_planning_image) }}">
                                </div>
                            </div>
                        </div>
                        @endif

                        @if (!empty($propertyContent->latitude) && !empty($propertyContent->longitude))
                        <div class="new-details-bg-white mt-5">
                            <div class="product-location mb-40">
                                <h3 class="mb-20 new-title-pps">{{ __('Location') }}</h3>
                                <div class="lazy-container radius-lg ratio ratio-21-9 border">
                                    <iframe class="lazyload"
                                        src="https://maps.google.com/maps?q={{ $propertyContent->latitude }},{{ $propertyContent->longitude }}&hl={{ $currentLanguageInfo->code }}&z=14&amp;output=embed"></iframe>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-3 col-xl-3 mt-3">
                    <aside class="sidebar-widget-area mb-10" data-aos="fade-up">
                        <div class="widget widget-recent radius-md mb-30  new-widgets-color"
                            style="border: 1px solid #ced4dd; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.08);">
                            <h3 class="title">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#products" aria-expanded="true" aria-controls="products">
                                    {{ __('Similar  Property') }}
                                </button>
                            </h3>
                            <div id="products" class="collapse show">
                                <div class="accordion-body p-0">
                                    @foreach ($relatedProperty as $property)
                                    <div class="product-default product-inline mt-20 new-hover">
                                        <figure class="product-img">
                                            <a href="{{ route('frontend.property.details', $property->slug) }}"
                                                class="lazy-container ratio ratio-1-1 radius-md">
                                                <img class="lazyload" src="assets/images/placeholder.png"
                                                    data-src="{{ asset('assets/img/property/featureds/' . $property->featured_image) }}">
                                            </a>
                                        </figure>
                                        <div class="px-3">
                                            <h6 class="product-title"><a
                                                    href="{{ route('frontend.property.details', $property->slug) }}">{{ $property->title }}</a>
                                            </h6>
                                            <span class="product-location icon-start"> <i
                                                    class="fal fa-map-marker-alt"></i>
                                                {{ $property->city->getContent($property->language_id)?->name }}
                                                {{ $property->isStateActive ? ', ' . $property->state?->getContent($property->language_id)?->name : '' }}
                                                {{ $property->isCountryActive ? ', ' . $property->country?->getContent($property->language_id)?->name : '' }}</span>
                                            <div class="product-price">

                                                <span class="new-price">{{ __('Price:') }}
                                                    {{ $property->price ? symbolPrice($property->price) : __('Negotiable') }}</span>
                                            </div>
                                            <ul class="product-info p-0 list-unstyled d-flex align-items-center">
                                                <li class="icon-start" data-tooltip="tooltip" data-bs-placement="top"
                                                    title="{{ __('Area') }}">
                                                    <i class="fal fa-vector-square"></i>
                                                    <span>{{ $property->area }}</span>
                                                </li>
                                                @if ($property->type == 'residential')
                                                <li class="icon-start" data-tooltip="tooltip" data-bs-placement="top"
                                                    title="{{ __('Bed') }}">
                                                    <i class="fal fa-bed"></i>
                                                    <span>{{ $property->beds }} </span>
                                                </li>
                                                <li class="icon-start" data-tooltip="tooltip" data-bs-placement="top"
                                                    title="{{ __('Bath') }}">
                                                    <i class="fal fa-bath"></i>
                                                    <span>{{ $property->bath }} </span>
                                                </li>
                                                @endif

                                            </ul>
                                        </div>
                                    </div><!-- product-default -->
                                    @endforeach
                                </div>
                            </div>
                        </div>



                    </aside>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- share on social media modal --}}
<div class="modal fade" id="socialMediaModal" tabindex="-1" role="dialog" aria-labelledby="socialMediaModalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"> {{ __('Share On') }} </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="actions d-flex gap-3 justify-content-start">
                    <div class="action-btn">
                        <a class="facebook btn"
                            href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}&src=sdkpreparse">
                            <i class="fab fa-facebook-f"></i></a>
                        <br>
                        <span> {{ __('Facebook') }} </span>
                    </div>
                    <!-- <div class="action-btn">
                        <a href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}"
                            class="linkedin btn"><i class="fab fa-linkedin-in"></i></a>
                        <br>
                        <span> {{ __('Linkedin') }} </span>
                    </div> -->
                    <!-- <div class="action-btn">
                        <a class="twitter btn"
                            href="https://twitter.com/intent/tweet?text={{ url()->current() }}"><i
                                class="fab fa-twitter"></i></a>
                        <br>
                        <span> {{ __('Twitter') }} </span>
                    </div> -->
                    <div class="action-btn">
                        <a class="instagram btn" target="_blank"
                            href="https://www.instagram.com/{{ config('app.instagram_username') }}">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <br>
                        <span> {{ __('Instagram') }} </span>
                    </div>
                    <div class="action-btn">
                        <a class="whatsapp btn" href="whatsapp://send?text={{ url()->current() }}"><i
                                class="fab fa-whatsapp"></i></a>
                        <br>
                        <span> {{ __('Whatsapp') }} </span>
                    </div>
                    <!-- <div class="action-btn">
                        <a class="sms btn" href="sms:?body={{ url()->current() }}" class="sms"><i
                                class="fas fa-sms"></i></a>
                        <br>
                        <span> {{ __('SMS') }} </span>
                    </div> -->
                    <!-- <div class="action-btn">
                        <a class="mail btn"
                            href="mailto:?subject=Digital Card&body=Check out this digital card {{ url()->current() }}."><i
                                class="fas fa-at"></i></a>
                        <br>
                        <span> {{ __('Mail') }} </span>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection