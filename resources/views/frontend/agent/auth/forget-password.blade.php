
@php
     $version = $basicInfo->theme_version;
@endphp
@extends("frontend.layouts.layout-v$version")
@section('pageHeading')
    {{ !empty($pageHeading) ? $pageHeading->agent_forget_password_page_title : __('Forget Password') }}
@endsection
@section('metaKeywords')
    @if (!empty($seoInfo))
        {{ $seoInfo->meta_keywords_agent_forget_password }}
    @endif
@endsection

@section('metaDescription')
    @if (!empty($seoInfo))
        {{ $seoInfo->meta_descriptions_agent_forget_password }}
    @endif
@endsection

@section('content')
  
    <!-- Authentication-area start -->
     <div class="authentication-area ptb-100">
    <div class="container">

        <div class="login-container">
            <!-- Left Login Form -->
            <div class="login-left ">
                <div class="new-title-LRP" style="margin : 100px auto;">
                  @if (Session::has('success'))
                    <div class="alert alert-success">{{ __(Session::get('success')) }}</div>
                @endif
                @if (Session::has('error'))
                    <div class="alert alert-danger">{{ __(Session::get('error')) }}</div>
                @endif
                    <form action="{{ route('agent.forget.mail') }}" method="POST">
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

    <!-- <div class="authentication-area ptb-100">
        <div class="container">
            <div class="auth-form border radius-md">
                @if (Session::has('success'))
                    <div class="alert alert-success">{{ __(Session::get('success')) }}</div>
                @endif
                @if (Session::has('error'))
                    <div class="alert alert-danger">{{ __(Session::get('error')) }}</div>
                @endif
                <form action="{{ route('agent.forget.mail') }}" method="POST">
                    @csrf
                    <div class="title">
                        <h4 class="mb-20">{{ __('Forget Password') }}</h4>
                    </div>
                    <div class="form-group mb-30">
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
                    <button type="submit" class="btn btn-lg btn-primary radius-md w-100"> {{ __('Submit') }} </button>
                </form>
            </div>
        </div>
    </div> -->




    
    <!-- Authentication-area end -->
@endsection
