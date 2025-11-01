@extends('backend.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Inquiry') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('vendor.dashboard') }}">
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
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card-title d-inline-block">{{ __('All Inquiry') }}</div>
                        </div>
                        <div class="col-lg-8">
                            <form action="{{ route('admin.property_message.index') }}" method="get"
                                id="carSearchForm">
                                <div class="row">
                                    <div class="col-lg-5">
                                        <select name="purpose" id="" class="select2"
                                            onchange="document.getElementById('carSearchForm').submit()">
                                            <option value="" selected>{{ __('All Purpose') }}</option>
                                            <option value="rent"  {{ request()->filled('purpose') && request()->input('purpose') == 'rent' ? 'selected' : '' }}>{{ __('Rent') }}</option>
                                            <option value="sell"  {{ request()->filled('purpose') && request()->input('purpose') == 'sell' ? 'selected' : '' }}>{{ __('Sell') }}</option>
                                            <option value="buy"  {{ request()->filled('purpose') && request()->input('purpose') == 'buy' ? 'selected' : '' }}>{{ __('Buy') }}</option>
                                            <option value="lease"  {{ request()->filled('purpose') && request()->input('purpose') == 'lease' ? 'selected' : '' }}>{{ __('Lease') }}</option>
                                            <option value="franchiese"  {{ request()->filled('purpose') && request()->input('purpose') == 'franchiese' ? 'selected' : '' }}>{{ __('Franchiese') }}</option>
                                            <option value="business_for_sale"  {{ request()->filled('purpose') && request()->input('purpose') == 'business_for_sale' ? 'selected' : '' }}>{{ __('Business For Sale') }}</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-5">
                                        <input type="text" name="inquiry_date" id="expireRange" class="form-control"
                                            value="{{ request('inquiry_date') }}" placeholder="Select inquiry date Range" autocomplete="off">
                                    </div>
                                    <div class="col-lg-2" >
                                      <a href="{{ request()->fullUrlWithQuery(['export' => 1]) }}"  class="btn btn-dark btn-sm" style="padding : 11px;">
                                          <i class="fa fa-file-excel"></i> Export to Excel
                                      </a>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($messages) == 0)
                                <h3 class="text-center mt-2">{{ __('NO INQUIRY FOUND') . '!' }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3" id="basic-datatables">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">{{ __('Property') }}</th>
                                                <th scope="col">{{ __('Property Purpose') }}</th>
                                                <th scope="col">{{ __('Name') }}</th>
                                                <th scope="col">{{ __('Email ID') }}</th>
                                                <th scope="col">{{ __('Phone') }}</th>
                                                <th scope="col">{{ __('Added By') }}</th>
                                                <th scope="col">{{ __('Status') }}</th>
                                                <th scope="col">{{ __('Inquiry Date') }}</th>
                                                <th scope="col">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($messages as $message)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>

                                                    <td class="table-title">
                                                        @php
                                                            $property_content = $message->property->propertyContent;
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
                                                    <td>{{ $message->property->purpose }}</td>

                                                    <td>{{ $message->name }}</td>
                                                    <td><a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                                                    </td>
                                                    <td> <a href="tel:{{ $message->phone }}">{{ $message->phone }}</a>
                                                    </td>
                                                    <td>
                                                        @if ($message->vendor_id != 0)
                                                            <a href="{{ route('admin.vendor_management.vendor_details', ['id' => @$message->vendor->id, 'language' => $defaultLang->code]) }}">{{ @$message->vendor->username }}</a>
                                                        @else
                                                            <span class="badge badge-success">{{ __('Admin') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                      @if($message->inquiryStatus)
                                                        <span class="badge badge-success">{{ $message->inquiryStatus->name}}</span>
                                                      @else
                                                       <span class="badge badge-warning">{{ 'Pending' }}</span>
                                                       @endif
                                                    </td>
                                                    <td>{{ $message->created_at->format('d-m-Y') }}</td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-secondary dropdown-toggle btn-sm"
                                                                type="button" id="dropdownMenuButton"
                                                                data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                                {{ __('Select') }}
                                                            </button>

                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                                                <a class="dropdown-item editBtn" href="#"
                                                                    data-toggle="modal" data-target="#changeStatusModal"
                                                                    data-id="{{ $message->id }}"
                                                                    data-status="{{ $message->status }}"
                                                                    data-comment="{{ $message->comment }}">
                                                                    <span class="btn-label">
                                                                        <i class="fas fa-eye"></i> {{ __('Change Status') }}
                                                                    </span>
                                                                </a>

                                                                <a class="dropdown-item editBtn" href="#"
                                                                    data-toggle="modal" data-target="#editModal"
                                                                    data-id="{{ $message->id }}"
                                                                    data-name="{{ $message->name }}"
                                                                    data-phone="{{ $message->phone }}"
                                                                    data-message="{{ $message->message }}"
                                                                    data-email="{{ $message->email }}">
                                                                    <span class="btn-label">
                                                                        <i class="fas fa-eye"></i> {{ __('View') }}
                                                                    </span>
                                                                </a>
                                                                <form class="deleteForm d-inline-block dropdown-item p-0"
                                                                    action="{{ route('admin.property_message.destroy') }}"
                                                                    method="post">
                                                                    @csrf
                                                                    <input type="hidden" name="message_id"
                                                                        value="{{ $message->id }}">

                                                                    <button type="submit" class=" deleteBtn">
                                                                        <span class="btn-label">
                                                                            <i class="fas fa-trash-alt"></i>
                                                                            {{ __('Delete') }}
                                                                        </span>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </td>
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
    @include('backend.property.message-view')
@endsection

@section('script')
<script>
    $(function () {
        $('#expireRange').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });

        $('#expireRange').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format('YYYY-MM-DD'));
            $('#carSearchForm').submit();
        });

        $('#expireRange').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            $('#carSearchForm').submit();
        });
    });
</script>
@endsection
