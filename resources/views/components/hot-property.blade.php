<div {{ $attributes }} style="position : relative">
	<div class="product-default mb-30 new-shadow-box" style="border-radius: 20px;" {{ $animation ? 'data-aos="fade-up" data-aos-delay="100"' : '' }}>
		<!-- <figure class="product-img">
			<a href="{{ route('frontend.property.details', ['slug' => $property->slug ?? $property->propertyContent->slug]) }}"
			class="lazy-container">
			<img class="lazyload" src="assets/images/placeholder.png"
			data-src="{{ asset('assets/img/property/featureds/' . $property->featured_image) }}">
			</a>
		</figure> -->
		<div class="product-details"  style="padding : 0px;">
			<div class="product-image-box"> <!-- wrapper div add -->
				<a href="{{ route('frontend.property.details', ['slug' => $property->slug ?? $property->propertyContent->slug]) }}">
					<img class="lazyload new-images-card"
					src="assets/images/placeholder.png"
					data-src="{{ asset('assets/img/property/featureds/' . $property->featured_image) }}">
				</a>
			</div>
			
			<div class="product-new-div">
				<h3 class="product-title">
					<a
					href="{{ route('frontend.property.details', $property->slug ?? $property->propertyContent->slug) }}">{{ Str::limit($property->title ?? $property->propertyContent->title, 20) }}</a>
				</h3>
				
				<span class="product-location icon-start"> <i class="fal fa-map-marker-alt"></i>
					{{ $property->city->getContent($property->language_id)?->name }}
					{{ $property->isStateActive ? ', ' . $property->state?->getContent($property->language_id)?->name : '' }}
					{{-- {{ $property->isCountryActive ? ', ' . $property->country?->getContent($property->language_id)?->name : '' }} --}}
				</span>
				
				
				<div class="product-price">
					<span class="new-price">{{ __('') }}
					{{ $property->price ? symbolPrice($property->price) : __('Negotiable') }}</span>
				</div>
				
				
				<ul class="product-info p-0 list-unstyled d-flex align-items-center">
					<li class="icon-start new-badge-product" data-tooltip="tooltip" data-bs-placement="top" title="{{ __('Area') }}">
						<i class="fal fa-vector-square new-icon-color"></i>
						<span>{{ $property->area }} {{ __('Sqft') }}</span>
					</li>
					@if ($property->type == 'residential')
					<li class="icon-start new-badge-product" data-tooltip="tooltip" data-bs-placement="top" title="{{ __('Beds') }}">
						<i class="fal fa-bed new-icon-color"></i>
						<span>{{ $property->beds }} {{ __('Beds') }}</span>
					</li>
					<li class="icon-start new-badge-product" data-tooltip="tooltip" data-bs-placement="top" title="{{ __('Baths') }}">
						<i class="fal fa-bath new-icon-color"></i>
						<span>{{ $property->bath }} {{ __('Baths') }}</span>
					</li>
					@endif
				</ul>
				
				<div class="button-new-add mt-3">
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
							<i class="fal fa-heart"></i>
						</a>
					@else
						<a href="javascript:void(0);"
						   class="btn-wishlist {{ $checkWishList ? 'wishlist-active' : '' }}"
						   data-id="{{ $property->id }}"
						   data-action="{{ $checkWishList ? 'remove' : 'add' }}"
						   data-add-url="{{ route('addto.wishlist', $property->id) }}"
						   data-remove-url="{{ route('remove.wishlist', $property->id) }}"
						   data-url="{{ $checkWishList ? route('remove.wishlist', $property->id) : route('addto.wishlist', $property->id) }}"
						   title="{{ $checkWishList ? __('Saved') : __('Add to Wishlist') }}">
							<i class="fal fa-heart"></i>
						</a>


					@endif 
					<a href="{{ route('frontend.property.details', $property->slug ?? $property->propertyContent->slug) }}" class=""> View Details
					</a>
				</div>
				
			</div>
		</div>
		
	</div>
	<span class="label new-lables">{{ ucwords(str_replace('_', ' ', $property->purpose)) }}</span>
	<!-- <span class="round-new"></span> -->
</div> 
