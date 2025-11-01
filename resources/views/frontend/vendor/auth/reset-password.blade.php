@php
$version = $basicInfo->theme_version;
@endphp
@extends("frontend.layouts.layout-v$version")
@section('pageHeading')
{{ __('Reset Password') }}
@endsection


@section('content')

<!-- Authentication-area start -->
<div class="authentication-area ptb-100">
    <div class="container">

        <div class="login-container">
            <!-- Left Login Form -->
            <div class="login-left ">
                <div class="new-title-LRP">
                    @if (Session::has('success'))
                    <div class="alert alert-success">{{ Session::get('success') }}</div>
                    @endif
                    @if (Session::has('error'))
                    <div class="alert alert-danger">{{ Session::get('error') }}</div>
                    @endif
                    <form aaction="{{ route('vendor.update-forget-password') }}" method="POST">
                        @csrf
                        <input type="hidden" name="token" value="{{ request()->input('token') }}">
                        <div class="title">
                            <h4 class="mb-20">{{ __('Reset Password') }}</h4>
                        </div>
                        <div class="form-group mb-30 input-box">
                            <img src="{{ asset('assets/front/images/new-images/password.png') }}" alt="user">
                            <input type="password" class="form-control" name="new_password" placeholder="{{ __('Password') }}"
                                required>
                            @error('new_password')
                            <p class="text-danger mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group mb-30 input-box">
                            <img src="{{ asset('assets/front/images/new-images/password.png') }}" alt="user">
                            <input type="password" name="new_password_confirmation"
                                value="{{ old('new_password_confirmation') }}" class="form-control"
                                placeholder="{{ __('Confirm Password') }}" required>
                            @error('new_password_confirmation')
                            <p class="text-danger mt-2">{{ $message }}</p>
                            @enderror
                        </div>




                        <button type="submit" class="btn btn-lg btn-primary radius-md w-100"> {{ __('Submit') }} </button>




                    </form>
                </div>
            </div>

            <!-- Right Side Image + Text -->
            <div class="login-right ">
                <div class="promo-card">
                    <p class="promo-text">
                        Very good works are
                        waiting for you
                        <b>Login Now!!!</b>
                    </p>
                    <img src="{{ asset('assets/front/images/new-images/Woman.png') }}" alt="promo" class="promo-img">
                    <div class="promo-icon">
                        <img src="{{ asset('assets/front/images/new-images/Walt.png') }}" alt="" class="want-img">
                    </div>
                </div>
            </div>

        </div>

        <!-- <div class="auth-form border radius-md">
                @if (Session::has('success'))
                    <div class="alert alert-success">{{ Session::get('success') }}</div>
                @endif
                @if (Session::has('error'))
                    <div class="alert alert-danger">{{ Session::get('error') }}</div>
                @endif
                <form action="{{ route('vendor.update-forget-password') }}" method="POST">
                    @csrf

                    <input type="hidden" name="token" value="{{ request()->input('token') }}">
                    <div class="title">
                        <h4 class="mb-20">{{ __('Reset Password') }}</h4>
                    </div>
                    <div class="form-group mb-30">
                        <input type="password" class="form-control" name="new_password" placeholder="{{ __('Password') }}"
                            required>
                        @error('new_password')
                            <p class="text-danger mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group mb-30">
                        <input type="password" name="new_password_confirmation"
                            value="{{ old('new_password_confirmation') }}" class="form-control"
                            placeholder="{{ __('Confirm Password') }}" required>
                        @error('new_password_confirmation')
                            <p class="text-danger mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-lg btn-primary radius-md w-100"> {{ __('Submit') }} </button>
                </form>
            </div> -->
    </div>
</div>
<!-- Authentication-area end -->
@endsection