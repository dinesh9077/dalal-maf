@php
$version = $basicInfo->theme_version;
@endphp
@extends("frontend.layouts.layout-v$version")

@section('pageHeading')
{{ !empty($pageHeading) ? $pageHeading->contact_page_title : __('Contact') }}
@endsection

@section('metaKeywords')
@if (!empty($seoInfo))
{{ $seoInfo->meta_keyword_contact }}
@endif
@endsection

@section('metaDescription')
@if (!empty($seoInfo))
{{ $seoInfo->meta_description_contact }}
@endif
@endsection

@section('content')

<style>
.new-main-navbar {
    background-color: #6c603c;
}

@media(min-width:320px) and (max-width:760px) {
    .new-contect-design-frame-5 {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        margin: 20px 0px;
        gap: 20px;
    }

    .new-contect-design-frame-6 {
        margin-top: 0 !important;
    }
}

@media (min-width: 768px) and (max-width: 991px) {
    .new-contect-design-frame-5 {
        display: flex;
        flex-direction: row;
        align-items: flex-start;
        margin: 20px 0px;
        gap: 50px;
    }

    .new-contect-design-frame-6 {
        margin-top: 0 !important;
    }
}
</style>

<!--====================================================-->
<!--============== Start Contact Section ===============-->
<!--====================================================-->
<!-- <div class="contact-area ptb-100">
    <div class="container">
        <div class="row justify-content-center">
            @if (!empty($info->contact_number))
            <div class="col-lg-4 col-md-6">
                <div class="card mb-30 color-1" data-aos="fade-up" data-aos-delay="100">
                    <div class="icon">
                        <i class="fal fa-phone-plus"></i>
                    </div>
                    <div class="card-text">

                        <p><a href="tel:{{ $info->contact_number }}">{{ $info->contact_number }}</a></p>

                    </div>
                </div>
            </div>
            @endif
            @if (!empty($info->address))
            <div class="col-lg-4 col-md-6">
                <div class="card mb-30 color-2" data-aos="fade-up" data-aos-delay="200">
                    <div class="icon">
                        <i class="fal fa-envelope"></i>
                    </div>
                    <div class="card-text">

                        <p><a href="javascript:void(0)">{{ $info->address }}</a></p>

                    </div>
                </div>
            </div>
            @endif
            @if (!empty($info->email_address))
            <div class="col-lg-4 col-md-6">
                <div class="card mb-30 color-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="icon">
                        <i class="fal fa-map-marker-alt"></i>
                    </div>
                    <div class="card-text">

                        <p><a href="mailTo:{{ $info->email_address }}">{{ $info->email_address }}</a></p>

                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="pb-70"></div>

        <div class="row gx-xl-5">
            <div class="col-lg-6 mb-30" data-aos="fade-left">
                @if (!empty($info->latitude) && !empty($info->longitude))
                <iframe width="100%" height="450" frameborder="0" scrolling="no" marginheight="0"
                    marginwidth="0"
                    src="//maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q={{ $info->latitude }},%20{{ $info->longitude }}+({{ $websiteInfo->website_title }})&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe>
                @endif
            </div>
            <div class="col-lg-6 mb-30 order-lg-first" data-aos="fade-right">
                @if (Session::has('success'))
                <div class="alert alert-success">{{ __(Session::get('success')) }}</div>
                @endif
                @if (Session::has('error'))
                <div class="alert alert-success">{{ __(Session::get('error')) }}</div>
                @endif
                <form id="contactForm" action="{{ route('contact.send_mail') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-20">
                                <input type="text" name="name" class="form-control" id="name"
                                    placeholder="{{ __('Enter Your Full Name') }}" />
                                @error('name')
                                <div class="help-block with-errors text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-20">
                                <input type="email" name="email" class="form-control" id="email" required
                                    data-error="Enter your email" placeholder="{{ __('Enter Your Email') }}" />
                                @error('email')
                                <div class="help-block with-errors text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-20">
                                <input type="text" name="subject" class="form-control" id="" required
                                    placeholder="{{ __('Enter Email Subject') }}" />
                                @error('subject')
                                <div class="help-block with-errors text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group mb-20">
                                <textarea name="message" id="message" class="form-control" cols="30" rows="8" required
                                    placeholder="{{ __('Write Your Message') }}"></textarea>
                                @error('message')
                                <div class="help-block with-errors text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @if ($info->google_recaptcha_status == 1)
                        <div class="col-md-12">
                            <div class="form-group mb-20">
                                {!! NoCaptcha::renderJs() !!}
                                {!! NoCaptcha::display() !!}
                                @error('g-recaptcha-response')
                                <div class="help-block with-errors text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif

                        <div class="col-md-12">
                            <button type="submit" class="btn btn-lg btn-primary"
                                title="{{ __('Send message') }}">{{ __('Send') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="pb-70"></div>
    </div>

    @if (!empty(showAd(3)))
    <div class="text-center">
        {!! showAd(3) !!}
    </div>
    @endif
</div> -->


<div class="contact-area  pt-60 pb-60" style="margin-top: 100px;">

    <a href="https://wa.me/9925133440" target="_blank">
        <div class="whatsapp-btn" data-aos="fade-up">
            <img src="{{ asset('assets/front/images/new-images/whatsapp.png') }}" alt="WhatsApp">
        </div>
    </a>


    <!-- <div class="container">
        <div class="new-contect-design new-contect-width" data-model-id="308:2305-frame" data-aos="fade-up">
            <div class="new-contect-design-group">
                <div class="new-contect-design-overlap-group">
                    <div class="row gx-xl-5">          
                        <div class="col-lg-6 mb-30 order-lg-first" >
                            {{-- Success / Error Messages --}}
                            @if (Session::has('success'))
                            <div class="alert alert-success">{{ __(Session::get('success')) }}</div>
                            @endif
                            @if (Session::has('error'))
                            <div class="alert alert-danger">{{ __(Session::get('error')) }}</div>
                            @endif

                            {{-- Contact Form --}}
                            <form id="contactForm" action="{{ route('contact.send_mail') }}" method="post">
                                @csrf

                                <div class="new-contect-design-frame-wrapper">
                                    <div class="new-contect-design-frame">

                                        {{-- Heading --}}
                                        <div class="new-contect-design-div">
                                            <div class="new-contect-design-text-wrapper">Get In Touch</div>
                                            <p class="new-contect-design-lorem">
                                               We’d love to hear from you. Reach out with questions, ideas, or collaboration opportunities today.
                                            </p>
                                        </div>

                                        {{-- Name --}}
                                        <div class="new-contect-design-frame-2">
                                            <label for="" style="color: black;">{{ __('Full Name') }}</label>
                                            <input class="new-contect-design-input" name="name" id="name" type="text"
                                                value="{{ old('name') }}" required />
                                            @error('name')
                                            <div class="help-block with-errors text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Email --}}
                                        <div class="new-contect-design-frame-2">
                                            <label for="" style="color: black;">{{ __('Email') }}</label>
                                            <input class="new-contect-design-input" name="email" id="email" type="email"
                                                value="{{ old('email') }}" required />
                                            @error('email')
                                            <div class="help-block with-errors text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                       
                                        {{-- Message --}}
                                        <div class="new-contect-design-frame-2">
                                             <label for="" style="color: black;">{{ __('Message') }}</label>
                                            <textarea class="new-contect-design-input" name="message" id="message" style="min-height: 70px !important;"
                                                required>{{ old('message') }}</textarea>
                                            @error('message')
                                            <div class="help-block with-errors text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Google reCAPTCHA --}}
                                        @if ($info->google_recaptcha_status == 1)
                                        <div class="new-contect-design-frame-2">
                                            {!! NoCaptcha::renderJs() !!}
                                            {!! NoCaptcha::display() !!}
                                            @error('g-recaptcha-response')
                                            <div class="help-block with-errors text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        @endif

                                        {{-- Submit Button --}}
                                        <div class="new-contect-design-send-wrapper">
                                            <button type="submit" class="new-contect-design-send" title="{{ __('Send message') }}">
                                                {{ __('Send') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="new-contect-design-div-wrapper">
                        <div class="new-contect-design-frame-3" style="text-align: left;">
                            <div class="new-contect-design-frame-4">
                                <div class="new-contect-design-connect">CONNECT WITH US</div>
                                <div class="new-contect-design-frame-5">

                                    {{-- Contact Number --}}
                                    @if (!empty($info->contact_number))
                                    <div class="new-contect-design-frame-6 d-flex" style="gap : 20px; margin-top : 87px;">
                                        <img class="new-contect-design-img" src="{{ asset('assets/front/images/new-images/telephone-call.png') }}" alt="Call Icon" />
                                        <div class="new-contect-design-frame-7">
                                            <div class="new-contect-design-text-2">CALL US</div>
                                            <div class="new-contect-design-text-4">
                                                <a href="tel:{{ $info->contact_number }}">{{ $info->contact_number }}</a>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    {{-- Address --}}
                     

                                    {{-- Email --}}
                                    @if (!empty($info->email_address))
                                    <div class="new-contect-design-frame-6 d-flex" style="gap : 20px; margin-top : 79px;">
                                        <img class="new-contect-design-img" src="{{ asset('assets/front/images/new-images/email.png') }}" alt="Email Icon" />
                                        <div class="new-contect-design-frame-7">
                                            <div class="new-contect-design-text-2">EMAIL</div>
                                            <div class="new-contect-design-text-4">
                                                <a href="mailto:{{ $info->email_address }}">{{ $info->email_address }}</a>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                  

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div> -->



    <section class="layout-pt-md layout-pb-lg">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="row x-gap-80 y-gap-20 justify-between">
                        <div class="col-12">
                            <!-- <div class="text-30 sm:text-24 fw-600">Contact Us</div> -->
                            <span class="sec-subtitle style-2 text-25 sm:text-24 fw-600">Get In Touch</span>
                        </div>

                        <div class="new-contect-design-div">
                            <p class="new-contect-design-lorem">
                                We’d love to hear from you. Reach out with questions, ideas, or collaboration
                                opportunities today.
                                We’d love to hear from you. Reach out with questions, ideas, or collaboration
                                opportunities today.
                            </p>
                        </div>

                        <div class="new-contect-design-frame-5">


                            @if (!empty($info->contact_number))
                            <div class="new-contect-design-frame-6 d-flex" style="gap : 20px; margin-top : 70px;">
                                <img class="new-contect-design-img"
                                    src="{{ asset('assets/front/images/acrs-imag/phone-call.png') }}" alt="Call Icon" />
                                <div class="new-contect-design-frame-7">
                                    <div class="new-contect-design-text-2">CALL US</div>
                                    <div class="new-contect-design-text-4">
                                        <a href="tel:{{ $info->contact_number }}">{{ $info->contact_number }}</a>
                                    </div>
                                </div>
                            </div>
                            @endif


                            @if (!empty($info->email_address))
                            <div class="new-contect-design-frame-6 d-flex" style="gap : 20px; margin-top : 60px;">
                                <img class="new-contect-design-img"
                                    src="{{ asset('assets/front/images/acrs-imag/email (1).png') }}" alt="Email Icon" />
                                <div class="new-contect-design-frame-7">
                                    <div class="new-contect-design-text-2">EMAIL</div>
                                    <div class="new-contect-design-text-4">
                                        <a href="mailto:{{ $info->email_address }}">{{ $info->email_address }}</a>
                                    </div>
                                </div>
                            </div>
                            @endif


                        </div>

                    </div>
                </div>

                <div class="col-lg-6 ">
                    <span class="sec-subtitle style-2 text-25 sm:text-24 fw-600 contact-pad"> Send a message</span>


                    {{-- Success / Error Messages --}}
                    @if (Session::has('success'))
                    <div class="alert alert-success">{{ __(Session::get('success')) }}</div>
                    @endif
                    @if (Session::has('error'))
                    <div class="alert alert-danger">{{ __(Session::get('error')) }}</div>
                    @endif

                    {{-- Contact Form --}}
                    <form id="contactForm" action="{{ route('contact.send_mail') }}" method="post">
                        @csrf

                        <div class="new-contect-design-frame-wrapper">
                            <div class="new-contect-design-frame">

                                {{-- Heading --}}


                                {{-- Name --}}
                                <div class="new-contect-design-frame-2">
                                    <label for=""
                                        style="color: black; margin-bottom : 5px;">{{ __('Full Name') }}</label>
                                    <input class="new-contect-design-input" name="name" id="name" type="text"
                                        value="{{ old('name') }}" required />
                                    @error('name')
                                    <div class="help-block with-errors text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div class="new-contect-design-frame-2">
                                    <label for="" style="color: black; margin-bottom : 5px;">{{ __('Email') }}</label>
                                    <input class="new-contect-design-input" name="email" id="email" type="email"
                                        value="{{ old('email') }}" required />
                                    @error('email')
                                    <div class="help-block with-errors text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Subject --}}
                                <!-- <div class="new-contect-design-frame-2">
                                                <input class="new-contect-design-input" name="subject" type="text"
                                                    placeholder="{{ __('Enter Email Subject') }}" value="{{ old('subject') }}" required />
                                                @error('subject')
                                                <div class="help-block with-errors text-danger">{{ $message }}</div>
                                                @enderror
                                            </div> -->

                                {{-- Message --}}
                                <div class="new-contect-design-frame-2">
                                    <label for="" style="color: black; margin-bottom : 5px;">{{ __('Message') }}</label>
                                    <textarea class="new-contect-design-input" name="message" id="message"
                                        style="min-height: 70px !important;" required>{{ old('message') }}</textarea>
                                    @error('message')
                                    <div class="help-block with-errors text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Google reCAPTCHA --}}
                                @if ($info->google_recaptcha_status == 1)
                                <div class="new-contect-design-frame-2">
                                    {!! NoCaptcha::renderJs() !!}
                                    {!! NoCaptcha::display() !!}
                                    @error('g-recaptcha-response')
                                    <div class="help-block with-errors text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                @endif

                                {{-- Submit Button --}}
                                <div class="new-contect-design-send-wrapper">
                                    <button type="submit" class="new-contect-design-send"
                                        title="{{ __('Send message') }}">
                                        {{ __('Send') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>

            </div>

        </div>
    </section>



</div>



<!--============ End Contact Section =============-->
@endsection