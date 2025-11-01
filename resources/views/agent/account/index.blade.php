<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

@extends('agent.layout')
<style>
/* invoice css start */

.invoice-main .header-title {
    font-size: 24px;
    text-transform: capitalize;
    font-weight: 600;
    color: #000;
    margin-bottom: 20px;
}

.invoice-middle-main table td,
.invoice-middle-main table th {
    border: none !important;
}

.invoice-middle-main table th {
    background: #e5e5e5;
    white-space: nowrap;
    color: #000;
    font-weight: 600;
}
.invoice-middle-main table th:first-child {
	border-radius: 10px 0px 0px 0px;
}

.invoice-middle-main table th:last-child {
	border-radius:  0px 10px 0px  0px;
}

.invoice-middle-main .btn.btn-gradient.bootstrap-touchspin-up.delet-btn {
    padding: 6px 6px;
    line-height: 1;
}

.invoice-middle-main table td {
    padding: 20px 14px !important;
    white-space: nowrap;
}

.total-table tr td {
    padding: 14px 14px !important;
}

.billto h4 {
    color: #000;
    font-size: 14px;
    margin: 5px 0;
    font-weight: 500;
}

.in-foot textarea {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #000;
}

.total-table td input {
    padding: 7px;
    width: 70px;
}

.invoice-footer textarea {
    width: 100%;
    border-radius: 5px;
    border: 1px solid #a3a3a3;
    padding: 12px;
}

.invoice-save {
    text-align: center;
    margin-top: 25px;
}

.sales-in-main .row {
    row-gap: 15px;
}

.invoice-main {
    background: #fff;
    border-radius: 10px;
    padding: 20px;
    box-shadow: var(--box-shadow);
}

.sales-in-main .main-btn {
    width: 100%;
    margin-bottom: 15px;
}

textarea.modal-input {
    width: 100%;
    border-radius: 10px;
    border: 1px solid #D8E0F0;
    background: #FFF;
    box-shadow: 0px 1px 2px 0px rgba(184, 200, 224, 0.22);
    font-size: 14px;
    color: #7D8592;
    width: 100%;
}

.footer-area.filter-deta {
    border-radius: 0;
    background: transparent;
    box-shadow: none;
    padding: 0;
    margin-bottom: 0;
    margin-top: 20px;
}


body[data-background-color="white"] .select2.select2-container .select2-selection {
	height: 40px;
}



/* invoice css end */
</style>
@section('content')
<div class="page-content">
	<form method="post" action="{{route('account.sales-bill.store')}}" id="invoice_create_form" enctype="multipart/form-data">
		{{ csrf_field() }}
		<div class="import-row-main">
			<div class="invoice-main filter-deta new-box-shadow-invoice">
				<div class="card-box">
					<div class="top-invoice-main">
						<h4 class="header-title">Invoice</h4>
						<div class="row justify-content-between">
							<div class="col-lg-4">
								<div class="sales-in-main">
									<div class="row">
										<div class="col-lg-12">
											<div class="add-invoice-left">
												<label class="modal-label">Customer <span class="text-danger">*</span></label>
												<select class="modal-input select2" id="customer_id" name="customer_id" onchange="customerDetails()">
													<option value="">Select Customer</option>
													@foreach($customers as $customer)
													<option value="{{$customer->id}}">{{$customer->name}}</option>
													@endforeach
												</select>
											</div>
										</div>

										<div class="col-lg-12">
											{{-- <button class="main-btn" type="button" onclick="addCustomer()"> Add Customer</button> --}}
											<div class="add-text-deta" id="customer_bill" style="display:none;">

											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="add-invoice-right">
									<div class="row">
										<div class="col-12">
											<div class="form-group">
												<label class="modal-label">Invoice Number <span class="text-danger">*</span></label>
												<input class="form-control" type="text" name="invoice_number" id="invoice_number" value="{{Helper::getAutoInvoiceNumber('invoice')}}">
												<input class="form-control" type="hidden" name="prefix" value="sale">
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
												<label class="modal-label">Invoice Date <span class="text-danger">*</span></label>
												<input class="form-control" type="text" name="invoice_date" id="invoice_date" value="{{date('Y-m-d')}}" readonly style="background-color: transparent  !important;background: transparent  !important;">
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
												<label class="modal-label">Payment Due Date <span class="text-danger">*</span></label>
												<input class="form-control due_datepicker" type="text" name="due_date" id="due_date">
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
												<label class="modal-label">recurring ? <span class="text-danger">*</span></label>
												<select name="recurring" class="modal-input select2" id="recurring">
													<option value="0">No</option>
													<option value="1">Every 1 month</option>
													<option value="2">Every 2 months</option>
													<option value="3">Every 3 months</option>
													<option value="4">Every 4 months</option>
													<option value="5">Every 5 months</option>
													<option value="6">Every 6 months</option>
													<option value="7">Every 7 months</option>
													<option value="8">Every 8 months</option>
													<option value="9">Every 9 months</option>
													<option value="10">Every 10 months</option>
													<option value="11">Every 11 months</option>
													<option value="12">Every 12 months</option>
													<option value="custom">Custom</option>
												</select>
											</div>
										</div>

										<div class="col-lg-6 custom_recurring" style="display:none;">
											<div class="form-group">
												<input class="form-control" type="text" name="repeat_every_custom" value="1" id="repeat_every_custom">
											</div>
										</div>
										<div class="col-lg-6 custom_recurring" style="display:none;">
											<div class="form-group">
												<select name="repeat_type_custom" class="form-select select2" id="repeat_type_custom">
													<option value="days">Day(s)</option>
													<option value="weeks">Week(s)</option>
													<option value="month">Month(s)</option>
													<option value="year">Years(s)</option>
												</select>
											</div>
										</div>
										<div class="col-lg-12 cycles"  style="display:none;">
											<div class="form-group">
												<div class="infinity-sec">
													<input type="number" class="form-control" name="cycle" id="cycles" value="0" readonly>
													<div class="input-group-addon">
														<div class="checkbox">
															<input type="checkbox" value="0" checked="" id="unlimited_cycles">
															<label for="unlimited_cycles">Infinity</label>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card-box">
					<div class="invoice-middle-main">
						<div class="table-responsive">
							<table id="account_datatable" class="table table-bordered  dt-responsive nowrap extra" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
									<tr>
										<th style="width: 15%;">item <span class="text-danger">*</span></th>
										<th>Item Description <span class="text-danger">*</span></th>
										<th style="width: 10%;">Price Per Unit</th>
										<th style="width: 15%;">Area / Quantity </th>
										<th style="width: 15%;">Unit <span class="text-danger">*</span></th>
										<th class="text-right">Total</th>
										<th class="text-right"></th>
									</tr>
								</thead>
								<tbody>
									<tbody id="invoice_row">
									</tbody>
									<tr>
										<td style="width: 10%;">
											<button class="add-main add_item_btn">Add An Item</button>
										</td>
									</tr>
									<table class="total-table">
										<tr>
											<td style="width: 100%;"></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td style="font-weight: bold;color: #000;">Sub Total : </td>
											<td style="font-weight: bold;color: #000;">
												<span class="currency_wrapper"></span><span id="subtotal">0.00</span>
												<input type="hidden" id="subtotal_amount" class="subtotal" name="subtotal" value="">
											</td>
											<td></td>
										</tr>
										<tr>
											<td style="width: 100%;"></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td style="font-weight: bold;color: #000;">Discount : </td>
											<td style="font-weight: bold;color: #000;">
												<span class="currency_wrapper"></span><span id="total_discount">0.00</span>
												<input type="hidden" class="total_discount" name="total_discount" value="">
											</td>
											<td></td>
										</tr>
										<tbody id="gst_total_tax">
										</tbody>
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td style="font-weight: bold;color: #000;">Grand Total : </td>
											<td style="font-weight: bold;color: #000;">
												<span class="currency_wrapper"></span><span id="grandTotal">0</span>
												<input type="hidden" class="grandtotal" name="grand_total" value="">
											</td>
											<td></td>
										</tr>
									</table>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="invoice-main mt-5 new-box-shadow-invoice">
				<div class="card-box">
					<div class="top-invoice-main">
						<h4 class="header-title">Footer</h4>
						<div class="footer-area">
							<textarea class="form-control" name="footer_note" rows="2" placeholder="Enter a footer for this invoice (eg. Tax info, Thank you note, etc.)"></textarea>
						</div>
					</div>
				</div>
				<input type="hidden" name="total_item" id="total_item" value="1">
				<input type="hidden" name="base_url" id="base_url" value="{{url('/')}}">
				<input type="hidden" name="amount_decimal" id="amount_decimal" value="2">
				<div class="invoice-save">
					<button class="add_item_btn-save">Save</button>
				</div>
			</div>
		</div>
	</form>
</div>
@endsection

@section('script')

<script src="{{asset('assets/js/invoice.js')}}"></script>
<script>

  var prefix = '/agent';
	$('.select2').select2({
		theme: 'bootstrap4',
		width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
		placeholder: $(this).data('placeholder'),
		allowClear: Boolean($(this).data('allow-clear')),
	});

	$(function() {
		$('.due_datepicker').daterangepicker({
			singleDatePicker: true,
			locale: {
				format: 'YYYY-MM-DD',
			}
		});
	});

	$('#recurring').change(function()
	{
		$('.custom_recurring').hide();
		$('.cycles').hide();
		if($(this).val() == "custom")
		{
			$('.custom_recurring').show();
			$('.cycles').show();
		}
		if($(this).val() != 0)
		{
			$('.cycles').show();
		}
	});

	$('#unlimited_cycles').click(function()
	{
		if ($('#unlimited_cycles').prop('checked'))
		{
			$('#cycles').attr('readonly',true);
		}
		else
		{
			$('#cycles').attr('readonly',false);
		}
	});

	function customerDetails()
	{
		var customer_id = $('#customer_id').val();
     $("[class^='row_id_']").remove();
		$('#customer_bill').hide();
		if(customer_id != "")
		{
			$.get("{{url('agent/account/sales-bill/customer-details')}}/"+customer_id,
			function(res)
			{
				$('#customer_bill').html(res.output);
				$('#customer_bill').show();
			});
		}
	}

	$('#invoice_create_form').submit(function(event) {
		event.preventDefault();
		// run_waitMe($('body'), 1, 'img');
		var formData = new FormData(this);
		formData.append('_token',"{{csrf_token()}}");
		$.ajax({
			async: true,
			type: $(this).attr('method'),
			url: $(this).attr('action'),
			data: formData,
			cache: false,
			processData: false,
			contentType: false,
			dataType: 'Json',
			success: function (res)
			{
        console.log(res.status);
				// $('body').waitMe('hide');
				if(res.status == "error")
				{
					toastrMsg(res.status,res.msg);
				}
				else if(res.status == "validation")
        {
          $('.error').remove();
          $.each(res.errors, function(key, value) {
            var inputField = $('#' + key);
            var errorSpan = $('<span>')
            .addClass('error text-danger')
            .attr('id', key + 'Error')
            .text(value[0]);
            inputField.parent().append(errorSpan);
          });
        }
        // else if(res.status =='success')
        // {
        //   toastrMsg(res.status,res.msg);
        //   window.location.href = "{{route('vendor.sales-bill.index')}}";
        // }
				else
				{
					toastrMsg(res.status,res.msg);
					setTimeout(function ()
					{
						window.location.href = "{{route('agent.sales-bill.index')}}";
					}, 1500);
				}
			}
		});
	});
</script>
@endsection
