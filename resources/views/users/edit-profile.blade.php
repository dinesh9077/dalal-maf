@extends('users.layout')

@section('content')
<div class="page-header">
    <h4 class="page-title">{{ __('Edit Profile') }}</h4>
    <ul class="breadcrumbs">
        <li class="nav-home">
            <a href="{{ route('user.dashboard') }}">
                <i class="flaticon-home"></i>
            </a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="#">{{ __('Edit Profile') }}</a>
        </li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-title">{{ __('Edit Profile') }}</div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <div class="alert alert-danger pb-1 dis-none" id="carErrors">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <ul></ul>
                        </div>
                        <form action="{{ route('user.update_profile') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="">{{ __('Photo') }}</label>
                                        <br>
                                        <div class="thumb-preview">
                                            @if (Auth::guard('web')->user()->image != null)
                                            <img src="{{ asset('assets/img/users/' . Auth::guard('web')->user()->image) }}"
                                                alt="..." class="uploaded-img">
                                            @else
                                            <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..."
                                                class="uploaded-img">
                                            @endif

                                        </div>

                                        <div class="mt-3">
                                            <div role="button" class="btn btn-primary btn-sm upload-btn">
                                                {{ __('Choose Photo') }}
                                                <input type="file" class="img-input" name="image">
                                            </div>
                                            <p id="editErr_photo" class="mt-1 mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group mb-30">
                                        <label for="" class="mb-1">{{ __('Name') . ' *' }}</label>
                                        <input type="text" class="form-control new-eds-progile"
                                            value="{{ Auth::guard('web')->user()->name }}"
                                            placeholder="{{ __('Name') }}" name="name">
                                        @error('name')
                                        <p class="text-danger mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-30">
                                        <label for="" class="mb-1">{{ __('Username') . ' *' }}</label>
                                        <input type="text" class="form-control new-eds-progile"
                                            placeholder="{{ __('Username') }}" name="username"
                                            value="{{ Auth::guard('web')->user()->username }}">
                                        @error('username')
                                        <p class="text-danger mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-30">
                                        <label for="" class="mb-1">{{ __('Email') . ' *' }}</label>
                                        <input type="email" class="form-control new-eds-progile"
                                            placeholder="{{ __('Email') }}" name="email"
                                            value="{{ Auth::guard('web')->user()->email }}">
                                        @error('email')
                                        <p class="text-danger mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                {{-- PHONE --}}
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>{{ __('Phone') }}</label>
                                        <div class="input-group">
                                            <input type="tel" value="{{ Auth::guard('web')->user()->phone }}"
                                                class="form-control" id="phone" maxlength="10" pattern="\d{10}"
                                                data-original-phone="{{ Auth::guard('web')->user()->phone }}" disabled>
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-primary"
                                                    id="btnEditModal">Edit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-30">
                                        <label for="" class="mb-1">{{ __('Country') . ' *' }}</label>
                                        <input type="text" class="form-control new-eds-progile"
                                            placeholder="{{ __('Country') }}" name="country"
                                            value="{{ Auth::guard('web')->user()->country }}">
                                        @error('country')
                                        <p class="text-danger mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group mb-30">
                                        <label for="" class="mb-1">{{ __('City') . ' *' }}</label>
                                        <input type="text" class="form-control new-eds-progile"
                                            placeholder="{{ __('City') }}" name="city"
                                            value="{{ Auth::guard('web')->user()->city }}">
                                        @error('city')
                                        <p class="text-danger mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group mb-30">
                                        <label for="" class="mb-1">{{ __('State') . ' *' }}</label>
                                        <input type="text" class="form-control new-eds-progile"
                                            placeholder="{{ __('State') }}" name="state"
                                            value="{{ Auth::guard('web')->user()->state }}">
                                        @error('state')
                                        <p class="text-danger mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group mb-30">
                                        <label for="" class="mb-1">{{ __('Zip Code') . ' *' }}</label>
                                        <input type="text" class="form-control new-eds-progile"
                                            placeholder="{{ __('Zip Code') }}" name="zip_code"
                                            value="{{ Auth::guard('web')->user()->zip_code }}">
                                        @error('zip_code')
                                        <p class="text-danger mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group mb-30">
                                        <label for="" class="mb-1">{{ __('Address') . ' *' }}</label>
                                        <textarea name="address" id="" class="form-control new-eds-progile" rows="3"
                                            placeholder="{{ __('Address') }}"
                                            style="min-height: 55px;">{{ Auth::guard('web')->user()->address }}</textarea>
                                        @error('address')
                                        <p class="text-danger mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-12 mb-15">
                                    <div class="form-button">
                                        <button type="submit"
                                            class="btn btn-lg btn-primary">{{ __('Update Profile') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- <div class="card-footer">
                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="submit"class="btn btn-lg btn-primary">{{ __('Update Profile') }}</button>
        </div>
    </div>
</div> --}}
</div>
</div>
</div>
<div class="modal fade" id="customerPhoneModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content model-new" style="box-shadow: 0 8px 12px rgba(31, 92, 163, .2); ">
            <div class="modal-header box-p"
                style="justify-content: space-between; flex-direction: column; align-items: flex-start; border-bottom:none;">
                <div style="width: 100%; display: flex; justify-content: space-between; align-items: center;">
                    <h4 class="modal-title" id="exampleModalLongTitle">{{ __('Update Phone') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-xmark"></i>
                    </button>
                </div>
            </div>

            <div class="modal-body  box-p-1">

                <div class="row no-gutters">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="">{{ __('Phone') }}</label>
                            <input type="text" name="phone" id="in_phone"
                                value="{{ Auth::guard('web')->user()->phone }}" class="form-control new-form-designs">
                            <p id="editErr_in_phone" class="mt-2 mb-0 text-danger em"></p>
                        </div>
                    </div>
                </div>

                <div class="modal-footer" style="border-top:none;">
                    <button id="sendOtp" type="button" class="btn btn-primary btn-sm"
                        style="background: #6c603c ; margin-top : 20px; ">
                        {{ __('Send OTP') }}
                    </button>
                </div>
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
                <button type="button" class="close-btn-login" data-dismiss="modal" aria-label="Close"
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

@endsection
@section('script')
<script>
document.getElementById('phone').addEventListener('input', function(e) {
    this.value = this.value.replace(/\D/g, '');
    // Limit to 10 digits
    if (this.value.length > 10) {
        this.value = this.value.slice(0, 10);
    }
});

$('#btnEditModal').click(function() {
    $('#customerPhoneModal').modal('show');
})

$(document).ready(function() {
    $('#customerPhoneModal').on('show.bs.modal', function(event) {
        let button = $(event.relatedTarget);
        let action = button.data('action');
        $(this).data('action', action);
    });

    document.getElementById('in_phone').addEventListener('input', function(e) {
        // Remove any non-digit characters
        this.value = this.value.replace(/\D/g, '');

        // Limit to 10 digits
        if (this.value.length > 10) {
            this.value = this.value.slice(0, 10);
        }
    });
    // Phone number input validation
    $('#in_phone').on('input', function() {
        let phone = $(this).val();

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
        $('#in_phone').val(phone);
        $('#customerPhoneModal').modal('show');
        $('#otpVerificationModal').modal('hide');
    });

    // Send OTP
    $('#sendOtp').on('click', function() {
        let phone = $('#in_phone').val();
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
            url: '{{ route("user.phone.send-otp") }}', // Update this route
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
                $('#editErr_in_phone').text(xhr.responseJSON.message ||
                    'An error occurred.');
                $('#sendOtp').prop('disabled', false).text('Send OTP');
            }
        });
    });

    // Verify OTP
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
            url: '{{ route("user.phone.verify-otp") }}',
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

document.querySelectorAll(".otp-box").forEach((box, index, boxes) => {
    box.addEventListener("input", (e) => {
        if (e.target.value.length === 1 && index < boxes.length - 1) {
            boxes[index + 1].focus();
        }
        // Combine all digits into hidden input
        document.getElementById("customerOtpInput").value =
            Array.from(boxes).map(b => b.value).join("");
    });

    box.addEventListener("keydown", (e) => {
        if (e.key === "Backspace" && !e.target.value && index > 0) {
            boxes[index - 1].focus();
        }
    });
});

// === RESEND + 10-MIN EXPIRY (non-breaking) ===============================
(function() {
    const OTP_VALID_MS = 10 * 60 * 1000; // 10 minutes validity
    const RESEND_COOLDOWN_MS = 30 * 1000; // 30s anti-spam cooldown

    let otpExpiryAt = null;
    let resendCooldownUntil = 0;
    let otpTimerInterval = null;
    let resendTimerInterval = null;

    // Gracefully find/create the UI anchors if IDs weren't added in HTML
    function ensureAnchors() {
        // Try to find the "Resend OTP" span; if missing, attach an id to the first one we see
        let $resend = $('#resendOtp');
        if (!$resend.length) {
            $resend = $('p:contains("Resend OTP") span:contains("Resend OTP")').first();
            if ($resend.length) $resend.attr('id', 'resendOtp').css('cursor', 'pointer');
        }

        // Ensure we have a timer label next to it
        let $timer = $('#otpTimer');
        if (!$timer.length) {
            const $p = $resend.closest('p');
            if ($p.length) {
                $timer = $('<span id="otpTimer" style="margin-left:8px; color:#6b7280;"></span>');
                $p.append($timer);
            }
        }
        return {
            $resend,
            $timer
        };
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
        const {
            $timer
        } = ensureAnchors();
        if (!otpExpiryAt) otpExpiryAt = Date.now() + OTP_VALID_MS;

        if (otpTimerInterval) clearInterval(otpTimerInterval);
        otpTimerInterval = setInterval(() => {
            const remaining = otpExpiryAt - Date.now();
            if (remaining <= 0) {
                clearInterval(otpTimerInterval);
                setVerifyEnabled(false);
                // Gentle inline hint
                $('#customerOtpError').text('OTP expired. Please tap “Resend OTP”.').show();
                return;
            }
            // $timer && $timer.text(`Code expires in ${fmt(remaining)}`);
        }, 1000);
        // immediate draw
        const remainingNow = otpExpiryAt - Date.now();
        // $timer && $timer.text(`Code expires in ${fmt(remainingNow)}`);
        setVerifyEnabled(true);
        $('#customerOtpError').hide();
    }

    function setResendEnabled(enabled, labelOverride) {
        const {
            $resend
        } = ensureAnchors();
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
        tick(); // draw immediately
    }
    // Wire into your existing flows:

    // 1) When "Send OTP" succeeds (your existing success callback),
    //    we hook via the modal show event to ensure timer starts every time it opens.
    $('#otpVerificationModal').on('shown.bs.modal', function() {
        otpExpiryAt = Date.now() + OTP_VALID_MS; // reset validity window on each send
        startOtpValidityTimer();
        startResendCooldown(); // small cooldown before allowing another resend
    });

    // 2) Block verification if expired (non-destructive addition to your handler)
    $('#verifyOtpBtn').off('click.otpExpiryGuard').on('click.otpExpiryGuard', function() {
        if (otpExpiryAt && Date.now() > otpExpiryAt) {
            $('#otp_error').text('OTP expired. Please resend a new code.').show();
            setVerifyEnabled(false);
            return false;
        }
        // else let your original click handler continue
        return true;
    });

    // 3) Handle "Resend OTP" click
    $(document).on('click', '#resendOtp', function() {
        // cooldown guard
        if (resendCooldownUntil && Date.now() < resendCooldownUntil) return;

        const phone = $('#otpVerificationModal').data('phone');
        if (!phone) {
            $('#customerOtpError').text('Phone number missing. Please go back and enter it again.').show();
            return;
        }

        setResendEnabled(false, 'Sending...');
        $.ajax({
            url: '{{ route("user.phone.resend-otp") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                phone: phone,
                // Optionally tell backend we're refreshing the same OTP flow:
                // reason: 'resend'
            },
            success: function() {
                // Reset input boxes visually
                $('.otp-box').val('');
                $('#customerOtpInput').val('');

                // Restart validity window and timers
                otpExpiryAt = Date.now() + OTP_VALID_MS;
                startOtpValidityTimer();
                startResendCooldown();

                $('#customerOtpError').hide();
            },
            error: function(xhr) {
                const msg = (xhr.responseJSON && xhr.responseJSON.message) ||
                    'Unable to resend OTP right now.';
                $('#customerOtpError').text(msg).show();
                // Let user try again soon
                setResendEnabled(true, 'Resend OTP');
            }
        });
    });

    // 4) Clean up timers when the OTP modal closes
    $('#otpVerificationModal').on('hide.bs.modal', function() {
        clearAllIntervals();
    });

    // 5) Also ensure when you jump back to "edit phone" we clean timers
    $('#editPhoneNumber').on('click', function() {
        clearAllIntervals();
    });
})();
</script>
@endsection