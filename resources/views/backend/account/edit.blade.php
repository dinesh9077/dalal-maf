<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

@extends('backend.layout')
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
    background: #EEEAFB;
    white-space: nowrap;
    color: #000;
    font-weight: 600;
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

.modal-input{
	width: 100%;
	height: 40px;
	border:1px solid  #ebedf2 ;
	outline: none;
	padding: 0px 10px;
	border-radius: 5px;
}

.invoice-middle-main table th {
	background-color: #E5E5E5;
}


	.invoice-middle-main table th:first-child {
	border-radius: 10px 0px 0px 0px;
}

.invoice-middle-main table th:last-child {
	border-radius:  0px 10px 0px  0px;
}
body[data-background-color="white"] .select2.select2-container .select2-selection {
	height: 40px;
}

/* invoice css end */
</style>
@section('content')

<div class="page-content">
	<form method="post" action="{{route('admin.account.sales-bill.update',['id'=>$saleInvoice->id])}}" id="invoice_update_form" enctype="multipart/form-data">
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
												<label class="modal-label" style="    font-weight: bold;">Customer<span class="text-danger">*</span></label>
												<select class="modal-input select2" id="customer_id" name="customer_id" onchange="customerDetails()">
													<option value="">Select Customer</option>
													@foreach($customers as $customer)
													<option value="{{$customer->id}}" <?php echo ($customer->id == $saleInvoice->customer_id)?'selected':''; ?>>{{$customer->name}}</option>
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
												<label class="modal-label">Invoice Number <span class="text-danger">*</span></label><br>
												<input class="modal-input" type="text" name="invoice_number" id="invoice_number" value="{{$saleInvoice->invoice_number}}" style="width : 100%;">
												<input class="modal-input" type="hidden" name="prefix" value="{{$saleInvoice->prefix}}" style="width : 100%;">
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
												<label class="modal-label">Invoice Date <span class="text-danger">*</span></label>
												<input class="modal-input" type="text" name="invoice_date" id="invoice_date" value="{{$saleInvoice->invoice_date}}">
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
												<label class="modal-label">Payment Due Date <span class="text-danger">*</span></label>
												<input class="modal-input datepicker" type="text" name="due_date" id="due_date"  value="{{$saleInvoice->due_date}}">
											</div>
										</div>

										<div class="col-lg-6">
											<div class="form-group">
												<label class="modal-label">recurring ? <span class="text-danger">*</span></label>
												<select name="recurring" class="modal-input select2" id="recurring" required>
													<option value="0" <?php echo ($saleInvoice->recurring == 0)?'selected':''; ?>>No</option>
													<option value="1" <?php echo ($saleInvoice->recurring == 1)?'selected':''; ?>>Every 1 month</option>
													<option value="2" <?php echo ($saleInvoice->recurring == 2)?'selected':''; ?>>Every 2 months</option>
													<option value="3" <?php echo ($saleInvoice->recurring == 3)?'selected':''; ?>>Every 3 months</option>
													<option value="4" <?php echo ($saleInvoice->recurring == 4)?'selected':''; ?>>Every 4 months</option>
													<option value="5" <?php echo ($saleInvoice->recurring == 5)?'selected':''; ?>>Every 5 months</option>
													<option value="6" <?php echo ($saleInvoice->recurring == 6)?'selected':''; ?>>Every 6 months</option>
													<option value="7" <?php echo ($saleInvoice->recurring == 7)?'selected':''; ?>>Every 7 months</option>
													<option value="8" <?php echo ($saleInvoice->recurring == 8)?'selected':''; ?>>Every 8 months</option>
													<option value="9" <?php echo ($saleInvoice->recurring == 9)?'selected':''; ?>>Every 9 months</option>
													<option value="10" <?php echo ($saleInvoice->recurring == 10)?'selected':''; ?>>Every 10 months</option>
													<option value="11" <?php echo ($saleInvoice->recurring == 11)?'selected':''; ?>>Every 11 months</option>
													<option value="12" <?php echo ($saleInvoice->recurring == 12)?'selected':''; ?>>Every 12 months</option>
													<option value="custom" <?php echo ($saleInvoice->recurring == "custom")?'selected':''; ?>>Custom</option>
												</select>
											</div>
										</div>

										<div class="col-lg-6 custom_recurring" style="display:none;">
											<div class="form-group">
												<input class="modal-input" type="text" name="repeat_every_custom" value="{{$saleInvoice->repeat_every_custom}}" id="repeat_every_custom">
											</div>
										</div>
										<div class="col-lg-6 custom_recurring" style="display:none;">
											<div class="form-group">
												<select name="repeat_type_custom" class="modal-input select2" id="repeat_type_custom">
													<option value="days" <?php echo ($saleInvoice->repeat_type_custom == "days")?'selected':''; ?>>Day(s)</option>
													<option value="weeks" <?php echo ($saleInvoice->repeat_type_custom == "weeks")?'selected':''; ?>>Week(s)</option>
													<option value="month" <?php echo ($saleInvoice->repeat_type_custom == "month")?'selected':''; ?>>Month(s)</option>
													<option value="year" <?php echo ($saleInvoice->repeat_type_custom == "year")?'selected':''; ?>>Years(s)</option>
												</select>
											</div>
										</div>
										<div class="col-lg-12 cycles"  style="display:none;">
											<div class="form-group">
												<div class="infinity-sec">
													<input type="number" class="modal-input" name="cycle" id="cycles" value="{{$saleInvoice->cycle}}" readonly>
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
							<table id="account_datatable" class="table table-bordered  dt-responsive nowrap extra"
							style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
									<tr>
										<th style="width: 15%;">item <span class="text-danger">*</span></th>
										<th>Item Description <span class="text-danger">*</span></th>
										<th style="width: 10%;">Price Per Unit</th>
										<th style="width: 15%;">Area / Quantity</th>
										<th style="width: 15%;">Unit <span class="text-danger">*</span></th>
										<th class="text-right">Total</th>
										<th class="text-right"></th>
									</tr>
								</thead>
								<tbody>
									<tbody id="invoice_row">
										<?php
											$j = 0;
											$k = 0;
											if(count($invoiceItems) > 0)
											{
												foreach($invoiceItems as $key => $invoiceItem)
												{
													$i = $key + 2;
													$taxs = [];
													if($invoiceItem->tax )
													{
														$taxs = json_decode($invoiceItem->tax,true);
													}
												?>
												<input type="hidden" name="item_id[{{$key}}]" value="{{$invoiceItem->id}}">
                        <input type="hidden" name="status_item_id[{{$key}}]" value="{{$invoiceItem->item_id }}" id="item_id_{{$i}}" style="margin-bottom : 10px;
	height: 40px;
	border:1px solid  #ebedf2 ;
	outline: none;
	padding: 0px 10px;
	border-radius: 5px;">

												<tr class="row_id_{{$i}}">
													<td>
														 <select class="modal-input select2 item_value" name="items[{{$key}}]" id="item_{{$key}}" required>
                              <option value="">Select Item</option>
                              @foreach($propertyStatus as $prtStatus)
                                  @php
                                      $wingName = explode('/', optional($prtStatus->wing)->wing_name)[0] ?? '';
                                      $floorName = explode('/', optional($prtStatus->floors)->floor_name)[0] ?? '';
                                      $flatNo = optional($prtStatus->Units)->flat_no ?? '';
                                  @endphp

                                  <option value="{{ $prtStatus->property_id }}" <?php echo ($prtStatus->id == $invoiceItem->item_id)?'selected':''; ?> data-item-id="{{ $prtStatus->id }}" data-price="{{ $prtStatus->Units->price}}" data-unit="{{ $prtStatus->Units->unit->unit_name}}" data-description="{{ trim($wingName) }}/{{ trim($floorName) }}/{{ $flatNo }}">
                                      {{optional($prtStatus->property->propertyContent)->title}}/{{ trim($wingName) }}/{{ trim($floorName) }}/{{ $flatNo }}
                                  </option>
                              @endforeach
                            </select>
													</td>
													<td>
														<input name="details[{{$key}}]" id="details_{{$i}}" class="modal-input" rows="1" placeholder="Enter item description" value="{{$invoiceItem->details}}" required>
													</td>
													<td>
														<input class="modal-input invo_price text-right" placeholder="Price" type="text" name="price[{{$key}}]" value="{{Helper::decimalsprint($invoiceItem->price,2)}}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" id="price_{{$i}}">
													</td>
													<td>
														<input class="modal-input invo_qty" placeholder="Qnty." type="text" name="quantity[{{$key}}]" value="{{$invoiceItem->quantity}}" id="qty_{{$i}}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" max="1" readOnly>
													</td>
													<td>
                            <input type="text" name="unit[{{$key}}]" id="unit_{{$key}}" value="{{$invoiceItem->unit}}" readOnly style="height : 40px; 	width: 100%;
	height: 40px;
	border:1px solid  #ebedf2 ;
	outline: none;
	padding: 0px 10px;
	border-radius: 5px;">
														{{-- <select class="modal-input select2" name="unit[{{$key}}]" id="unit_{{$i}}" required>
															<option value="">Select Unit</option>
															@foreach($accountUnits as $accountUnit)
															<option value="{{$accountUnit->unit_name}}" <?php echo ($invoiceItem->unit == $accountUnit->unit_name)?'selected':''; ?>>{{$accountUnit->unit_name}}</option>
															@endforeach
															<option value="Person" <?php echo ($invoiceItem->unit == 'Person')?'selected':''; ?>>Person</option>
														</select> --}}
													</td>
													<td class="text-right">
														<span class="currency_wrapper"></span>
														<span class="total" id="price_text_{{$i}}">{{$amount = $invoiceItem->quantity * $invoiceItem->price}}</span>
													</td>
													<td class="text-right">
														<button type="button" class="main-btn delet-buss delet-btn delet-btn remove_row" href="javascript:;" id="{{$i}}" style="cursor:pointer" title="Remove row">
															<i class="fa-solid fa-trash"></i>
														</button>
													</td>
												</tr>

												<tr class="row_id_{{$i}}">
													<td></td>
													<td></td>
													<td></td>
													<td style="text-align: end;">
														<label class="label-main">Discount </label>
													</td>
													<td>
														<input type="text" id="discount_{{$i}}" name="discount[{{$key}}]" value="{{Helper::decimalsprint($invoiceItem->discount,2)}}" class="modal-input invo_discount" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
													</td>
													<td class="text-right">
														<span class="currency_wrapper"></span>
														<span class="total" id="discount_text_{{$i}}">{{$amount * $invoiceItem->discount / 100}}</span>
													</td>
													<td class="text-right"></td>
												</tr>

												<tr class="row_id_{{$i}}">
													<td></td>
													<td></td>
													<td> </td>
													<td style="text-align: end;vertical-align: baseline;">
														<label class="label-main">Tax </label>
													</td>
													<td id="gst_row_id_{{$i}}">
														<?php
															if(count($taxs) > 0)
															{
																foreach($taxs as $tax)
																{
																	$j++
																?>
																<div class="taxdelete" style="margin-bottom: 10px;" id="gst_row_<?php echo $i; ?>_<?php echo $j; ?>">
																	<select class="modal-input changesel" id="tax_<?php echo $i; ?>_<?php echo $j; ?>" data-id="<?php echo $i; ?>" data-keyid="<?php echo $key; ?>" name="tax[<?php echo $key; ?>][]" style="text-transform: capitalize;display:unset;" >
																		<option value="">Select Tax</option>
																		<option value="GST-18.00" data-rate="18.00" data-name="GST 18.00%" <?php echo ($invoiceItem->tax == 'GST-18.00')?'selected':''; ?>>GST 18.00%</option>
                                    <option value="SGST-12.00" data-rate="12.00" data-name="SGST 12.00%" <?php echo ($invoiceItem->tax == 'SGST-12.00')?'selected':''; ?>>SGST 12.00%</option>
                                    <option value="CGST-12.00" data-rate="12.00" data-name="CGST 12.00%" <?php echo ($invoiceItem->tax == 'CGST-12.00')?'selected':''; ?>>CGST 12.00%</option>
                                    <option value="TDS-10.00" data-rate="10.00" data-name="TDS 10.00%" <?php echo ($invoiceItem->tax == 'TDS-10.00')?'selected':''; ?>>TDS 10.00%</option>
																	</select>
																	<span> <i class="mdi mdi-delete-outline text-danger remove_gst_row" id="<?php echo $j; ?>" data-id="<?php echo $i; ?>" aria-hidden="true" style="font-size: 22px;"></i></span>
																</div>
																<?php
																}
															}
														?>
														<div style="margin-bottom: 10px;">
															<select class="modal-input selectgst select2" id="tax_<?php echo $i; ?>_1"  data-keyid="<?php echo $key; ?>" data-id="<?php echo $i; ?>" style="text-transform: capitalize;display:unset;" >
																<option value="">Select Tax</option>
																	<option value="GST-18.00" data-rate="18.00" data-name="GST 18.00%" <?php echo ($invoiceItem->tax == 'GST-18.00')?'selected':''; ?>>GST 18.00%</option>
                                  <option value="SGST-12.00" data-rate="12.00" data-name="SGST 12.00%" <?php echo ($invoiceItem->tax == 'SGST-12.00')?'selected':''; ?>>SGST 12.00%</option>
                                  <option value="CGST-12.00" data-rate="12.00" data-name="CGST 12.00%" <?php echo ($invoiceItem->tax == 'CGST-12.00')?'selected':''; ?>>CGST 12.00%</option>
                                  <option value="TDS-10.00" data-rate="10.00" data-name="TDS 10.00%" <?php echo ($invoiceItem->tax == 'TDS-10.00')?'selected':''; ?>>TDS 10.00%</option>
															</select>
															<span>
																<div class="room_right-main including_amount1_<?php echo $i; ?>" style="padding: 5px 0;pointer-events:none;display:none;" >
																	<input type="checkbox" name="include_tax[]" id="including_<?php echo $i; ?>" data-id="<?php echo $i; ?>" <?php echo ($invoiceItem->include_tax == 1)?'checked':''; ?>  value="1">
																	<label for="including1_<?php echo $i; ?>" data-toggle="tooltip" data-placement="top" data-trigger="hover" title="Including Tax"></label>
																</div>
															</span>
														</div>
													</div>
												</td>
												<td class="text-right" id="gst_row_text_{{$i}}">
													<?php if(count($taxs) > 0)
														{
															foreach($taxs as $keyt => $tax)
															{
																$k++;
															?>
															<div class="total-amount" id="gst_text_<?php echo $i; ?>_<?php echo $k; ?>">
																<span class="total" id="tax_text_<?php echo $i; ?>_<?php echo $k; ?>">0.00</span>
															</div>
															<?php
															}
														}
													?>
													<div class="total-amount">
														<span class="total">-</span>
													</div>
												</td>
												<td></td>
											</tr>
										<?php } ?>
										<input type="hidden" value="<?php echo ($j)?$j:1; ?>" id="total_gst_row">
									<?php } ?>
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
							<textarea class="modal-input" name="footer_note" rows="2" style="height : 150px !important" placeholder="Enter a footer for this invoice (eg. Tax info, Thank you note, etc.)">{{$saleInvoice->footer_note}}</textarea>
						</div>
					</div>
				</div>
				<input type="hidden" name="total_item" id="total_item" value="{{count($invoiceItems)+1}}">
					<input type="hidden" name="base_url" id="base_url" value="{{url('/')}}">
					<input type="hidden" name="amount_decimal" id="amount_decimal" value="2">
				<div class="invoice-save">
					<button type="submit" class="add_item_btn-save">Save</button>
				</div>
			</div>
		</div>
	</form>
</div>

@endsection

@section('script')
<script src="{{asset('assets/js/invoice.js')}}"></script>
<script>
  var prefix = '/admin';
	$('.select2').select2({
		theme: 'bootstrap4',
		width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
		placeholder: $(this).data('placeholder'),
		allowClear: Boolean($(this).data('allow-clear')),
	});

	$(function() {
		$('.datepicker').daterangepicker({
			singleDatePicker: true,
			locale: {
				format: 'YYYY-MM-DD',
			}
		});
	});

	setTimeout(function()
	{
		$('#recurring').trigger('change');
	},100)

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
	customerDetails()
	function customerDetails()
	{
		var customer_id = $('#customer_id').val();
		$('#customer_bill').hide();
		if(customer_id != "")
		{
			$.get("{{url('account/sales-bill/customer-details')}}/"+customer_id,
			function(res)
			{
				$('#customer_bill').html(res.output);
				$('#customer_bill').show();
			});
		}
	}


	$(document).ready(function()
	{
		var j = <?php echo ($j)?$j:1; ?>;
		$(document).on('change', ".selectgst", function(event)
		{
			var id = $(this).attr('data-id');
			var keyid = $(this).attr('data-keyid');
			j++;
			$('#total_gst_row').val(j);
			var value = $(this).val();
			var name = $(this).find('option:selected').attr('data-name');
			$(this).val('');
			var html = '';
      html +='<div class="taxdelete" style="margin-bottom: 10px;" id="gst_row_'+id+'_'+j+'"><select class="modal-input changesel select2 taxvalue_'+id+'" id="tax_'+id+'_'+j+'" data-id="'+id+'" name="tax['+id+'][]" style="text-transform: capitalize;display:unset;"><option value="">Select Tax</option><option value="GST-18.00" data-rate="18.00" data-name="GST 18.00%">GST 18.00%</option><option value="SGST-12.00" data-rate="12.00" data-name="SGST 12.00%">SGST 12.00%</option> <option value="CGST-12.00" data-rate="12.00" data-name="CGST 12.00%">CGST 12.00%</option> <option value="TDS-10.00" data-rate="10.00" data-name="TDS 10.00%">TDS 10.00%</option></select><span> <i class="fas fa-trash-alt remove_gst_row" id="'+j+'" data-id="'+id+'" aria-hidden="true" style="font-size: 22px;"></i></span></div>';
			// html +='<div class="taxdelete" style="margin-bottom: 10px;" id="gst_row_'+id+'_'+j+'"><span> <i class="mdi mdi-delete-outline text-danger remove_gst_row" id="'+j+'" data-id="'+id+'" aria-hidden="true" style="font-size: 22px;"></i></span></div>';
			$('#gst_row_id_'+id).prepend(html);

			var totalAmountWithTax = $('#price_'+id).val();
			$('.including_amount_'+id).hide();
			if(totalAmountWithTax != "0")
			{
				$('.including_amount_'+id).hide();
			}

			var html1 = '';
			html1 +='<div class="total-amount" id="gst_text_'+id+'_'+j+'"><span class="total" id="tax_text_'+id+'_'+j+'">0.00</span></div> ';
			$('#gst_row_text_'+id).prepend(html1);
			$('#tax_'+id+'_'+j+'').val(value);
			inv_cal_final_total();

		});

		$(document).on('click', '.remove_gst_row', function () {
			var row_j = $(this).attr("id");
			var row_id = $(this).attr("data-id");
			$('#gst_row_' + row_id+'_'+row_j).remove();
			$('#gst_text_' + row_id+'_'+row_j).remove();
			inv_cal_final_total();
		});

		$(document).on('change', ".changesel", function(event)
		{
			inv_cal_final_total();
		});
	});

	$('#invoice_update_form').submit(function(event) {
		event.preventDefault();
		//run_waitMe($('body'), 1, 'img');
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
				else
				{
					toastrMsg(res.status,res.msg);
					setTimeout(function ()
					{
						window.location.href = "{{route('admin.sales-bill.index')}}";
					}, 1500);
				}
			}
		});
	});
    $(document).on('change', '.item_value', function () {
      let index = $(this).attr('id').split('_')[1]; // Extract the number from id, e.g. item_1 → 1
      let selectedOption = $(this).find('option:selected');

      let price = selectedOption.data('price') || 0;
      let unit = selectedOption.data('unit') || '';
      let desc = selectedOption.data('description');
      let ItemId = selectedOption.data('item-id');

      // Set the values in inputs
      $('#price_' + index).val(price);
      $('#unit_' + index).val(unit);
      $('#details_'+ index).val(desc);
       $('#item_id_'+ index).val(ItemId);
      inv_cal_final_total()
  });
</script>
@endsection
