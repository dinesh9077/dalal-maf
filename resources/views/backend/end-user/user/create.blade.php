@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Add User') }}</h4>
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
        <a href="#">{{ __('Add User') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-8">
              <div class="card-title">{{ __('Add User') }}</div>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-10 mx-auto">
              <form id="ajaxEditForm" action="{{ route('admin.user_management.registered_user.store') }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label for="">{{ __('Photo') . '*' }}</label>
                      <br>
                      <div class="thumb-preview">
                        <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img">

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
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Name*') }}</label>
                      <input type="text" class="form-control" name="name">
                      <p id="editErr_name" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Username*') }}</label>
                      <input type="text" class="form-control" name="username">
                      <p id="editErr_username" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Email*') }}</label>
                      <input type="text" class="form-control" name="email">
                      <p id="editErr_email" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Phone*') }}</label>
                      <input type="text" class="form-control" name="phone" max="10" pattern="\d{10}" id="phone">
                      <p id="editErr_phone" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>

                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('City *') }}</label>
                      <select class="form-control" id="city_id" name="city_id" required>
                        <option value="">{{ __('Select City') }}</option>
                        @foreach($cities as $city)
                          <option value="{{ $city->id }}">{{ $city->name }}</option>
                        @endforeach
                      </select>
                      <input type="hidden" id="city_name" name="city">
                      <p id="editErr_city" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('State *') }}</label>
                      <input type="text" class="form-control" id="state" name="state" readonly>
                      <input type="hidden" id="state_id" name="state_id">
                      <p id="editErr_state" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Country *') }}</label>
                      <input type="text" class="form-control" id="country" name="country" readonly>
                      <input type="hidden" id="country_id" name="country_id">
                      <p id="editErr_country" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Zip Code *') }}</label>
                      <input type="text" class="form-control" name="zip_code">
                      <p id="editErr_zip_code" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Address *') }}</label>
                      <input type="text" class="form-control" name="address">
                      <p id="editErr_address" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>

                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Password') }}</label>
                      <input type="password" class="form-control" name="password">
                      <p id="editErr_password" class="mt-1 mb-0 text-danger em"></p>
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
              <button type="submit" id="updateBtn" class="btn btn-success">
                {{ __('Save') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endsection
  @section('script')
    <script>
      // Handle city selection change
      document.getElementById('city_id').addEventListener('change', function() {
        const cityId = this.value;
        
        if (cityId) {
          // Show loading state
          $('#state_name').val('Loading...');
          $('#country_name').val('Loading...');
          
          // Fetch city details via AJAX
          $.ajax({
            url: '{{ route("admin.user_management.registered_user.getCityDetails", "") }}/' + cityId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
              // Update state and country fields
              $('#state_id').val(response.state_id);
              $('#state_name').val(response.state_name);
              $('#country_id').val(response.country_id);
              $('#country_name').val(response.country_name);
              
              // Clear any previous error messages
              $('#editErr_state').text('');
              $('#editErr_country').text('');
            },
            error: function(xhr) {
              console.error('Error fetching city details:', xhr);
              $('#state_name').val('');
              $('#country_name').val('');
              $('#editErr_state').text('Error loading state information');
              $('#editErr_country').text('Error loading country information');
            }
          });
        } else {
          // Clear fields if no city is selected
          $('#state_id').val('');
          $('#state_name').val('');
          $('#country_id').val('');
          $('#country_name').val('');
        }
      });

      // Phone number validation
      document.getElementById('phone').addEventListener('input', function (e) {
          // Remove any non-digit characters
          this.value = this.value.replace(/\D/g, '');

          // Limit to 10 digits
          if (this.value.length > 10) {
              this.value = this.value.slice(0, 10);
          }
      });

      // City change event
      document.getElementById('city_id').addEventListener('change', function() {
          const cityId = this.value;
          const cityName = this.options[this.selectedIndex].text;
          
          // Set the city name in the hidden input
          document.getElementById('city_name').value = cityName;
          
          if (cityId) {
              // Fetch state and country details for the selected city
              fetch(`/admin/user-management/get-city-details/${cityId}`)
                  .then(response => response.json())
                  .then(data => {
                      // Update state field
                      document.getElementById('state').value = data.state_name;
                      document.getElementById('state_id').value = data.state_id;
                      
                      // Update country field
                      document.getElementById('country').value = data.country_name;
                      document.getElementById('country_id').value = data.country_id;
                  })
                  .catch(error => {
                      console.error('Error:', error);
                      alert('An error occurred while fetching city details.');
                  });
          } else {
              // Clear fields if no city is selected
              document.getElementById('state').value = '';
              document.getElementById('state_id').value = '';
              document.getElementById('country').value = '';
              document.getElementById('country_id').value = '';
          }
      });

      // Trigger change event on page load if a city is already selected
      document.addEventListener('DOMContentLoaded', function() {
          const citySelect = document.getElementById('city_id');
          if (citySelect.value) {
              citySelect.dispatchEvent(new Event('change'));
          }
      });
    </script>
  @endsection
