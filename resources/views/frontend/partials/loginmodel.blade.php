<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
    integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
.new-form-designs {
    border: 1px solid var(--border-color);
    border-radius: 5px;
    height: 39px;
    line-height: 55px;
    padding: 0;
    padding-inline-start: 45px; /* space for flag */
    padding-inline-end: 10px;
    font-size: 16px;
    margin-top: 10px;
}

.phone-input-wrapper {
    position: relative;
}

.phone-flag {
    position: absolute;
    top: 49%;
    left: 12px;
    transform: translateY(-50%);
    font-size: 18px;
}

.modal-footer {
    padding: 0px;
}

.close-btn-login {
    background-color: #947E41;
    border-radius: 50px;
    width: 17px;
    font-size: 12px;
    height: 17px;
    display: flex;
    justify-content: center;
    align-items: center;
    opacity: 1;
    color: white;
}

.close-btn-login:hover {
    background-color: #947E41 !important;
    border-radius: 50px;
    width: 17px;
    font-size: 12px;
    height: 17px;
    display: flex;
    justify-content: center;
    align-items: center;
    opacity: 1;
    color: black !important;
}

.model-new {
    width: 400px;
    margin: auto;
}

.model-otp {
    width: 400px;
    margin: auto;
}

@media(max-width:300px) {
    .model-otp {
        width: 300px !important;
        margin: auto;
    }
}

@media(min-width:320px) and (max-width:350px) {
    .model-otp {
        width: 300px !important;
        margin: auto;
    }
}

@media (min-width: 350px) and (max-width: 500px) {
    .model-otp {
        width: 350px !important;
        margin: auto;
    }
}

.box-p {
    padding-top: 28px;
    padding-right: 24px;
    padding-left: 24px;
    padding-bottom: 0px;
}

.box-p-1 {
    padding-top: 25px;
    padding-right: 24px;
    padding-left: 24px;
    padding-bottom: 28px;
}

.box-p-2 {
    padding-top: 28px;
    padding-right: 24px;
    padding-left: 24px;
    padding-bottom: 0px;
}

.box-p-3 {
    padding-right: 24px;
    padding-left: 24px;
}

.otp-input-wrapper input.otp-box {
    width: 45px;
    height: 45px;
    font-size: 18px;
    border: 1px solid #ccc;
    border-radius: 6px;
    outline: none;
    transition: border-color 0.2s;
}

.otp-input-wrapper input.otp-box:focus {
    border-color: #947E41;
    box-shadow: 0 0 3px rgba(148, 126, 65, 0.5);
}
</style>

<div class="modal fade" id="customerPhoneModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content model-new" style="box-shadow: 0 8px 12px rgba(31, 92, 163, .2); ">
            <div class="modal-header box-p"
                style="justify-content: space-between; flex-direction: column; align-items: flex-start; border-bottom:none;">
                <div style="width: 100%; display: flex; justify-content: space-between; align-items: center;">
                    <h4 class="modal-title" id="exampleModalLongTitle">{{ __('Login / Register') }}</h4>
                    <button type="button" class="close-btn-login" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa fa-xmark"></i>
                    </button>
                </div>
                <p class="login-subtext" style="margin-top: 4px; margin-bottom:0px; color: #6b7280; font-size: 14px;">
                    {{ __('Please enter your Phone Number') }}
                </p>
            </div>

            <div class="modal-body  box-p-1">
                <div class="row no-gutters">
                    <div class="col-lg-12">
                        <div class="form-group phone-input-wrapper">
                            <span class="phone-flag"> <img src="https://flagcdn.com/w20/in.png" alt="India Flag"
                                        style="width: 24px; height: 16px; border-radius: 2px;"></span>  
                            <input type="text" name="phone" id="in_phone"
                                class="form-control new-form-designs" value="+91 " />
                            <p id="editErr_in_phone" class="mt-2 mb-0 text-danger em"></p>
                        </div>
                    </div>
                </div>

                <div class="modal-footer" style="border-top:none;">
                    <button id="sendOtp" type="button" class="btn btn-primary btn-sm" disabled
                        style="background: #6c603c ; margin-top : 20px; ">
                        {{ __('Send OTP') }}
                    </button>
                </div>
                <a href="{{ url('terms-&-condition')}}" target="_blank" class="text-center d-block"
                    style="margin-top: 20px; font-size:15px; text-align: center;">
                    <span style="color: #6c603c; margin-left: 5px;">Terms and conditions</span>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="otpVerificationModal" tabindex="-1" role="dialog"
    aria-labelledby="otpVerificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered model-otp" role="document">
        <div class="modal-content model-otp">
            <div class="modal-header box-p-2 mb-2"
                style="justify-content: space-between; align-items: flex-start; border-bottom:none;">
                <div>
                    <h4 class="modal-title mb-1" id="otpVerificationModalLabel">
                        {{ __('Verify your number') }}
                    </h4>
                    <h4 style="color:#000; font-weight:600; margin:0;">
                        <span id="editFrontPhone">+91-7854875487</span>
                        <i class="fa fa-pencil" id="editPhoneNumber"
                            style="color:#6c603c; font-size:13px; margin-left:4px; cursor:pointer;"></i>
                    </h4>
                </div>
                <button type="button" class="close-btn-login" data-bs-dismiss="modal" aria-label="Close"
                    style="margin-top:2px;">
                    <i class="fa fa-xmark"></i>
                </button>
            </div>

            <div class="modal-body text-center box-p-3 ">
                <label class="form-label d-block" style="text-align:left !important;">
                    {{ __('Enter your 4 digit OTP') }}
                </label>

                <div class="d-flex gap-2 otp-input-wrapper">
                    <input type="text" maxlength="1" class="otp-box form-control" />
                    <input type="text" maxlength="1" class="otp-box form-control" />
                    <input type="text" maxlength="1" class="otp-box form-control" />
                    <input type="text" maxlength="1" class="otp-box form-control" />
                </div>

                <div>
                    <p style="text-align:left; margin-top:5px;">
                        Haven't received yet?
                        <span id="resendOtp" style="color:#6c603c; cursor:pointer; text-decoration:underline;">
                            Resend OTP
                        </span>
                        <span id="otpTimer" style="margin-left:8px; color:#6b7280;"></span>
                    </p>
                </div>

                <input type="hidden" id="customerOtpInput" name="customerOtpInput">

                <div class="invalid-feedback" id="customerOtpError">
                    {{ __('Invalid OTP. Please try again.') }}
                </div>

                <p id="otp_error" class="mt-2 mb-0 text-danger em"></p>
            </div>

            <div class="modal-footer" style="border-top:none; margin-bottom:24px;">
                <button id="verifyOtpBtn" type="button" class="btn btn-primary btn-sm mx-3 mb-3"
                    style="background: #947E41 !important; margin-top:20px;">
                    {{ __('Verify') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="{{ asset('assets/js/jquery.min.js') }}"></script>

<script>
$(document).ready(function() {
    $('#customerPhoneModal').on('show.bs.modal', function(event) {
        let button = $(event.relatedTarget);
        let action = button.data('action');
        $(this).data('action', action);
    });

    // Phone number input logic with +91 prefix lock
    const phoneInput = document.getElementById('in_phone');
    const prefix = '+91 ';
    phoneInput.value = prefix;

    phoneInput.addEventListener('input', function(e) {
        // Ensure prefix always remains
        if (!this.value.startsWith(prefix)) {
            this.value = prefix + this.value.replace(/\D/g, '').substring(0, 10);
        } else {
            // Only allow digits after prefix
            let digits = this.value.substring(prefix.length).replace(/\D/g, '');
            if (digits.length > 10) digits = digits.substring(0, 10);
            this.value = prefix + digits;
        }
    });

    phoneInput.addEventListener('keydown', function(e) {
        // Prevent deleting the prefix
        if (this.selectionStart <= prefix.length && (e.key === "Backspace" || e.key === "Delete")) {
            e.preventDefault();
        }
    });

    $('#in_phone').on('input', function() {
        let phone = $(this).val().replace(prefix, '');
        if (/^\d{10}$/.test(phone)) {
            $('#sendOtp').prop('disabled', false).css({
                'cursor': 'pointer',
                'opacity': '1'
            });
        } else {
            $('#sendOtp').prop('disabled', true).css({
                'cursor': 'not-allowed',
                'opacity': '.5'
            });
        }
    });

    $('#editPhoneNumber').on('click', function() {
        const phone = $(this).attr('data-phone');
        $('#in_phone').val(prefix + phone);
        $('#customerPhoneModal').modal('show');
        $('#otpVerificationModal').modal('hide');
    });

    // === Send OTP ===
    $('#sendOtp').on('click', function() {
        let phone = $('#in_phone').val().replace(prefix, '');
        let action = $('#customerPhoneModal').data('action');

        if (!/^\d{10}$/.test(phone)) {
            $('#editErr_in_phone').text('Please enter a valid 10-digit phone number.');
            return;
        }

        $('#editErr_in_phone').text('');
        $(this).prop('disabled', true).text('Sending...');
        $('#editFrontPhone').text('+91-' + phone);
        $('#editPhoneNumber').attr('data-phone', phone);

        $.ajax({
            url: '{{ route("send.otp") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                phone: phone
            },
            success: function(response) {
                $('#sendOtp').prop('disabled', false).text('Send OTP');
                $('#customerPhoneModal').modal('hide');
                $('#otpVerificationModal').modal('show');
                $('#otpVerificationModal').data('phone', phone);
                $('#otpVerificationModal').data('action', action);
            },
            error: function(xhr) {
                $('#editErr_in_phone').text(xhr.responseJSON.message || 'An error occurred.');
                $('#sendOtp').prop('disabled', false).text('Send OTP');
            }
        });
    });

    // === Verify OTP ===
    $('#verifyOtpBtn').on('click', function() {
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
            success: function(response) {
                window.location.href = response.url;
            },
            error: function(xhr) {
                $('#otp_error').text(xhr.responseJSON.message || 'Invalid OTP.');
                $('#verifyOtpBtn').prop('disabled', false).text('Verify OTP');
            }
        });
    });
});

// OTP box logic
document.querySelectorAll(".otp-box").forEach((box, index, boxes) => {
    box.addEventListener("input", (e) => {
        if (e.target.value.length === 1 && index < boxes.length - 1) {
            boxes[index + 1].focus();
        }
        document.getElementById("customerOtpInput").value =
            Array.from(boxes).map(b => b.value).join("");
    });

    box.addEventListener("keydown", (e) => {
        if (e.key === "Backspace" && !e.target.value && index > 0) {
            boxes[index - 1].focus();
        }
    });
});

// OTP timer + resend logic (unchanged)
(function() {
    const OTP_VALID_MS = 10 * 60 * 1000;
    const RESEND_COOLDOWN_MS = 30 * 1000;

    let otpExpiryAt = null;
    let resendCooldownUntil = 0;
    let otpTimerInterval = null;
    let resendTimerInterval = null;

    function ensureAnchors() {
        let $resend = $('#resendOtp');
        if (!$resend.length) {
            $resend = $('p:contains("Resend OTP") span:contains("Resend OTP")').first();
            if ($resend.length) $resend.attr('id', 'resendOtp').css('cursor', 'pointer');
        }

        let $timer = $('#otpTimer');
        if (!$timer.length) {
            const $p = $resend.closest('p');
            if ($p.length) {
                $timer = $('<span id="otpTimer" style="margin-left:8px; color:#6b7280;"></span>');
                $p.append($timer);
            }
        }
        return { $resend, $timer };
    }

    function fmt(ms) {
        const s = Math.max(0, Math.floor(ms / 1000));
        const m = Math.floor(s / 60);
        const r = s % 60;
        return `${String(m).padStart(2, '0')}:${String(r).padStart(2, '0')}`;
    }

    function clearAllIntervals() {
        if (otpTimerInterval) {
            clearInterval(otpTimerInterval);
            otpTimerInterval = null;
        }
        if (resendTimerInterval) {
            clearInterval(resendTimerInterval);
            resendTimerInterval = null;
        }
    }

    function setVerifyEnabled(enabled) {
        $('#verifyOtpBtn').prop('disabled', !enabled);
    }

    function startOtpValidityTimer() {
        const { $timer } = ensureAnchors();
        if (!otpExpiryAt) otpExpiryAt = Date.now() + OTP_VALID_MS;

        if (otpTimerInterval) clearInterval(otpTimerInterval);
        otpTimerInterval = setInterval(() => {
            const remaining = otpExpiryAt - Date.now();
            if (remaining <= 0) {
                clearInterval(otpTimerInterval);
                setVerifyEnabled(false);
                $('#customerOtpError').text('OTP expired. Please tap “Resend OTP”.').show();
                return;
            }
        }, 1000);
        setVerifyEnabled(true);
        $('#customerOtpError').hide();
    }

    function setResendEnabled(enabled, labelOverride) {
        const { $resend } = ensureAnchors();
        if (!$resend.length) return;

        if (enabled) {
            $resend.css({
                pointerEvents: 'auto',
                opacity: 1,
                textDecoration: 'underline'
            });
            $resend.text(labelOverride || 'Resend OTP');
        } else {
            $resend.css({
                pointerEvents: 'none',
                opacity: 0.5,
                textDecoration: 'none'
            });
            if (labelOverride) $resend.text(labelOverride);
        }
    }

    function startResendCooldown(ms = RESEND_COOLDOWN_MS) {
        resendCooldownUntil = Date.now() + ms;
        if (resendTimerInterval) clearInterval(resendTimerInterval);

        const tick = () => {
            const left = resendCooldownUntil - Date.now();
            if (left <= 0) {
                clearInterval(resendTimerInterval);
                resendTimerInterval = null;
                setResendEnabled(true, 'Resend OTP');
            } else {
                setResendEnabled(false, `Resend OTP (${fmt(left)})`);
            }
        };
        resendTimerInterval = setInterval(tick, 1000);
        tick();
    }

    $('#otpVerificationModal').on('shown.bs.modal', function() {
        otpExpiryAt = Date.now() + OTP_VALID_MS;
        startOtpValidityTimer();
        startResendCooldown();
    });

    $('#verifyOtpBtn').off('click.otpExpiryGuard').on('click.otpExpiryGuard', function() {
        if (otpExpiryAt && Date.now() > otpExpiryAt) {
            $('#otp_error').text('OTP expired. Please resend a new code.').show();
            setVerifyEnabled(false);
            return false;
        }
        return true;
    });

    $(document).on('click', '#resendOtp', function() {
        if (resendCooldownUntil && Date.now() < resendCooldownUntil) return;

        const phone = $('#otpVerificationModal').data('phone');
        if (!phone) {
            $('#customerOtpError').text('Phone number missing. Please go back and enter it again.').show();
            return;
        }

        setResendEnabled(false, 'Sending...');
        $.ajax({
            url: '{{ route("resend.otp") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                phone: phone,
            },
            success: function() {
                $('.otp-box').val('');
                $('#customerOtpInput').val('');
                otpExpiryAt = Date.now() + OTP_VALID_MS;
                startOtpValidityTimer();
                startResendCooldown();
                $('#customerOtpError').hide();
            },
            error: function(xhr) {
                const msg = (xhr.responseJSON && xhr.responseJSON.message) ||
                    'Unable to resend OTP right now.';
                $('#customerOtpError').text(msg).show();
                setResendEnabled(true, 'Resend OTP');
            }
        });
    });

    $('#otpVerificationModal').on('hide.bs.modal', function() {
        clearAllIntervals();
    });

    $('#editPhoneNumber').on('click', function() {
        clearAllIntervals();
    });
})();
</script>
