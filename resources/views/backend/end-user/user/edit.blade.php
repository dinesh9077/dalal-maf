@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit User') }}</h4>
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
        <a href="#">{{ __('Users Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="{{ route('admin.user_management.registered_users') }}">{{ __('Registered Users') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit User') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-8">
              <div class="card-title">{{ __('Edit User') }}</div>
            </div>
            <div class="col-lg-4">
              <a class="btn btn-info btn-sm float-right d-inline-block"
                href="{{ route('admin.user_management.registered_users') }}">
                <span class="btn-label">
                  <i class="fas fa-backward"></i>
                </span>
                {{ __('Back') }}
              </a>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 mx-auto">
              <form id="ajaxEditForm"
                action="{{ route('admin.user_management.registered_user.update', ['id' => $user->id]) }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label for="">{{ __('Photo') }}</label>
                      <br>
                      <div class="thumb-preview">
                        @if ($user->image != null)
                          <img src="{{ asset('assets/img/users/' . $user->image) }}" alt="..." class="uploaded-img">
                        @else
                          <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                        @endif

                      </div>

                      <div class="mt-3">
                        <div role="button" class="btn btn-primary btn-sm upload-btn">
                          {{ __('Choose Photo') }}
                          <input type="file" class="img-input" name="image">
                        </div>
                        <p id="editErr_image" class="mt-1 mb-0 text-danger em"></p>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-8">
                    <div class="form-group">
                      <label>{{ __('Name*') }}</label>
                      <input type="text" value="{{ $user->name }}" class="form-control" name="name">
                      <p id="editErr_name" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Username*') }}</label>
                      <input type="text" value="{{ $user->username }}" class="form-control" name="username">
                      <p id="editErr_username" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Email*') }}</label>
                      <input type="text" value="{{ $user->email }}" class="form-control" name="email">
                      <p id="editErr_email" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Phone*') }}</label>
                      <input type="text" value="{{ $user->phone }}" class="form-control" name="phone" max="10" pattern="\d{10}" id="phone">
                      <p id="editErr_phone" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('City*') }}</label>
                      <select class="form-control" id="city_id" name="city_id" required>
                        <option value="">{{ __('Select City') }}</option>
                        @foreach($cities as $city)
                          <option value="{{ $city->id }}" {{ $user->city == $city->name ? 'selected' : '' }}>{{ $city->name }}</option>
                        @endforeach
                      </select>
                      <input type="hidden" id="city_name" name="city" value="{{ $user->city }}">
                      <p id="editErr_city" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('State*') }}</label>
                      <select class="form-control" id="state_id" name="state_id" required>
                        <option value="">{{ __('Select State') }}</option>
                        @if($user->state_id)
                          <option value="{{ $user->state_id }}" selected>{{ $user->state }}</option>
                        @endif
                      </select>
                      <input type="hidden" id="state" name="state" value="{{ $user->state }}">
                      <p id="editErr_state" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Country*') }}</label>
                      <select class="form-control" id="country_id" name="country_id" required>
                        <option value="">{{ __('Select Country') }}</option>
                        @if($user->country_id)
                          <option value="{{ $user->country_id }}" selected>{{ $user->country }}</option>
                        @endif
                      </select>
                      <input type="hidden" id="country" name="country" value="{{ $user->country }}">
                      <p id="editErr_country" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Zip Code*') }}</label>
                      <input type="text" value="{{ $user->zip_code }}" class="form-control" name="zip_code">
                      <p id="editErr_zip_code" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label>{{ __('Address*') }}</label>
                      <textarea name="address" id="" class="form-control" name="address" rows="1">{{ $user->address }}</textarea>
                      <p id="editErr_address" class="mt-1 mb-0 text-danger em"></p>
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
      // Phone field: allow only 10 digits
      document.getElementById('phone').addEventListener('input', function (e) {
          this.value = this.value.replace(/\D/g, '');
          if (this.value.length > 10) {
              this.value = this.value.slice(0, 10);
          }
      });

      // Function to update state and country based on city selection
      function updateLocationFields(cityId) {
          if (!cityId) {
              document.getElementById('state').value = '';
              document.getElementById('state_id').innerHTML = '<option value="">{{ __("Select State") }}</option>';
              document.getElementById('country').value = '';
              document.getElementById('country_id').innerHTML = '<option value="">{{ __("Select Country") }}</option>';
              return;
          }
          
          // Fetch city details to get state and country
          fetch(`{{ route('admin.user_management.registered_user.getCityDetails', '') }}/${cityId}`)
              .then(response => response.json())
              .then(data => {
                  // Update state
                  const stateSelect = document.getElementById('state_id');
                  stateSelect.innerHTML = '<option value="">{{ __("Select State") }}</option>';
                  if (data.state_id) {
                      const option = new Option(data.state_name, data.state_id);
                      option.selected = true;
                      stateSelect.add(option);
                      document.getElementById('state').value = data.state_name;
                  }
                  
                  // Update country
                  const countrySelect = document.getElementById('country_id');
                  countrySelect.innerHTML = '<option value="">{{ __("Select Country") }}</option>';
                  if (data.country_id) {
                      const option = new Option(data.country_name, data.country_id);
                      option.selected = true;
                      countrySelect.add(option);
                      document.getElementById('country').value = data.country_name;
                  }
              })
              .catch(error => {
                  console.error('Error fetching location details:', error);
                  document.getElementById('state').value = '';
                  document.getElementById('state_id').innerHTML = '<option value="">{{ __("Select State") }}</option>';
                  document.getElementById('country').value = '';
                  document.getElementById('country_id').innerHTML = '<option value="">{{ __("Select Country") }}</option>';
              });
      }

      // City change: update hidden city name and fetch state/country
      document.getElementById('city_id').addEventListener('change', function () {
          const cityId = this.value;
          const cityName = this.options[this.selectedIndex].text;

          document.getElementById('city_name').value = cityName;
          
          if (cityId) {
              // Fetch state and country for selected city
              fetch(`{{ route('admin.user_management.registered_user.getCityDetails', '') }}/${cityId}`)
                  .then(response => response.json())
                  .then(data => {
                      // Update state
                      const stateSelect = document.getElementById('state_id');
                      stateSelect.innerHTML = '';
                      if (data.state_id) {
                          const stateOption = new Option(data.state_name, data.state_id);
                          stateOption.selected = true;
                          stateSelect.add(stateOption);
                          document.getElementById('state').value = data.state_name;
                      }
                      
                      // Update country
                      const countrySelect = document.getElementById('country_id');
                      countrySelect.innerHTML = '';
                      if (data.country_id) {
                          const countryOption = new Option(data.country_name, data.country_id);
                          countryOption.selected = true;
                          countrySelect.add(countryOption);
                          document.getElementById('country').value = data.country_name;
                      }
                  })
                  .catch(error => {
                      console.error('Error fetching city details:', error);
                      document.getElementById('state').value = '';
                      document.getElementById('state_id').innerHTML = '<option value="">{{ __("Select State") }}</option>';
                      document.getElementById('country').value = '';
                      document.getElementById('country_id').innerHTML = '<option value="">{{ __("Select Country") }}</option>';
                  });
          } else {
              document.getElementById('state').value = '';
              document.getElementById('state_id').innerHTML = '<option value="">{{ __("Select State") }}</option>';
              document.getElementById('country').value = '';
              document.getElementById('country_id').innerHTML = '<option value="">{{ __("Select Country") }}</option>';
          }
      });

      // State change: update hidden field
      document.getElementById('state_id').addEventListener('change', function() {
          const selectedOption = this.options[this.selectedIndex];
          document.getElementById('state').value = selectedOption.text;
      });

      // Country change: update hidden field
      document.getElementById('country_id').addEventListener('change', function() {
          const selectedOption = this.options[this.selectedIndex];
          document.getElementById('country').value = selectedOption.text;
      });

      // On page load: initialize dropdowns
      document.addEventListener('DOMContentLoaded', function () {
          // If city is selected, update state and country
          const citySelect = document.getElementById('city_id');
          if (citySelect && citySelect.value) {
              updateLocationFields(citySelect.value);
          }
          
          // If no city is selected but we have state and country, set them
          @if($user->state_id && $user->country_id)
              const stateSelect = document.getElementById('state_id');
              if (stateSelect) {
                  stateSelect.innerHTML = `<option value="{{ $user->state_id }}" selected>{{ $user->state }}</option>`;
                  document.getElementById('state').value = '{{ $user->state }}';
              }
              
              const countrySelect = document.getElementById('country_id');
              if (countrySelect) {
                  countrySelect.innerHTML = `<option value="{{ $user->country_id }}" selected>{{ $user->country }}</option>`;
                  document.getElementById('country').value = '{{ $user->country }}';
              }
          @endif
      });

      // Ensure Update button submits the form
     document.getElementById('updateBtn').addEventListener('click', function (e) {
    e.preventDefault();

    let form = document.getElementById('ajaxEditForm');
    let formData = new FormData(form);

    // पुराने errors हटाओ
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
            toastr.success('User created successfully!');
            setTimeout(() => {
                window.location.href = data.redirect_url;
            }, 1000);
        }
    });
});

    </script>
  @endsection
