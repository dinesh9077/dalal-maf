{{-- resources/views/partials/amenities-list.blade.php --}}
<div class="accordion-body">
    <div class="custom-checkbox new-animitis-divs">
        @php
            $selectedAmenities = \Illuminate\Support\Arr::wrap(request()->input('amenities', []));
        @endphp

        @foreach ($amenities as $amenity)
            @if ($amenity->amenityContent)
                <div>
                    <input class="input-checkbox amenity-checkbox"
                           type="checkbox"
                           name="amenities[]"
                           id="amenityCheckbox{{ $amenity->id }}"
                           value="{{ $amenity->id }}"
                           {{ in_array($amenity->amenityContent?->name, $selectedAmenities) ? 'checked' : '' }}
                           onchange="updateAmenities('amenities[]={{ $amenity->amenityContent?->name }}',this)">

                    <label for="amenityCheckbox{{ $amenity->id }}">
                        <span class="animits-div-tab">{{ $amenity->amenityContent?->name }}</span>
                    </label>
                </div>
            @endif
        @endforeach
    </div>
</div>
