@extends('users.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Inquiry') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('user.dashboard') }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>


            <li class="nav-item">
                <a href="#">{{ __('Inquiry') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <!-- <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card-title d-inline-block">{{ __('All sent inquiry') }}</div>
                        </div>

                    </div>
                </div> -->

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($inquiries) == 0)
                                <h3 class="text-center mt-2">{{ __('NO SENT INQUIRY FOUND') . '!' }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3" id="basic-datatables">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Property') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Email') }}</th>
                                                <th>{{ __('Phone') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Comment') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($inquiries as $inquiry)
                                               <tr>
                                                    <td class="table-title">
                                                        @php
                                                            $property_content = $inquiry->property->propertyContent;
                                                            if (is_null($property_content)) {
                                                                $property_content = $property
                                                                    ->propertyContents()
                                                                    ->first();
                                                            }
                                                        @endphp
                                                        @if (!empty($property_content))
                                                            <a href="{{ route('frontend.property.details', ['slug' => $property_content->slug]) }}"
                                                                target="_blank">
                                                                {{ strlen(@$property_content->title) > 100 ? mb_substr(@$property_content->title, 0, 1000, 'utf-8') . '...' : @$property_content->title }}
                                                            </a>
                                                        @endif
                                                    </td>

                                                    <td>{{ $inquiry->name }}</td>
                                                    <td>{{ $inquiry->email }}</td>
                                                    <td> {{ $inquiry->phone }}</td>
                                                    <td>
                                                       {{ $inquiry->inquiryStatus ? $inquiry->inquiryStatus->name : '' }}
                                                    </td>
                                                    <td> {{ $inquiry->comment }}</td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-footer"></div>
            </div>
        </div>
    </div>

    {{-- create modal --}}

    {{-- edit modal --}}
    @include('users.property.message-view')
@endsection
