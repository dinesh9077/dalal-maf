@extends('vendors.layout')


{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl_style')

<style>
    .form-group{
        padding: 0px !important;
    }
</style>

@section('content')
<div class="page-header">
    <h4 class="page-title">{{ __('Properties') }}</h4>
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
            <a href="#">{{ __('Property Management') }}</a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="#">{{ __('Properties') }}</a>
        </li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="p-4">
                <div class="row">
                    <!-- <div class="col-lg-3">
                            <div class="card-title d-inline-block mt-lg-3">{{ __('Properties') }}</div>
                        </div> -->
                    <div class="col-lg-6">
                        <form action="{{ route($search_url) }}" method="get"
                            id="carSearchForm">
                            <div class="row">
                                <div class="col-lg-3 mt-3 mt-lg-0">
                                    <select name="category_id" id="" class="select2"
                                        onchange="document.getElementById('carSearchForm').submit()">
                                        <option value="" selected>{{ __('All Category') }}</option>
                                        @foreach ($categotyConetnt as $category)
											<option @selected($category->category_id == request()->input('category_id')) value="{{ $category->category_id }}">
												{{ $category->name }}
											</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 mt-3 mt-lg-0">
                                    <select name="purpose" id="" class="select2"
                                        onchange="document.getElementById('carSearchForm').submit()">
                                        <option value="" selected>{{ __('All Purpose') }}</option>
                                        <option value="rent" {{ request()->filled('purpose') && request()->input('purpose') == 'rent' ? 'selected' : '' }}>{{ __('Rent') }}</option>
                                        <option value="sell" {{ request()->filled('purpose') && request()->input('purpose') == 'sell' ? 'selected' : '' }}>{{ __('Sell') }}</option>
                                        <option value="buy" {{ request()->filled('purpose') && request()->input('purpose') == 'buy' ? 'selected' : '' }}>{{ __('Buy') }}</option>
                                        <option value="lease" {{ request()->filled('purpose') && request()->input('purpose') == 'lease' ? 'selected' : '' }}>{{ __('Lease') }}</option>
                                        <option value="franchiese" {{ request()->filled('purpose') && request()->input('purpose') == 'franchiese' ? 'selected' : '' }}>{{ __('Franchiese') }}</option>
                                        <option value="business_for_sale" {{ request()->filled('purpose') && request()->input('purpose') == 'business_for_sale' ? 'selected' : '' }}>{{ __('Business For Sale') }}</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 mt-3 mt-lg-0">
                                    <select name="city_id" id="" class="select2"
                                        onchange="document.getElementById('carSearchForm').submit()">
                                        <option value="" selected>{{ __('All City') }}</option>
                                        @foreach ($cities as $city)
                                        <option @selected($city->city_id == request()->input('city_id')) value="{{ $city->city_id }}">
                                            {{ $city->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 mt-3 mt-lg-0">
                                    <div class="form-group">
                                        <input type="text" name="title" value="{{ request()->input('title') }}"
                                            class="form-control" placeholder="Title">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group" style="height: 42.19px;">
                            @includeIf('vendors.partials.languages')
                        </div>
                    </div>
                    <div class="col-lg-4 mt-3 mt-lg-0">
                        <a href="{{ route('vendor.property_management.type') }}"
                            class="btn btn-sm float-lg-right Add-pproperty-button"><i class="fas fa-plus"></i>
                            {{ __('Add Property') }}</a>

                        <button class="btn  btn-sm float-lg-right mr-2 d-none bulk-delete  Add-pproperty-button"
                            data-href="{{ route('vendor.property_management.bulk_delete_property') }}"><i
                                class="flaticon-interface-5"></i>
                            {{ __('Delete') }}</button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        @if (count($properties) == 0)
                        <h3 class="text-center">{{ __('NO PROPERTY FOUND!') }}</h3>
                        @else
                        <div class="table-responsive new-v-table">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">
                                            <input type="checkbox" class="bulk-check" data-val="all">
                                        </th>
                                        <th scope="col">{{ __('Title') }}</th>
                                        <th scope="col">{{ __('Post by') }}</th>
                                        <th scope="col">{{ __('Type') }}</th>
                                        <th scope="col">{{ __('Category') }}</th>
                                        <th scope="col">{{ __('Purpose') }}</th>
                                        <th scope="col">{{ __('City') }}</th>
                                        <th scope="col">{{ __('Area') }}</th>
                                        <th scope="col">{{ __('Approval Status') }}</th>
                                        <!--<th scope="col">{{ __('Featured') }}</th>-->
                                        <th scope="col">{{ __('Status') }}</th>
                                        <th scope="col">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($properties as $property)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="bulk-check"
                                                data-val="{{ $property->id }}">
                                        </td>
                                        <td class="table-title">
                                            @php
                                            $property_content = $property->getContent($language->id);
                                            if (is_null($property_content)) {
                                            $property_content = $property
                                            ->propertyContents()
                                            ->where('language_id', $language->id)
                                            ->first();
                                            }
                                            @endphp
                                            @if (!empty($property_content))
                                            @if($property->property_type == 'full')
                                            <a href="{{ route('vendor.property_inventory.view', $property->id) }}"
                                                target="_blank">
                                                {{ strlen(@$property_content->title) > 100 ? mb_substr(@$property_content->title, 0, 100, 'utf-8') . '...' : @$property_content->title }}
                                            </a>
                                            @else
                                            <a href="{{ route('frontend.property.details', ['slug' => $property_content->slug]) }}"
                                                target="_blank">
                                                {{ strlen(@$property_content->title) > 100 ? mb_substr(@$property_content->title, 0, 100, 'utf-8') . '...' : @$property_content->title }}
                                            </a>
                                            @endif
                                            @endif
                                        </td>

                                        <td>
                                            @if (!is_null($property->agent_id) && $property->agent_id != 0)
                                            <a
                                                href="{{ route('frontend.agent.details', ['username' => @$property->agent->username]) }}">{{ @$property->agent->username }}</a>
                                            @else

                                            <span class="badge badge-success">{{ __('Partner') }}</span>

                                            @endif
                                        </td>
                                        <td>
                                            {{ $property->type }}
                                        </td>
                                        <td> {{ $property->categoryContent?->name }} </td>
                                        <td> {{ $property->purpose }} </td>
                                        <td>
                                            {{ $property->cityContent->name }}
                                        </td>
                                        <td>
                                            {{ $property->areaContent?->name }}
                                        </td>
                                        <td>
                                            @if ($property->approve_status == 1)
                                            <span class="badge badge-success">{{ __('Approved') }}</span>
                                            @elseif($property->approve_status == 0)
                                            <span class="badge badge-warning">{{ __('Pending') }}</span>
                                            @else
                                            <span class="badge badge-danger">{{ __('Rejected') }}</span>
                                            @endif
                                        </td>
                                         
                                        <td>
                                            <form id="statusForm{{ $property->id }}" class="d-inline-block"  style="margin: 0px;"
                                                action="{{ route('vendor.property_management.update_status') }}"
                                                method="post">
                                                @csrf
                                                <input type="hidden" name="propertyId"
                                                    value="{{ $property->id }}">

                                                <select
                                                    class="form-control {{ $property->status == 1 ? 'bg-success' : 'bg-danger' }} form-control-sm"
                                                    name="status"
                                                    onchange="document.getElementById('statusForm{{ $property->id }}').submit();">
                                                    <option value="1"
                                                        {{ $property->status == 1 ? 'selected' : '' }}>
                                                        {{ __('Active') }}
                                                    </option>
                                                    <option value="0"
                                                        {{ $property->status == 0 ? 'selected' : '' }}>
                                                        {{ __('Inactive') }}
                                                    </option>
                                                </select>
                                            </form>
                                        </td>

                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle btn-sm"
                                                    type="button" id="dropdownMenuButton"
                                                    data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    {{ __('Select') }}
                                                </button>

                                                <div class="dropdown-menu"
                                                    aria-labelledby="dropdownMenuButton">

                                                    <a class="dropdown-item"
                                                        href="{{ route($url, $property->id) }}">
                                                        <span class="btn-label">
                                                            <i class="fas fa-edit"></i> {{ __('Edit') }}
                                                        </span>
                                                    </a>

                                                    <form class="deleteForm d-inline-block dropdown-item"  style="margin: 0px;"
                                                        action="{{ route('vendor.property_management.delete_property') }}"
                                                        method="post">
                                                        @csrf
                                                        <input type="hidden" name="property_id"
                                                            value="{{ $property->id }}">

                                                        <button type="submit" class="p-0 deleteBtn">
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

            <!-- <div class="card-footer">
                    {{ $properties->appends([
                            'vendor_id' => request()->input('vendor_id'),
                            'title' => request()->input('title'),
                        ])->links() }}
                </div> -->

        </div>
    </div>
</div>



<div class="modal fade" id="featuredRequest" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="my-checkout-form" class="modal-form"
                action="{{ route('vendor.property_management.featured_payment') }}" method="post"
                enctype="multipart/form-data">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __(' Featured Request') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="in_property_id" name="property_id" value="{{ old('property_id') }}">


                    <div class="form-group">
                        <label for=""> {{ __('Select Pricing *') }}</label>
                        <div class="row mt-2 justify-content-center">

                            @foreach ($featurePricing as $key => $pricing)
                            <div class="col-md-3 ">
                                <label class="imagecheck">
                                    <input name="featured_pricing_id" type="radio" value="{{ $pricing->id }}"
                                        class="imagecheck-input" checked>

                                    <figure class="imagecheck-figure">

                                        <div class="card-pricing3  card-secondary ">
                                            <div class="pricing-header  pb-2">

                                            </div>
                                            <div class="price-value">
                                                <div class="value">
                                                    <span
                                                        class="amount">{{ $pricing->price == 0 ? 'Free' : symbolPrice($pricing->price) }}</span>
                                                </div>
                                            </div>

                                            <ul class="pricing-content">
                                                <li>{{ __('Number Of Days') . ' :' }}
                                                    {{ $pricing->number_of_days }}
                                                </li>
                                            </ul>

                                        </div>

                                    </figure>
                                </label>
                            </div>
                            @endforeach


                        </div>
                    </div>

                    @error('featured_pricing_id')
                    <div class="col-12">
                        <p class="mt-2 text-danger">{{ $message }}</p>
                    </div>
                    @enderror
                    <div class="form-group mb-2">
                        <label for="payment-gateway"> {{ __('Select Payment Method *') }}</label>
                        <select name="gateway" id="payment-gateway" required class="form-control select">
                            <option selected disabled value="">
                                {{ __('Select Payment Gateway') }}
                            </option>
                            @if (count($onlineGateways) > 0)
                            @foreach ($onlineGateways as $onlineGateway)
                            <option value="{{ $onlineGateway->keyword }}"
                                {{ $onlineGateway->keyword == old('gateway') ? 'selected' : '' }}>
                                {{ $onlineGateway->name }}
                            </option>
                            @endforeach
                            @endif

                            @if (count($offlineGateways) > 0)
                            @foreach ($offlineGateways as $offlineGateway)
                            <option value="{{ $offlineGateway->id }}"
                                {{ $offlineGateway->id == old('gateway') ? 'selected' : '' }}>
                                {{ $offlineGateway->name }}
                            </option>
                            @endforeach
                            @endif
                        </select>

                        @error('gateway')
                        <p class="mt-2 text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    @foreach ($onlineGateways as $onlineGateway)
                    @if ($onlineGateway->keyword == 'stripe')
                    {{-- <div class="col-12"> --}}
                    <div id="stripe-element" class="mb-2">
                        <!-- A Stripe Element will be inserted here. -->
                    </div>
                    <!-- Used to display form errors -->
                    <div id="stripe-errors" class="pb-2 text-danger" role="alert"></div>
                    @endif
                    @if ($onlineGateway->keyword == 'authorize.net')
                    <div id="authorize-net-input" class="  d-none  ">
                        <div class="form-group  ">
                            <input type="number" class="form-control" id="anetCardNumber"
                                name="anetCardNumber" placeholder="{{ __('Enter Your Card Number') }}"
                                autocomplete="off">
                            <p class="mt-2 text-danger" id="anetCardNumber-error"></p>
                            @error('anetCardNumber')
                            <p class="mt-2 text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group ">
                            <input type="text" class="form-control" id="anetExpMonth" name="anetExpMonth"
                                placeholder="{{ __('Enter Expiry Month') }}" autocomplete="off">
                            <p class="mt-2 text-danger" id="anetExpMonth-error"></p>
                            @error('anetExpMonth')
                            <p class="mt-2 text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group  ">
                            <input type="text" class="form-control" id="anetExpYear" name="anetExpYear"
                                placeholder="{{ __('Enter Expiry Year') }}" autocomplete="off">
                            @error('anetExpYear')
                            <p class="mt-2 text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group  ">
                            <input type="text" class="form-control" id="anetCardCode" name="anetCardCode"
                                placeholder="{{ __('Enter Card Code') }}" autocomplete="off">
                            @error('anetCardCode')
                            <p class="mt-2 text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <input type="hidden" name="opaqueDataValue" id="opaqueDataValue" />
                        <input type="hidden" name="opaqueDataDescriptor" id="opaqueDataDescriptor" />
                        <ul id="anetErrors" class="dis-none"></ul>
                    </div>
                    @endif
                    @endforeach

                    @foreach ($offlineGateways as $offlineGateway)
                    <div class=" @if ($errors->has('attachment')) d-block @else d-none @endif  offline-gateway-info"
                        id="{{ 'offline-gateway-' . $offlineGateway->id }}">

                        @if (!is_null($offlineGateway->short_description))
                        <div class="form-group mb-4">
                            <label>{{ __('Description') }}</label>
                            <p>{{ $offlineGateway->short_description }}</p>
                        </div>
                        @endif

                        @if (!is_null($offlineGateway->instructions))
                        <div class="form-group mb-4">
                            <label>{{ __('Instructions') }}</label>
                            <p>{!! $offlineGateway->instructions !!}</p>
                        </div>
                        @endif
                        @if ($offlineGateway->has_attachment == 1)
                        <div class="form-group mb-4">
                            <label>{{ __('Attachment/Recipient Image') }} *</label>
                            <br>
                            <input type="file" name="attachment" class="form-control">
                            @error('attachment')
                            <p class="mt-2 text-danger">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-danger em " id="err_attachment"></p>
                        </div>
                        @endif

                    </div>
                    @endforeach


                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">
                        {{ __('Close') }}
                    </button>
                    <button type="button" id="payment" class="btn btn-primary btn-lg">
                        {{ __('Pay') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
@section('script')
{{-- START: Authorize.net Scripts --}}
@php
$anet = App\Models\PaymentGateway\OnlineGateway::query()->where('keyword', 'authorize.net')->first();

$anetSrc = 'https://jstest.authorize.net/v1/Accept.js';
$anetAcceptSrc = 'https://jstest.authorize.net/v1/Accept.js';
if (!is_null($anet)) {
$anetInfo = $anet->convertAutoData();
$anetTest = $anetInfo['sandbox_check'] ?? '';
if ($anetTest != 1) {
$anetSrc = 'https://jstest.authorize.net/v1/Accept.js';
$anetAcceptSrc = 'https://js.authorize.net/v1/Accept.js';
}
}
@endphp
<script type="text/javascript" src="{{ asset("${anetSrc}") }}" charset="utf-8"></script>
<script type="text/javascript" src="{{ $anetAcceptSrc }}" charset="utf-8"></script>
@if (!empty($stripe_key))
<script src="https://js.stripe.com/v3/"></script>
@endif

@if (count($errors) > 0)
<script type="text/javascript">
    $('#featuredRequest').modal('show');
</script>
@endif

<script>
    "use strict";
    var clientKey = "{{ isset($anetInfo) && !is_null($anetInfo) ? $anetInfo['public_key'] : null }}";
    var apiLoginID = "{{ isset($anetInfo) && !is_null($anetInfo) ? $anetInfo['login_id'] : null }}";
    var stripe_key = "{{ $stripe_key ?? '' }}";
</script>
<script src="{{ asset('assets/js/feature-payment.js') }}"></script>
@endsection
