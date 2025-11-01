@php
$version = $basicInfo->theme_version;
@endphp
@extends("frontend.layouts.layout-v$version")

<style>
  .new-main-navbar {
    background-color: #6c603c;
  }
</style>

@section('content')

<div style="margin-top: 150px ; margin-bottom: 80px;">
  <div class="container" data-aos="fade-up">
    <div class="section-title title-inline mb-40 aos-init aos-animate d-flex justify-content-center" data-aos="fade-up">
      <h2 class="title">{{ $title }} Properties</h2>
    </div>
  </div>

  <div class="container " style="margin-top: 100px;" data-aos="fade-up">
    <div class="row">
      @foreach($property_contents as $property)
      <div class="col-lg-3">
        <div style="    border: 1px solid #dcdcdc;
    padding: 10px;
    background: white;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.09);
    border-radius: 20px;
    margin-top: 10px;">
           <div class="product-details">
          <div class="product-image-box"> <!-- wrapper div add -->
            <a href="{{ route('frontend.property.details', ['slug' => $property->slug ?? $property->propertyContent->slug]) }}">
              <img class="lazyload new-images-card" src="assets/images/placeholder.png" data-src="{{ asset('assets/img/property/featureds/' . $property->featured_image) }}">
            </a>


          </div>

          <div class="product-new-div">
            <h3 class="product-title">
              <a href="{{ route('frontend.property.details', $property->slug ?? $property->propertyContent->slug) }}">{{ Str::limit($property->title ?? $property->propertyContent->title, 25) }}</a>
            </h3>

            <span class="product-location icon-start"> <i class="fal fa-map-marker-alt"></i>
              {{ $property->city->getContent($property->language_id)?->name }}
              {{ $property->isStateActive ? ', ' . $property->state?->getContent($property->language_id)?->name : '' }}
              {{ $property->isCountryActive ? ', ' . $property->country?->getContent($property->language_id)?->name : '' }}

            </span>


            <div class="product-price">
              <span class="new-price">
                {{ $property->price ? symbolPrice($property->price) : __('Negotiable') }}</span>
            </div>


            <ul class="product-info p-0 list-unstyled d-flex align-items-center">
              <li class="icon-start new-badge-product" data-tooltip="tooltip" data-bs-placement="top"
                data-bs-original-title="Area">
                <i class="fal fa-vector-square new-icon-color"></i>
                <span>{{ $property->area }} {{ __('Sqft') }}</span>
              </li>
              <li class="icon-start new-badge-product" data-tooltip="tooltip" data-bs-placement="top"
                data-bs-original-title="Area">
                <i class="fal fa-vector-square new-icon-color"></i>
                <span>>{{ $property->beds }} {{ __('Beds') }}</span>
              </li>

              <li class="icon-start new-badge-product" data-tooltip="tooltip" data-bs-placement="top"
                data-bs-original-title="Area">
                <i class="fal fa-vector-square new-icon-color"></i>
                <span>{{ $property->bath }} {{ __('Baths') }}</span>
              </li>
            </ul>

            <div class="button-new-add mt-3">
              @if (Auth::guard('web')->check())
              @php
              $user_id = Auth::guard('web')->user()->id;
              $checkWishList = checkWishList($property->id, $user_id);
              @endphp
              @elseif(Auth::guard('vendor')->check())
              @php
              $user_id = Auth::guard('vendor')->user()->id;
              $checkWishList = checkWishList($property->id, $user_id,'vendor');
              @endphp
              @else
              @php
              $checkWishList = false;
              @endphp
              @endif
              @if (!Auth::guard('vendor')->check() && !Auth::guard('web')->check())
              <a type="button" class="btn-wishlist" data-bs-toggle="modal" data-bs-target="#customerPhoneModal" data-action="login">
                <i class="fal fa-heart"></i>
              </a>
              @else
              <a href="{{ $checkWishList == false ? route('addto.wishlist', $property->id) : route('remove.wishlist', $property->id) }}"
                class="btn-wishlist {{ $checkWishList == false ? '' : 'wishlist-active' }}" data-tooltip="tooltip"
                data-bs-placement="top" title="{{ $checkWishList == false ? __('Add to Wishlist') : __('Saved') }}">
                <i class="fal fa-heart"></i>
              </a>

              @endif

              <a href="{{ route('frontend.property.details', $property->slug ?? $property->propertyContent->slug) }}" class=""> View Details
              </a>
            </div>

          </div>
        </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</div>

<a href="https://wa.me/9925133440" target="_blank">
  <div class="whatsapp-btn" data-aos="fade-up">
    <img src="{{ asset('assets/front/images/new-images/whatsapp.png') }}" alt="WhatsApp">
  </div>
</a>


@endsection