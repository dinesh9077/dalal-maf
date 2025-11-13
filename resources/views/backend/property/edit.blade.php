@extends('backend.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Edit Property') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Property Management') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Edit Property') }}
                    @if ($property->type == 'residential')
                        {{ '(Residential)' }}
                    @elseif($property->type == 'industrial')
                        {{ '(Industrial)' }}
                    @else
                        {{ '(Commercial)' }}
                    @endif
                </a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ __('Edit Property') }}</div>
                    <a class="btn btn-info btn-sm float-right d-inline-block"
                        href="{{ route('admin.property_management.properties', ['language' => $defaultLang->code]) }}">
                        <span class="btn-label">
                            <i class="fas fa-backward"></i>
                        </span>
                        {{ __('Back') }}
                    </a>
                    @php
                        $dContent = App\Models\Property\Content::where('property_id', $property->id)
                            ->where('language_id', $defaultLang->id)
                            ->first();
                        $slug = !empty($dContent) ? $dContent->slug : '';
                    @endphp
                    @if ($dContent)
                        <a class="btn btn-success btn-sm float-right mr-1 d-inline-block"
                            href="{{ route('frontend.property.details', ['slug' => $slug]) }}" target="_blank">
                            <span class="btn-label">
                                <i class="fas fa-eye"></i>
                            </span>
                            {{ __('Preview') }}
                        </a>
                    @endif

                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-10 offset-lg-1">
                            <div class="alert alert-danger pb-1 dis-none" id="carErrors">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <ul></ul>
                            </div>

                            <div class="col-lg-12">
                                <label for=""
                                    class="mb-2"><strong>{{ __('Gallery Images') . '*' }}</strong></label>
                                <div id="reload-slider-div">
                                    <div class="row">

                                        <div class="col-12">
                                            <table class="table table-striped" id="imgtable">

                                                @foreach ($galleryImages as $item)
                                                    <tr class="trdb table-row" id="trdb{{ $item->id }}">
                                                        <td>
                                                            <div class="">
                                                                <img class="thumb-preview wf-150"
                                                                    src="{{ asset('assets/img/property/slider-images/' . $item->image) }}"
                                                                    alt="Ad Image">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <i class="fa fa-times rmvbtndb"
                                                                data-indb="{{ $item->id }}"></i>
                                                        </td>
                                                        
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <form action="{{ route('admin.property.imagesstore') }}" id="my-dropzone"
                                    enctype="multipart/formdata" class="dropzone create">
                                    @csrf
                                    <div class="fallback">
                                        <input name="file" type="file" multiple />
                                    </div>
                                    <input type="hidden" value="{{ $property->id }}" name="property_id">
                                </form>
                                <p class="em text-danger mb-0" id="errslider_images"></p>
                                                    <p class="text-warning mb-0">Image Size : 400 x 200</p>

                            </div>

                            <form id="carForm"
                                action="{{ route($url, $property->id) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="property_id" value="{{ $property->id }}">
                                <input type="hidden" name="type" value="{{ $property->type }}">
                                <input type="hidden" name="vendor_id" value="{{ $property->vendor_id }}">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="">{{ __('Thumbnail Image') . '*' }}</label>
                                            <br>
                                            <div class="thumb-preview">
                                                <img src="{{ $property->featured_image ? asset('assets/img/property/featureds/' . $property->featured_image) : asset('assets/img/noimage.jpg') }}"
                                                    alt="..." class="uploaded-img">
                                            </div>
                                            <div class="mt-3">
                                                <div role="button" class="btn btn-primary btn-sm upload-btn">
                                                    {{ __('Choose Image') }}
                                                    <input type="file" class="img-input" name="featured_image">
                                                </div>
                                            </div>
<p class="text-warning mb-0">Image Size :  310 x 180</p>

                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="">{{ __('Floor Planning Image') }}</label>
                                            <br>
                                            <div class="thumb-preview remove">

                                                <img src="{{ !empty($property->floor_planning_image) ? asset('assets/img/property/plannings/' . $property->floor_planning_image) : asset('assets/img/noimage.jpg') }}"
                                                    alt="..." class="uploaded-img2">
                                                @if (!empty($property->floor_planning_image))
                                                    <i class="fas fa-times text-danger rmvflrImg"
                                                        data-indb="{{ $property->id }}"></i>
                                                @endif
                                            </div>

                                            <div class="mt-3">
                                                <div role="button" class="btn btn-primary btn-sm upload-btn">
                                                    {{ __('Choose Image') }}
                                                    <input type="file" class="img-input2" name="floor_planning_image">
                                                </div>
                                            </div>
<p class="text-warning mb-0">Image Size :  900 x 500</p>

                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="">{{ __('Video Image') }}</label>
                                            <br>
                                            <div class="thumb-preview remove">

                                                <img src="{{ !empty($property->video_image) ? asset('assets/img/property/video/' . $property->video_image) : asset('assets/img/noimage.jpg') }}"
                                                    alt="..." class="uploaded-img3">
                                                @if (!empty($property->video_image))
                                                    <i class="fas fa-times text-danger rmvvdoImg"
                                                        data-indb="{{ $property->id }}"></i>
                                                @endif
                                            </div>

                                            <div class="mt-3">
                                                <div role="button" class="btn btn-primary btn-sm upload-btn">
                                                    {{ __('Choose Image') }}
                                                    <input type="file" class="img-input3" name="video_image">
                                                </div>
                                            </div>
<p class="text-warning mb-0">Image Size :  900 x 500</p>

                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>{{ __('Video Url') }} </label>
                                            <input type="text" class="form-control" name="video_url"
                                                placeholder="Enter video url" value="{{ $property->video_url }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>{{ __('Purpose') }}*</label>

                                            <select id="purpose" name="purpose" class="form-control">
                                                <option disabled> {{ __('Select a Purpose') }} </option>
                                                <option value="rent" @if ($property->purpose == 'rent') selected @endif>
                                                    {{ __('Rent') }}
                                                </option>
                                                {{-- <option value="sell" @if ($property->purpose == 'sell') selected @endif>
                                                    {{ __('Sell') }}
                                                </option> --}}
                                                <option value="buy" @if ($property->purpose == 'buy') selected @endif>
                                                    {{ __('Buy') }}
                                                </option>
                                                 <option value="lease" @if ($property->purpose == 'lease') selected @endif>
                                                    {{ __('Lease') }}
                                                </option>
                                                <option value="franchiese" @if ($property->purpose == 'franchiese') selected @endif>
                                                    {{ __('Franchiese') }}
                                                </option>
                                                <option value="business_for_sale" @if ($property->purpose == 'business_for_sale') selected @endif>
                                                    {{ __('Business For Sale') }}
                                                </option>
                                            </select> 
                                        </div>

                                    </div>

                                    <div class="col-lg-3">
                                        <div class="form-group ">
                                            <label>{{ __('Category') }} *</label>
                                            <select name="category_id" class="form-control category">
                                                <option disabled selected>
                                                    {{ __('Select a Category') }}
                                                </option>

                                                @foreach ($propertyCategories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ $property->category_id == $category->id ? 'selected' : '' }}>
                                                        {{ $category->categoryContent->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="">{{ __('Amenities') }}</label>
                                            <select name="amenities[]" class="form-control js-example-basic-single2"
                                                multiple="multiple">
                                                <option value="" disabled>
                                                    {{ __('Please Select Amenities') }}
                                                </option>
                                                @foreach ($amenities as $amenity)
                                                    <option value="{{ $amenity->id }}"
                                                        @foreach ($propertyAmenities as $propertyAmenity)
                                                            {{ $propertyAmenity->amenity_id == $amenity->id ? 'selected' : '' }} @endforeach>
                                                        {{ $amenity->amenityContent->name }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-lg-3 area">
                                      <div class="form-group">
                                          <label>{{ __('Area') }} *</label>
                                          <select name="area_id" class="form-control area_id js-example-basic-single">
                                              <option value="">{{ __('Select Area') }}</option>
                                              @foreach ($allAreas as $area)
                                                   <option value="{{ $area->id }}"
                                                        {{ $property->area_id == $area->id ? 'selected' : '' }}>
                                                        {{ $area->name }}</option>
                                              @endforeach
                                          </select>
                                      </div>
                                  </div>

                                  <div class="col-lg-3 state">
                                      <div class="form-group">
                                          <label>{{ __('State') }} *</label>
                                          <select name="state_id" class="form-control state_id js-example-basic-single3">
                                              <option value="">{{ __('Select State') }}</option>
                                              @if ($property->state_id)
                                                  <option value="{{ $property->state_id }}" selected>
                                                      {{ $property->state?->stateContent?->name }}
                                                  </option>
                                              @endif
                                          </select>
                                      </div>
                                  </div>

                                  <div class="col-lg-3 city">
                                      <div class="form-group">
                                          <label>{{ __('City') }} *</label>
                                          <select name="city_id" class="form-control city_id js-example-basic-single3">
                                              <option value="">{{ __('Select City') }}</option>
                                              @if ($property->city_id)
                                                  <option value="{{ $property->city_id }}" selected>
                                                      {{ $property->city?->cityContent?->name }}
                                                  </option>
                                              @endif
                                          </select>
                                      </div>
                                  </div>

                                  <div class="col-lg-3 country_old">
                                      <div class="form-group">
                                          <label>{{ __('Country') }} *</label>
                                          <select name="country_id" class="form-control js-example-basic-single3 country">
                                              <option value="">{{ __('Select Country') }}</option>
                                              @if ($property->country_id)
                                                  <option value="{{ $property->country_id }}" selected>
                                                      {{ $property->country?->countryContent?->name }}
                                                  </option>
                                              @endif
                                          </select>
                                      </div>
                                  </div>

                                    <div class="col-lg-12">
                                        <div
                                            class="form-group">
                                            <label>{{ __('Address') . '*' }} </label>
                                            <input type="text"
                                                name="address"
                                                placeholder="Enter Address"
                                                value="{{ @$peoperty->address }}"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 hideNotes" style="display:{{ !in_array($property->purpose, ['franchiese', 'business_for_sale']) ? 'none' : 'block' }}">
                                        <div class="form-group">
                                            <label>{{ __('Notes') }} *</label>
                                            <input type="text" class="form-control" name="notes"
                                                placeholder="Enter Notes"  value="{{ $property->notes }}">

                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>{{ __('Price') . ' (' . $settings->base_currency_text . ')' }} </label>
                                            <input type="number" class="form-control" name="price"
                                                placeholder="Enter Current Price" value="{{ $property->price }}">
                                            <p class="text-warning">
                                                {{ __('If you leave it blank, price will be negotiable.') }}
                                            </p>
                                        </div>
                                    </div>


                                    @if ($property->type == 'residential')
                                        {{-- <div class="col-lg-3">
                                            <div class="form-group">
                                                <label>{{ __('Beds') }} *</label>
                                                <input type="text" class="form-control" name="beds"
                                                    value="{{ $property->beds }}" placeholder="Enter number of bed">
                                            </div>
                                        </div> --}}
                                        <div class="col-lg-3 hideBath" style="display:{{ in_array($property->purpose, ['franchiese', 'business_for_sale']) ? 'none' : 'block' }}">
                                            <div class="form-group">
                                                <label>{{ __('Baths') }} *</label>
                                                <input type="text" class="form-control" name="bath"
                                                    value="{{ $property->bath }}" placeholder="Enter number of bath">
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-lg-3 hideSqft" style="display:{{ in_array($property->purpose, ['franchiese', 'business_for_sale']) ? 'none' : 'block' }}">
                                        <div class="form-group">
                                            <label>{{ __('Area (sqft)') }} *</label>
                                            <input type="text" class="form-control" name="area"
                                                value="{{ $property->area }}" placeholder="Enter area (sqft) ">
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                      <div class="form-group">
                                        <label class="modal-label">Unit Type <span class="text-danger">*</span></label>
                                        <div class="d-flex align-items-center add-property-type">
                                        <select name="unit_type[]" class="form-control js-example-basic-single2"
                                                multiple="multiple">
                                                <option value="" disabled>
                                                    {{ __('Please Select Unit Type') }}
                                                </option>
                                                @foreach ($unitTypes as $unitType)
                                                    <option value="{{ $unitType->id }}"
                                                        @foreach ($propertyUnities as $propertyUnity)
                                                            {{ $propertyUnity->unit_id == $unitType->id ? 'selected' : '' }} @endforeach>
                                                        {{ $unitType->unit_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                      </div>
                                  </div>

                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>{{ __('Status') }} *</label>
                                            <select name="status" id="" class="form-control">
                                                <option {{ $property->status == 1 ? 'selected' : '' }} value="1">
                                                    {{ __('Active') }}</option>
                                                <option {{ $property->status == 0 ? 'selected' : '' }} value="0">
                                                    {{ __('Inactive') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>{{ __('Latitude') }} </label>
                                            <input type="text" class="form-control" value="{{ $property->latitude }}"
                                                name="latitude" placeholder="Enter Latitude">
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>{{ __('Longitude') }} *</label>
                                            <input type="text" class="form-control"
                                                value="{{ $property->longitude }}" name="longitude"
                                                placeholder="Enter Longitude">
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="">{{ __('Partner') }}</label>
                                            <select name="vendor_id" class="form-control vendor js-example-basic-single1">
                                                <option value="0" selected>{{ __('Please Select') }}
                                                </option>
                                                @foreach ($vendors as $item)
                                                    <option {{ $property->vendor_id == $item->id ? 'selected' : '' }}
                                                        value="{{ $item->id }}">
                                                        {{ $item->username }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>{{ __('Possession Date') }} </label>
                                            <input type="date" class="form-control" name="possession_date" value="{{ $property->possession_date }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="">{{ __('Furnishing') }}</label>
                                            <select name="furnishing" class="form-control js-example-basic-single1">
                                                <option value="">{{ __('Please Select a furnishing') }} </option>
                                                <option value="Unfurnished" @if ($property->furnishing == 'Unfurnished') selected @endif>Unfurnished</option>
                                                <option value="Semi-Furnished" @if ($property->furnishing == 'Semi-Furnished') selected @endif>Semi-Furnished</option>
                                                <option value="Fully-Furnished" @if ($property->furnishing == 'Fully-Furnished') selected @endif>Fully-Furnished </option>
                                            </select>

                                        </div>
                                    </div>

                                    @php
                                        $agents = App\Models\Agent::where('vendor_id', $property->vendor_id)
                                            ->select('id', 'username')
                                            ->get();
                                    @endphp
                                    <div class="col-lg-3">
                                        <div class="form-group agent @if (empty($property->agent_id)) d-none @endif">
                                            <label for="">{{ __('Staff') }}</label>
                                            <select name="agent_id" class="form-control agent_id js-example-basic-single1">
                                                <option value="" selected>{{ __('Please Select') }}
                                                </option>
                                                @foreach ($agents as $agent)
                                                    <option value="{{ $agent->id }}"
                                                        {{ $agent->id == $property->agent_id ? 'selected' : '' }}>
                                                        {{ $agent->username }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <p class="text-warning">
                                                {{ __('if you do not select any staff, then this property will be listed under Partner') }}
                                            </p>
                                        </div>
                                    </div>


                                </div>

                                <div id="accordion" class="mt-3">
                                    @foreach ($languages as $language)
                                        @php
                                            $peopertyContent = App\Models\Property\Content::where(
                                                'property_id',
                                                $property->id,
                                            )
                                                ->where('language_id', $language->id)
                                                ->first();

                                        @endphp
                                        <div class="version">
                                            <div class="version-header" id="heading{{ $language->id }}">
                                                <h5 class="mb-0">
                                                    <button type="button" class="btn btn-link" data-toggle="collapse"
                                                        data-target="#collapse{{ $language->id }}"
                                                        aria-expanded="{{ $language->is_default == 1 ? 'true' : 'false' }}"
                                                        aria-controls="collapse{{ $language->id }}">
                                                        {{ $language->name . __(' Language') }}
                                                        {{ $language->is_default == 1 ? '(Default)' : '' }}
                                                    </button>
                                                </h5>
                                            </div>

                                            <div id="collapse{{ $language->id }}"
                                                class="collapse {{ $language->is_default == 1 ? 'show' : '' }}"
                                                aria-labelledby="heading{{ $language->id }}" data-parent="#accordion">
                                                <div class="version-body">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div
                                                                class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ __('Property Name*') }}</label>
                                                                <input type="text" class="form-control"
                                                                    name="{{ $language->code }}_title"
                                                                    placeholder="Enter Property Name"
                                                                    value="{{ $peopertyContent ? $peopertyContent->title : '' }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div
                                                                class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ __('Description') }} *</label>
                                                                <textarea class="form-control summernote " id="{{ $language->code }}_description"
                                                                    name="{{ $language->code }}_description" data-height="300">{{ @$peopertyContent->description }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div
                                                                class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ __('Meta Keywords') }} </label>
                                                                <input class="form-control"
                                                                    name="{{ $language->code }}_meta_keyword"
                                                                    placeholder="Enter Meta Keywords"
                                                                    data-role="tagsinput"
                                                                    value="{{ $peopertyContent ? $peopertyContent->meta_keyword : '' }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div
                                                                class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ __('Meta Description') }} </label>
                                                                <textarea class="form-control" name="{{ $language->code }}_meta_description" rows="5"
                                                                    placeholder="Enter Meta Description">{{ $peopertyContent ? $peopertyContent->meta_description : '' }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col">
                                                            @php $currLang = $language; @endphp

                                                            @foreach ($languages as $language)
                                                                @continue($language->id == $currLang->id)
                                                                <div class="form-check py-0">
                                                                    <label class="form-check-label">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            onchange="cloneInput('collapse{{ $currLang->id }}', 'collapse{{ $language->id }}', event)">
                                                                        <span
                                                                            class="form-check-sign">{{ __('Clone for') }}
                                                                            <strong
                                                                                class="text-capitalize text-secondary">{{ $language->name }}</strong>
                                                                            {{ __('language') }}</span>
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="row">
                                    <div class="col-lg-12" id="variation_pricing">
                                        <h4 for="">{{ __('Additional Features (Optional)') }}</h4>
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Label') }}</th>
                                                    <th>{{ __('Value') }}</th>
                                                    <th><a href="javascrit:void(0)"
                                                            class="btn  btn-sm btn-success addRow"><i
                                                                class="fas fa-plus-circle"></i></a></th>
                                                </tr>
                                            <tbody id="tbody">

                                                @if (count($specifications) > 0)
                                                    @foreach ($specifications as $specification)
                                                        <tr>
                                                            <td>
                                                                @foreach ($languages as $language)
                                                                    @php
                                                                        $sp_content = App\Models\Property\SpacificationCotent::where(
                                                                            [
                                                                                ['language_id', $language->id],
                                                                                [
                                                                                    'property_spacification_id',
                                                                                    $specification->id,
                                                                                ],
                                                                            ],
                                                                        )->first();
                                                                    @endphp
                                                                    <div
                                                                        class="form-group  {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                        <input type="text"
                                                                            name="{{ $language->code }}_label[]"
                                                                            value="{{ @$sp_content->label }}"
                                                                            class="form-control"
                                                                            placeholder="Label ({{ $language->name }})">
                                                                    </div>
                                                                @endforeach
                                                            </td>
                                                            <td>
                                                                @foreach ($languages as $language)
                                                                    @php
                                                                        $sp_content = App\Models\Property\SpacificationCotent::where(
                                                                            [
                                                                                ['language_id', $language->id],
                                                                                [
                                                                                    'property_spacification_id',
                                                                                    $specification->id,
                                                                                ],
                                                                            ],
                                                                        )->first();
                                                                    @endphp
                                                                    <div
                                                                        class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                        <input type="text"
                                                                            name="{{ $language->code }}_value[]"
                                                                            value="{{ @$sp_content->value }}"
                                                                            class="form-control"
                                                                            placeholder="Value ({{ $language->name }})">
                                                                    </div>
                                                                @endforeach
                                                            </td>
                                                            <td>
                                                                <a href="javascript:void(0)"
                                                                    data-specification="{{ $specification->id }}"
                                                                    class="btn  btn-sm btn-danger deleteSpecification">
                                                                    <i class="fas fa-minus"></i></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td>
                                                            @foreach ($languages as $language)
                                                                <div
                                                                    class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                    <input type="text"
                                                                        name="{{ $language->code }}_label[]"
                                                                        class="form-control"
                                                                        placeholder="Label ({{ $language->name }})">
                                                                </div>
                                                            @endforeach
                                                        </td>
                                                        <td>
                                                            @foreach ($languages as $language)
                                                                <div
                                                                    class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                    <input type="text"
                                                                        name="{{ $language->code }}_value[]"
                                                                        class="form-control"
                                                                        placeholder="Value ({{ $language->name }})">
                                                                </div>
                                                            @endforeach
                                                        </td>
                                                        <td>
                                                            <a href="javascript:void(0)"
                                                                class="btn btn-danger  btn-sm deleteRow">
                                                                <i class="fas fa-minus"></i></a>
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                            </thead>
                                        </table>
                                    </div>
                                </div>

                                <div id="sliders"></div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="submit" id="PropertySubmit" class="btn btn-primary">
                                {{ __('Update') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@php
    $languages = App\Models\Language::get();
    $labels = '';
    $values = '';
    foreach ($languages as $language) {
        $label_name = $language->code . '_label[]';
        $value_name = $language->code . '_value[]';
        if ($language->direction == 1) {
            $direction = 'form-group rtl text-right';
        } else {
            $direction = 'form-group';
        }

        $labels .=
            "<div class='$direction'><input type='text' name='" .
            $label_name .
            "' class='form-control' placeholder='Label ($language->name)'></div>";
        $values .= "<div class='$direction'><input type='text' name='$value_name' class='form-control' placeholder='Value ($language->name)'></div>";
    }
@endphp

@section('script')
    <script>
        'use strict';
        var labels = "{!! $labels !!}";
        var values = "{!! $values !!}";
        var stateUrl = "{{ route('admin.property_specification.get_state_cities') }}";
        var cityUrl = "{{ route('admin.property_specification.get_cities') }}";
        var storeUrl = "{{ route('admin.property.imagesupdate', ['vendor_id' => $property->vendor_id]) }}";
        var removeUrl = "{{ route('admin.property.imagermv') }}";
        var rmvdbUrl = "{{ route('admin.property.imgdbrmv') }}";
        let agentUrl = "{{ route('admin.property_management.get_agent') }}";
        let areaUrl = "{{ route('admin.property_specification.get_areas') }}";
        var getLocationByAreaUrl = "{{ route('admin.property_specification.get_location_by_area') }}";
        var specificationRmvUrl = "{{ route('admin.property_management.specification_delete') }}";

        let galleryImages = {{ $uploadGImg }};

         $('#purpose').on('change', function() {
            const val = this.value;
            const hideFields = val === 'franchiese' || val === 'business_for_sale';
            
            // toggle visibility
            $('.hideBath, .hideSqft').toggle(!hideFields);
            $('.hideNotes').toggle(hideFields);
             
            // reset values
            $('input[name="bath"], input[name="area"]').val(0);
            $('input[name="notes"]').val('');
        });
    </script>
    <script type="text/javascript" src="{{ asset('assets/js/admin-partial.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/admin-dropzone.js') }}"></script>
    <script src="{{ asset('assets/js/property.js') }}"></script>

@endsection