<div {{ $attributes }} style="position: relative;">
    <style>
        .product-latest {
            display: flex;
            flex-direction: column;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: all 0.3s ease-in-out;
            margin-bottom: 20px;
            position: relative;
            margin-top: 10px;
            width: 320px; 
            height: 260px;
            justify-content: space-between;
        }

        .product-latest:hover {
            box-shadow: 0 4px 18px rgba(0,0,0,0.15);
            /* transform: translateY(-3px); */
        }

        .property-purpose-tag {
            position: absolute;
            top: 14px;
            left: 0;
            display: inline-block;
            background: #faf4e4;
            color: #000;
            font-size: 12px;
            font-weight: 700;
            padding: 8px 14px;
            text-transform: uppercase;
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
            line-height: 1.2;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            z-index: 3;
            white-space: nowrap;
        }

        .latest-content {
            display: flex;
            align-items: flex-start;
            padding: 10px 15px 12px;
            margin-top: 38px;
            flex: 1;
            overflow: hidden; 
			gap:10px;
        }


        .latest-image-box {
            flex: 0 0 90px;
            height: 90px;
            border-radius: 50%;
            overflow: hidden;
            position: relative;
        }

        .latest-image-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            padding-top: 10px;
        }

        .latest-details {
            flex: 1;
            margin-left: 14px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 4px;
        }

        .latest-title {
            font-size: 16px;
            font-weight: 600;
            color: #111;
            margin: 0;
            line-height: 1.3;
        }

        .latest-location {
            font-size: 13px;
            color: #666;
            margin-top: 2px;
        }

        .latest-price {
            font-size: 15px;
            font-weight: 600;
            color: #222;
        }
        .latest-info {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            font-size: 13px;
            color: #444;
            margin-top: 4px;
        }

        .latest-info li {
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 4px 8px;
            background: #fafafa;
            display: flex;
            align-items: center;
            line-height: 1.2;
        }

        .latest-info i {
            margin-right: 5px;
            color: #999;
            font-size: 13px;
        }

        .latest-footer {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            border-top: 1px solid #e6e6e6;
            background: #fff;
        }

        .latest-footer a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 10px 18px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.3s ease;
            width: 48%;
            text-align: center;
        }

        .latest-footer a.btn-wishlist {
            background-color: #fff;
            color: #6c603c;
        }

        .latest-footer a.btn-wishlist:hover {
            background-color: #6c603c;
            color: #fff;
        }

        .latest-footer a.btn-wishlist.wishlist-active {
            background-color: #6c603c;
            color: #fff;
        }

        .latest-footer a.view-btn {
            background-color: #6c603c;
            border: 1px solid #6c603c;
            color: #fff;
        }

        .latest-footer a.view-btn:hover {
            background-color: #e6e6e6;
            color: #000;
            border-color: #e6e6e6;
        }

 
        @media (max-width: 767px) {
            .product-latest {
                width: 100%;
                height: auto;
            }
        }
    </style>

    <div class="product-latest">
      
        <span class="property-purpose-tag">
            {{ strtoupper(str_replace('_', ' ', $property->purpose)) }}
        </span>

        <div class="latest-content">
            <div class="latest-image-box">
                <a href="{{ route('frontend.property.details', ['slug' => $property->slug ?? $property->propertyContent->slug]) }}">
                    <img class="lazyload"
                         src="assets/images/placeholder.png"
                         data-src="{{ asset('assets/img/property/featureds/' . $property->featured_image) }}"
                         alt="{{ $property->title ?? $property->propertyContent->title }}">
                </a>
            </div>

            <div class="latest-details">
                <h3 class="latest-title">
                    <a href="{{ route('frontend.property.details', $property->slug ?? $property->propertyContent->slug) }}">
                        {{ Str::limit($property->title ?? $property->propertyContent->title, 15) }}
                    </a>
                </h3>

                <span class="latest-location">
                    <i class="fal fa-map-marker-alt"></i>
                    {{ $property->city->getContent($property->language_id)?->name }}
                    {{ $property->isStateActive ? ', ' . $property->state?->getContent($property->language_id)?->name : '' }}
                </span>

                <div class="latest-price">
                    {{ $property->price ? symbolPrice($property->price) : __('Negotiable') }}
                </div>

                <ul class="latest-info p-0 list-unstyled d-flex align-items-center flex-wrap">
                    <li><i class="fal fa-vector-square"></i> {{ $property->area }} Sqft</li>
                    @if ($property->type == 'residential')
                        <li><i class="fal fa-bed"></i> {{ $property->beds }} Beds</li>
                        <li><i class="fal fa-bath"></i> {{ $property->bath }} Baths</li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="latest-footer">
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
                <a type="button" class="btn-wishlist" data-bs-toggle="modal" data-bs-target="#customerPhoneModal" data-action="login">
                    <i class="fal fa-heart"></i> Wishlist
                </a>
            @else
                <a href="javascript:void(0);"
                   class="btn-wishlist {{ $checkWishList ? 'wishlist-active' : '' }}"
                   data-id="{{ $property->id }}"
                   data-action="{{ $checkWishList ? 'remove' : 'add' }}"
                   data-add-url="{{ route('addto.wishlist', $property->id) }}"
                   data-remove-url="{{ route('remove.wishlist', $property->id) }}">
                    <i class="fal fa-heart"></i> {{ $checkWishList ? __('Saved') : __('Wishlist') }}
                </a>
            @endif

            <a href="{{ route('frontend.property.details', $property->slug ?? $property->propertyContent->slug) }}" class="view-btn">
                View Details
            </a>
        </div>
    </div>
</div>
