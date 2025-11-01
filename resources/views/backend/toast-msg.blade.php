<script>
	@if(Session::has('info'))
	$.toast({
		text: "{{ session('info') }}",
		position:"top-right",
		loaderBg:"#3b98b5",
		icon:"info",
		hideAfter:3e3,
		stack:1
	})
	@endif

	@if(Session::has('error'))
		$.toast({
			text: "{{ session('error') }}",
			position: "top-right",
			loaderBg: "#bf441d",
			icon: "error",
			hideAfter: 3e3,
			stack: 1
		})
	@endif

    @if(Session::has('success'))
	$.toast({
		text: "{{ session('success') }}",
		position: "top-right",
		loaderBg: "#5ba035",
		icon: "success",
		hideAfter: 3e3,
		stack: 1
	})
    @endif

	@if(Session::has('warning'))
	$.toast({
		text: "{{ session('warning') }}",
		position: "top-right",
		loaderBg: "#da8609",
		icon: "warning",
		hideAfter: 3e3,
		stack: 1
	})
	@endif

	function toastrMsg(type,msg)
	{
		$.toast({
			text: msg,
			position: "top-right",
			loaderBg: "#da8609",
			icon: type,
			hideAfter: 3e3,
			stack: 1
		})
	}

	function deleteConfirmModal(obj, event)
	{

		event.preventDefault();

        // Get the URL and message from the button that triggered the modal
        var deleteUrl = $(obj).data('url');

        var deleteMessage = $(obj).data('message') || 'Are you sure you want to delete this item?';

        // Update the modal content
        $('#deleteMessage').text(deleteMessage);
        $('#delete_error_msg').text('');
        $('#confirm_delete_input').val('');

        // Set delete URL for the AJAX call
		$('#deleteFormModal').find('input[name="ids"]').remove();
		$('#deleteFormModal').attr('action', deleteUrl);

		// Show the modal
        $('#deleteConfirmModal').modal('show');
	}

	// Form submission with AJAX
	$('#deleteFormModal').submit(function(event)
	{
		event.preventDefault();

		var form = $(this);
		var confirm_delete_input = $('#confirm_delete_input').val();

		$('#delete_error_msg').text('');

		if (!confirm_delete_input) {
			$('#delete_error_msg').text('The input field is required.');
			return false;
		}

		if (confirm_delete_input !== "DELETE") {
			$('#delete_error_msg').text('You must type DELETE exactly to confirm permanent deletion.');
			return false;
		}

		var formData = new FormData(form[0]);
		formData.append('_token', "{{csrf_token()}}");

		var currentUrl = form.attr('action');
		var params = getQueryParams(currentUrl);

		$.ajax({
			url: currentUrl,
			method: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			success: function(response) {
				toastrMsg(response.status, response.msg);

				if(response.status === 'success') {
					$('#deleteConfirmModal').modal('hide');
				}
			}
		});
	});

	function getQueryParams(url) {
		var queryString = url.split('?')[1];

		if (!queryString) {
			return {};
		}

		var queryParams = {};
		var queryArray = queryString.split('&');

		queryArray.forEach(function(pair) {
			var keyValue = pair.split('=');
			queryParams[keyValue[0]] = decodeURIComponent(keyValue[1] || '');
		});

		return queryParams;
	}
</script>
