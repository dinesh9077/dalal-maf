<style>
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


    .images-ffps {
        border-radius: 0px !important;
        width: 100% !important;
        height: 150px !important;

    }


    .new-img-p-box {
        border-radius: 0px;
        height: 150px !important;
        position: relative;
        overflow: hidden;
        border-radius: 10px;

    }

    /* TOP GRADIENT OVERLAY */
    .new-img-p-box::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 45px;
        display: block;
        z-index: 1;
        border-radius: 4px;
        background: linear-gradient(180deg, hsla(0, 0%, 100%, 0), #000);
        background-blend-mode: multiply;
        transform: rotate(-180deg);
    }

    /* BOTTOM GRADIENT OVERLAY */
    .new-img-p-box::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 50px;
        display: block;
        opacity: .9;
        background-image: linear-gradient(0deg, #000000 0%, rgba(255, 255, 255, 0) 100%);
    }

    .HP-lables {
        position: absolute;
        top: 11px;
        left: 9px;
        color: black;
        font-size: 13px;
        background-color: #FAF4E4;
        padding: 2px 10px;
        width: fit-content;
        margin-inline-start: 0;
        margin-inline-end: auto;
        font-size: var(--font-sm);
        border-radius: 6px;
        z-index: 1;
    }

    .new-img-p-box .btn-wishlist {
        background-color: transparent;
    }

    .PD .btn-wishlist {
        background-color: transparent !important;
        box-shadow: none !important;
        color: white !important;
        font-size: 20px;
        top: 5px;
        right: 5px;
    }

    .PD .btn-wishlist.wishlist-active i {
        font-weight: 700;
        color: red;
    }

    .PD .btn-wishlist:hover i {
        color: red;
    }

    .PND .product-title {
        font-size: 14px !important;
        text-transform: capitalize;
        color: black !important;
        margin-bottom: 3px;
    }

    .PND {
        padding: 10px 5px;
    }

    .PND:hover {}
</style>

<div {{ $attributes }} style="position: relative; margin-bottom:0; ">
    <div class="PD  mb-0 " style="border-radius: 20px;"
        {{ $animation ? 'data-aos="fade-up" data-aos-delay="100"' : '' }}>

        <div style="padding: 0px;">

            <div class="product-image-box new-img-p-box">

                <a
                    href="{{ route('frontend.property.details', ['slug' => $property->slug ?? $property->propertyContent->slug]) }}">
                    <img class="lazyload new-images-card images-ffps" src="assets/images/placeholder.png"
                        data-src="{{ asset('assets/img/property/featureds/' . $property->featured_image) }}">
                </a>

                <span class="HP-lables">{{ $property->purpose === "sell" ? "Buy" : ucwords(str_replace('_', ' ', $property->purpose)) }}</span>


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

            <a href="{{ route('frontend.property.details', $property->slug ?? $property->propertyContent->slug) }}">
                <div class="product-new-div PND">
                    <h3 class=" product-title">

                        {{ Str::limit($property->title ?? $property->propertyContent->title, 30) }}
                    </h3>






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


                    <div class="button-new-add" style="    margin-top: 5px;padding-top: 5px;">

                    <span class="product-location icon-start " style="color:#091E42 ; font-size : 14px ;">
                        <i class="fal fa-map-marker-alt" style="color:#091E42 ; font-size : 14px  ;     margin-inline-end: 0px;" ></i>
                       {{ $property->areaContent?->name
                                                        ? $property->areaContent->name . ', ' . ($property->city?->getContent($property->language_id)?->name ?? '')
                                                        : ($property->city?->getContent($property->language_id)?->name ?? '') }}
                        {{ $property->isStateActive ? ', ' . $property->state?->getContent($property->language_id)?->name : '' }}
                    </span>

                        <div class="product-price">
                            <span class="new-price" style="margin-inline-end: 0px;">
                                {{ $property->price ? symbolPrice($property->price) : __('Negotiable') }}
                            </span>
                        </div>


                        <!-- <a href="{{ route('frontend.property.details', $property->slug ?? $property->propertyContent->slug) }}"
                                class="VD-arrow ">
                                <i class="fa-solid fa-eye"></i>
                            </a> -->
                    </div>

                </div>
            </a>

        </div>
    </div>

</div>









<!-- <style>
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
    border-radius: 0px;
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



                 <span class="product-location icon-start">
                    <i class="fal fa-map-marker-alt"></i>
                    {{ $property->city->getContent($property->language_id)?->name }}
                    {{ $property->isStateActive ? ', ' . $property->state?->getContent($property->language_id)?->name : '' }}
                </span>

                   <div class="product-price">
                    <span class="new-price">
                        {{ $property->price ? symbolPrice($property->price) : __('Negotiable') }}
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

    <span class="label new-lables">{{ ucwords(str_replace('_', ' ', $property->purpose)) }}</span>
</div> -->
