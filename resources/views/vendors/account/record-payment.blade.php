<style>
	.modal-header{
		display: flex;
		align-items: center;
	}
	body[data-background-color="white"] .select2.select2-container .select2-selection {
		height: 40px;
	}
	
	.label-main{
		margin-top: 10px;
	}
</style>


<div class="add-modal-main modal fade" id="record_payment_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered  ">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel">Record Payment</h5>
				<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" style="font-size : 24px; color : #000000; border : none; background : white;">×</button>
			</div>
			<form id="recordPaymentForm" action="{{route('account.record.payment.store')}}" method="post" enctype="multipart/form-data">
				<div class="modal-body">
					<div class="modal-form">
						<div class="row">
							<div class="col-lg-12">
								<div class="row left-row">
									<div class="col-sm-12">
										<label class="label-main">Payment Type</label>
										<select id="payment_type" class="form-control select2" name="payment_type" onchange="getPaymentType()" required>
											<option value="1">Full payment</option>
											<option value="2">Partial Payment</option>
										</select>
									</div>

									<input type="hidden" name="total_amount" id="total_amount" value="{{$saleInvoice->grand_total}}">
									<input type="hidden" name="invoice_id" id="invoice_id" value="{{$invoice_id}}">
									<div class="col-sm-12">
										<label class="label-main">Enter Amount</label>
										<input type="text" id="amount" class="form-control" name="amount" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="{{$amount_due}}" readonly required>
									</div>
									<div class="col-sm-12">
										<label class="label-main">Payment Date</label>
										<input type="text" id="payment_date" class="form-control datepicker" name="payment_date" value="" required>
									</div>
									<div class="col-sm-12">
										<label class="label-main">Bank</label>
                    <input type="text" id="bank_id" class="form-control" name="bank_id" value="" required>
										</select>
									</div>
									<div class="col-sm-12">
										<label class="label-main"> Payment Method</label>
										<select name="payment_method" class="sel-main select2" id="payment_method">
											<option value="">Select payment method</option>
											<option value="Bank payment">Bank payment</option>
											<option value="Cash">Cash</option>
											<option value="Cheque">Cheque</option>
											<option value="Online">Online</option>
											<option value="Others" >Others</option>
										</select>
									</div>
									<div class="col-sm-12">
										<label class="label-main">Remarks</label>
										<textarea type="text" id="remarks" class="form-control" name="remarks"></textarea>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button"  class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-secondary btn-sm">Save</button>
				</div>
			</form>
		</div>
	</div>
	<script>
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

		function getPaymentType()
		{
			if($('#payment_type').val() == 1)
			{
				$('#amount').attr('readonly',true);
				$('#amount').val("{{$amount_due}}");
			}
			else
			{
				$('#amount').attr('readonly',false);
			}
		}

		$('#recordPaymentForm').submit(function(event)
		{
			event.preventDefault();
			$(this).find('button').prop('disabled',true);
			$(this).find('button.spin-button').addClass('loading').html('<span class="spinner"></span>');
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
					$('#recordPaymentForm').find('button').prop('disabled',false);
					$('#recordPaymentForm').find('button').prop('disabled',false);
					if(res.status == "error")
					{
						toastrMsg(res.status,res.msg);
					}
					else
					{
						toastrMsg(res.status,res.msg);
						$('#record_payment_modal').modal('hide');
						$('#record_payment_modal').remove();
						$('.modal-backdrop').remove();
						$('body').css({'overflow': 'auto'});
						dataTable.draw();
					}
				}
			});
		});

	</script>
</div>
