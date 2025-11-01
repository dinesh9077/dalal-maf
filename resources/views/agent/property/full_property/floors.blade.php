<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@if($wings != 0)


<style>
	.edit-input {
		width: 100% !important;
		padding: 7px;
		text-align: start !important;
		outline: none;
	}

	.card-radio.card-edit-fild {
		gap: 10px;
	}

	.card-radio.card-edit-fild .add-main {
		font-size: 12px;
	}

	/* .card-radio.card-edit-fild {
		margin-top: 10px;
		} */
	.property-page-main .property-box .property-card .card-radio {
		align-items: start;
	}

	.card-radio.card-edit-fild .add-main:hover {
		color: #fff;
	}

	.wing-add-main.side-pro-sec {
		height: 350px;
		overflow: auto;
	}

	.wing-add-main.side-pro-sec::-webkit-scrollbar {
		overflow: hidden;
	}


	.pro-full-head p {
		border: none !important;
		padding: 0 !important;
		margin: 0 !important;
	}

	.pro-full-head .main-btn {
		padding: 7px 13px !important;
		background: var(--main-btn);
	}

	.pro-full-head {
		display: flex;
		align-items: center;
		justify-content: space-between;
		border-bottom: 1px solid #e7e8ea;
		padding-bottom: 10px;
		margin-bottom: 15px;
	}



	/* ======================= */

	.property-card {
		background: #ffffff;
		border-radius: 10px;
		padding: 20px;
		margin-top: 20px;
		box-shadow: 0 6px 58px 0 rgba(196, 203, 214, 0.1),
			0 0 4px 0 rgba(0, 0, 0, 0.1),
			0 3px 10px 0 rgba(0, 0, 0, 0.4);

	}

	.edit-input {
		width: 100% !important;
		padding: 7px;
		text-align: start !important;
		outline: none;
		border: 1px solid black;
		border-radius: 5px;
		background-color: #e8e8e8;
	}

	.card-radio {
		padding: 10px;
		justify-content: space-between;
		box-shadow: 0px 0px 4px rgba(0, 0, 0, 0.1);
		border-radius: 10px;
		width: 99%;
		margin: auto;
	}

	.card-radio.card-edit-fild {
		display: flex;
		gap: 10px;
		box-shadow: 0px 0px 4px rgba(0, 0, 0, 0.1);
		margin-left: 40px;
		border-radius: 10px;
	}

	.card-radio.card-edit-fild .add-main {
		font-size: 12px;
		background: black;
		border: 1px solid black;
		color: white;
		border-radius: 4px;
		box-shadow: 0px 0px 4px rgba(0, 0, 0, 0.1);
	}

	.property-page-main .property-box .property-card .card-radio {
		align-items: start;
	}

	.card-radio.card-edit-fild .add-main:hover {
		color: #fff;
	}

	.pro-input-add {
		width: 25%;
		display: flex;
		align-items: center;
	}

	.new-color-ws {
		color: black;
		border-bottom: 1px solid #e7e8ea;
		padding-bottom: 15px;
		font-size: 16px;
		font-weight: bold;
	}

	.new-ul-des-start {
		padding: 0px;
		margin: 0px;
	}

	.checkbox-ratio {
		appearance: none;
		-webkit-appearance: none;
		-moz-appearance: none;
		width: 20px;
		height: 20px;
		border: 2px solid black;
		border-radius: 50%;
		outline: none;
		cursor: pointer;
		position: relative;
		/* background: black; */
	}

	/* On checked → inside white */
	.checkbox-ratio:checked {
		background: white;
		border: 6px solid black;
		/* creates white center with black ring */
	}

	.checkbox-box {
		display: flex;
		align-items: center;
		gap: 5px;
	}

	.addMoreWings {
		width: 33%;
		margin: auto;
		padding: 10px;
		background-color: black;
		color: white;
		border: 1px solid black;
		border-radius: 10px;
	}

	.new-cards-pro {
		display: flex;
		gap: 20px;
	}

	.recycle-icons {
		background-color: black;
		color: white;
		padding: 13px 10px;
		height: 39px;
		width: 36px !important	;
		border-radius: 5px;
	}

	.form-control {
		background-color: #f1f1f1;
		border: 1px solid #f1f1f1;
	}
</style>
<div class="property-card ">
	<div class="pro-full-head">
		<p class="font-black-15 new-color-ws">Floors/Rows</p>
		<a href="javascript:;" onclick="copyAllRowsAndFloors(this,event)" style="display:none;" class="main-btn" id="is_copy_rows">Copy all rows and floors </a>
	</div>
	<div class="wing-add-main side-pro-sec">
		<?php for ($i = 1; $i <= $wings; $i++) { ?>
			<?php $floor = App\Models\Property\PrtFloor::where('wing_id', $wing_id)->where('floor_number', $i)->first(); ?>
			<div class="card-radio d-flex mt-3">
				<div class="wings-radio">
					<ul class="new-ul-des-start">
						<li id="floorcheck_{{$i}}">
							@if($floor)
							<label class="container checkbox-box">
								<input type="radio" data-floor="{{$floor->id}}" class="floorsCheckbox checkbox-ratio" id="floors{{($i)}}" name="floor" value="{{$i}}" onclick="loadPartial({{$floor->floors}},{{$i}},{{$floor->wing_id}},{{$floor->id}})"><span class="checkmark" for="floors{{$i}}"></span>
								<b id="dfloorname_{{($i)}}" style="font-weight: 500;">
									{{ $floor && $floor->floor_name ? $floor->floor_name : 'Floor'.($i).' / Row'.($i) }}
								</b>
								<a href="javascript:;" data-number="{{$i}}" onclick="openFloorField(this,event)"><button type="button" class="btn btn-icon waves-effect waves-light action-icon3 ml-1"><i class="fas fa-edit" aria-hidden="true"></i></button></a>
							</label>
							<div class="card-radio card-edit-fild" id="editfloorfield_{{$i}}" style="display:none;">
								<input type="text" value="{{ $floor && $floor->floor_name ? $floor->floor_name : 'Floor'.($i).' / Row'.($i) }} " class="edit-input" id="floor_name_{{$i}}">
								<button type="button" class="add-main" data-number="{{$i}}" onclick="floorSubmit(this,event)">Save</a>
							</div>
							@else
							<label class="container checkbox-box">
								<b id="dfloorname_{{($i)}}" style="font-weight: 500;">
									{{ $floor && $floor->floor_name ? $floor->floor_name : 'Floor'.($i).' / Row'.($i) }}
								</b>
								<a href="javascript:;" data-number="{{$i}}" onclick="openFloorField(this,event)"><button type="button" class="btn btn-icon waves-effect waves-light action-icon3 ml-1"><i class="fas fa-edit" aria-hidden="true"></i></button></a>
							</label>
							<div class="card-radio card-edit-fild" id="editfloorfield_{{$i}}" style="display:none;">
								<input type="text" value="{{ $floor && $floor->floor_name ? $floor->floor_name : 'Floor'.($i).' / Row'.($i) }} " class="edit-input" id="floor_name_{{$i}}">
								<button type="button" class="add-main" data-number="{{$i}}" onclick="floorSubmit(this,event)">Save</a>
							</div>
							@endif
						</li>
					</ul>
				</div>
				@if($floor)
				<?php $prode = App\Models\Property\PrtUnit::where('wing_id', $wing_id)->where('floor_id', $floor->id)->count(); ?>
				<div class="pro-input-add">
					<input type="text" id="floors_{{($i)}}" class="form-control floors <?php echo ($floor->floors == 0) ? 'floors' : ''; ?>" value="{{$floor->floors}}" <?php echo ($floor->floors != 0) ? 'readonly' : ''; ?> oninput="this.value = this.value.replace(/\D/g, '')">
					<input type="hidden" id="id_{{($i)}}" value="{{$floor->id}}">

					<a href="javascript:;" onclick="removeRowFloor({{$floor->id}},{{$wings}},{{$wing_number}},{{$wing_id}})" style="display:<?php echo ($floor->floors == 0) ? 'block' : (($prode == 0) ? 'block' : 'none'); ?>;" class="main-btn floor-delet ml-2"><i class="fa-solid fa-trash-can recycle-icons" style="height : 36px; width : 39px;"></i></a>

					<a href="javascript:;" class="main-btn add-image-btn add-pro-modal ml-2 floor-add recycle-icons-save" data-number="{{$i}}" onclick="floorSubmit(this,event)" style="display:none;">Save</a>
				</div>
				@else
				<div class="pro-input-add">
					<input type="text" id="floors_{{($i)}}" class="form-control floors" value="" oninput="this.value = this.value.replace(/\D/g, '')">
					<input type="hidden" id="id_{{($i)}}" value="">
					<a href="javascript:;" style="display:none;" id="setremoveflor{{($i)}}" class="main-btn  floor-delet ml-2"><i class="fa-solid fa-trash-can recycle-icons"></i></a>
					<a href="javascript:;" class="main-btn add-image-btn add-pro-modal ml-2 floor-add recycle-icons-save" data-number="{{$i}}" onclick="floorSubmit(this,event)" style="display:none;">Save</a>
				</div>
				@endif

			</div>
		<?php } ?>
	</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
	var property_id = "{{$property_id}}";
	var wing_id = "{{$wing_id}}";
	var wing_number = "{{$wing_number}}";
	var wingss = "{{$wings}}";

	function floorSubmit(obj, event) {
		var currenti = $(obj).data('number');
		var id = $('#id_' + currenti).val();
		var floorsValue = $('#floors_' + currenti).val();
		var floors = (floorsValue == null || floorsValue === "") ? 0 : floorsValue;
		var floor_name = $('#floor_name_' + currenti).val();

		$.post("{{route('agent.property_inventory.floor.store')}}", {
				_token: "{{csrf_token()}}",
				property_id: property_id,
				id: id,
				floors: floors,
				wing_id: wing_id,
				floor_name: floor_name,
				floor_number: currenti
			},
			function(res) {
				$('#load_property_details').html(res.view);
				$('#floorcheck_' + currenti + '').html('<label class="container"><input type="radio" id="floors' + currenti + '" name="floor" value="' + currenti + '" checked><span class="checkmark" for="floors' + currenti + '" onclick="loadPartial(' + res.floors + ',' + currenti + ',' + wing_id + ',' + res.floor_id + ')"></span>' + res.floor_name + '<a href="javascript:;" data-number="' + currenti + '" onclick="openFloorField(this,event)"><button type="button" class="btn btn-icon waves-effect waves-light action-icon3 ml-1"><i class="fas fa-edit" aria-hidden="true"></i></button></a> </label><div class="card-radio card-edit-fild" id="editfloorfield_' + currenti + '" style="display:none;"><input type="text" value="' + res.floor_name + '" class="edit-input" id="floor_name_' + currenti + '"><button type="button" class="add-main" data-number="' + currenti + '" onclick="floorSubmit(this,event)">Save</a></div>');
				$('#id_' + currenti).val(res.floor_id);
				$('#setremovewing_' + wing_number).remove();
				$('#setremoveflor' + currenti).attr('onclick', 'removeRowFloor(' + res.floor_id + ',' + wingss + ',' + wing_number + ',' + wing_id + ')');
				$('#setremoveflor' + currenti).show();
				$(obj).hide();
			});
	}

	$('.floors').keyup(function() {
		var wingsValue = $(this).val();
		var closestButton = $(this).closest('div').find('.floor-add');
		var closestButtonDel = $(this).closest('div').find('.floor-delet');
		if (parseFloat(wingsValue) > 0) {
			closestButtonDel.hide();
			closestButton.show();
		} else {
			closestButtonDel.show();
			closestButton.hide();
		}
	});

	function openFloorField(obj, event) {
		event.preventDefault();
		var number = $(obj).data('number');
		$('#floorcheck_' + number).find('#editfloorfield_' + number).toggle();
	}

	function loadPartial(floors, floor_number, wing_id, floor_id) {
		$.post("{{route('agent.property_inventory.floor.partial')}}", {
				_token: "{{csrf_token()}}",
				property_id: property_id,
				floors: floors,
				wing_id: wing_id,
				floor_id: floor_id,
				floor_number: floor_number
			},

			function(res) {
				$('#load_property_details').html(res.view);
				$('#is_copy_rows').show();
				setTimeout(function() {
					$('#floors' + floor_number).prop('checked', true);
				}, 100)
			});
	}

	function removeRowFloor(floor_id, wings, wing_number, wing_id) {
		if (confirm('Are you sure to delete?')) {
			$.post("{{route('agent.property_inventory.floor.delete')}}", {
					_token: "{{csrf_token()}}",
					property_id: property_id,
					wings: wings,
					wing_id: wing_id,
					floor_id: floor_id,
					wing_number: wing_number
				},
				function(res)
        {
          toastrMsg(res.status,res.msg);
          loadWings();
          loadFloors(res.prtwings.wings,res.prtwings.wing_number,res.prtwings.id)
        },'Json');

		}
	}

	function copyAllRowsAndFloors(obj, event) {
		event.preventDefault();
		var floor_id = $('.floorsCheckbox:checked').attr('data-floor');
		Swal.fire({
			title: "Are you sure?",
			text: "You want to copy all raws and floor!",
			type: "warning",
			showCancelButton: !0,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Yes, Copy it!"
		}).then(function(t) {
			t.value &&

				$.post("{{url('agent/property-inventory/copy/floors')}}/" + floor_id, {
					_token: "{{csrf_token()}}"
				}, function(res) {
					if (res.status == "error") {
						Swal.fire("Error!", res.msg, "error")
					} else {
						loadFloors(res.wing.wings, res.wing.wing_number, res.wing.id)
						Swal.fire("Copied!", res.msg, "success")
					}
				});
		})
	}
</script>
@endif
