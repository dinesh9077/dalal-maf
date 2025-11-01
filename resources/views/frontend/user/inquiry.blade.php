@php
    $version = $basicInfo->theme_version;
@endphp
@extends("frontend.layouts.layout-v$version")
@section('pageHeading')
    {{ __('Inquiry') }}
@endsection


@section('content')


    <!--====== Start Dashboard Section ======-->
    <div class="user-dashboard pb-60"  style="margin-top: 150px;">
        <div class="container">
            <div class="row gx-xl-5">
                @includeIf('frontend.user.side-navbar')
                <div class="col-lg-9">
                    <div class="account-info radius-md mb-40">
                        <div class="title">
                            <h4>{{ __('Inquiries') }}</h4>
                        </div>
                        <div class="main-info">
                            <div class="main-table">
                                <div class="table-responsive">
                                    <table id="myTable" class="table table-striped w-100">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--====== End Dashboard Section ======-->
@endsection
