@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl_style')

@section('content')
<div class="page-header">
    <h4 class="page-title">{{ __('Properties') }}</h4>
    <ul class="breadcrumbs">
        <li class="nav-home">
            <a href="{{ route('admin.dashboard') }}">
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
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-2 col-md-12 col-sm-12">
                        <div class="card-title mb-3">{{ __('Properties') }}</div>
					</div>
                    <div class="col-lg-10 col-md-12 col-sm-12">
                        <form action="{{ route($search_url) }}" method="get" id="carSearchForm">
                            <div class="row g-2 align-items-center">
                                <div class="col-sm-12 col-md-12 col-lg mt-2 mt-md-2 mt-lg-0">
                                    <select name="vendor_id" class="form-select select2"
									onchange="document.getElementById('carSearchForm').submit()">
                                        <option value="">{{ __('All') }}</option>
                                        <option value="admin" @selected(request()->input('vendor_id') == 'admin')>
                                            {{ Auth::guard('admin')->user()->username }} ({{ __('Admin') }})
										</option>
                                        @foreach ($vendors as $vendor)
                                        <option @selected($vendor->id == request()->input('vendor_id')) value="{{ $vendor->id }}">
                                            {{ $vendor->username }}
										</option>
                                        @endforeach
									</select>
								</div>
								
                                <div class="col-sm-12 col-md-12 col-lg mt-2 mt-md-2 mt-lg-0">
                                    <select name="category_id" class="form-select select2"
									onchange="document.getElementById('carSearchForm').submit()">
                                        <option value="">{{ __('All Category') }}</option>
                                        @foreach ($categotyConetnt as $category)
                                        <option @selected($category->category_id == request()->input('category_id')) value="{{ $category->category_id }}">
                                            {{ $category->name }}
										</option>
                                        @endforeach
									</select>
								</div>
								
                                <div class="col-sm-12 col-md-12 col-lg mt-2 mt-md-2 mt-lg-0">
                                    <select name="purpose" class="form-select select2"
									onchange="document.getElementById('carSearchForm').submit()">
                                        <option value="">{{ __('All Purpose') }}</option>
                                        <option value="rent" @selected(request()->input('purpose')=='rent')>{{ __('Rent') }}</option>
                                        <option value="sell" @selected(request()->input('purpose')=='sell')>{{ __('Sell') }}</option>
                                        <option value="buy" @selected(request()->input('purpose')=='buy')>{{ __('Buy') }}</option>
                                        <option value="lease" @selected(request()->input('purpose')=='lease')>{{ __('Lease') }}</option>
                                        <option value="franchiese" @selected(request()->input('purpose')=='franchiese')>{{ __('Franchiese') }}</option>
                                        <option value="business_for_sale" @selected(request()->input('purpose')=='business_for_sale')>{{ __('Business For Sale') }}</option>
									</select>
								</div>
								
                                <div class="col-sm-12 col-md-12 col-lg mt-2 mt-md-2 mt-lg-0">
                                    <select name="city_id" class="form-select select2"
									onchange="document.getElementById('carSearchForm').submit()">
                                        <option value="">{{ __('All City') }}</option>
                                        @foreach ($cities as $city)
                                        <option @selected($city->city_id == request()->input('city_id')) value="{{ $city->city_id }}">
                                            {{ $city->name }}
										</option>
                                        @endforeach
									</select>
								</div>
								
                                <div class="col-sm-12 col-md-12 col-lg mt-2 mt-md-2 mt-lg-0" style="height: 39px;">
                                    <input type="text" name="title" value="{{ request()->input('title') }}"
									class="form-control" placeholder="Title">
								</div>
								
                                {{-- <div class="col-sm-12 col-md-12 col-lg mt-2 mt-md-2 mt-lg-0" style="height: 39px;">
                                    @includeIf('backend.partials.languages')
								</div> --}}
                                <div class="col-sm-12 col-md-12 col-lg mt-2 mt-md-2 mt-lg-0" >
									<a href="{{ request()->fullUrlWithQuery(['export' => 1]) }}" class="btn btn-dark btn-sm" style="padding : 10px;">
										<i class="fa fa-file-excel"></i> Export to Excel
									</a>
								</div>
								
                                <div style="margin-right: 10px;" class=" mt-2 mt-md-0 mt-lg-0">
									<a href="{{ route('admin.property_management.type') }}"
									class="btn btn-primary btn-sm" style="padding: 10.3px 12px;"><i class="fas fa-plus"></i>
									{{ __('Add Property') }}</a>
									
									<button class="btn btn-danger btn-sm bulk-delete d-none"
									data-href="{{ route('admin.property_management.bulk_delete_property') }}" style="padding: 10.3px 12px;">
										<i class="flaticon-interface-5"></i> {{ __('Delete') }}
									</button>
								</div>
								
							</div>
						</form>
					</div>
				</div>
			</div>
			
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        @if (count($properties) == 0)
                        <h3 class="text-center">{{ __('NO PROPERTY FOUND!') }}</h3>
                        @else
                        <div class="table-responsive">
                            <table class="table table-striped mt-3">
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
                                        <th scope="col" style="width: 150px;">{{ __('Approval Status') }}</th>
                                        <th scope="col">{{ __('Featured') }}</th>
										<th scope="col">{{ __('Recommended') }}</th>
										<th scope="col">{{ __('Hot') }}</th>
										<th scope="col">{{ __('Fast Selling') }}</th> 
                                        <th scope="col"  style="width: 150px;">{{ __('Status') }}</th>
                                        <th scope="col">{{ __('Actions') }}</th>
									</tr>
								</thead>
                                <tbody>
                                    @foreach ($properties as $property)
                                    <tr>
                                        <td> 
											<input type="checkbox" class="bulk-check" data-val="{{ $property->id }}">
										</td>
                                        <td class="table-title">
                                            @php
                                            $property_content = $property->getContent($language->id);
                                            if (is_null($property_content)) {
                                            $property_content = $property
                                            ->propertyContents()
                                            ->first();
                                            }
                                            @endphp
                                            @if (!empty($property_content))
                                            @if($property->property_type == 'full')
                                            <a href="{{ route('admin.property_inventory.view',$property->id) }}"
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
                                            @if ($property->vendor_id != 0)
                                            <a
											href="{{ route('admin.vendor_management.vendor_details', ['id' => @$property->vendor->id, 'language' => $defaultLang->code]) }}">{{ @$property->vendor->username }}</a>
                                            @elseif($property->user_id !== '')
											<a
											href="{{ route('admin.user_management.registered_users', ['id' => @$property->user->id]) }}">{{ @$property->user->username }}</a>
                                            @else()
                                            <span class="badge badge-success">{{ __('Admin') }}</span>
                                            @endif
										</td>
                                        <td>
                                            {{ $property->type }}
										</td>
                                        <td> {{ $property->categoryContent?->name }} </td>
                                        <td> {{ ucwords(str_replace('_', ' ', $property->purpose)) }} </td>
                                        <td>
                                            {{ $property->cityContent?->name }}
										</td>
                                        <td>
                                            {{ $property->areaContent?->name }}
										</td>
                                        <td>
											
                                            <form class="d-inline-block"
											action="{{ route('admin.property_management.approve_status') }}"
											method="POST">
                                                @csrf
                                                <input type="hidden" name="property"
												value="{{ $property->id }}">
                                                <select
												onchange="$('.request-loader').addClass('show'); this.form.submit();"
												class="form-control  @if ($property->approve_status == 1) bg-success @elseif($property->approve_status == 0)
												bg-warning @else bg-danger @endif form-control-sm"
												name="approve_status">
                                                    <option value="2"
													{{ $property->approve_status == 2 ? 'selected' : '' }}>
                                                        {{ __('Rejected') }}
													</option>
                                                    <option value="1"
													{{ $property->approve_status == 1 ? 'selected' : '' }}>
                                                        {{ __('Approve') }}
													</option>
                                                    <option value="0"
													{{ $property->approve_status == 0 ? 'selected' : '' }}>
                                                        {{ __('Pending') }}
													</option>
												</select>
											</form>
										</td>
										<td>
											<div class="d-inline-block">
												<select class="form-control {{ $property->is_featured == 1 ? 'bg-success' : 'bg-danger' }} form-control-sm"
													data-id="{{ $property->id }}" data-field="is_featured" onchange="updatePropertyStatus(this)">
													<option value="0" {{ $property->is_featured == 0 ? 'selected' : '' }}>No</option>
													<option value="1" {{ $property->is_featured == 1 ? 'selected' : '' }}>Yes</option>
												</select>
											</div>
										</td>

										<td>	
											<div class="d-inline-block">
												<select class="form-control {{ $property->is_recommended == 1 ? 'bg-success' : 'bg-danger' }} form-control-sm"
													data-id="{{ $property->id }}" data-field="is_recommended" onchange="updatePropertyStatus(this)">
													<option value="0" {{ $property->is_recommended == 0 ? 'selected' : '' }}>No</option>
													<option value="1" {{ $property->is_recommended == 1 ? 'selected' : '' }}>Yes</option>
												</select>
											</div>
										</td>

										<td>
											<div class="d-inline-block">
												<select class="form-control {{ $property->is_hot == 1 ? 'bg-success' : 'bg-danger' }} form-control-sm"
													data-id="{{ $property->id }}" data-field="is_hot" onchange="updatePropertyStatus(this)">
													<option value="0" {{ $property->is_hot == 0 ? 'selected' : '' }}>No</option>
													<option value="1" {{ $property->is_hot == 1 ? 'selected' : '' }}>Yes</option>
												</select>
											</div>
										</td>

										<td>
											<div class="d-inline-block">
												<select class="form-control {{ $property->is_fast_selling == 1 ? 'bg-success' : 'bg-danger' }} form-control-sm"
													data-id="{{ $property->id }}" data-field="is_fast_selling" onchange="updatePropertyStatus(this)">
													<option value="0" {{ $property->is_fast_selling == 0 ? 'selected' : '' }}>No</option>
													<option value="1" {{ $property->is_fast_selling == 1 ? 'selected' : '' }}>Yes</option>
												</select>
											</div>
										</td>
										
                                        <td>
                                            <form id="statusForm{{ $property->id }}" class="d-inline-block"
											action="{{ route('admin.property_management.update_status') }}"
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
													
                                                    @if($property->property_type == 'full')
                                                    <a class="dropdown-item"
													href="{{ route('admin.property_inventory.view',$property->id) }}">
                                                        <span class="btn-label">
                                                            <i class="fas fa-eye"></i> {{ __('View') }}
														</span>
													</a>
                                                    @endif
                                                    <a class="dropdown-item"
													href="{{ route($url, $property->id) }}">
                                                        <span class="btn-label">
                                                            <i class="fas fa-edit"></i> {{ __('Edit') }}
														</span>
													</a>
													
                                                    <form class="deleteForm d-inline-block p-0 dropdown-item"
													action="{{ route('admin.property_management.delete_property') }}"
													method="post">
                                                        @csrf
                                                        <input type="hidden" name="property_id"
														value="{{ $property->id }}">
														
                                                        <button type="submit" class="deleteBtn">
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
			
            <div class="card-footer">
                {{ $properties->appends([
				'vendor_id' => request()->input('vendor_id'),
				'title' => request()->input('title'),
				])->links() }}
			</div>
			
		</div>
	</div>
</div>

{{-- <div class="modal fade" id="featuredRequest" tabindex="-1" role="dialog"
aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<form id="my-checkout-form" class="modal-form"
			action="{{ route('admin.property_management.featured_payment') }}" method="post"
			enctype="multipart/form-data">
				@csrf
				
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLongTitle">{{ __(' Featured Request') }}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				
				<div class="modal-body">
					
					<input type="hidden" id="in_property_id" name="property_id">
					
					
					<div class="form-group">
						<label for=""> {{ 'Select Pricing *' }} </label>
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
							<option value="{{ $offlineGateway->name }}"
							{{ $offlineGateway->name == old('gateway') ? 'selected' : '' }}>
								{{ $offlineGateway->name }}
							</option>
							@endforeach
							@endif
						</select>
						@if ($errors->has('payment_method'))
						<span class="method-error">
							<strong>{{ $errors->first('payment_method') }}</strong>
						</span>
						@endif
					</div>
					
				</div>
				
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary " data-dismiss="modal">
						{{ __('Close') }}
					</button>
					<button type="submit" id="paymentAdmin" class="btn btn-primary ">
						{{ __('Pay') }}
					</button>
				</div>
			</form>
		</div>
	</div>
</div> --}}


@endsection

@section('script')
<script>
    "use strict";
    var stripe_key = "";
</script> 
<script src="{{ asset('assets/js/feature-payment.js') }}"></script> 
<script>
	function updatePropertyStatus(el) {
		let propertyId = $(el).data("id");
		let field = $(el).data("field"); // 👈 dynamic field name
		let status = $(el).val();

		$.ajax({
			url: "{{ route('admin.property_management.update_featured') }}",
			method: "POST",
			data: {
				_token: "{{ csrf_token() }}",
				property_id: propertyId,
				field: field,
				status: status
			},
			success: function(res) {  
				if (status == 1) {
					$(el).removeClass('bg-danger').addClass('bg-success');
				} else {
					$(el).removeClass('bg-success').addClass('bg-danger');
				}
			},
			error: function(xhr) {
				toastr.error("Something went wrong!");
			}
		});
	}

	
	$('.bulk-delete').on('click', function (e) {
		e.preventDefault();
		swal({
			title: 'Are you sure?',
			text: "You won't be able to revert this",
			type: 'warning',
			buttons: {
				confirm: {
					text: 'Yes, delete it',
					className: 'btn btn-success'
				},
				cancel: {
					visible: true,
					className: 'btn btn-danger'
				}
			}
			}).then((Delete) => {
			if (Delete) {
				$(".request-loader").addClass('show');
				let href = $(this).data('href');
				let ids = [];
				
				// take ids of checked one's
				$(".bulk-check:checked").each(function () {
					if ($(this).data('val') != 'all') {
						ids.push($(this).data('val'));
					}
				});
				
				let fd = new FormData();
				for (let i = 0; i < ids.length; i++) {
					fd.append('ids[]', ids[i]);
				}
				
				$.ajax({
					url: href,
					method: 'POST',
					data: fd,
					contentType: false,
					processData: false,
					success: function (data) {
						$(".request-loader").removeClass('show');
						
						if (data.status == "success") {
							location.reload();
						}
					}
				});
				} else {
				swal.close();
			}
		});
	});
	
	
    function updateFeatured(el) {
        let propertyId = $(el).data("property");
        let status = $(el).val();
		
        $.ajax({
            url: "{{ route('admin.property_management.featured_payment') }}", // your route
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                property_id: propertyId,
                status: status
			},
            success: function(res) {
                // Optional: show a toast or change color dynamically
                if (status == 1) {
                    $(el).removeClass('bg-danger').addClass('bg-success');
					} else {
                    $(el).removeClass('bg-success').addClass('bg-danger');
				}
			},
            error: function(xhr) {
                alert("Something went wrong!");
			}
		});
	}
</script>
@endsection
