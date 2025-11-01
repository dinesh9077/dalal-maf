@php
$version = $basicInfo->theme_version;
@endphp
@extends("frontend.layouts.layout-v$version")

@section('pageHeading')
{{ !empty($pageHeading) ? $pageHeading->about_us_title : __('About Us') }}
@endsection

@section('metaKeywords')
@if (!empty($seoInfo))
{{ $seoInfo->meta_keywords_about_page }}
@endif
@endsection

@section('metaDescription')
@if (!empty($seoInfo))
{{ $seoInfo->meta_description_about_page }}
@endif
@endsection

@section('content')
@includeIf('frontend.partials.breadcrumb', [
'breadcrumb' => $bgImg->breadcrumb,
'title' => !empty($pageHeading) ? $pageHeading->about_us_title : __('About Us'),
'subtitle' => __('About Us'),
])

<style>
        .new-main-navbar {
    background-color: #6c603c;
}

</style>
<!-- 
    @if ($secInfo->about_section_status == 1)
        <section class="about-area pt-100 pb-70 mt-30">
            <div class="container">
                <div class="row gx-xl-5">
                    <div class="col-lg-6">
                        <div class="img-content mb-30" data-aos="fade-right">
                            <div class="image">
                                <img class="lazyload blur-up"
                                    data-src="{{ asset('assets/img/about_section/' . $aboutImg->about_section_image1) }}">

                                <img class="lazyload blur-up"
                                    data-src="{{ asset('assets/img/about_section/' . $aboutImg->about_section_image2) }}">
                            </div>
                            <div class="absolute-text bg-secondary">
                                <div class="center-text">
                                    <span class="h2 color-primary">{{ $aboutInfo?->years_of_expricence }}+</span>
                                    <span>{{ __('Years') }}</span>
                                </div>
                                <div id="curveText">{{ __('We are highly experience') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="content  mb-30" data-aos="fade-left">
                            <div class="content-title">
                                <span class="subtitle"><span
                                        class="line"></span>{{ $aboutInfo->title}}</span>
                                <h2>{{ $aboutInfo?->sub_title }}</h2>
                            </div>
                            <div class="text summernote-content">{!! $aboutInfo?->description !!}</div>

                            <div class="d-flex align-items-center flex-wrap gap-15">


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif -->

<a href="https://wa.me/9925133440" target="_blank">
    <div class="whatsapp-btn" data-aos="fade-up">
        <img src="{{ asset('assets/front/images/new-images/whatsapp.png') }}" alt="WhatsApp">
    </div>
</a>



<section class="about-section">
    <div class="container">

        <!-- <div class="section-title title-center title-inline mb-40 aos-init aos-animate" data-aos="fade-up">
                        <h2 class="title">About Us</h2>
            </div> -->

        <p class="subtitle" data-aos="fade-up">
            We are Surat’s most reliable real estate experts, dedicated to helping you find the perfect home, office, or investment property. With deep knowledge of Surat’s property market and a client-first approach, we make buying, selling, and investing simple, secure, and successful.
        </p>

        <div class="row d-flex align-items-center">
            <!-- Left Content -->
            <div class="col-lg-6" data-aos="fade-right">
                <div class="row">
                    <div class="col-md-6 about-box mt-5">
                        <span>1.</span>
                        <h4>Who We Are</h4>
                        <p>We are Surat’s trusted real estate experts, connecting people with dream homes and investments.</p>
                    </div>
                    <div class="col-md-6 about-box mt-5">
                        <span>2.</span>
                        <h4>What Do We Do</h4>
                        <p>We simplify property buying, selling, and investing with expert guidance and verified options.</p>
                    </div>
                    <div class="col-md-6 about-box mt-5">
                        <span>3.</span>
                        <h4>How Do We Help</h4>
                        <p>We provide transparent deals, legal support, and personalized property solutions tailored to clients.</p>
                    </div>
                    <div class="col-md-6 about-box mt-5">
                        <span>4.</span>
                        <h4>Create Success Story</h4>
                        <p>We turn dreams into addresses, helping clients achieve profitable investments and lifelong happiness.</p>
                    </div>
                </div>
            </div>

            <!-- Right Images -->
            <div class="col-lg-6 new-images-div" data-aos="fade-left">
                <img src="{{ asset('assets/front/images/new-images/Full-images.png') }}" alt="Image 1" />
            </div>

        </div>
    </div>
    </div>
</section>




<div class="container">
    <span class="lines-abs"></span>
</div>


<div class="container py-5 ">
    <!-- Welcome Section -->
    <section class="row align-items-center gy-4 pb-70">
        <!-- Image -->
        <div class="col-lg-5 text-center" data-aos="fade-right">
            <div class="image-wrapper">
                <img src="{{ asset('assets/front/images/new-images/Rectangle 477.png') }}" alt="Modern Property" class="img-fluid rounded">
                <div class="badge-custom">
                    <img src="{{ asset('assets/front/images/new-images/smile.png') }}" alt="">

                    <h6 class="mt-2 cus">210K</h6>
                    <h6 class="cus">Happy Customer</h6>
                </div>
            </div>
        </div>
        <!-- Text -->
        <div class="col-lg-7" data-aos="fade-left">
            <h2 class="mb-3 home-town-title">Welcome To The Property</h2>
            <p class="mb-0 abs-2-p">
                Welcome to The Property, Surat’s most reliable real estate agency dedicated to turning your property dreams into reality. Whether you’re looking for a luxurious home, a modern commercial space, or a profitable investment, we provide trusted solutions with complete transparency. Our expert team guides you every step of the way, ensuring a smooth, secure, and rewarding real estate experience.
            </p>
        </div>
    </section>
</div>




<div class="container">
    <span class="lines-abs"></span>
</div>


<div class="container py-5">
    <!-- Mission & Vision Section -->
    <section class="row text-center gy-4  pb-70">
        <div class="col-md-6  mv-s" data-aos="fade-right">
            <img src="{{ asset('assets/front/images/new-images/mission.png') }}" alt="Mission Icon" class="mb-3" width="100">
            <h3 class="h5 ms-title">Our Mission</h3>
            <p class="mb-0">
                To deliver trusted, transparent, and hassle-free real estate solutions, helping clients find homes, investments, and lifelong satisfaction.
            </p>
        </div>
        <div class="col-md-6  mv-s" data-aos="fade-left">
            <img src="{{ asset('assets/front/images/new-images/vision.png') }}" alt="Vision Icon" class="mb-3" width="100">
            <h3 class="h5 ms-title">Our Vision</h3>
            <p class="mb-0">
                To be Surat’s most preferred real estate partner, known for innovation, integrity, and creating value-driven property experiences.
            </p>
        </div>
    </section>

</div>



<div class="container">
    <span class="lines-abs"></span>
</div>


<!-- @if ($secInfo->why_choose_us_section_status == 1)
        <section class="choose-area pb-70">
            <div class="container">
                <div class="row gx-xl-5">
                    <div class="col-lg-7">
                        <div class="img-content mb-30 image-right" data-aos="fade-left">
                            <div class="img-1">
                                <img class="lazyload blur-up"
                                    data-src="  {{ asset('assets/img/why-choose-us/' . $whyChooseUsImg->why_choose_us_section_img1) }} ">
                                @if (!empty($whyChooseUsImg->why_choose_us_section_video_link))
                                    <a href="{{ $whyChooseUsImg->why_choose_us_section_video_link }}"
                                        class="video-btn youtube-popup p-absolute">
                                        <i class="fas fa-play"></i>
                                    </a>
                                @endif
                            </div>
                            <div class="img-2">
                                <img class="lazyload blur-up"
                                    data-src="  {{ asset('assets/img/why-choose-us/' . $whyChooseUsImg->why_choose_us_section_img2) }} ">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 order-lg-first">
                        <div class="content" data-aos="fade-right">
                            <div class="content-title">
                                <span class="subtitle"><span
                                        class="line"></span>{{ $whyChooseUsInfo->title}}</span>
                                <h2>{{ $whyChooseUsInfo?->sub_title }}</h2>
                            </div>
                            <div class="text">{!! $whyChooseUsInfo?->description !!}</div>


                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif -->
<!-- @if ($secInfo->work_process_section_status == 1)
        <section class="work-process pt-100 pb-70">
       
            <img class="lazyload bg-img" src="{{ asset('assets/front/images/2548hg445t5464676.png') }}">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="section-title title-center mb-40" data-aos="fade-up">
                            <h2 class="title" style="font-size: 25px;">{{ $workProcessSecInfo?->subtitle }}</h2>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="row gx-xl-5">
                            @foreach ($processes as $process)
                                <div class="col-xl-3 col-lg-4 col-sm-6" data-aos="fade-up">
                                    <div class="process-item text-center mb-30 color-1">
                                        <div class="process-icon">
                                            <div class="progress-content">
                                                <span class="h2 lh-1 new-ccs">{{ $loop->iteration }}</span>
                                                <i class="{{ $process->icon }}"></i>
                                            </div>
                                            <div class="progressbar-line-outer"></div>
                                            <div class="progressbar-line-inner">
                                                <svg>
                                                    <circle class="progressbar-circle" r="96" cx="100" cy="100"
                                                        stroke-dasharray="500" stroke-dashoffset="180" stroke-width="6"
                                                        fill="none" transform="rotate(-5 100 100)">
                                                    </circle>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="process-content mt-20">
                                            <h3 class="process-title">{{ $process?->title }}</h3>
                                            <p class="text m-0">{{ $process?->text }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif -->

<section class="work-process pt-100 pb-70 blur-up lazyloaded" style="background-image: url(&quot;http://127.0.0.1:8000/assets/front/images/2548hg445t5464676.png&quot;); background-size: cover; background-position: center center; display: block;">

    <img class="lazyload bg-img" src="" style="display: none;">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title title-center mb-40 aos-init aos-animate" data-aos="fade-up">

                    <h2 class="title" style="font-size: 25px;">We Follow Some Great Steps For Project</h2>
                </div>
            </div>
            <div class="col-12">
                <div class="row gx-xl-5">
                    <div class="col-xl-3 col-lg-4 col-sm-6" data-aos="fade-up">
                        <div class="process-item text-center mb-30 color-1">
                            <div class="process-icon">
                                <div class="progress-content">
                                    <span class="h2 lh-1 new-ccs">1</span>
                                    <i class="fal fa-home"  style="color : white;"></i>
                                </div>
                                <div class="progressbar-line-outer"></div>
                                <div class="progressbar-line-inner">
                                    <svg>
                                        <circle class="progressbar-circle" r="96" cx="100" cy="100" stroke-dasharray="500" stroke-dashoffset="180" stroke-width="6" fill="none" transform="rotate(-5 100 100)">
                                        </circle>
                                    </svg>
                                </div>
                            </div>
                            <div class="process-content mt-20">
                                <h3 class="process-title">Contract</h3>
                                <p class="text m-0">We begin with a clear and transparent agreement, ensuring all terms and responsibilities are well-defined for a smooth start.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6 " data-aos="fade-up">
                        <div class="process-item text-center mb-30 color-1">
                            <div class="process-icon">
                                <div class="progress-content">
                                    <span class="h2 lh-1 new-ccs">2</span>
                                    <i class="fal fa-map-marked-alt"  style="color : white;"></i>
                                </div>
                                <div class="progressbar-line-outer"></div>
                                <div class="progressbar-line-inner">
                                    <svg>
                                        <circle class="progressbar-circle" r="96" cx="100" cy="100" stroke-dasharray="500" stroke-dashoffset="180" stroke-width="6" fill="none" transform="rotate(-5 100 100)">
                                        </circle>
                                    </svg>
                                </div>
                            </div>
                            <div class="process-content mt-20">
                                <h3 class="process-title">Location</h3>
                                <p class="text m-0">Next, we identify and finalize the right location that aligns with your project goals and requirements.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6 " data-aos="fade-up">
                        <div class="process-item text-center mb-30 color-1">
                            <div class="process-icon">
                                <div class="progress-content">
                                    <span class="h2 lh-1 new-ccs">3</span>
                                    <i class="fal fa-chart-line"  style="color : white;"></i>
                                </div>
                                <div class="progressbar-line-outer"></div>
                                <div class="progressbar-line-inner">
                                    <svg>
                                        <circle class="progressbar-circle" r="96" cx="100" cy="100" stroke-dasharray="500" stroke-dashoffset="180" stroke-width="6" fill="none" transform="rotate(-5 100 100)">
                                        </circle>
                                    </svg>
                                </div>
                            </div>
                            <div class="process-content mt-20">
                                <h3 class="process-title">Start Small</h3>
                                <p class="text m-0">We believe in starting small to minimize risk, test the strategy, and build a strong foundation for long-term success.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6 " data-aos="fade-up">
                        <div class="process-item text-center mb-30 color-1">
                            <div class="process-icon">
                                <div class="progress-content" >
                                    <span class="h2 lh-1 new-ccs">4</span>
                                    <i class="fal fa-hands-helping" style="color : white;"></i>
                                </div>
                                <div class="progressbar-line-outer"></div>
                                <div class="progressbar-line-inner">
                                    <svg>
                                        <circle class="progressbar-circle" r="96" cx="100" cy="100" stroke-dasharray="500" stroke-dashoffset="180" stroke-width="6" fill="none" transform="rotate(-5 100 100)">
                                        </circle>
                                    </svg>
                                </div>
                            </div>
                            <div class="process-content mt-20">
                                <h3 class="process-title">Hire Agent</h3>
                                <p class="text m-0">Finally, we assign the right experts and agents to manage, execute, and guide the project efficiently until completion.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- @if ($secInfo->testimonial_section_status == 1)
        <section class="testimonial-area pt-100 pb-70">
            <div class="overlay-bg d-none d-lg-block">
                <img class="lazyload blur-up" data-src="{{ asset('assets/img/' . $testimonialSecImage) }}">
            </div>
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-4">
                        <div class="content mb-30" data-aos="fade-up">
                            <div class="content-title">
                                <span class="subtitle"><span
                                        class="line"></span>{{ $testimonialSecInfo->title }}</span>
                                <h2 class="title">
                                    {{ !empty($testimonialSecInfo?->subtitle) ? $testimonialSecInfo?->subtitle : '' }}</h2>
                            </div>
                            <p class="text mb-30">
                                {{ !empty($testimonialSecInfo?->content) ? $testimonialSecInfo?->content : '' }}</p>
               
                            <div class="slider-navigation scroll-animate">
                                <button type="button" title="Slide prev" class="slider-btn slider-btn-prev">
                                    <i class="fal fa-angle-left"></i>
                                </button>
                                <button type="button" title="Slide next" class="slider-btn slider-btn-next">
                                    <i class="fal fa-angle-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="swiper" id="testimonial-slider-1">
                            <div class="swiper-wrapper">
                                @foreach ($testimonials as $testimonial)
                                    <div class="swiper-slide pb-30" data-aos="fade-up">
                                        <div class="slider-item">
                                            <div class="client-img">
                                                <div class="lazy-container ratio ratio-1-1">
                                                    @if (is_null($testimonial->image))
                                                        <img data-src="{{ asset('assets/img/profile.jpg') }}"
                                                            class="lazyload">
                                                    @else
                                                        <img class="lazyload"
                                                            data-src="{{ asset('assets/img/clients/' . $testimonial->image) }}">
                                                    @endif


                                                </div>
                                            </div>
                                            <div class="client-content mt-30">
                                                <div class="quote">
                                                    <p class="text">{{ $testimonial->comment }}</p>
                                                </div>
                                                <div
                                                    class="client-info d-flex flex-wrap gap-10 align-items-center justify-content-between">
                                                    <div class="content">
                                                        <h6 class="name">{{ $testimonial->name }}</h6>
                                                        <span class="designation">{{ $testimonial->occupation }}</span>
                                                    </div>
                                                    <div class="ratings">

                                                        <div class="rate">
                                                            <div class="rating-icon"
                                                                style="width: {{ $testimonial->rating * 20 }}%"></div>
                                                        </div>
                                                        <span class="ratings-total">({{ $testimonial->rating }}) </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif -->


<!-- @if (!empty(showAd(3)))
        <div class="text-center mt-4">
            {!! showAd(3) !!}
        </div>
        {{-- Spacer --}}
        <div class="pb-100"></div>
    @endif -->

@endsection