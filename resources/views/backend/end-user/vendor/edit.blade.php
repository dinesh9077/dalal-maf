@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Vendor') }}</h4>
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
        <a href="#">{{ __('Partner Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="{{ route('admin.vendor_management.registered_vendor') }}">{{ __('Registered Partners') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit Vendor') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-12">
              <div class="card-title">{{ __('Edit Vendor') }}</div>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-10 mx-auto">
              <form id="ajaxEditForm"
                action="{{ route('admin.vendor_management.vendor.update_vendor', ['id' => $vendor->id]) }}"
                method="post">
                @csrf
                <h2>Details</h2>
                <hr>
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label for="">{{ __('Photo') }}</label>
                      <br>
                      <div class="thumb-preview">
                        @if ($vendor->photo != null)
                          <img src="{{ asset('assets/admin/img/vendor-photo/' . $vendor->photo) }}" alt="..."
                            class="uploaded-img">
                        @else
                          <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                        @endif

                      </div>

                      <div class="mt-3">
                        <div role="button" class="btn btn-primary btn-sm upload-btn">
                          {{ __('Choose Photo') }}
                          <input type="file" class="img-input" name="photo">
                        </div>
                        <p id="editErr_photo" class="mt-1 mb-0 text-danger em"></p>
                      </div>
                    </div>
                  </div>
                  {{-- <div class="col-lg-3">
                      <div class="form-group">
                          <label>{{ __('Type*') }}</label>
                          <select name="type" class="form-control">
                              <option value="broker" @if ($vendor->type == 'broker') selected @endif>{{ __('Broker') }}</option>
                              <option value="builder" @if ($vendor->type == 'builder') selected @endif>{{ __('Builder') }}</option>
                          </select>
                      </div>
                  </div> --}}
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Username*') }}</label>
                      <input type="text" value="{{ $vendor->username }}" class="form-control" name="username">
                      <p id="editErr_username" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Email*') }}</label>
                      <input type="text" value="{{ $vendor->email }}" class="form-control" name="email">
                      <p id="editErr_email" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Phone') }}</label>
                      <input type="tel" value="{{ $vendor->phone }}" class="form-control" name="phone" max="10" pattern="\d{10}" id="phone">
                      <p id="editErr_phone" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>

                  {{-- <div class="col-lg-12">
                    <div class="row">
                      <div class="col-lg-4">
                        <div class="form-group">
                          <div class="custom-control custom-checkbox">
                            <input type="checkbox" {{ $vendor->show_email_addresss == 1 ? 'checked' : '' }}
                              name="show_email_addresss" class="custom-control-input" id="show_email_addresss">
                            <label class="custom-control-label"
                              for="show_email_addresss">{{ __('Show Email Address in Profile Page') }}</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-4">
                        <div class="form-group">
                          <div class="custom-control custom-checkbox">
                            <input type="checkbox" {{ $vendor->show_phone_number == 1 ? 'checked' : '' }}
                              name="show_phone_number" class="custom-control-input" id="show_phone_number">
                            <label class="custom-control-label"
                              for="show_phone_number">{{ __('Show Phone Number in Profile Page') }}</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-4">
                        <div class="form-group">
                          <div class="custom-control custom-checkbox">
                            <input type="checkbox" {{ $vendor->show_contact_form == 1 ? 'checked' : '' }}
                              name="show_contact_form" class="custom-control-input" id="show_contact_form">
                            <label class="custom-control-label"
                              for="show_contact_form">{{ __('Show Contact Form') }}</label>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div> --}}

                  <div class="col-lg-12">
                    <div id="accordion" class="mt-5">
                      @foreach ($languages as $language)
                        <div class="version">
                          <div class="version-header" id="heading{{ $language->id }}">
                            <h5 class="mb-0">
                              <button type="button"
                                class="btn btn-link {{ $language->direction == 1 ? 'rtl text-right' : '' }}"
                                data-toggle="collapse" data-target="#collapse{{ $language->id }}"
                                aria-expanded="{{ $language->is_default == 1 ? 'true' : 'false' }}"
                                aria-controls="collapse{{ $language->id }}">
                                {{ $language->name . __(' Language') }}
                                {{ $language->is_default == 1 ? '(Default)' : '' }}
                              </button>
                            </h5>
                          </div>

                          @php
                            $vendor_info = App\Models\VendorInfo::where('vendor_id', $vendor->id)
                                ->where('language_id', $language->id)
                                ->first();
                          @endphp

                          <div id="collapse{{ $language->id }}"
                            class="collapse {{ $language->is_default == 1 ? 'show' : '' }}"
                            aria-labelledby="heading{{ $language->id }}" data-parent="#accordion">
                            <div class="version-body">
                              <div class="row">
                                <div class="col-lg-4">
                                  <div class="form-group">
                                    <label>{{ __('Name*') }}</label>
                                    <input type="text" value="{{ !empty($vendor_info) ? $vendor_info->name : '' }}"
                                      class="form-control" name="{{ $language->code }}_name"
                                      placeholder="{{ __('Enter Name') }}">
                                    <p id="editErr_{{ $language->code }}_name" class="mt-1 mb-0 text-danger em"></p>
                                  </div>
                                </div>
                                <div class="col-lg-4">
                                  <div class="form-group">
                                    <label>{{ __('City') }}</label>
                                    <select class="form-control vendor-city"
                                            name="{{ $language->code }}_city_id"
                                            data-lang="{{ $language->code }}">
                                      <option value="">{{ __('Select City') }}</option>
                                      @foreach($cities as $city)
                                        <option value="{{ $city->id }}" {{ (!empty($vendor_info) && $vendor_info->city == $city->name) ? 'selected' : '' }}>
                                          {{ $city->name }}
                                        </option>
                                      @endforeach
                                    </select>
                                    {{-- hidden city name used for saving actual city text --}}
                                    <input type="hidden" class="vendor-city-name"
                                           name="{{ $language->code }}_city"
                                           data-lang="{{ $language->code }}"
                                           value="{{ !empty($vendor_info) ? $vendor_info->city : '' }}">
                                    <p id="editErr_{{ $language->code }}_city" class="mt-1 mb-0 text-danger em"></p>
                                  </div>
                                </div>
                                <div class="col-lg-4">
                                  <div class="form-group">
                                    <label>{{ __('State') }}</label>
                                    <select class="form-control vendor-state" 
                                            name="{{ $language->code }}_state_id" 
                                            data-lang="{{ $language->code }}">
                                      <option value="">{{ __('Select State') }}</option>
                                    </select>
                                    <input type="hidden" class="vendor-state-name" 
                                           name="{{ $language->code }}_state"
                                           data-lang="{{ $language->code }}"
                                           value="{{ !empty($vendor_info) ? $vendor_info->state : '' }}">
                                    <p id="editErr_{{ $language->code }}_state" class="mt-1 mb-0 text-danger em"></p>
                                  </div>
                                </div>
                                <div class="col-lg-4">
                                  <div class="form-group">
                                    <label>{{ __('Country') }}</label>
                                    <select class="form-control vendor-country" 
                                            name="{{ $language->code }}_country_id" 
                                            data-lang="{{ $language->code }}">
                                      <option value="">{{ __('Select Country') }}</option>
                                    </select>
                                    <input type="hidden" class="vendor-country-name" 
                                           name="{{ $language->code }}_country"
                                           data-lang="{{ $language->code }}"
                                           value="{{ !empty($vendor_info) ? $vendor_info->country : '' }}">
                                    <p id="editErr_{{ $language->code }}_country" class="mt-1 mb-0 text-danger em"></p>
                                  </div>
                                </div>
                                <div class="col-lg-4">
                                  <div class="form-group">
                                    <label>{{ __('Zip Code') }}</label>
                                    <input type="text"
                                      value="{{ !empty($vendor_info) ? $vendor_info->zip_code : '' }}"
                                      class="form-control" name="{{ $language->code }}_zip_code"
                                      placeholder="{{ __('Enter Zip Code') }}">
                                    <p id="editErr_{{ $language->code }}_zip_code" class="mt-1 mb-0 text-danger em">
                                    </p>
                                  </div>
                                </div>
                                <div class="col-lg-12">
                                  <div class="form-group">
                                    <label>{{ __('Address') }}</label>
                                    <textarea name="{{ $language->code }}_address" class="form-control" placeholder="{{ __('Enter Address') }}">{{ !empty($vendor_info) ? $vendor_info->address : '' }}</textarea>
                                    <p id="editErr_{{ $language->code }}_email" class="mt-1 mb-0 text-danger em"></p>
                                  </div>
                                </div>
                                <div class="col-lg-12">
                                  <div class="form-group">
                                    <label>{{ __('Details') }}</label>
                                    <textarea name="{{ $language->code }}_details" class="form-control" rows="5"
                                      placeholder="{{ __('Enter Details') }}">{{ !empty($vendor_info) ? $vendor_info->details : '' }}</textarea>
                                    <p id="editErr_{{ $language->code }}_details" class="mt-1 mb-0 text-danger em"></p>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      @endforeach
                    </div>
                  </div>

                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
           <button type="button" id="updateBtn" class="btn btn-success">

                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endsection
 @section('script')
    <script>
      // Phone: only digits, max 10
      document.getElementById('phone').addEventListener('input', function () {
          this.value = this.value.replace(/\D/g, '');
          if (this.value.length > 10) {
              this.value = this.value.slice(0, 10);
          }
      });

      // Function to update state and country based on city selection
      function updateVendorLocationFields(cityId, lang) {
          const stateSelect = document.querySelector(`.vendor-state[data-lang="${lang}"]`);
          const stateNameInput = document.querySelector(`.vendor-state-name[data-lang="${lang}"]`);
          const countrySelect = document.querySelector(`.vendor-country[data-lang="${lang}"]`);
          const countryNameInput = document.querySelector(`.vendor-country-name[data-lang="${lang}"]`);
          
          if (!cityId) {
              // Clear state and country if no city is selected
              if (stateSelect) stateSelect.innerHTML = '<option value="">{{ __("Select State") }}</option>';
              if (stateNameInput) stateNameInput.value = '';
              if (countrySelect) countrySelect.innerHTML = '<option value="">{{ __("Select Country") }}</option>';
              if (countryNameInput) countryNameInput.value = '';
              return;
          }
          
          // Fetch city details to get state and country
          fetch(`{{ route('admin.user_management.registered_user.getCityDetails', '') }}/${cityId}`)
              .then(response => response.json())
              .then(data => {
                  // Update state
                  if (stateSelect && stateNameInput) {
                      stateSelect.innerHTML = '<option value="">{{ __("Select State") }}</option>';
                      if (data.state_id) {
                          const option = new Option(data.state_name, data.state_id);
                          option.selected = true;
                          stateSelect.add(option);
                          stateNameInput.value = data.state_name;
                      }
                  }
                  
                  // Update country
                  if (countrySelect && countryNameInput) {
                      countrySelect.innerHTML = '<option value="">{{ __("Select Country") }}</option>';
                      if (data.country_id) {
                          const option = new Option(data.country_name, data.country_id);
                          option.selected = true;
                          countrySelect.add(option);
                          countryNameInput.value = data.country_name;
                      }
                  }
              })
              .catch(error => {
                  console.error('Error fetching location details:', error);
                  if (stateSelect) stateSelect.innerHTML = '<option value="">{{ __("Select State") }}</option>';
                  if (stateNameInput) stateNameInput.value = '';
                  if (countrySelect) countrySelect.innerHTML = '<option value="">{{ __("Select Country") }}</option>';
                  if (countryNameInput) countryNameInput.value = '';
              });
      }

      // City dropdown change event
      document.querySelectorAll('.vendor-city').forEach(function(select) {
          // Initialize with current values if any
          const lang = select.getAttribute('data-lang');
          const cityId = select.value;
          const cityName = select.options[select.selectedIndex]?.text || '';
          
          // Update hidden city name
          const cityNameInput = document.querySelector(`.vendor-city-name[data-lang="${lang}"]`);
          if (cityNameInput) {
              cityNameInput.value = cityName;
          }
          
          // Update state and country dropdowns
          if (cityId) {
              updateVendorLocationFields(cityId, lang);
          }
          
          // Add change event listener
          select.addEventListener('change', function() {
              const lang = this.getAttribute('data-lang');
              const cityId = this.value;
              const cityName = this.options[this.selectedIndex]?.text || '';
              
              // Update hidden city name
              const cityNameInput = document.querySelector(`.vendor-city-name[data-lang="${lang}"]`);
              if (cityNameInput) {
                  cityNameInput.value = cityName;
              }
              
              // Update state and country dropdowns
              updateVendorLocationFields(cityId, lang);
          });
      });
      
      // State dropdown change event
      document.addEventListener('change', function(e) {
          if (e.target && e.target.matches('.vendor-state')) {
              const lang = e.target.getAttribute('data-lang');
              const stateName = e.target.options[e.target.selectedIndex]?.text || '';
              const stateNameInput = document.querySelector(`.vendor-state-name[data-lang="${lang}"]`);
              if (stateNameInput) {
                  stateNameInput.value = stateName;
              }
          }
      });
      
      // Country dropdown change event
      document.addEventListener('change', function(e) {
          if (e.target && e.target.matches('.vendor-country')) {
              const lang = e.target.getAttribute('data-lang');
              const countryName = e.target.options[e.target.selectedIndex]?.text || '';
              const countryNameInput = document.querySelector(`.vendor-country-name[data-lang="${lang}"]`);
              if (countryNameInput) {
                  countryNameInput.value = countryName;
              }
          }
      });

  document.getElementById('updateBtn').addEventListener('click', function (e) {
    e.preventDefault();

    let form = document.getElementById('ajaxEditForm');
    let formData = new FormData(form);

    // Purane errors hatana
    document.querySelectorAll('.em').forEach(el => el.innerHTML = "");

    fetch(form.action, {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {

        // Validation errors
        if (data.errors) {
            Object.keys(data.errors).forEach(key => {
                let errField = document.getElementById("editErr_" + key);
                if (errField) {
                    errField.innerHTML = data.errors[key][0];
                }
            });
        }

        // Success response
        if (data.success) {
            toastr.success('Vendor updated successfully!');
            setTimeout(() => {
                window.location.href = data.redirect_url;
            }, 1000);
        }
    });
});

    </script>
  @endsection
