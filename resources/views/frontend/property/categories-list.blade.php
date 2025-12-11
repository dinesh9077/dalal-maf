{{-- resources/views/partials/categories-list.blade.php --}}
<div class="accordion-body">
    <div class="custom-checkbox new-animitis-divs">
        @php
            $selectedCategories = \Illuminate\Support\Arr::wrap(request()->input('category', []));
        @endphp

        @foreach ($categories as $category)
            @if ($category->categoryContent)
                <div>
                    <input class="input-checkbox category-checkbox"
                           type="checkbox"
                           name="category[]"
                           id="categoryCheckbox{{ $category->id }}"
                           value="{{ $category->id }}"
                           {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}
                           onchange="updateAmenities('category[]={{ $category->id }}',this)">

                    <label for="categoryCheckbox{{ $category->id }}">
                        <span class="animits-div-tab">{{ $category->categoryContent?->name }}</span>
                    </label>
                </div>
            @endif
        @endforeach
    </div>
</div> 