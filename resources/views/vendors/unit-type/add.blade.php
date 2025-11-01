<div class="add-modal-main modal fade" id="add_unittype_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" style="z-index:99999">
	<div class="modal-dialog modal-dialog-centered  ">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel">Create New Unit Type</h5>
				<button type="button" class="btn-close new-close-btn" data-dismiss="modal" aria-label="Close">×</button>
			</div>
			<form id="unitTypeAddForm" action="{{route('vendor.unit_type.store')}}" method="post" enctype="multipart/form-data">
				<div class="modal-body">
					<div class="modal-form">
						<div class="row">
              <div class="col-lg-12">
              <div class="form-group">
                  <label>Unit Type *</label>
                	<input class="form-control" type="text" name="unit_name" id="unit_name">
              </div>
               </div>
             <div class="col-lg-12">
              <div class="form-group">
                  <label>{{ __('Status') }} *</label>
                 	<select name="status" class="form-control" id="status11">
                      <option value="1">{{ __('Active') }}</option>
                      <option value="0">{{ __('Inactive') }}</option>
                  </select>
              </div>
             </div>

							</div>
						</div>
					</div>

				<div class="modal-footer">
					<button type="button" class="add-main red-btn save-close-btn" data-dismiss="modal">Cancel</button>
					<button type="submit" class="add-main spin-button save-close-btn">Save</button>
				</div>
			</form>
		</div>
	</div>
	<script>
	  $('#unitTypeAddForm').find('.select2').select2({
      dropdownParent: '#add_unittype_modal',
		});



		$('#unitTypeAddForm').submit(function(event)
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
					$('#unitTypeAddForm').find('button').prop('disabled',false);
					$('#unitTypeAddForm').find('button.spin-button').removeClass('loading').html('Save');
					if(res.status == "error")
					{
						console.log(res.status,res.msg);
					}
					else if(res.status == "validation")
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
					}
					else
					{
						toastrMsg(res.status,res.msg);
						$('#add_unittype_modal').modal('hide');
						$('#add_unittype_modal').remove();
						@if(!isset($_GET['outsource']))
							$('.modal-backdrop').remove();
							$('body').css({'overflow': 'auto'});
						@else
							$('.modal-backdrop').remove()
							$('.modal-backdrop.show').css({'opacity': '0'});
							$('<div class="modal-backdrop fade show"></div>').appendTo(document.body);
						@endif
						$('#unit_type').append('<option value="'+res.id+'" selected>'+res.name+'</option>');

					}
				}
			});
		});

		@if(isset($_GET['outsource']))
		$('.btn-close,.red-btn').click(function()
		{
			$('#add_unittype_modal').remove();
			$('.modal-backdrop').remove()
			$('.modal-backdrop.show').css({'opacity': '0'});
			$('<div class="modal-backdrop fade show"></div>').appendTo(document.body);
			setTimeout(function()
			{
				$('body').addClass('modal-open');
			},200);
		})
		@endif
	</script>
</div>
