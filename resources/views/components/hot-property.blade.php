<style>
.product-new {
    border: none !important;
    background-color: #f8f7f1;
    box-shadow: 0 10px 20px -8px rgba(var(--color-dark-rgb), 0.3);
    margin-bottom: 0 !important;
}

.product-new:hover {
    box-shadow: 0 5px 15px -5px rgba(var(--color-dark-rgb), 0.2);
}

.product-image-box {
    position: relative;
    overflow: hidden;
    border-radius: 15px;
}

.product-image-box img {
    width: 100%;
    border-radius: 15px;
    display: block;
}

.product-image-box .btn-wishlist {
    position: absolute;
    top: 10px;
    right: 10px;
    background-color: #fff;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #333;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    z-index: 10;
}

.product-image-box .btn-wishlist:hover {
    background-color: #f5f5f5;
}

.product-image-box .btn-wishlist.wishlist-active {
    color: red;
}

.product-new-div {
    position: relative;
    padding-bottom: 15px;
    margin-bottom: 0;
}

.btn-view-details-1 {
    position: absolute;
    bottom: 16px;
    right: 0px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background-color: #6c603c;
    color: #fff;
    transition: all 0.3s ease;
    text-decoration: none;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
}

.btn-view-details-1:hover {
    background-color: #e6e6e6;
    color: #000;
}

.btn-view-details-1 i {
    font-size: 14px;
}
</style>

<div {{ $attributes }} style="position: relative; margin-bottom:0; ">
    <div class="product-default product-new mb-0 " style="border-radius: 20px;"
        {{ $animation ? 'data-aos="fade-up" data-aos-delay="100"' : '' }}>

        <div class="product-details product-new " style="padding: 0px;">

            <div class="product-image-box">
                <a
                    href="{{ route('frontend.property.details', ['slug' => $property->slug ?? $property->propertyContent->slug]) }}">
                    <img class="lazyload new-images-card" src="assets/images/placeholder.png"
                        data-src="{{ asset('assets/img/property/featureds/' . $property->featured_image) }}">
                </a>

                @if (Auth::guard('web')->check())
                @php
                $user_id = Auth::guard('web')->user()->id;
                $checkWishList = checkWishList($property->id, $user_id,'user');
                @endphp
                @elseif(Auth::guard('vendor')->check())
                @php
                $user_id = Auth::guard('vendor')->user()->id;
                $checkWishList = checkWishList($property->id, $user_id,'vendor');
                @endphp
                @elseif(Auth::guard('agent')->check())
                @php
                $user_id = Auth::guard('agent')->user()->id;
                $checkWishList = checkWishList($property->id, $user_id,'agent');
                @endphp
                @else
                @php
                $checkWishList = false;
                @endphp
                @endif
                @if (!Auth::guard('vendor')->check() && !Auth::guard('web')->check() && !Auth::guard('agent')->check())
                <a type="button" class="btn-wishlist" data-bs-toggle="modal" data-bs-target="#customerPhoneModal"
                    data-action="login">
                    <i class="fal fa-heart"></i>
                </a>
                @else
                <a href="javascript:void(0);" class="btn-wishlist {{ $checkWishList ? 'wishlist-active' : '' }}"
                    data-id="{{ $property->id }}" data-action="{{ $checkWishList ? 'remove' : 'add' }}"
                    data-add-url="{{ route('addto.wishlist', $property->id) }}"
                    data-remove-url="{{ route('remove.wishlist', $property->id) }}"
                    data-url="{{ $checkWishList ? route('remove.wishlist', $property->id) : route('addto.wishlist', $property->id) }}"
                    title="{{ $checkWishList ? __('Saved') : __('Add to Wishlist') }}">
                    <i class="fal fa-heart"></i>
                </a>


                @endif
            </div>

            <div class="product-new-div">
                <h3 class="product-title">
                    <a
                        href="{{ route('frontend.property.details', $property->slug ?? $property->propertyContent->slug) }}">
                        {{ Str::limit($property->title ?? $property->propertyContent->title, 20) }}
                    </a>
                </h3>

              

                  <!-- <span class="product-location icon-start">
                    <i class="fal fa-map-marker-alt"></i>
                    {{ $property->city->getContent($property->language_id)?->name }}
                    {{ $property->isStateActive ? ', ' . $property->state?->getContent($property->language_id)?->name : '' }}
                </span>

                   <div class="product-price">
                    <span class="new-price">
                        {{ $property->price ? symbolPrice($property->price) : __('Negotiable') }}
                    </span>
                </div> -->


				<div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; margin-bottom: 5px;">


                
                <div class="product-price">
                    <span class="new-price">
                        {{ $property->price ? symbolPrice($property->price) : __('Negotiable') }}
                    </span>
                </div>

                  <span class="product-location icon-start">
                    <i class="fal fa-map-marker-alt"></i>
                    {{ $property->city->getContent($property->language_id)?->name }}
                    {{ $property->isStateActive ? ', ' . $property->state?->getContent($property->language_id)?->name : '' }}
                </span>


                </div>

                <ul class="product-info p-0 list-unstyled d-flex  align-items-center" style="gap:0px;">
                    @if (!in_array($property->purpose, ['franchiese', 'business_for_sale']))
                    <li class="icon-start new-badge-product" data-tooltip="tooltip" title="{{ __('Area') }}">
                        <i class="fal fa-vector-square new-icon-color"></i>
                        <span>{{ $property->area }} {{ __('Sqft') }}</span>
                    </li>
                    @if ($property->type == 'residential')
                    <li class="icon-start new-badge-product ms-3" data-tooltip="tooltip" title="{{ __('Beds') }}">
                        <i class="fal fa-bed new-icon-color"></i>
                        <span>{{ $property->beds }} {{ __('Beds') }}</span>
                    </li>
                    <li class="icon-start new-badge-product ms-3" data-tooltip="tooltip" title="{{ __('Baths') }}">
                        <i class="fal fa-bath new-icon-color"></i>
                        <span>{{ $property->bath }} {{ __('Baths') }}</span>
                    </li>
                    @endif
                    @else
                    <li class="icon-start new-badge-product" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="{{ __('Listing Type') }}">
                        <i class="fal fa-info new-icon-color"></i>
                        <span>{{ $property->notes ?? __('N/A') }}</span>
                    </li>
                    @endif
                </ul>

                <a href="{{ route('frontend.property.details', $property->slug ?? $property->propertyContent->slug) }}"
                    class="btn-view-details-1 ">
                    <i class="fal fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <span class="label new-lables">{{ $property->purpose === "sell" ? "Buy" : ucwords(str_replace('_', ' ', $property->purpose)) }}</span>
</div>