@extends('vendors.layout')
@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Choose Property Type') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="#">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Property Management') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Property Type') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="card" style="box-shadow: none;background: transparent;">
                <!-- <div class="card-header">
                    <h3>{{ __('Choose Property Type') }}</h3>
                </div> -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-3 col-lg-5">
                            <a href="{{ route($url, ['type' => 'commercial']) }}"
                                class="d-block text-decoration-none">
                                <div class="card card-stats" style="border-radius: 20px;" >
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-3">
                                                <div class="col-icon">
                                                    <div class="icon-big text-center  bubble-shadow-small" style="background-color: #947E41; color : white; border-radius : 15px;">
                                                    <!-- <div class="icon-big text-center icon-success bubble-shadow-small"> -->
                                                        <i class="far fa-building"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-9">
                                                <div class="numbers ">
                                                    <h2 class="card-title  mb-4 text-uppercase text-right">{{ __('Commercial') }}
                                                    </h2>
                                                    <p class="card-category text-right"><strong>{{ __('Total') }}:</strong>
                                                        {{ $commertialCount }}
                                                        {{ __('Properties') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-3 col-lg-5">
                            <a href="{{ route($url, ['type' => 'residential']) }}"
                                class="d-block text-decoration-none">
                                <div class="card card-stats" style="border-radius: 20px;">
                                    <div class="card-body ">
                                        <div class="row align-items-center">

                                            <div class="col-3">
                                                <div class="col-icon ">
                                                <!-- <div class="col-icon mx-auto">  -->
                                                    <div class="icon-big text-center  bubble-shadow-small" style="background-color: #947E41; color : white; border-radius : 15px;">
                                                    <!-- <div class="icon-big text-center icon-warning bubble-shadow-small" > -->
                                                        <i class="fas fa-home"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-9 ">
                                                <div class="numbers">
                                                    <h2 class="card-title mb-4 text-uppercase text-right">{{ __('Residential') }}
                                                    </h2>
                                                    <p class="card-category text-right"><strong>{{ __('Total') }}:</strong>
                                                        {{ $residentialCount }}
                                                        {{ __('Properties') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-3 col-lg-5">
                            <a href="{{ route($url, ['type' => 'industrial']) }}"
                                class="d-block text-decoration-none">
                                <div class="card card-stats" style="border-radius: 20px;">
                                    <div class="card-body ">
                                        <div class="row align-items-center">

                                            <div class="col-3">
                                                <div class="col-icon ">
                                                <!-- <div class="col-icon mx-auto">  -->
                                                    <div class="icon-big text-center  bubble-shadow-small" style="background-color: #947E41; color : white; border-radius : 15px;">
                                                    <!-- <div class="icon-big text-center icon-warning bubble-shadow-small" > -->
                                                        <i class="fas fa-industry"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-9 ">
                                                <div class="numbers">
                                                    <h2 class="card-title mb-4 text-uppercase text-right">{{ __('Industrial') }}
                                                    </h2>
                                                    <p class="card-category text-right"><strong>{{ __('Total') }}:</strong>
                                                        {{ $industrialCount }}
                                                        {{ __('Properties') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
