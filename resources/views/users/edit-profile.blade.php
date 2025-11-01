@extends('users.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Edit Profile') }}</h4>
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
                <a href="#">{{ __('Edit Profile') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-title">{{ __('Edit Profile') }}</div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <div class="alert alert-danger pb-1 dis-none" id="carErrors">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <ul></ul>
                            </div>
                            <form action="{{ route('user.update_profile') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="">{{ __('Photo') }}</label>
                                            <br>
                                            <div class="thumb-preview">
                                                  @if (Auth::guard('web')->user()->image != null)
                                                    <img src="{{ asset('assets/img/users/' . Auth::guard('web')->user()->image) }}"
                                                        alt="..." class="uploaded-img">
                                                @else
                                                    <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..."
                                                        class="uploaded-img">
                                                @endif

                                            </div>

                                            <div class="mt-3">
                                                <div role="button" class="btn btn-primary btn-sm upload-btn">
                                                    {{ __('Choose Photo') }}
                                                    <input type="file" class="img-input" name="image">
                                                </div>
                                                <p id="editErr_photo" class="mt-1 mb-0 text-danger em"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group mb-30">
                                            <label for="" class="mb-1">{{ __('Name') . ' *' }}</label>
                                            <input type="text" class="form-control new-eds-progile"
                                                value="{{ Auth::guard('web')->user()->name }}" placeholder="{{ __('Name') }}"
                                                name="name">
                                            @error('name')
                                            <p class="text-danger mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group mb-30">
                                            <label for="" class="mb-1">{{ __('Username') . ' *' }}</label>
                                            <input type="text" class="form-control new-eds-progile" placeholder="{{ __('Username') }}"
                                                name="username" value="{{ Auth::guard('web')->user()->username }}">
                                            @error('username')
                                            <p class="text-danger mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group mb-30">
                                            <label for="" class="mb-1">{{ __('Email') . ' *' }}</label>
                                            <input type="email" class="form-control new-eds-progile" placeholder="{{ __('Email') }}"
                                                name="email" value="{{ Auth::guard('web')->user()->email }}">
                                            @error('email')
                                            <p class="text-danger mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group mb-30">
                                            <label for="" class="mb-1">{{ __('Phone') . ' *' }}</label>
                                            <input type="text" class="form-control new-eds-progile" placeholder="{{ __('Phone') }}"
                                                name="phone" value="{{ Auth::guard('web')->user()->phone }}">
                                            @error('phone')
                                            <p class="text-danger mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group mb-30">
                                            <label for="" class="mb-1">{{ __('Country') . ' *' }}</label>
                                            <input type="text" class="form-control new-eds-progile" placeholder="{{ __('Country') }}"
                                                name="country" value="{{ Auth::guard('web')->user()->country }}">
                                            @error('country')
                                            <p class="text-danger mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group mb-30">
                                            <label for="" class="mb-1">{{ __('City'). ' *' }}</label>
                                            <input type="text" class="form-control new-eds-progile" placeholder="{{ __('City') }}"
                                                name="city" value="{{ Auth::guard('web')->user()->city }}">
                                            @error('city')
                                            <p class="text-danger mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group mb-30">
                                            <label for="" class="mb-1">{{ __('State') . ' *'}}</label>
                                            <input type="text" class="form-control new-eds-progile" placeholder="{{ __('State') }}"
                                                name="state" value="{{ Auth::guard('web')->user()->state }}">
                                            @error('state')
                                            <p class="text-danger mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group mb-30">
                                            <label for="" class="mb-1">{{ __('Zip Code'). ' *' }}</label>
                                            <input type="text" class="form-control new-eds-progile" placeholder="{{ __('Zip Code') }}"
                                                name="zip_code" value="{{ Auth::guard('web')->user()->zip_code }}">
                                            @error('zip_code')
                                            <p class="text-danger mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-group mb-30">
                                            <label for="" class="mb-1">{{ __('Address'). ' *' }}</label>
                                            <textarea name="address" id="" class="form-control new-eds-progile" rows="3" placeholder="{{ __('Address') }}" style="min-height: 55px;">{{ Auth::guard('web')->user()->address }}</textarea>
                                            @error('address')
                                            <p class="text-danger mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-12 mb-15">
                                        <div class="form-button">
                                            <button type="submit"
                                                class="btn btn-lg btn-primary">{{ __('Update Profile') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- <div class="card-footer">
                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="submit"class="btn btn-lg btn-primary">{{ __('Update Profile') }}</button>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    @endsection
