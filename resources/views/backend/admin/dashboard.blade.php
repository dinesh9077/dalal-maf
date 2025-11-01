@extends('backend.layout')

@section('content')
    <div class="d-flex" style="justify-content: space-between;">
        <div class="mt-2 mb-4">
            <h2 class="pb-2">{{ __('Welcome,') }} {{ $authAdmin->first_name . ' ' . $authAdmin->last_name . '!' }}</h2>
        </div>

        <div style="font-weight:bold;     height: fit-content;
    background: white;
    padding: 10px 20px;
    color: black;
    border-radius: 20px;" >
        👥 Live Visitors: {{ $live_visitors }}
        </div>
    </div>

    {{-- dashboard information start --}}
    @php
        if (!is_null($roleInfo)) {
            $rolePermissions = json_decode($roleInfo->permissions);
        }
    @endphp



    <div class="row dashboard-items">
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Total Earning', $rolePermissions)))
            <div class="col-md-3">
                <a href="{{ route('admin.property_management.properties') }}">
                    <div class="card card-stats card-primary card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-money-check-alt"></i>
                                    </div>
                                </div>

                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">{{ __('Total Properties') }}</p>
                                        <h4 class="card-title">{{ $property }}
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif




        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Blog Management', $rolePermissions)))
            <div class="col-sm-6 col-md-3">
                <a href="{{ route('admin.property_message.index') }}">
                    <div class="card card-stats card-info card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fal fa-blog"></i>
                                    </div>
                                </div>

                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">{{ __('Total Inquiries') }}</p>
                                        <h4 class="card-title">{{ $inquiry }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Vendors Management', $rolePermissions)))
            <div class="col-md-3">
                <a href="{{ route('admin.vendor_management.registered_vendor') }}">
                    <div class="card card-stats card-secondary card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">{{ __('Total Partners') }}</p>
                                        <h4 class="card-title">
                                            {{ $vendors }}
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('User Management', $rolePermissions)))
            <div class="col-sm-6 col-md-3">
                <a href="{{ route('admin.user_management.registered_users') }}">
                    <div class="card card-stats card-orchid card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="la flaticon-users"></i>
                                    </div>
                                </div>

                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">{{ __('Total Users') }}</p>
                                        <h4 class="card-title">{{ $totalUser }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        {{-- @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('User Management', $rolePermissions)))
            <div class="col-sm-6 col-md-3">
                <a href="{{ route('admin.user_management.subscribers') }}">
                    <div class="card card-stats card-dark card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fal fa-bell"></i>
                                    </div>
                                </div>

                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">{{ __('Subscribers') }}</p>
                                        <h4 class="card-title">{{ $totalSubscriber }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif --}}

    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{ __('Monthly Package Purchase') }} ({{ date('Y') }})</div>
                </div>

                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="packagePurchaseChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{ __('Month wise registered users') }} ({{ date('Y') }})</div>
                </div>

                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="userChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- dashboard information end --}}
@endsection

@section('script')
    {{-- chart js --}}
    <script type="text/javascript" src="{{ asset('assets/js/chart.min.js') }}"></script>

    <script>
        "use strict";
        const monthArr = @php echo json_encode($monthArr) @endphp;
        const packagePurchaseIncomesArr = @php echo json_encode($packagePurchaseIncomesArr) @endphp;
        const totalUsersArr = @php echo json_encode($totalUsersArr) @endphp;
    </script>

    <script type="text/javascript" src="{{ asset('assets/js/chart-init.js') }}"></script>

@endsection
