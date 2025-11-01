<div class="table-responsive">
	<table class="table table-centered table-hover mb-0">
		<thead>
			<tr>
				<th>Floor</th>
				@foreach($wings as $wing)
				<th>Wing {{$wing->wing_number}}</th>
				@endforeach
			</tr>
		</thead>
		<tbody>

			@foreach($floors as $key => $floor)

			<tr>
				<td>Floor {{$floor->floor_number}}</td>
				@foreach($wings as $wing)
				<?php
					$flr = App\Models\Property\PrtFloor::where('wing_id',$wing->id)->where('floor_number',$floor->floor_number)->first();
					$floor_details = App\Models\Property\PrtUnit::where('wing_id',$wing->id)->where('floor_id',$flr->id)->get();
				?>
				<td>
					<div class="deta_flor-main">
						@foreach($floor_details as $key1 => $floor_detail)
							<?php
								$color = $floor_detail->property_status == 'Available' ? "graf-box1" : ($floor_detail->property_status == 'Sold' ? "graf-box2" : "graf-box3");
							?>
							<div class="deta_flor filter-deta">
								<img class="{{$color}} floor_images" src="{{asset('assets/img/6899f1b8d959d.png')}}" height="30px" width="30px" >
								<h6>{{$floor_detail->flat_no}} {{ $floor_detail->property_status == 3 && $floor_detail->soldby && $floor_detail->soldby->lead ? '('.$floor_detail->soldby->lead->lead_name.')':'' }}</h6>
                {{-- <span class="badge">{{$floor_detail->property_status}}</h6> --}}
                 <select class="form-control new-controls-property" name="property_status"
                     data-property-id="{{$floor_detail->property_id}}" data-wing-id="{{$wing->id}}" data-floor-id="{{$flr->id}}" data-unit-id="{{$floor_detail->id}}" data-property-status="{{$floor_detail->property_status}}" id="property_work{{($key1 + 1)}}_{{$floor_detail->id}}" onchange="openWorkModel(this)">
                      <option value="Available" {{ $floor_details && $floor_detail->property_status == "Available" ? 'selected' : '' }}>Available</option>
                      <option value="Hold" {{ $floor_details && $floor_detail->property_status == "Hold" ? 'selected' : '' }}>Hold</option>
                      <option value="Sold" {{ $floor_details && $floor_detail->property_status == "Sold" ? 'selected' : '' }}>Sold</option>
                  </select>
							</div>
						@endforeach
					</div>
				</td>
				@endforeach
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
<script>
	// $('.select2').select2({
	// 	theme: 'bootstrap4',
	// 	width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
	// 	placeholder: $(this).data('placeholder'),
	// 	allowClear: Boolean($(this).data('allow-clear')),
	// });

  function openWorkModel(obj) {
    var property_id = $(obj).attr('data-property-id');
    var wing_id = $(obj).attr('data-wing-id');
    var floor_id = $(obj).attr('data-floor-id');
    var unit_id = $(obj).attr('data-unit-id');
    var property_status = $(obj).val();

    if (property_status === "Available") {
        // Direct save without modal
        $.post("{{route('admin.property_inventory.floor.status_store')}}", {
            _token: "{{csrf_token()}}",
            property_id: property_id,
            wing_id: wing_id,
            floor_id: floor_id,
            unit_id: unit_id,
            property_status: property_status
        }, function(res) {
            toastrMsg(res.status,res.msg);
        }, 'json');
    } else {
        // Open modal for Hold/Sold
        $.post("{{route('admin.property_inventory.floor.status_change')}}", {
            _token: "{{csrf_token()}}",
            property_id: property_id,
            wing_id: wing_id,
            floor_id: floor_id,
            unit_id: unit_id,
            property_status: property_status
        }, function(res) {
            $('body').find('#modal-view-render').html(res.view);
            $('#property_work_modal').modal('show');
        }, 'json');
    }
  }
</script>

