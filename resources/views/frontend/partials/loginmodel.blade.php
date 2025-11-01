
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
    .new-form-designs{
    border: 1px solid var(--border-color);
    border-radius: 5px;
    height: 39px;
    line-height: 55px;
    padding: 0;
    padding-inline-start: 18px;
    padding-inline-end: 10px;
    font-size: 16px;
    margin-top: 10px;
    }
	
    .modal-footer {
	padding: 0px;
    }
	
	.close-btn-login{
	background-color: #947E41;
    border-radius: 50px;
    width: 17px;
    font-size: 12px;
    height: 17px;
    display: flex
	;
    justify-content: center;
    align-items: center;
    opacity: 1;
    color: white;
	}
	
	.close-btn-login:hover{
	background-color: #947E41 !important;
    border-radius: 50px;
    width: 17px;
    font-size: 12px;
    height: 17px;
    display: flex
	;
    justify-content: center;
    align-items: center;
    opacity: 1;
    color: black !important;
	}
</style>  


<div class="modal fade" 
     id="customerPhoneModal" 
     tabindex="-1" 
     role="dialog" 
     aria-labelledby="exampleModalCenterTitle" 
     aria-hidden="true"
     data-bs-backdrop="false" 
     data-bs-keyboard="false">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content" style="box-shadow: 0 8px 12px rgba(31, 92, 163, .2);">
			<div class="modal-header" style="justify-content: space-between;">
				<h5 class="modal-title" id="exampleModalLongTitle">{{ __('Login') }}</h5>
				<button type="button" class="close-btn-login" data-bs-dismiss="modal" aria-label="Close">
					
					<i class="fa fa-xmark"></i>
				</button>
			</div>
			
			<div class="modal-body">
				
				<div class="row no-gutters">
					<div class="col-lg-12">
						<div class="form-group">
							<label for="">{{ __('Phone') }}</label>
							<input type="text" name="phone" id="in_phone" class="form-control new-form-designs">
							<p id="editErr_in_phone" class="mt-2 mb-0 text-danger em"></p>
						</div>
					</div>
					
				</div>
				
				<div class="modal-footer">
					{{-- <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
						{{ __('Close') }}
					</button> --}}
					<button id="sendOtp" type="button" class="btn btn-primary btn-sm" disabled style="background: #6c603c ; margin-top : 20px;">
						{{ __('Send OTP') }}
					</button>
				</div> 
			</div>
		</div>
	</div>
</div>
<div class="modal fade" 
     id="otpVerificationModal" 
     tabindex="-1" 
     role="dialog" 
     aria-labelledby="otpVerificationModalLabel" 
     aria-hidden="true"
     data-bs-backdrop="false" 
     data-bs-keyboard="false"> 
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header" style="justify-content: space-between;">
				<h5 class="modal-title" id="otpVerificationModalLabel">{{ __('Verify OTP') }}</h5>
				<button type="button" class="close-btn-login"  data-bs-dismiss="modal" aria-label="Close">
					<i class="fa fa-xmark"></i>
				</button>
			</div>
			<div class="modal-body">
				<p class="mb-2">{{ __('An OTP has been sent to your phone.') }}</p>
				<div class="mb-3">
					<label for="customerOtpInput" class="form-label">{{ __('Enter 4-digit OTP') }}</label>
					<input type="text" maxlength="4" class="form-control new-form-designs" id="customerOtpInput" placeholder="1234">
					<div class="invalid-feedback" id="customerOtpError">{{ __('Invalid OTP. Please try 1234.') }}</div>
				</div>
				<p id="otp_error" class="mt-2 mb-0 text-danger em"></p>
			</div>
			<div class="modal-footer">
				<button id="verifyOtpBtn" type="button" class="btn btn-primary btn-sm mx-3 mb-3" style="background: #947E41 !important; margin-top : 20px;">
					{{ __('Verify') }}
				</button>
			</div>
			
		</div>
	</div>
</div>

<!-- Header End -->
<script type="text/javascript" src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script>
	
	document.addEventListener('DOMContentLoaded', function () {
		const modal = document.getElementById('customerPhoneModal');
		const input = document.getElementById('in_phone');

		// Bootstrap 5 event — triggered when modal fully hides
		modal.addEventListener('hidden.bs.modal', function () {
			input.value = ''; // Clear input
			document.getElementById('editErr_in_phone').textContent = ''; // Clear error if any
			document.getElementById('sendOtp').disabled = true;  
		});

		// Enable "Send OTP" button only when input has value
		input.addEventListener('input', function () {
			document.getElementById('sendOtp').disabled = this.value.trim() === '';
		});
	});

	$(document).ready(function () {
		$('#customerPhoneModal').on('show.bs.modal', function (event) {
			let button = $(event.relatedTarget);
			let action = button.data('action');
			$(this).data('action', action);
		});
		
		document.getElementById('in_phone').addEventListener('input', function (e) {
			// Remove any non-digit characters
			this.value = this.value.replace(/\D/g, '');
			
			// Limit to 10 digits
			if (this.value.length > 10) {
				this.value = this.value.slice(0, 10);
			}
		});
		// Phone number input validation
		$('#in_phone').on('input', function () {
			let phone = $(this).val();
			
			if (/^\d{10}$/.test(phone)) {
				$('#sendOtp').prop('disabled', false).css({ 'cursor': 'pointer', 'opacity': '1' });
				} else {
				$('#sendOtp').prop('disabled', true).css({ 'cursor': 'not-allowed', 'opacity': '.5' });
			}
		});
		
		// Send OTP
		$('#sendOtp').on('click', function () {
			let phone = $('#in_phone').val();
			let action = $('#customerPhoneModal').data('action');
			
			$('#verifyOtpBtn').prop('disabled', false).text('Verify');
			$('#customerOtpInput').val('');
			
			if (!/^\d{10}$/.test(phone)) {
				$('#editErr_in_phone').text('Please enter a valid 10-digit phone number.');
				return;
			}
			
			$('#editErr_in_phone').text('');
			$(this).prop('disabled', true).text('Sending...');
			
			$.ajax({
				url: '{{ route("send.otp") }}', // Update this route
				method: 'POST',
				data: {
					_token: '{{ csrf_token() }}',
					phone: phone
				},
				success: function (response) {
					$('#sendOtp').prop('disabled', false).text('Send OTP');
					
					$('#customerPhoneModal').modal('hide');
					$('#otpVerificationModal').modal('show');
					
					$('#otpVerificationModal').data('phone', phone);
					$('#otpVerificationModal').data('action', action);
				},
				error: function (xhr) {
					$('#editErr_in_phone').text(xhr.responseJSON.message || 'An error occurred.');
					$('#sendOtp').prop('disabled', false).text('Send OTP');
				}
			});
		});
		
		// Verify OTP
		$('#verifyOtpBtn').on('click', function () {
			let otp = $('#customerOtpInput').val();
			let phone = $('#otpVerificationModal').data('phone');
			let action = $('#otpVerificationModal').data('action');
			
			if (!otp) {
				$('#otp_error').text('OTP is required.');
				return;
			}
			
			$('#otp_error').text('');
			$(this).prop('disabled', true).text('Verifying...');
			
			$.ajax({
				url: '{{ route("verify.otp") }}',
				method: 'POST',
				data: {
					_token: '{{ csrf_token() }}',
					phone: phone,
					otp: otp,
					from: action
				},
				success: function (response) {
					window.location.href = response.url;
				},
				error: function (xhr) {
					$('#otp_error').text(xhr.responseJSON.message || 'Invalid OTP.');
					$('#verifyOtpBtn').prop('disabled', false).text('Verify OTP');
				}
			});
		});
	});
</script>

