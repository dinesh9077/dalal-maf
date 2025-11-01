@php
    $version = $basicInfo->theme_version;
@endphp
@extends("frontend.layouts.layout-v$version")
@section('pageHeading')
    {{ !empty($pageHeading) ? $pageHeading->agent_login_page_title : __('Agent Login') }}
@endsection
@section('metaKeywords')
    @if (!empty($seoInfo))
        {{ $seoInfo->meta_keyword_agent_login }}
    @endif
@endsection

@section('metaDescription')
    @if (!empty($seoInfo))
        {{ $seoInfo->meta_description_agent_login }}
    @endif
@endsection

@section('content')

    <!-- Authentication-area start -->
    <!-- <div class="authentication-area ptb-100">
        <div class="container">
            <div class="auth-form border radius-md">
                @if (Session::has('success'))
                    <div class="alert alert-success">{{ __(Session::get('success')) }}</div>
                @endif
                @if (Session::has('error'))
                    <div class="alert alert-danger">{{ __(Session::get('error')) }}</div>
                @endif
                <form action="{{ route('agent.login_submit') }}" method="POST">
                    @csrf
                    <div class="title">
                        <h4 class="mb-20">{{ __('Login') }}</h4>
                    </div>
                    <div class="form-group mb-30">
                        <input type="text"  class="form-control" name="username" placeholder="{{ __('Username') }}"
                            required>
                        @error('username')
                            <p class="text-danger mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group mb-30">
                        <input type="password" class="form-control" name="password" placeholder="{{ __('Password') }}"
                            required>
                        @error('password')
                            <p class="text-danger mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    @if ($bs->google_recaptcha_status == 1)
                        <div class="form-group mb-30">
                            {!! NoCaptcha::renderJs() !!}
                            {!! NoCaptcha::display() !!}

                            @error('g-recaptcha-response')
                                <p class="mt-1 text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif
                    <div class="row align-items-center mb-20">
                        <div class="col-4 col-xs-12">
                            <div class="link">
                                <a href="{{ route('agent.forget.password') }}">{{ __('Forgot password') . '?' }}</a>
                            </div>
                        </div>
                      
                    </div>
                    <button type="submit" class="btn btn-lg btn-primary radius-md w-100"> {{ __('Login') }} </button>
                </form>
            </div>
        </div>
    </div> -->
    <div class="authentication-area ptb-100">
        <div class="container">
            <div class="login-container">
                <!-- Left Login Form -->
                <div class="login-left ">
                    <div class="new-title-LRP">
                    @if (Session::has('success'))
                        <div class="alert alert-success">{{ __(Session::get('success')) }}</div>
                    @endif
                    @if (Session::has('error'))
                        <div class="alert alert-danger">{{ __(Session::get('error')) }}</div>
                    @endif
                        <form action="{{ route('agent.login_submit') }}" method="POST">
                            @csrf
                            <div class="title">
                                <h4 class="mb-20">{{ __('Login') }}</h4>
                            </div>
                            <div class="form-group mb-30 input-box">
                                <img src="{{ asset('assets/front/images/new-images/user.png') }}" alt="user">
                                <input type="text"  class="form-control" name="username" placeholder="{{ __('Username') }}"
                                required>
                            @error('username')
                                <p class="text-danger mt-2">{{ $message }}</p>
                            @enderror
                            </div>

                            <div class="form-group mb-30 input-box">
                                <img src="{{ asset('assets/front/images/new-images/password.png') }}" alt="user">
                            <input type="password" class="form-control" name="password" placeholder="{{ __('Password') }}"
                                required>
                            @error('password')
                                <p class="text-danger mt-2">{{ $message }}</p>
                            @enderror
                            </div>
                        @if ($bs->google_recaptcha_status == 1)
                            <div class="form-group mb-30">
                                {!! NoCaptcha::renderJs() !!}
                                {!! NoCaptcha::display() !!}

                                @error('g-recaptcha-response')
                                    <p class="mt-1 text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                            <div class="mb-10 d-flex flex-column flex-sm-row justify-content-between align-items-center">
                                
                                <div class="link">
                                    <a href="{{ route('agent.forget.password') }}">{{ __('Forgot password') . '?' }}</a>
                                </div>

                            </div>
                            <button type="submit" class="btn btn-lg btn-primary radius-md w-100"> {{ __('Login') }} </button>
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
        
        </div>
    </div>
    <!-- Authentication-area end -->
@endsection
