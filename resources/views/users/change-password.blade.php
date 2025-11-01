@extends('users.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Change Password') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('user.dashboard') }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Password Settings') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-title">{{ __('Change Password') }}</div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3">
                            <form action="{{ route('user.update_password') }}" method="POST">
                               @csrf
                              <div class="form-group mb-20 ">
                                      <img src="{{ asset('assets/front/images/new-images/password.png') }}" alt="user">
                                  <input type="password" class="form-control"
                                      placeholder="{{ __('Current Password') . '*' }}"  name="current_password" required>
                                  @error('current_password')
                                      <p class="text-danger mt-1">{{ $message }}</p>
                                  @enderror
                              </div>
                              <div class="form-group mb-20">
                                      <img src="{{ asset('assets/front/images/new-images/password.png') }}" alt="user">

                                  <input type="password" class="form-control" placeholder="{{ __('New Password') }}"
                                      name="new_password" required>
                                  @error('new_password')
                                      <p class="text-danger mt-1">{{ $message }}</p>
                                  @enderror
                              </div>
                              <div class="form-group mb-20">
                                      <img src="{{ asset('assets/front/images/new-images/password.png') }}" alt="user">

                                  <input type="password" class="form-control"
                                      placeholder="{{ __('Confirm Password') }}" name="new_password_confirmation"
                                      required>
                              </div>
                              <div class="mb-15">
                                  <div class="form-button">
                                      <button type="submit"
                                          class="btn btn-lg btn-primary">{{ __('Submit') }}</button>
                                  </div>
                              </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- <div class="card-footer">
                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="submit" id="updateBtn" class="btn btn-success">
                                {{ __('Update') }}
                            </button>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
@endsection
