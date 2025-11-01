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
                @if (Session::has('warning'))
                    <div class="alert alert-success">{{ Session::get('warning') }}</div>
                @endif
                    <form action="{{ route('user.reset_password_submit') }}" method="POST">
                        @csrf
                        <div class="title">
                             <h4 class="mb-20">{{ __('Reset Password') }}</h4>
                        </div>
                        <div class="form-group mb-30 input-box">
                            <img src="{{ asset('assets/front/images/new-images/password.png') }}" alt="user">
                             <input type="password" class="form-control" name="new_password"
                            placeholder="{{ __('New Password') }}">
                        @error('new_password')
                            <p class="text-danger mt-2">{{ $message }}</p>
                        @enderror
                        </div>

                        <div class="form-group mb-30 input-box">
                            <img src="{{ asset('assets/front/images/new-images/password.png') }}" alt="user">
                             <input type="password" class="form-control" name="new_password_confirmation"
                            placeholder="{{ __('Confirm Password') }}">
                        @error('new_password_confirmation')
                            <p class="text-danger mt-2">{{ $message }}</p>
                        @enderror
                        </div>
                       



                        <button type="submit" class="btn btn-lg btn-primary radius-md w-100"> {{ __('Reset Password') }} </button>


                      

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
                @if (Session::has('warning'))
                    <div class="alert alert-success">{{ Session::get('warning') }}</div>
                @endif
                <form action="{{ route('user.reset_password_submit') }}" method="POST">
                    @csrf
                    <div class="title">
                        <h4 class="mb-20">{{ __('Reset Password') }}</h4>
                    </div>
                    <div class="form-group mb-30">
                        <input type="password" class="form-control" name="new_password"
                            placeholder="{{ __('New Password') }}">
                        @error('new_password')
                            <p class="text-danger mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group mb-30">
                        <input type="password" class="form-control" name="new_password_confirmation"
                            placeholder="{{ __('Confirm Password') }}">
                        @error('new_password_confirmation')
                            <p class="text-danger mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit"
                        class="btn btn-lg btn-primary radius-md w-100">{{ __('Reset Password') }}</button>
                </form>
        </div> -->
        </div>
    </div>
    <!-- Authentication-area end -->
@endsection
