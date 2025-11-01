<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />



<style>
  .row-floor {
    display: flex;
    gap: 15px;
  }

  /* .new-property-box{
	justify-content: space-between;
  } */

  	.new-color-ws-pro {
	border-bottom: 1px solid #e7e8ea;
	padding-bottom: 15px;
	}

	.property-title-main{
	color: black;
font-size: 16px;
		font-weight: bold;
	}

	.new-input-des-pro{
		border: 1px solid #dcdcdc;
		padding: 10px;
		border-radius: 10px;
	}

	.save-btn{
		background: black;
    border: none;
    outline: none;
    border-radius: 5px;
    color: white;
    width: 80px;
    font-size: 15px;
    font-weight: bold;
    height: 44px;
    margin-top: 3px;
	}

	.property-title{
	font-weight: bold;
    font-size: 12px;
    line-height: 15px;
    text-transform: capitalize;
	letter-spacing: 0.5px;
	}

	.new-controls-property{
		height: 50px;
		border: 1px solid #dcdcdc !important;
		background-color: white !important;
		border-radius: 10px !important;
		padding: 10px !important;
	}

</style>
@if($floors != 0)
<form id="propertyFlatAddForm" class="property-card " action="{{ route('agent.property_inventory.flat.store') }}" method="post" enctype="multipart/form-data">
	<div class="floor-row">
        @csrf
        <input type="hidden" name="wing_id" value="{{ $wing_id }}">
        <input type="hidden" name="floor_id" value="{{ $floor_id }}">
        <input type="hidden" name="property_id" value="{{ $property_id }}">

        <div class="floor-box d-flex justify-content-between align-items-end new-color-ws-pro">
            <p class="font-black-15 p-0 m-0 property-title-main">Property Details</p>
            <div class="pro-partial d-flex" style="gap: 10px;">
                @if($flatcount == 0)
                    <div class="control-group d-flex align-items-center new-input-des-pro" style="height:50px;">
						<label class="control control--radio d-flex align-items-center">
							<input type="radio" id="flat_same" value="1" class="checkbox-ratio">
							<span class="ml-2">Is All Details Same</span>
						</label>
					</div>

                @endif
                <button type="submit" class="add-main spin-button save-btn">Save</button>
            </div>
        </div>

        @for($i = 1; $i <= $floors; $i++)
            @php
                $j = $i <= 9 ? '0'.$i : $i;
                $partailsDetail = $partailsDetails->get($i);
            @endphp
            <input type="hidden" class="pro-input" name="id[]" id="id{{ $i }}" value="{{ $partailsDetail ? $partailsDetail->id : '' }}">
            <input type="hidden" class="pro-input" name="flat_number[]" id="flat_number{{ $i }}" value="{{ $i }}" required>

            <div class="floor-wrapper mt-3">
                <div class="pro-flor-row filter-deta">
                    <div class="row-floor mt-2 new-property-box align-items-end">
                        <div class="col-md">
                            <p class="font-black-12 p-0 m-0 border-0 mb-2 property-title">Flat/Shop/Raw h. No</p>
                            <input type="text" class="form-control modal-input new-controls-property" name="flat_no[]" id="flat_no{{ $i }}"  value="{{ $partailsDetail ? $partailsDetail->flat_no : $floor_number.$j }}" required>
                        </div>
                        <div class="col-md">
                            <p class="font-black-12 p-0 m-0 border-0 mb-2 property-title">Unit</p>
                            <select class="form-control new-controls-property" name="unit_type[]" id="unit_type{{ $i }}"  >
                              <option value="">Select Unit</option>
                              @foreach ($units as $unit)
                                <option value="{{ $unit->id }}" {{ $partailsDetail && $unit->id == $partailsDetail->unit_type ? 'selected' : '' }}>
                                    {{ $unit->unit_name }}
                                </option>
                              @endforeach
                            </select>
                        </div>
                        <div class="col-md">
                            <p class="font-black-12 p-0 m-0 border-0 mb-2 property-title">Sq.ft/carpet area<span class="text-danger">*</span></p>
                            <input type="text" class="form-control modal-input new-controls-property" name="sqft[]" id="sqft{{ $i }}" value="{{ $partailsDetail ? Helper::decimalsprint($partailsDetail->sqft, 2) : '' }}" oninput="$(this).val($(this).val().replace(/[^0-9.]/g, ''));" required>
                        </div>
                        <div class="col-md">
                            <p class="font-black-12 p-0 m-0 border-0 mb-2 property-title">Price <span class="text-danger">*</span></p>
                            <input type="text" class="form-control modal-input new-controls-property" name="price[]" id="price{{ $i }}"   oninput="$(this).val($(this).val().replace(/[^0-9.]/g, ''));" value="{{ $partailsDetail ? Helper::decimalsprint($partailsDetail->price, 3) : '' }}" required>
                        </div>
                        {{-- <div class="col-md">
                            <p class="font-black-12 p-0 m-0 border-0 mb-2">Property Status <span class="text-danger">*</span></p>
                            <select class="form-control new-controls-property" name="property_status[]" id="property_status{{ $i }}" data-property-id="{{$property_id}}" data-unit-id="{{$partailsDetail->id ?? ''}}" data-wing-id="{{$wing_id}}" data-floor-id="{{$floor_id}}" required onchange="openWorkModel(this)">
                                <option value="Available" {{ $partailsDetail && $partailsDetail->property_status == "Available" ? 'selected' : '' }}>Available</option>
                                <option value="Hold" {{ $partailsDetail && $partailsDetail->property_status == "Hold" ? 'selected' : '' }}>Hold</option>
                                <option value="Sold" {{ $partailsDetail && $partailsDetail->property_status == "Sold" ? 'selected' : '' }}>Sold</option>

                            </select>
                        </div> --}}
                        @if($flatcount != 0 && $partailsDetail && $partailsDetail->id)
                            <div style="margin-top: 27px;">
                                <a href="javascript:;" onclick="removeDetailsRow({{ $partailsDetail->id }}, {{ $floors }}, {{ $floor_number }}, {{ $wing_id }}, {{ $floor_id }})">
									                <i class="fa-solid fa-trash-can recycle-icons" style="height : 36px; width : 39px;"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endfor
	</div>
</form>
@endif
<script>

	$('#propertyFlatAddForm').submit(function(event)
	{
		event.preventDefault();
		 $(this).find('button').prop('disabled',true);
		$(this).find('button.spin-button').addClass('loading').html('<span class="spinner"></span>');
		var formData = new FormData(this);
		formData.append('_token','{{csrf_token()}}');

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
				$('#propertyFlatAddForm').find('button').prop('disabled',false);
				$('#propertyFlatAddForm').find('button.spin-button').removeClass('loading').html('Save');
				if(res.status == "error")
				{
					toastrMsg(res.status,res.msg);
				}
				else
				{
					toastrMsg(res.status,res.msg);
					loadFloors(res.prtwings.wings,res.prtwings.wing_number,res.prtwings.id)
					$('#load_property_details').html('');
				}
			}
		});
	});

	$('#flat_same').click(function() {
		if (confirm('Are you sure you want to set all details the same?')) {
		  var toFloor = {{ $floors ?? 0 }};
			$('#flat_same').prop('checked',true);
			// Cache elements
			var categoryId1 = $('#category_id1').val();
			var unitType1 = $('#unit_type1').val();
			var facing1 = $('#facing1').val();
			var sqft1 = $('#sqft1').val();
			var plotSize1 = $('#plot_size1').val();
			var budget1 = $('#budget1').val();
			var price1 = $('#price1').val();
			var propertyStatus1 = $('#property_status1').val();

			// Iterate through all floors from 2 to toFloor
			for (var i = 2; i <= toFloor; i++) {
				$('#category_id' + i).val(categoryId1).trigger('change');
				$('#unit_type' + i).val(unitType1).trigger('change');
				$('#facing' + i).val(facing1).trigger('change');
				$('#sqft' + i).val(sqft1);
				$('#plot_size' + i).val(plotSize1);
				$('#budget' + i).val(budget1).trigger('change');
				$('#price' + i).val(price1);
				$('#property_status' + i).val(propertyStatus1).trigger('change');
			}
		}
	});

	function removeDetailsRow(details_id,floors,floor_number,wing_id,floor_id)
	{
		if(confirm('Are you sure to delete?'))
		{
			$.post("{{route('agent.property_inventory.flat.delete')}}",
			{
				_token:"{{csrf_token()}}",
				property_id:property_id,
				details_id:details_id,
				floors:floors,
				wing_id:wing_id,
				floor_id:floor_id,
				floor_number:floor_number
			},
			function(res)
			{
				toastrMsg(res.status,res.msg);
				$('#floors'+floor_number).val(res.count);
				loadFloors(res.prtwings.wings,res.prtwings.wing_number,res.prtwings.id)
				loadPartial(res.count,floor_number,wing_id,floor_id)
			},'Json');
		}
	}

  // function openWorkModel(obj)
	// {
	// 	var property_id = $(obj).attr('data-property-id');
	// 	var wing_id = $(obj).attr('data-wing-id');
	// 	var floor_id = $(obj).attr('data-floor-id');
  //   var unit_id = $(obj).attr('data-unit-id');
	// 	var property_status = $(obj).val();

	// 	$.post("{{route('vendor.property_inventory.floor.status_change')}}",{
	// 		_token:"{{csrf_token()}}",
	// 		property_id:property_id,
	// 		wing_id:wing_id,
	// 		floor_id:floor_id,
	// 		unit_id:unit_id,
	// 		property_status:property_status,
	// 	}, function(res)
	// 	{
	// 		$('body').find('#modal-view-render').html(res.view);
	// 		$('#property_work_modal').modal('show');
	// 	});
	// }
</script>
