@php
$version = $basicInfo->theme_version;
@endphp
@extends("frontend.layouts.layout-v$version")
@section('pageHeading')
{{ !empty($pageHeading) ? $pageHeading->vendor_forget_password_page_title : __('Forget Password') }}
@endsection
@section('metaKeywords')
@if (!empty($seoInfo))
{{ $seoInfo->meta_keywords_vendor_forget_password }}
@endif
@endsection

@section('metaDescription')
@if (!empty($seoInfo))
{{ $seoInfo->meta_descriptions_vendor_forget_password }}
@endif
@endsection

@section('content')

<div class="new-login-page-design-wrapper">
    <!-- Left Side -->
    <div class="new-login-page-design-left col-lg-6">
        <div class="w-100" style="max-width: 450px;">
            <div class="mb-4 text-left">
                <h5 class="welcome-text">Welcome back to Dalal Maaf!</h5>
                <p class="text-muted mt-3" style="    font-size: 13px;">Log in to access your personalized dashboard, manage your listings, and explore properties tailored to your needs. Continue your real estate journey with just one click.</p>
            </div>
            <div class="new-title-LRP">
                                   @if (Session::has('success'))
                    <div class="alert alert-success">{{ __(Session::get('success')) }}</div>
                    @endif
                    @if (Session::has('error'))
                    <div class="alert alert-danger">{{ __(Session::get('error')) }}</div>
                    @endif
                <form action="{{ route('vendor.forget.mail') }}" method="POST">
                       @csrf
                    
                    <label for="#" style="color: black; font-size: 14px;">Email</label>
                    <div class="form-group mb-30 input-box mt-2">
                        <input type="email" class="form-control" name="email" placeholder="{{ __('Email Address') }}"
                                required>
                        
                    </div>
                        @error('email')
                            <p class="text-danger mt-2">{{ $message }}</p>
                            @enderror

                 
                       @if ($bs->google_recaptcha_status == 1)
                        <div class="form-group mb-30">
                            {!! NoCaptcha::renderJs() !!}
                            {!! NoCaptcha::display() !!}

                            @error('g-recaptcha-response')
                            <p class="mt-1 text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        @endif

                   
                    <button type="submit" class="btn btn-lg  w-100 mt-3">  {{ __('Submit') }} </button>
                    
                </form>
            </div>


        </div>
    </div>

    <!-- Right Side -->
    <div class="new-login-page-design-right col-lg-6">
        <div class="w-100" style="max-width: 600px;">
            <img src="{{ asset('assets/front/images/acrs-imag/login-vector2.png') }}" alt="" style="width: 85%;">
            <h2 style="color: black; font-size : 40px;">Find your dream home, smart investment, or perfect rental with ease.</h2>
            <p class="new-login-page-design-testimonial" style="color: black;">Thousands of verified listings and expert insights—your trusted real estate partner.</p>
           
        </div>
    </div>
</div>

<!-- Authentication-area start -->
<!-- <div class="authentication-area ptb-100">
    <div class="container">


        <div class="login-container">
  
            <div class="login-left ">
                <div class="new-title-LRP" style="margin : 100px auto;">
                    @if (Session::has('success'))
                    <div class="alert alert-success">{{ __(Session::get('success')) }}</div>
                    @endif
                    @if (Session::has('error'))
                    <div class="alert alert-danger">{{ __(Session::get('error')) }}</div>
                    @endif
                    <form action="{{ route('vendor.forget.mail') }}" method="POST">
                        @csrf
                        <div class="title">
                            <h4 class="mb-20">{{ __('Forget Password') }}</h4>
                        </div>
                        <div class="form-group mb-30 input-box">
                            <img src="{{ asset('assets/front/images/new-images/mail.png') }}" alt="user">
                            <input type="email" class="form-control" name="email" placeholder="{{ __('Email Address') }}"
                                required>
                            @error('email')
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

                        <button type="submit" class="btn btn-lg  w-100"> {{ __('Submit') }} </button>

                    </form>
                </div>
            </div>

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
</div> -->
<!-- Authentication-area end -->
@endsection