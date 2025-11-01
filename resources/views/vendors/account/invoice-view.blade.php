@extends('vendors.layout')

@section('content')

<style>

	.invoice-btn-main {
  display: flex;
  gap: 10px;
  justify-content: end;
  margin-bottom: 15px;
  width: 85.5%;
}

	.table-responsive {
    	white-space: unset !important;
    }
</style>

<div class="content">
	<div class="container-fluid">
		<div class="agent-main">
			<div class="row">
				<div class="card-box w-100 brdr-rds">
					<div class="col-12">
						<div class="invoice-btn-main">
								<a href="{{ url('vendor/account/sales-bill/export',$id)}}">
									<button class="add-main" data-toggle="modal delet-buss">Download Invoice</button>
								</a>
                <a href="{{ url('vendor/account/sales-bill/edit',$id)}}">
                  <button class="add-main" data-toggle="modal">Edit Invoice</button>
                </a>

						</div>
					</div>
				  @include('vendors.account.invoice_style_1',['amount_due_show'=>'invoice'])
				</div>
			</div>
		</div>
	</div>
</div>


@endsection
