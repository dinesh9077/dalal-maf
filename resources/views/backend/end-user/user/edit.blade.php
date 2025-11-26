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
                      <input type="text" class="form-control" id="state" name="state" value="{{ $user->state }}" readonly>
                      <input type="hidden" id="state_id" name="state_id">
                      <p id="editErr_state" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Country*') }}</label>
                      <input type="text" class="form-control" id="country" name="country" value="{{ $user->country }}" readonly>
                      <input type="hidden" id="country_id" name="country_id">
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
              <button type="submit" id="updateBtn" class="btn btn-success">
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
                      document.getElementById('state').value = data.state_name;
                      document.getElementById('state_id').value = data.state_id ?? '';
                      document.getElementById('country').value = data.country_name;
                      document.getElementById('country_id').value = data.country_id ?? '';
                  })
                  .catch(error => {
                      console.error('Error fetching city details:', error);
                      document.getElementById('state').value = '';
                      document.getElementById('state_id').value = '';
                      document.getElementById('country').value = '';
                      document.getElementById('country_id').value = '';
                  });
          } else {
              document.getElementById('state').value = '';
              document.getElementById('state_id').value = '';
              document.getElementById('country').value = '';
              document.getElementById('country_id').value = '';
          }
      });

      // On page load: if a city is pre-selected, trigger change to fill state/country
      document.addEventListener('DOMContentLoaded', function () {
          const citySelect = document.getElementById('city_id');
          if (citySelect && citySelect.value) {
              citySelect.dispatchEvent(new Event('change'));
          }
      });

      // Ensure Update button submits the form
      document.getElementById('updateBtn').addEventListener('click', function (e) {
          const form = document.getElementById('ajaxEditForm');
          if (form) {
              form.submit();
          }
      });
    </script>
  @endsection
