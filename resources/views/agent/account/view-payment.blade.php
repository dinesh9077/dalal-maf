<style>
	.modal-header{
		display: flex;
		align-items: center;
	}
	
</style>



<div class="add-modal-main modal fade" id="record_view_payment_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel"> View A Payment For This Invoice </h5>
				<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" style="font-size : 24px; color : #000000; border : none; background : white;">×</button>
			</div>

			<div class="modal-body">
				<div class="table-responsive">
					<table class="table table-bordered  dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
						<thead>
							<tr>
								<th>Bank</th>
								<th>Amount</th>
								<th>Payment Date</th>
								<th>Payment Method</th>
								<th>Status</th>
								<th>Remark</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@foreach($billingInvices as $billingInvice)
							<tr>
								<td>{{$billingInvice->bank_id}}</td>
								<td>{{Helper::decimal_number($billingInvice->amount,2)}}</td>
								<td>{{$billingInvice->payment_date}}</td>
								<td>{{$billingInvice->payment_method}}</td>
								<td>
									@if($billingInvice->status == 1)
									<span class="badge badge-success">Paid</span>
									@else
									<span class="badge badge-info">Partial</span>
									@endif
								</td>
								<td><span class="more">{{$billingInvice->remarks}}</span></td>
								<td>
									@if(config('permission.sales_invoice.delete'))
										@if($paidInvoice > 0 && $billingInvice->status == 1)
											<a href="{{url('account/sales-bill/billing-delete',$billingInvice->id)}}" onclick=	"deleteInvoiceBilling(this,event)"><button type="button" class="btn btn-icon waves-effect waves-light action-icon2"><i class="mdi mdi-delete-outline" aria-hidden="true"></i></button></a>
										@endif

										@if($paidInvoice == 0)
											<a href="{{url('account/sales-bill/billing-delete',$billingInvice->id)}}" onclick=	"deleteInvoiceBilling(this,event)"><button type="button" class="btn btn-icon waves-effect waves-light action-icon2"><i class="mdi mdi-delete-outline" aria-hidden="true"></i></button></a>
										@endif
									@endif
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
			</div>

		</div>
	</div>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script>
		var invoice_id = "{{$invoice_id}}";
		function deleteInvoiceBilling(obj,event)
		{
			//$(obj).closest('tr').remove();
			event.preventDefault();
			Swal.fire({
				title:"Are you sure?",
				text:"You won't be able to revert this!",
				type:"warning",
				showCancelButton:!0,
				confirmButtonColor:"#3085d6",
				cancelButtonColor:"#d33",
				confirmButtonText:"Yes, delete it!"
			}).then(function(t)
			{
				t.value&&

				$.post(obj,{_token:"{{csrf_token()}}"},function(res)
				{
					if(res.status == "error")
					{
						Swal.fire("Error!",res.msg,"error")
					}
					else
					{
						$('body').find('#modal-view-render').html('');
						recordViewPayment("{{url('account/sales-bill/record-view-payment')}}/"+invoice_id,event)
						Swal.fire("Deleted!",res.msg,"success")
						dataTable.draw();
					}
				});
			})
		}

	</script>
</div>
