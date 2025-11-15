<style>
.login-box {
    width: 400px !important;
    max-width: 90%;
    border-radius: 10px;
    background: #fff;
    border: none;
    box-shadow: 0 8px 12px rgba(31, 92, 163, 0.2);
    padding: 20px 20px;
    margin: 0 auto;
    height: 250px;
}

.modal-dialog {
    display: flex;
    justify-content: center;
}

.modal-title {
    font-family: var(--font-family-base) !important;
    color: var(--color-dark) !important;
    font-weight: 600 !important;
    line-height: none !important;
    font-size:20px !important;
    
}

.login-box .modal-header {
    border: none;
    padding: 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* Subtitle */
.login-box .login-subtitle {
    color: #6b7280;
    font-size: 14px;
    margin: 0px 0 5px 0;
    font-weight: 500;
}

.login-box .form-group {
    margin: 0;
    padding: 0;
}

.login-box .form-control {
    height: 40px;
    border-radius: 6px;
    border: 1px solid #ddd;
    padding-left: 12px;
    font-size: 14px;
}

.login-box .form-control:focus {
    border-color: #000;
    box-shadow: 0 0 0 2px rgba(31, 92, 163, 0.1);
}

.login-box .btn-send-otp {
    background-color: #6c603c;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 10px;
    font-weight: 600;
    transition: 0.3s;
}

.login-box .btn-send-otp:hover {
    background-color: #6c603c;
    color: #fff;
}

.login-box .terms-text {
    color: #6c603c;
    font-size: 14px;
    text-align: center;
    cursor: pointer;
}

.close {
    background-color: #6c603c !important; 
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    transition: all 0.3s ease;
}

.close i {
    color: #fff;
    font-size: 14px;
    transition: color 0.3s ease;

}

.close:hover {
    /* background-color: #6c603c;  */
}

.close:hover i {
    color: #000;
}

.model-otp {
    width: 400px !important;
    height: 350px !important;
    margin: auto;
    border-radius: 12px;
    overflow: hidden;
    background: #fff;
    position: relative;
}

@media(max-width: 350px) {
    .model-otp {
        width: 300px !important;
    }
}

@media (min-width: 350px) and (max-width: 500px) {
    .model-otp {
        width: 340px !important;
    }
}

.close-btn-login {
    /* position: absolute; */
    top: 15px;
    right: 15px;
    width: 28px;
    height: 28px;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    margin-top:10px;
}

.close-btn-login i {
    color: white;
    font-size: 14px;
}

.close-btn-login:hover {
    background: #6c603c;
}

.modal-header {
    padding: 28px 24px;
}

#editFrontPhone {
    font-size: 18px;
    font-weight: 600;
    color: #000;
}

#editPhoneNumber {
    cursor: pointer;
    margin-left: 8px;
    color: #6c603c;
}

.otp-input-wrapper input.otp-box-input {
    width: 45px;
    height: 45px;
    font-size: 20px;
    text-align: center;
    border: 1px solid #dcdcdc;
    border-radius: 8px;
    font-weight: 600;
}

.otp-input-wrapper input.otp-box-input:focus {
    border-color: #6c603c;
    box-shadow: 0 0 5px rgba(148, 126, 65, 0.4);
}

.resend-text {
    margin-top: 15px;
    font-size: 14px;
    text-align: center;
}

.resend-link {
    color: #6c603c !important;
    font-weight: 600;
    cursor: pointer;
}

.verify-btn {
    width: 100%;
    display: block;
    background: #6c603c !important;
    color: white;
    padding: 10px;
    font-weight: 600;
    border-radius: 6px;
}
</style>
@extends('vendors.layout')

@section('content')
<div class="page-header">
    <h4 class="page-title">{{ __('Edit Profile') }}</h4>
    <ul class="breadcrumbs">
        <li class="nav-home">
            <a href="{{ route('vendor.dashboard') }}">
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
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 mx-auto">
                        <div class="alert alert-danger pb-1 dis-none" id="carErrors">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <ul></ul>
                        </div>
                        <form id="carForm" action="{{ route('vendor.update_profile') }}" method="post">
                            @csrf
                            <h2>{{ __('Details') }}</h2>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="">{{ __('Photo') }}</label>
                                        <br>
                                        <div class="thumb-preview">
                                            @if ($vendor->photo != null)
                                            <img src="{{ asset('assets/admin/img/vendor-photo/' . $vendor->photo) }}"
                                                alt="..." class="uploaded-img">
                                            @else
                                            <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..."
                                                class="uploaded-img">
                                            @endif

                                        </div>

                                        <div class="mt-3">
                                            <div role="button" class="btn btn-primary btn-sm upload-btn">
                                                {{ __('Choose Photo') }}
                                                <input type="file" class="img-input" name="photo">
                                            </div>
                                            <p id="editErr_photo" class="mt-1 mb-0 text-danger em"></p>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>{{ __('Username*') }}</label>
                                        <input type="text" value="{{ $vendor->username }}" class="form-control"
                                            name="username">
                                        <p id="editErr_username" class="mt-1 mb-0 text-danger em"></p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>{{ __('Email*') }}</label>
                                        <input type="text" value="{{ $vendor->email }}" class="form-control"
                                            name="email">
                                        <p id="editErr_email" class="mt-1 mb-0 text-danger em"></p>
                                    </div>
                                </div>
                                {{-- PHONE --}}
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>{{ __('Phone') }}</label>
                                        <div class="input-group">
                                            <input type="tel" value="{{ $vendor->phone }}" class="form-control"
                                                id="phone" maxlength="10" pattern="\d{10}"
                                                data-original-phone="{{ $vendor->phone }}" disabled>
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-primary"
                                                    id="btnEditModal">Edit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div id="accordion" class="mt-5">
                                        @foreach ($languages as $language)
                                        <div class="version">
                                            <div class="version-header" id="heading{{ $language->id }}">
                                                <h5 class="mb-0">
                                                    <button type="button" class="btn btn-link " data-toggle="collapse"
                                                        data-target="#collapse{{ $language->id }}"
                                                        aria-expanded="{{ $language->is_default == 1 ? 'true' : 'false' }}"
                                                        aria-controls="collapse{{ $language->id }}">
                                                        {{ $language->name . __(' Language') }}
                                                        {{ $language->is_default == 1 ? '(Default)' : '' }}
                                                    </button>
                                                </h5>
                                            </div>

                                            @php
                                            $vendor_info = App\Models\VendorInfo::where(
                                            'vendor_id',
                                            $vendor->id,
                                            )
                                            ->where('language_id', $language->id)
                                            ->first();
                                            @endphp

                                            <div id="collapse{{ $language->id }}"
                                                class="collapse {{ $language->is_default == 1 ? 'show' : '' }}"
                                                aria-labelledby="heading{{ $language->id }}" data-parent="#accordion">
                                                <div class="version-body">
                                                    <div class="row">
                                                        <div class="col-lg-8">
                                                            <div
                                                                class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ __('Name*') }}</label>
                                                                <input type="text"
                                                                    value="{{ !empty($vendor_info) ? $vendor_info->name : '' }}"
                                                                    class="form-control"
                                                                    name="{{ $language->code }}_name"
                                                                    placeholder="Enter Name">
                                                                <p id="editErr_{{ $language->code }}_name"
                                                                    class="mt-1 mb-0 text-danger em"></p>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-4">
                                                            <div
                                                                class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ __('Country') }}</label>
                                                                <input type="text"
                                                                    value="{{ !empty($vendor_info) ? $vendor_info->country : '' }}"
                                                                    class="form-control"
                                                                    name="{{ $language->code }}_country"
                                                                    placeholder="Enter Country">
                                                                <p id="editErr_{{ $language->code }}_country"
                                                                    class="mt-1 mb-0 text-danger em"></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div
                                                                class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ __('City') }}</label>
                                                                <input type="text"
                                                                    value="{{ !empty($vendor_info) ? $vendor_info->city : '' }}"
                                                                    class="form-control"
                                                                    name="{{ $language->code }}_city"
                                                                    placeholder="Enter City">
                                                                <p id="editErr_{{ $language->code }}_city"
                                                                    class="mt-1 mb-0 text-danger em"></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div
                                                                class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ __('State') }}</label>
                                                                <input type="text"
                                                                    value="{{ !empty($vendor_info) ? $vendor_info->state : '' }}"
                                                                    class="form-control"
                                                                    name="{{ $language->code }}_state"
                                                                    placeholder="Enter State">
                                                                <p id="editErr_{{ $language->code }}_state"
                                                                    class="mt-1 mb-0 text-danger em"></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div
                                                                class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ __('Zip Code') }}</label>
                                                                <input type="text"
                                                                    value="{{ !empty($vendor_info) ? $vendor_info->zip_code : '' }}"
                                                                    class="form-control"
                                                                    name="{{ $language->code }}_zip_code"
                                                                    placeholder="Enter Zip Code">
                                                                <p id="editErr_{{ $language->code }}_zip_code"
                                                                    class="mt-1 mb-0 text-danger em">
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div
                                                                class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ __('Address') }}</label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Enter Address"
                                                                    name="{{ $language->code }}_address" id=""
                                                                    value="{{ !empty($vendor_info) ? $vendor_info->address : '' }}">

                                                                <p id="editErr_{{ $language->code }}_email"
                                                                    class="mt-1 mb-0 text-danger em"></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div
                                                                class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ __('Details') }}</label>
                                                                <textarea name="{{ $language->code }}_details"
                                                                    class="form-control" rows="5"
                                                                    placeholder="Enter Details">{{ !empty($vendor_info) ? $vendor_info->details : '' }}</textarea>
                                                                <p id="editErr_{{ $language->code }}_details"
                                                                    class="mt-1 mb-0 text-danger em"></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <div class="row">
                    <div class="col-12 text-center">
                        <button type="submit" id="PropertySubmit" class="btn Add-pproperty-button">
                            {{ __('Update') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="customerPhoneModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content login-box">

            <div class="modal-header">
                <h3 class="modal-title">Update Phone</h3>
                <button type="button" class="close" style="margin:0px;" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times"></i>
                </button>
            </div>

            <div class="form-group mt-3">
                <label for="in_phone">Phone</label>
                <input type="text" name="phone" id="in_phone" value="{{ $vendor->phone }}"
                    class="form-control new-form-designs">
                <p id="editErr_in_phone" class="mt-2 mb-0 text-danger em"></p>
            </div>

            <button id="sendOtp" type="button" class="btn btn-send-otp w-100 mt-4">
                {{ __('Send OTP') }}
            </button>
        </div>
    </div>
</div>

<div class="modal fade" id="otpVerificationModal" tabindex="-1" role="dialog"
    aria-labelledby="otpVerificationModalLabel" aria-hidden="true" style="height: fit-content;">
    <div class="modal-dialog modal-dialog-centered model-otp" role="document">
        <div class="modal-content model-otp">

            <div class="modal-header border-0 p-4" style="display:flex; flex-direction:column;">

                <div class="d-flex justify-content-between align-items-start w-100 mb-2">

                    <div>
                        <h4 class="modal-title m-0">{{ __('Verify your number') }}</h4>
                        <h4 class="m-0 mt-1" style="color:#000; font-weight:600;">
                            <span id="editFrontPhone">+91-7854875487</span>
                            <i class="fa fa-pencil" id="editPhoneNumber" style="cursor:pointer;"></i>
                        </h4>
                    </div>

                    <button type="button" class="close-btn-login" data-dismiss="modal" aria-label="Close"
                        style=" width:28px; background-color:#6c603c; position:none; height:28px; border-radius:50%; border:none;  align-items:center; justify-content:center;">
                        <i class="fa fa-times" style="color:#fff;  font-size:14px;"></i>
                    </button>
                </div>

                <h5 class="otp-input-wrapper" style="margin-bottom:10px; margin-top:10px; font-size:16px; font-weight:600;">Enter your 4 digit OTP</h5>

                <div class="d-flex otp-input-wrapper" style ="gap:10px;">
                    <input type="text" maxlength="1" class="otp-box-input form-control" />
                    <input type="text" maxlength="1" class="otp-box-input form-control" />
                    <input type="text" maxlength="1" class="otp-box-input form-control" />
                    <input type="text" maxlength="1" class="otp-box-input form-control" />
                </div>

                <h5 class="mt-2" style="font-size:16px; font-weight:600;">
                    Haven't received yet?
                    <span id="resendOtp" class="resend-link">Resend OTP</span>
                    <span id="otpTimer" class="timer-text"></span>
                </h5>

                <button id="verifyOtpBtn" type="button" class="btn verify-btn mt-5"
                    style="background-color:#6c603c; color:#fff; font-weight:;">
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
            url: '{{ route("vendor.phone.send-otp") }}', // Update this route
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
            url: '{{ route("vendor.phone.verify-otp") }}',
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
            url: '{{ route("vendor.phone.resend-otp") }}',
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