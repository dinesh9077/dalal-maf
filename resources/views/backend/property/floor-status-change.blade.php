
<style>
.modal-header{
  display: flex;
  align-items: center;
}
.label-main{
		margin-top: 10px;
	}
</style>

<div class="add-modal-main modal fade" id="property_work_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Change Status</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" style="font-size : 24px; color : #000000; border : none; background : white;">×</button>
			    </div>
            <form id="propertyWorkAddForm" action="{{route('admin.property_inventory.floor.status_store')}}" method="post" enctype="multipart/form-data">
              <input type="hidden" name="property_status" value="{{$property_status}}">
              <div class="modal-body">
                  <div class="modal-form">
                      <div class="row">
                        <div class="col-lg-12">
                          <label class="label-main">Customer <span class="text-danger">*</span></label>
                          <select name="customer_id" class="form-control" id="customer_id">
                              <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                  <option value="{{$customer->id}}">{{$customer->name}}</option>
                                @endforeach
                                <option value="add_new">➕ Add New Customer</option>
                          </select>
                        </div>
                        <div class="col-lg-6" style="pointer-events:none;">
                          <label class="label-main">Property <span class="text-danger">*</span></label>
                          <select name="property_id" class="form-control" id="property_ids">
                              <option value="{{$property->property_id}}" <?php echo ($property->id == $property_id)?'selected':''; ?>>{{$property->title}}</option>

                          </select>
                        </div>
                        <div class="col-lg-6" style="pointer-events:none;">
                            <label class="label-main">Select Wings <span class="text-danger">*</span></label>
                            <select name="wing_id" class="form-control" id="wing_id" >
                                <option value="">Select Wings</option>
                                @foreach($wings as $wing)
                                  <option value="{{$wing->id}}" <?php echo ($wing->id == $wing_id)?'selected':''; ?>>{{$wing->wing_number}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6" style="pointer-events:none;">
                            <label class="label-main">Select Floor <span class="text-danger">*</span></label>
                            <select name="floor_id" class="form-control" id="floor_id" >
                                <option value="">Select Floor</option>
                                @foreach($floors as $floor)
                                  <option value="{{$floor->id}}" <?php echo ($floor->id == $floor_id)?'selected':''; ?>>{{$floor->floor_number}}</option>
                                @endforeach
                              </select>
                        </div>
                        <div class="col-lg-6" style="pointer-events:none;">
                            <label class="label-main">Flats <span class="text-danger">*</span></label>
                            <select class="form-control" name="unit_id" id="unit_id" >
                              <option value="">Select Flats</option>
                              @foreach($floor_details as $floor_detail)
                                <option value="{{$floor_detail->id}}" <?php echo ($floor_detail->id == $unit_id)?'selected':''; ?>>{{$floor_detail->flat_no}}</option>
                              @endforeach
                            </select>
                        </div>
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-secondary btn-sm">Save</button>
                  <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
              </div>
			      </form>
		</div>
	</div>
	<script>

		$('#property_status').change(function()
		{
			var property_status = $('#property_status').val();

			if(property_status == 3)
			{
				toastrMsg('error','The Property can not be sold here.');
				$('#property_status').val('').select2();
				$('.select2').each(function () {
					$(this).select2({
						dropdownParent: $(this).parent(),
						width:"100%"
					});
				});
				$('.select2-selection--single').addClass('gray-bg');
				return false;
			}
		})

		$('#propertyWorkAddForm').submit(function(event)
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
					$('#propertyWorkAddForm').find('button').prop('disabled',false);
					$('#propertyWorkAddForm').find('button.spin-button').removeClass('loading').html('Save');
					if(res.status == "error")
					{
						toastrMsg(res.status,res.msg);
					}
					else if(res.status == "validation")
					{
						$('.error').remove();
						$.each(res.errors, function(key, value)
						{
							if(key == "property_id")
							{
								key = "property_ids";
							}
							var inputField = $('#' + key);
							var errorSpan = $('<span>')
							.addClass('error text-danger')
							.attr('id', key + 'Error')
							.text(value[0]);
							inputField.parent().append(errorSpan);
						});
					}
					else
					{
						toastrMsg(res.status,res.msg);
						$('#property_work_modal').modal('hide');
						$('#property_work_modal').remove();
						$('.modal-backdrop').remove();
						$('body').css({'overflow': 'auto'});
            
					}
				}
			});
		});
	</script>
</div>

<!-- Add Customer Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="addCustomerForm">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add New Customer</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Name *</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Email *</label>
            <input type="email" name="email" class="form-control" id="email" required>
          </div>
          <div class="form-group">
            <label>Phone *</label>
            <input type="number" name="phone" class="form-control"
                  oninput="if(this.value.length > 10) this.value = this.value.slice(0, 10);"
                  required placeholder="Enter phone number">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-secondary">Save</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
    // Detect "Add New" option
    $('#customer_id').on('change', function() {
        if ($(this).val() === "add_new") {
            $('#addCustomerModal').modal('show');
        }
    });

    // When modal is closed without saving
    $('#addCustomerModal').on('hidden.bs.modal', function () {
        if ($('#customer_id').val() === 'add_new') {
            $('#customer_id').val(''); // Reset to "Select Customer"
        }
    });

    // Handle form submit via AJAX
    $('#addCustomerForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: "{{ route('admin.property_inventory.add.customer') }}", // adjust route
            method: "POST",
            data: $(this).serialize(),
            success: function(res) {
               if(res.status == "validation")
              {
                $('.error').remove();
                $.each(res.errors, function(key, value) {
                  var inputField = $('#' + key);
                  var errorSpan = $('<span>')
                  .addClass('error text-danger')
                  .attr('id', key + 'Error')
                  .text(value[0]);
                  inputField.parent().append(errorSpan);
                });
              }else if(res.success) {
                    let newCustomer = res.customer;

                    // Add to dropdown
                    $('#customer_id option[value="add_new"]').before(
                        `<option value="${newCustomer.id}" selected>${newCustomer.name}</option>`
                    );

                    // Close modal
                    $('#addCustomerModal').modal('hide');

                    // Reset form
                    $('#addCustomerForm')[0].reset();
                } else {
                    toastrMsg("Something went wrong!");
                }
            }
        });
    });
});

</script>
