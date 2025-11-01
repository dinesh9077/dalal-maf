



<tr class="row_id_{{$i}}">
	<td>
    <input type="hidden" name="item_id[{{$i}}]" value="" id="item_id_{{$i}}">
    <select class="modal-input select2 item_value" name="items[{{$i}}]" id="item_{{$i}}" required>
			<option value="">Select Item</option>
		  @foreach($propertyStatus as $prtStatus)
          @php
              $wingName = explode('/', optional($prtStatus->wing)->wing_name)[0] ?? '';
              $floorName = explode('/', optional($prtStatus->floors)->floor_name)[0] ?? '';
              $flatNo = optional($prtStatus->Units)->flat_no ?? '';
          @endphp

          <option value="{{ $prtStatus->property_id }}" data-item-id="{{ $prtStatus->id }}" data-price="{{ $prtStatus->Units->price}}" data-unit="{{ $prtStatus->Units->unit->unit_name}}" data-description="{{ trim($wingName) }}/{{ trim($floorName) }}/{{ $flatNo }}">
              {{optional($prtStatus->property->propertyContent)->title}}/{{ trim($wingName) }}/{{ trim($floorName) }}/{{ $flatNo }}
          </option>
      @endforeach
		</select>
	</td>
	<td>
		<input name="details[{{$i}}]" id="details_{{$i}}" class="form-control" rows="1" placeholder="Enter Item Description" required style="height: 40px;">
	</td>
	<td>
		<input class="form-control invo_price text-right" data-id="{{$i}}" placeholder="Price" type="text" name="price[{{$i}}]" style="height: 40px;" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  value="0" id="price_{{$i}}">
	</td>
	<td>
		<input class="form-control invo_qty" placeholder="Qnty." type="text" name="quantity[{{$i}}]" value="1" id="qty_{{$i}}" style="height: 40px; background : transparent !important;background-color : transparent !important;" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" readOnly>
	</td>
	<td>
    <input type="text" name="unit[{{$i}}]" id="unit_{{$i}}" readOnly style="height : 40px;
    border:1px solid #ebedf2; width : 100% !important;">
		{{-- <select class="modal-input select2" name="unit[{{$i}}]" id="unit_{{$i}}" required>
			<option value="">Select Unit</option>
			@foreach($accountUnits as $accountUnit)
			<option value="{{$accountUnit->unit_name}}">{{$accountUnit->unit_name}}</option>
			@endforeach
		</select> --}}
	</td>
	<td class="text-right">
		<span class="currency_wrapper"></span>
		<span class="total" id="price_text_{{$i}}">0.00</span>
	</td>
	<td class="text-right">
		<button type="button" class="main-btn delet-buss delet-btn remove_row" href="javascript:;" id="{{$i}}" style="cursor:pointer" title="Remove row">
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
		<input type="text" id="discount_{{$i}}" style="height: 40px;" name="discount[{{$i}}]" value="0" class="form-control invo_discount" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  >
	</td>

	<td class="text-right">
		<span class="currency_wrapper"></span>
		<span class="total" id="discount_text_{{$i}}">0.00</span>
	</td>
	<td class="text-right" ></td>
</tr>

<tr class="row_id_{{$i}}">
	<td></td>
	<td></td>
	<td> </td>
	<td style="text-align: end;vertical-align: baseline;">
		<label class="label-main">Tax </label>
	</td>
	<td id="gst_row_id_{{$i}}">
		<div style="margin-bottom: 10px;">
			<select class="modal-input selectgst select2" id="tax_<?php echo $i; ?>_1" data-id="<?php echo $i; ?>" data-keyid="<?php echo $i; ?>">
				<option value="">Select Tax</option>
        	<option value="GST-18.00" data-rate="18.00" data-name="GST 18.00%" >GST 18.00%</option>
          <option value="SGST-12.00" data-rate="12.00" data-name="SGST 12.00%" >SGST 12.00%</option>
          <option value="CGST-12.00" data-rate="12.00" data-name="CGST 12.00%" >CGST 12.00%</option>
          <option value="TDS-10.00" data-rate="10.00" data-name="TDS 10.00%">TDS 10.00%</option>
			</select>
			<span>
				<div class="room_right-main including_amount1_<?php echo $i; ?>" style="padding: 5px 0;display:none;" >
					<input type="checkbox" name="include_tax[]" id="including_<?php echo $i; ?>" value="1" data-id="<?php echo $i; ?>">
					<label for="including_<?php echo $i; ?>" data-toggle="tooltip" data-placement="top" data-trigger="hover" title="Including Tax"></label>
				</div>
			</span>
		</div>
	</td>
	<td class="text-right" id="gst_row_text_{{$i}}">
		<div class="total-amount">
			<span class="total">-</span>
		</div>
	</td>
	<td class="text-right">
	</td>
</tr>
<?php if($i == 2){ ?>
	<input type="hidden" value="1" id="total_gst_row">
	<script>

		$(document).ready(function()
		{
			var j = 1;
			$(document).on('change', ".selectgst", function(event)
			{
				var id = $(this).attr('data-id');
				j++;
				$('#total_gst_row').val(j);
				var value = $(this).val();
				var name = $(this).find('option:selected').attr('data-name');
				$(this).val('');
				var html = '';
				html +='<div class="taxdelete" style="     display: flex;gap: 10px;  margin-bottom: 10px;" id="gst_row_'+id+'_'+j+'"><select class="modal-input changesel select2 taxvalue_'+id+'" id="tax_'+id+'_'+j+'" data-id="'+id+'" name="tax['+id+'][]" style="text-transform: capitalize;display:unset;  background: #dcdcdc;padding: 10px;border-radius: 5px;border: none;"><option value="">Select Tax</option><option value="GST-18.00" data-rate="18.00" data-name="GST 18.00%">GST 18.00%</option><option value="SGST-12.00" data-rate="12.00" data-name="SGST 12.00%">SGST 12.00%</option> <option value="CGST-12.00" data-rate="12.00" data-name="CGST 12.00%">CGST 12.00%</option> <option value="TDS-10.00" data-rate="10.00" data-name="TDS 10.00%">TDS 10.00%</option></select><span> <i class="fas fa-trash-alt remove_gst_row" id="'+j+'" data-id="'+id+'" aria-hidden="true" style="font-size: 18px; background: black;color: white;height: 39px;width: 39px;display: flex;justify-content: center;align-items: center;border-radius: 4px;"></i></span></div>';
				$('#gst_row_id_'+id).prepend(html);

				var html1 = '';
				html1 +='<div class="total-amount" id="gst_text_'+id+'_'+j+'"><span class="total" id="tax_text_'+id+'_'+j+'">0.00</span></div> ';
				$('#gst_row_text_'+id).prepend(html1);
				$('#tax_'+id+'_'+j+'').val(value);

				var totalAmountWithTax = $('#price_'+id).val();
				$('.including_amount_'+id).hide();
				if(totalAmountWithTax != "0")
				{
					$('.including_amount_'+id).hide();
				}
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
	</script>
<?php } ?>
<script>
	$('.select2').select2({
		theme: 'bootstrap4',
		width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
		placeholder: $(this).data('placeholder'),
		allowClear: Boolean($(this).data('allow-clear')),
	});
	$('[data-toggle="tooltip"]').tooltip();
	var idf = "{{$i}}";
	$('#including_'+idf).click(function()
	{
		if ($(this).is(':checked'))
		{
			var id = $(this).attr('data-id');
			$('.including_amount_'+id).css('pointer-events','none');
			var totalValue = 0;
			var totalAmountWithTax = $('#price_'+id).val();

			$('.taxvalue_'+id+'').each(function() {
				totalValue += parseFloat($(this).find(':selected').attr('data-rate'));
			});
			if(totalValue > 0)
			{
				var taxRate = (totalValue / 100);
				var invoiceAmount = totalAmountWithTax / (1 + taxRate);
				//var taxAmount = totalAmountWithTax - invoiceAmount;
				$('#price_'+id).val(invoiceAmount.toFixed(2));
				inv_cal_final_total();
			}
		}
		else
		{
			var id = $(this).attr('data-id');
			var totalValue = 0;
			var totalAmountWithTax = $('#price_'+id).val();

			$('.gettaxvalue_'+id).each(function()
			{
				totalValue += parseFloat($(this).text());
			});
			var invoiceAmount = parseFloat(totalAmountWithTax) + parseFloat(totalValue);
			$('#price_'+id).val(invoiceAmount.toFixed(2));
			inv_cal_final_total();
		}
	})
  $(document).on('change', '.item_value', function () {
      let index = $(this).attr('id').split('_')[1]; // Extract the number from id, e.g. item_1 → 1
      let selectedOption = $(this).find('option:selected');

      let price = parseFloat(selectedOption.data('price')) || 0;
      let unit = selectedOption.data('unit') || '';
      let desc = selectedOption.data('description');
      let ItemId = selectedOption.data('item-id');

      // Set the values in inputs
      var finalPrice = price.toFixed(2);
      $('#price_' + index).val(finalPrice);
      $('#unit_' + index).val(unit);
      $('#details_'+ index).val(desc);
      $('#item_id_'+ index).val(ItemId);

      inv_cal_final_total()
  });

</script>
