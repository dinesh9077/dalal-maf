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
    background-color: var(--theme-color);
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

.new-contect-design-send{
    border-radius: 12px;
}


</style>

<div class="contact-area  pb-60 theam-title-div" >
    <a href="https://wa.me/9925133440" target="_blank">
        <div class="whatsapp-btn" data-aos="fade-up">
            <img src="{{ asset('assets/front/images/new-images/whatsapp.png') }}" alt="WhatsApp">
        </div>
    </a>

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