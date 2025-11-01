
"use strict";
$(document).ready(function () {

	var base_url = $('#base_url').val();
	var count = $('#total_item').val();

$(document).on('click', ".add_item_btn", function () {
    count++;
    $('#total_item').val(count);
    var customer_id = $('#customer_id').val();
    var url = base_url + prefix +'/account/sales-bill/item/' + count;

    $.post(url, {
        customer_id: customer_id,
        _token: $('meta[name="csrf-token"]').attr('content') // required for POST in Laravel
    }, function (json) {
        $('#invoice_row').append(json.view);
        inv_cal_final_total();
    }, 'json');

    return false;
});

	$(document).on('click', '.remove_row', function () {
		var row_id = $(this).attr("id");
		$('.row_id_' + row_id).remove();
		inv_cal_final_total();
	});

	$(document).on('keyup', '.invo_qty', function () {
		inv_cal_final_total();
	});

	$(document).on('keyup', '.invo_price', function ()
	{
		var id = $(this).attr('data-id');
		var totalAmountWithTax = $('#price_'+id).val();
		$('.including_amount_'+id).hide();

		if(totalAmountWithTax != "0")
		{
			if(typeof $('.taxvalue_'+id).html() !== "undefined")
			{
				$('.including_amount_'+id).show();
			}
		}
		inv_cal_final_total();
	});

	$(document).on('keyup', '.invo_discount', function ()
		{
			/* if($(this).val() == '')
				{
				$(this).val(0);
			} */
			inv_cal_final_total();
			currency_fromat()
		});
})
inv_cal_final_total();


function inv_cal_final_total()
{
  console.log("function....");
	var count = $('#total_item').val();
	var amount_decimal = $('#amount_decimal').val();

	var sum_total_price = 0;
	var sum_total_discount = 0;
	const gst_arr = [];
	var grouped = [];
	for (var i = 2; i <= count; i++)
	{
		var gst_count = $('#total_gst_row').val();
		var html = $('.row_id_'+i).html();
		if(typeof html !== "undefined")
		{
			var quantity = $('#qty_' + i).val();
			quantity = parseFloat(quantity);

			var price = $('#price_' + i).val();
			price = parseFloat(price);

			var discount = ($('#discount_' + i).val())?$('#discount_' + i).val():0;
			discount = parseFloat(discount);

			var total_price = quantity * price;
			$('#price_text_'+i).text(total_price.toFixed(amount_decimal));
			$('#total_price_'+i).val(total_price.toFixed(amount_decimal));

			var total_discount = total_price * discount / 100;
			$('#discount_text_'+i).text(total_discount.toFixed(amount_decimal));

			var price_with_discount = total_price - total_discount;

			for (var j = 1; j <= gst_count; j++)
			{
				var gst_row = $('#gst_row_'+i+'_'+j+'').html();
				if(typeof gst_row !== "undefined")
				{
					var rate = $('#tax_'+i+'_'+j+'').find('option:selected').attr('data-rate');
					if(typeof rate !== "undefined")
					{
						var gst_name = $('#tax_'+i+'_'+j+'').find('option:selected').attr('data-name');
						var tax_text = price_with_discount * rate /100;
						$('#tax_text_'+i+'_'+j+'').html('<span class="currency_wrapper"></span>'+tax_text.toFixed(amount_decimal)+'<span class="gettaxvalue_'+i+'" style="display:none;">'+tax_text.toFixed(amount_decimal)+'</span>');

						$('#tax_val_'+i+'_'+j+'').val(tax_text.toFixed(amount_decimal));
						gst_arr.push({name: gst_name, amount: tax_text});
					}
				}
			}
			grouped = Array.from(
				gst_arr.reduce(
					(m, { name, amount }) => m.set(name, (m.get(name) || 0) + amount),
					new Map
				).entries(),
				([name, amount]) => ({ name, amount })
			);
			sum_total_price += total_price;
			sum_total_discount += total_discount;
		}
	}
	$('#subtotal').html(sum_total_price.toFixed(amount_decimal));
	$('.subtotal').val(sum_total_price.toFixed(amount_decimal));
	$('#total_discount').html(sum_total_discount.toFixed(amount_decimal));
	$('.total_discount').val(sum_total_discount.toFixed(amount_decimal));

	var gstshow = '';
	var total_tax = 0;
	grouped.forEach((element, index, array) => {
		var gstamt = element.amount;
		gstshow += '<tr><td colspan="6" class="text-right" style="font-weight: bold;color: #000;">'+element.name+': </td><td style="font-weight: bold;color: #000;"><span class="currency_wrapper"></span><span>'+gstamt.toFixed(amount_decimal)+'</span><input type="hidden" name="tax_value['+element.name+']" value="'+gstamt+'"></td><td></td></tr>';
		total_tax += element.amount;
	});

	$('#gst_total_tax').html(gstshow);
	var grand_total = sum_total_price - sum_total_discount + total_tax;
	$('#grandTotal').text(grand_total.toFixed(amount_decimal));
	$('.grandtotal').val(grand_total.toFixed(amount_decimal));
}
