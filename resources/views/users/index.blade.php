@extends('users.layout')
<style>
    .hero-slider {
        position: relative;
        width: 100%;
        height: 350px;
        overflow: hidden;
        border-radius: 12px;
    }

    .hero-slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        opacity: 0;
        transition: opacity 1s ease-in-out;
    }

    .hero-slide.active {
        opacity: 1;
        z-index: 1;
    }
</style>
@php
    $firstHeroImg = !empty($heroImg) && is_array($heroImg) ? $heroImg[0] : 'noimage.jpg';
@endphp
@section('content')
    <div class="mt-2 mb-4">
        <h2 class="pb-2">{{ __('Welcome,') }} {{ Auth::guard('web')->user()->username . '!' }}</h2>
    </div>

    <div class="hero-slider position-relative">
        @foreach ($heroImg as $index => $img)
            <div class="hero-slide {{ $index === 0 ? 'active' : '' }}"
                style="background-image: url('{{ asset('assets/img/hero/static/' . $img) }}');">
            </div>
        @endforeach
    </div>

    {{-- dashboard information start --}}
    <div class="row dashboard-items mt-5">
        <div class="col-sm-6 col-md-4">
            <a href="{{ route('agent.property_management.properties') }}">
                <div class="card card-stats card-success card-round">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="far fa-home"></i>
                                </div>
                            </div>

                            <div class="col-7 col-stats">
                                <div class="numbers">
                                    <p class="card-category">{{ __('Properties') }}</p>
                                    <h4 class="card-title">{{ $totalProperties }}</h4>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-md-4">
            <a href="{{ route('agent.property_management.properties') }}">
                <div class="card card-stats card-success card-round">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="far fa-home"></i>
                                </div>
                            </div>

                            <div class="col-7 col-stats">
                                <div class="numbers">
                                    <p class="card-category">{{ __('Sent Inquiry') }}</p>
                                    <h4 class="card-title">{{ $totalInquiry }}</h4>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-md-4">
            <a href="{{ route('agent.property_management.properties') }}">
                <div class="card card-stats card-success card-round">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="far fa-home"></i>
                                </div>
                            </div>

                            <div class="col-7 col-stats">
                                <div class="numbers">
                                    <p class="card-category">{{ __('Tickets') }}</p>
                                    <h4 class="card-title">{{ $totalTickets }}</h4>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-md-4">
            <a href="{{ route('agent.property_management.properties') }}">
                <div class="card card-stats card-success card-round">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="far fa-home"></i>
                                </div>
                            </div>

                            <div class="col-7 col-stats">
                                <div class="numbers">
                                    <p class="card-category">{{ __('Wishlist') }}</p>
                                    <h4 class="card-title">{{ $totalWishlist }}</h4>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{ __('Monthly Property Posts') }} ({{ date('Y') }})</div>
                </div>

                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="CarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>
    @php
        $imagePaths = is_array($heroImg)
            ? array_map(function ($img) {
                return asset('assets/img/hero/static/' . $img);
            }, $heroImg)
            : [asset('assets/img/noimage.jpg')];
    @endphp
@endsection

@section('script')
    {{-- chart js --}}
    <script type="text/javascript" src="{{ asset('assets/js/chart.min.js') }}"></script>

    <script>
        "use strict";
        const monthArr = @php echo json_encode($monthArr) @endphp;
        const totalPropertyArr = @php echo json_encode($totalPropertiesArr) @endphp;
    </script>

    <script type="text/javascript" src="{{ asset('assets/js/user-chart-init.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('.hero-slide');
            let currentIndex = 0;

            setInterval(() => {
                slides[currentIndex].classList.remove('active');
                currentIndex = (currentIndex + 1) % slides.length;
                slides[currentIndex].classList.add('active');
            }, 4000); // Change slide every 4 seconds
        });
    </script>
@endsection
