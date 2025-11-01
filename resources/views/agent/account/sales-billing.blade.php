@extends('agent.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Sales Billing') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('agent.dashboard') }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Sales Billing') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card-title d-inline-block">{{ __('All Sales Billing') }}</div>
                        </div>
                        <div class="col-lg-6">
                            <a href="{{ route('agent.accounting') }}"
                                class="btn  btn-sm float-lg-right" style="background-color: #947E41; color : white;"><i class="fas fa-plus"></i>
                                {{ __('Add Sale Report') }}</a>

                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($prtStatus) == 0)
                                <h3 class="text-center mt-2">{{ __('NO SALES BILLING FOUND') . '!' }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3" id="basic-datatables">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">{{ __('Status') }}</th>
                                                <th scope="col">{{ __('Date') }}</th>
                                                <th scope="col">{{ __('Number') }}</th>
                                                <th scope="col">{{ __('Customer') }}</th>
                                                <th scope="col">{{ __('Total') }}</th>
                                                <th scope="col">{{ __('Amount Due') }}</th>
                                                <th scope="col">{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                          @foreach ($prtStatus as $status)

                                              <tr>
                                                  <td>{{ $loop->iteration }}</td>

                                                    @php
                                                      $amount_due = Helper::getInvoiceRecordPartial($status->grand_total,$status->id);
                                                      $billing_status = "";
                                                      $billing_class = "";
                                                      $is_rec = '';
                                                      if($status->billing_status == "1")
                                                      {
                                                        $billing_class = 'badge-success';
                                                        $billing_status = 'Paid';
                                                      }
                                                      if($status->billing_status == "2")
                                                      {
                                                         $billing_class = 'badge-info';
                                                        $billing_status = 'Partial';
                                                      }
                                                      if($status->billing_status == "3")
                                                      {
                                                        $billing_class = 'badge-warning';
                                                        $billing_status = 'Pending';
                                                      }
                                                      if($status->recurring_invoice == 1)
                                                      {
                                                        $is_rec = '<br><span class="badge badge-primary">Recurring</span>';
                                                      }
                                                    @endphp
                                                  <td><span class="badge {{$billing_class}}">{{$billing_status}}</span></td>
                                                  <td>{{ \Carbon\Carbon::parse($status->invoice_date)->format('d-m-Y') }}</td>
                                                  <td>{{(($status->prefix)?$status->prefix.'-':'').$status->invoice_number.''.$is_rec}}</td>
                                                  <td>{{$status->name}}</td>
                                                  <td>{{Helper::decimal_number($status->grand_total)}}</td>
                                                  <td>{{Helper::decimal_number($amount_due)}}</td>
                                                  <td>

                                                    <a href="{{ url('agent/account/sales-bill/invoice-view', $status->id) }}" class="action-btn">
                                                        <i class="fas fa-eye new-icon-color"></i>
                                                        {{-- <img src="{{ asset('assets/images/custom-images/action-ico3.svg') }}" alt=""> --}}
                                                    </a>

                                                    <a href="{{ url('agent/account/sales-bill/edit', $status->id) }}" class="action-btn">
                                                        <i class="fas fa-edit new-icon-color"></i>
                                                        {{-- <img src="{{ asset('assets/images/custom-images/action-ico4.svg') }}" alt=""> --}}
                                                    </a>

                                                    <a href="javascript:;"
                                                      class="action-btn"
                                                      data-url="{{ url('agent/account/sales-bill/delete', $status->id) }}?datatable=true"
                                                      data-message="Are you sure you want to delete this item?"
                                                      onclick="deleteConfirmModal(this, event)">
                                                        <i class="fas fa-trash new-icon-color"></i>
                                                        {{-- <img src="{{ asset('assets/images/custom-images/action-ico5.svg') }}" alt="Delete"> --}}
                                                    </a>

                                                    @if($amount_due != 0)
                                                        <a class="action-btn convert-btn record1"
                                                          href="{{ url('agent/account/sales-bill/record-payment', $status->id) }}"
                                                          onclick="recordInvoicePayment(this,event)">
                                                            Record Payment
                                                        </a>
                                                    @endif

                                                    <a class="action-btn convert-btn record2"
                                                      href="{{ url('agent/account/sales-bill/record-view-payment', $status->id) }}"
                                                      onclick="recordViewPayment(this,event)">
                                                        View Payment
                                                    </a>

                                                    @if($status->recurring != "0" && $status->recurring_stop == 0)
                                                        <a class="action-btn convert-btn record3"
                                                          href="{{ url('agent/account/sales-bill/stop-recurring', $status->id) }}"
                                                          onclick="stopRecurring(this,event)">
                                                            Stop Recurring
                                                        </a>
                                                    @endif

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

@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
	var exportBtn = [];



	function recordInvoicePayment(obj,event)
	{
		event.preventDefault();
		$.get(obj, function(res)
		{
			$('body').find('#modal-view-render').html(res.view);
			$('#record_payment_modal').modal('show');
		});
	}
	function recordViewPayment(obj,event)
	{
		event.preventDefault();
		$.get(obj, function(res)
		{
			$('body').find('#modal-view-render').html(res.view);
			$('#record_view_payment_modal').modal('show');
		});
	}

	function deleteSaleInvoice(obj,event)
	{
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
					Swal.fire("Deleted!",res.msg,"success")

				}
			});
		})
	}

	function stopRecurring(obj,event)
	{
		event.preventDefault();
		Swal.fire({
			title:"Are you sure to stop recurring?",
			text:"You won't be able to revert this!",
			type:"warning",
			showCancelButton:!0,
			confirmButtonColor:"#3085d6",
			cancelButtonColor:"#d33",
			confirmButtonText:"Yes!"
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
					Swal.fire("Stop!",res.msg,"success")

				}
			});
		})
	}
</script>
@endsection
