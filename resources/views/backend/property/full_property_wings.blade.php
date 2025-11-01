<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>



	* {
		list-style: none !important;
	}

	.property-card {
		background: #ffffff;
		border-radius: 10px;
		padding: 20px;
		margin-top: 20px;
		box-shadow: 0 6px 58px 0 rgba(196, 203, 214, 0.1),
			0 0 4px 0 rgba(0, 0, 0, 0.1),
			0 3px 10px 0 rgba(0, 0, 0, 0.4);
	}

	.recycle-icons {
		background-color: black;
		color: white;
		padding: 13px 10px;
		height: 39px;
		width: 36px !important;
		border-radius: 5px;
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
		display: flex;
		justify-content: space-between;
		padding: 10px;
		box-shadow: 0px 0px 4px rgba(0, 0, 0, 0.1);
		border-radius: 10px;
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
		width: 25%
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

	.form-control{
		background-color: #f1f1f1 !important;
	}

	.height{
		height: 40px;
	}

	.pro-input-add{
		display: flex;
	}

	.recycle-icons-save {
    background-color: black;
    color: white;
    padding: 8px 4px;
    height: 39px;
    width: 36px;
    border-radius: 5px;
	}
</style>

<div class="new-cards-pro">
	<div class="property-card">
		<p class="font-black-15 new-color-ws">Wings/Society</p>
		<div class="wing-add-main" id="addMoreWings">
			@if(count($wings))
			@foreach($wings as $key => $wing)
			<div class="card-radio d-flex mt-3">
				<div class="wings-radio">
					<ul class="new-ul-des-start">
						<li id="checkboxadd_{{($key+1)}}" class="new-box">
							<label class="container checkbox-box">
								<input type="radio" id="wings{{($key+1)}}" name="wingscheck checkbox-ratio" value="{{($key+1)}}" onclick="loadFloors({{$wing->wings}},{{($key+1)}},{{$wing->id}})" class="checkbox-ratio">
								<span class="checkmark"></span>
								<b id="dwingname_{{($key+1)}}" style="font-weight: 500;">
									{{ $wing->wing_name ? $wing->wing_name : 'wings'.($key + 1).' / Society'.($key + 1) }}
								</b>
								<a href="javascript:;" data-number="{{($key+1)}}" onclick="openEditField(this,event)"><button type="button" class="btn btn-icon waves-effect waves-light action-icon3 ml-1"><i class="fas fa-edit" aria-hidden="true"></i></button></a>
							</label>
							<div class="card-radio card-edit-fild" id="editfield_{{($key+1)}}" style="display:none;">
								<input type="text" value="{{ $wing->wing_name ? $wing->wing_name : 'wings'.($key + 1).' / Society'.($key + 1) }}" class="edit-input" id="wing_name_{{($key+1)}}">
								<button type="button" class="add-main" data-wing-id="{{$wing->id}}" data-number="{{($key+1)}}" onclick="wingSubmit(this,event)">Save</a>
							</div>
						</li>
					</ul>
				</div>
				<div class="pro-input-add">
					<input type="text" id="wings_{{($key+1)}}" class="form-control height <?php echo ($wing->wings == 0) ? 'wings' : ''; ?>" value="{{$wing->wings}}" <?php echo ($wing->wings != 0) ? 'readonly' : ''; ?> oninput="this.value = this.value.replace(/\D/g, '')" />

					<?php $prode = App\Models\Property\PrtFloor::where('wing_id', $wing->id)->count(); ?>

					<a href="javascript:;" onclick="removeRowWing({{$wing->id}})" style="display:<?php echo ($wing->wings == 0) ? 'block' : (($prode == 0) ? 'block' : 'none'); ?>;" class="main-btn  wing-delet ml-2" id="setremovewing_{{($key+1)}}"><i class="fa-solid fa-trash-can recycle-icons"></i>
					</a>

					<a href="javascript:;" class="main-btn add-image-btn add-pro-modal ml-2 wing-add" id="main_save_{{($key+1)}}" data-wing-id="{{$wing->id}}" data-number="{{($key+1)}}" onclick="wingSubmit(this,event,1)" style="display:none;">Save</a>
				</div>
			</div>
			@endforeach
			@else
			<div class="card-radio d-flex">
				<div class="wings-radio">
					<ul class="new-ul-des-start">
						<li id="checkboxadd_1" class="new-box">
							<label class="container checkbox-box"> 
								<b id="dwingname_" style="font-weight: 500;">Wings1 / Society1</b> 
								<a href="javascript:;" data-number="1" onclick="openEditField(this,event)">
									<button type="button" class="btn btn-icon waves-effect waves-light action-icon3 ml-1">
										<i class="fas fa-edit" aria-hidden="true"></i>
									</button></a> 
								</label>
							<div class="card-radio card-edit-fild" id="editfield_1" style="display:none;">
								<input type="text" value="wings1 / Society1" class="edit-input" id="wing_name_1">
								<a href="javascript:;" class="add-main" data-wing-id="" data-number="1" onclick="wingSubmit(this,event)">Save</a>
							</div>
						</li>
					</ul>
				</div>
				<div class="pro-input-add">
					<input type="text" id="wings_1" class="form-control height wings" value="" oninput="this.value = this.value.replace(/\D/g, '')" />
					<a href="javascript:;" class="main-btn add-image-btn add-pro-modal ml-2 wing-add" id="main_save_1" data-wing-id="" data-number="1" onclick="wingSubmit(this,event,1)" style="display:none;">Save</a>
					<a href="javascript:;" class="main-btn wing-del delet-buss ml-2" id="setremovewing_1" onclick="deleteRow(1)" style="display:none;">Delete</a>
				</div>
			</div>
			@endif
		</div>
		<button type="submit" class="custom-btn addMoreWings mt-5" style="display:flex; justify-content:center;" <?php echo (count($wings) > 0) ? '' : 'disabled'; ?>>Add More Wings</button>
	</div>
	<div id="load_floors"></div>
</div>


<script>
	var property_id = "{{$property_id}}";

	$('.wings').keyup(function() {
		var wingsValue = $(this).val();
		var closestButton1 = $(this).closest('div').find('.wing-delet');
		var closestButton = $(this).closest('div').find('.wing-add');
		var closestButtonDel = $(this).closest('div').find('.wing-delet');
		if (wingsValue !== '' && parseFloat(wingsValue) > 0) {
			closestButtonDel.hide();
			closestButton.show();
			closestButton1.hide();
		} else {
			closestButtonDel.show();
			closestButton.hide();
			closestButton1.show();
		}
	});

	var i = "{{$countmore}}";
	$('.addMoreWings').click(function() {
		var wingsHtml = '';
			wingsHtml += '<div class="card-radio mt-3 remove_row_' + i + '"><div class="wings-radio"><ul class="new-ul-des-start"><li id="checkboxadd_' + i + '"><label class="container checkbox-box">Wings' + i + ' / Society' + i + ' <a href="javascript:;" data-number="' + i + '" onclick="openEditField(this,event)"><button type="button" class="btn btn-icon waves-effect waves-light action-icon3 ml-1"><i class="fas fa-edit" aria-hidden="true"></i></button></a> </label><div class="card-radio card-edit-fild" id="editfield_' + i + '" style="display:none;"><input type="text" value="Wings' + i + ' / Society' + i + '" class="edit-input" id="wing_name_' + i + '"><a href="javascript:;" class="add-main" data-wing-id="" data-number="' + i + '" onclick="wingSubmit(this,event)" style="padding: 7px;">Save</a></div></li></ul></div><div class="pro-input-add"><input type="text" id="wings_' + i + '" class="form-control height  wings' + i + '" value="" onkeypress="allowNumbersOnly(event)" /><a href="javascript:;" class="main-btn add-image-btn add-pro-modal ml-2 wing-add recycle-icons-save" id="main_save_' + i + '" data-wing-id="" data-number="' + i + '" onclick="wingSubmit(this,event,1)" style="display:none;">Save</a><a href="javascript:;" class="main-btn wing-del delet-buss ml-2" id="setremovewing_' + i + '" onclick="deleteRow(' + i + ')"> <i class="fa-solid fa-trash-can recycle-icons" style="height : 36px; width : 39px;"></i></a></div></div>';

		$('#addMoreWings').append(wingsHtml);
		$('.addMoreWings').attr('disabled', true)
		$('input[name="wingscheck"]').prop('checked', false);
		$('#load_floors').html('');

		$('.wings' + i + '').keyup(function() {
			var wingsValue = $(this).val();
			var closestButton1 = $(this).closest('div').find('.wing-del');
			var closestButton = $(this).closest('div').find('.wing-add');
			var closestButtonDel = $(this).closest('div').find('.wing-delet');
			if (wingsValue !== '' && parseFloat(wingsValue) > 0) {
				closestButtonDel.hide();
				closestButton.show();
				closestButton1.hide();
			} else {
				closestButtonDel.show();
				closestButton.hide();
				closestButton1.show();
			}
		});

		i++;
	});

	function wingSubmit(obj, event, num = null) {
		var currenti = $(obj).data('number');
		var id = $(obj).data('wing-id');
		var wing_name = $('#wing_name_' + currenti).val();
		$('#dwingname_' + currenti).text(wing_name);
		var wings = $('#wings_' + currenti).val();

		$.post("{{route('admin.property_inventory.wings.store')}}", {
				_token: "{{csrf_token()}}",
				property_id: property_id,
				id: id,
				wings: wings,
				wing_name: wing_name,
				wing_number: currenti
			},
			function(res) {
				$('#load_floors').show();
				$('#load_floors').html(res.view);
				$('.addMoreWings').attr('disabled', false)
				$('#checkboxadd_' + currenti).html('<label class="container checkbox-box"><input onclick="loadFloors(' + res.wings + ',' + currenti + ',' + res.wing_id + ')" type="radio" id="wings" name="wingscheck" value="" class="checkbox-ratio" checked><span class="checkmark"></span>' + res.wing_name + ' <a href="javascript:;" data-number="' + currenti + '" onclick="openEditField(this,event)"><button type="button" class="btn btn-icon waves-effect waves-light action-icon3 ml-1"><i class="fas fa-edit" aria-hidden="true"></i></button></a> </label><div class="card-radio card-edit-fild" id="editfield_' + currenti + '" style="display:none;"><input type="text" value="' + res.wing_name + '" class="edit-input" id="wing_name_' + currenti + '"><button type="button" class="add-main"   data-wing-id="' + res.wing_id + '" data-number="' + currenti + '" onclick="wingSubmit(this,event)" >Save</a></div>');
				$(obj).attr('data-wing-id', res.wing_id)
				$('#main_save_' + currenti + '').attr('data-wing-id', res.wing_id)
				$('#wings' + currenti + '').prop('checked', true);
				$('#setremovewing_' + currenti + '').attr('onclick', "removeRowWing(" + res.wing_id + ")");
				if (num != null) {
					$(obj).hide();
					$('#wings' + currenti + '').prop('readonly', true);
					$('#setremovewing_' + currenti + '').show();
				}
			});
	}

	function openEditField(obj, event) {
		event.preventDefault();
		var number = $(obj).data('number');
		$('#checkboxadd_' + number).find('#editfield_' + number).toggle();
	}

	function deleteRow(r) {

		$('.remove_row_' + r + '').remove();
		$('.addMoreWings').attr('disabled', false)
		i--;
	}

	function allowNumbersOnly(e) {
		var code = (e.which) ? e.which : e.keyCode;
		if (code > 31 && (code < 48 || code > 57)) {
			e.preventDefault();
		}
	}

	function loadFloors(wings, wing_number, wing_id) {
		$('#load_property_details').html('');

		$.post("{{route('admin.property_inventory.wings-floors')}}", {
				_token: "{{csrf_token()}}",
				property_id: property_id,
				wings: wings,
				wing_id: wing_id,
				wing_number: wing_number
			},
			function(res) {
				$('#load_floors').html(res.view);
				setTimeout(function() {
					$('#wings' + wing_number).prop('checked', true);
				}, 100)
			});
	}


	function removeRowWing(wing_id)
	{
		if(confirm('Are you sure to delete?'))
		{
			$.post("{{route('admin.property_inventory.wing-delete')}}",
			{
				_token:"{{csrf_token()}}",
				property_id:property_id,
				wing_id:wing_id
			},
			function(res)
			{
				toastrMsg(res.status,res.msg);
				loadWings();
			},'Json');

		}
	}
</script>