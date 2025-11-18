@php
    $version = $basicInfo->theme_version;
@endphp
@extends("frontend.layouts.layout-v$version")

<style>
    .new-main-navbar {
        background-color: #6c603c;
    }
</style>

@section('content')
    <div style="margin-top: 150px ; margin-bottom: 80px;">
        <div class="container" data-aos="fade-up">
            <div class="section-title title-inline mb-40 aos-init aos-animate d-flex justify-content-center"
                data-aos="fade-up">
                <h2 class="title">{{ $title }} Properties</h2>
            </div>
        </div>

        <div class="container " style="margin-top: 50px;" data-aos="fade-up">
            <div class="row">
                @foreach ($property_contents as $property)
                    <x-property :property="$property" class="col-lg-3 col-md-6 mt-3" />
                @endforeach
            </div>
        </div>
    </div>

    <a href="https://wa.me/9925133440" target="_blank">
        <div class="whatsapp-btn" data-aos="fade-up">
            <img src="{{ asset('assets/front/images/new-images/whatsapp.png') }}" alt="WhatsApp">
        </div>
    </a>
@endsection
