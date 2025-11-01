@extends('vendors.layout')

@section('content')
  <section class="bg-white">
    <div class="container">
        @if($data['get_vendor_kyc_detail'])
            @if($data['get_vendor_kyc_detail']->user_id == Auth::user()->id  && $data['get_vendor_kyc_detail']->is_aadhar == '' && $data['get_vendor_kyc_detail']->is_pancard == '' && $data['get_vendor_kyc_detail']->is_passbook == '')
            <div class="row">
                <div class="col-md-12  alert alert-success alert-dismissable top-message-text opacity-1">
                    Your KYC has been submitted successfully. Please wait while the admin reviews and approves your documents.
                </div>
            </div>
            @endif
        @endif
        <div class="tab-content">
            <div>
                <form method="post" action="{{url('vendor/become-a-vendor')}}" id="vendorKycForm"  enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="card-body card-box mt-5">
                        <div class="row">
                            <div class="col-lg-12">
                                <b>
                                    <p class="med-title text-left">Verify Your Identity</p>
                                </b>
                            </div>
                        </div>
                        <?php
                        $aadhar_number = '';
                        $aadhar_disable = '';
                        $pancard_disable = '';
                        $bank_disable = '';
                        $gst_disable = '';
                        $bank_detail_disable = '';
                        $aadhar_card_required = 'required';
                        if ($data['get_vendor_kyc_detail']) {
                            if ($data['get_vendor_kyc_detail']->is_aadhar == 1 || $data['get_vendor_kyc_detail']->is_aadhar == null) {
                                $aadhar_disable = 'disabled';
                                $aadhar_number = 'readonly';
                            }

                            if ($data['get_vendor_kyc_detail']->is_pancard == 1 || $data['get_vendor_kyc_detail']->is_pancard == null) {
                                $pancard_disable = 'disabled';
                            }

                            if ($data['get_vendor_kyc_detail']->is_gst == 1 || $data['get_vendor_kyc_detail']->is_gst == null) {
                                $gst_disable = 'readonly';
                            }

                            if ($data['get_vendor_kyc_detail']->bank_name !== null) {
                                $bank_disable = 'disabled';
                                $bank_detail_disable = 'readonly';
                            }

                            if ($data['get_vendor_kyc_detail']->aadhar_card_number) {
                                $aadhar_card_required = '';
                            } else {
                                $aadhar_card_required = 'required';
                            }
                        }

                        ?>

                        <div class="row mt-3">
                            <div class="col-lg-6 form-group">
                                <div class="col-lg-12">
                                    <p class="small-title">Aadhaar Card Number <span style="color:#9b0b0b;"> * </span></p>
                                </div>
                                <div class="col-lg-12 form-group">
                                    <input class="form-control prop_title " name="aadhar_card_number"
                                        id="aadhar_card_number" placeholder="Enter Aadhaar Card Number"
                                        value="<?php if ($data['get_vendor_kyc_detail']) {
                                            echo $data['get_vendor_kyc_detail']->aadhar_card_number;
                                        } else {
                                            echo old('aadhar_card_number');
                                        } ?>"oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                        {{ $aadhar_number }} {{ $aadhar_card_required }}>
                                    @if ($data['get_vendor_kyc_detail'])
                                        @if ($data['get_vendor_kyc_detail']->is_aadhar == '2')
                                            <div class="card alert-danger text-center my-2">
                                                <div class="card-body py-2">
                                                    <p class="text-black text-left mb-2" style="font-size: 14px">Note:
                                                        {{ $data['get_vendor_kyc_detail']->admin_gst_note }}</p>
                                                    <p class="text-black text-right m-0" style="font-size: 14px">-Team
                                                        DalalMaf</p>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <p class="small-title">Upload Your Aadhaar Card</p>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label>Aadhaar Card Front <span style="color:#9b0b0b;"> * </span></label>

                                    <input type="file" class="form-control-input w-100" id="front_document_img"
                                        name="aadhar_front" {{ $aadhar_disable }} <?php if (empty($data['get_vendor_kyc_detail']->aadhar_front)) {
                                            echo 'required';
                                        } ?>>
                                    <span class="text-danger">{{ $errors->first('aadhar_front') }}</span>
                                    @if ($data['get_vendor_kyc_detail'])
                                        <img id="preview_front_document_img"
                                            src="{{ asset('images/user_document/') }}/{{ Auth::user()->id }}/{{ $data['get_vendor_kyc_detail']->aadhar_front }}"
                                            class="card-img-top mt-4" alt="...">
                                    @else
                                        <img id="preview_front_document_img"
                                            src="{{ asset('images/placeholder.jpg') }}"
                                            class="card-img-top mt-4" alt="...">
                                    @endif
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label>Aadhaar Card Back <span style="color:#9b0b0b;"> * </span></label>
                                    <input type="file" class="form-control-input w-100" id="back_document_img"
                                        name="aadhar_back" {{ $aadhar_disable }} <?php if (empty($data['get_vendor_kyc_detail']->aadhar_back)) {
                                            echo 'required';
                                        } ?>>
                                    <span class="text-danger">{{ $errors->first('aadhar_back') }}</span>
                                    @if ($data['get_vendor_kyc_detail'])
                                        <img id="preview_back_document_img"
                                            src="{{ asset('images/user_document/') }}/{{ Auth::user()->id }}/{{ $data['get_vendor_kyc_detail']->aadhar_back }}"
                                            class="card-img-top mt-4" alt="...">
                                    @else
                                        <img id="preview_back_document_img"
                                            src="{{ asset('images/placeholder.jpg') }}"
                                            class="card-img-top mt-4" alt="...">
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-12 form-group">
                                @if ($data['get_vendor_kyc_detail'])
                                    @if ($data['get_vendor_kyc_detail']->is_aadhar == '2')
                                        <div class="card alert-danger text-center my-2">
                                            <div class="card-body py-2">
                                                <p class="text-black text-left mb-2" style="font-size: 14px">Note:
                                                    {{ $data['get_vendor_kyc_detail']->admin_document_note }}</p>
                                                <p class="text-black text-right m-0" style="font-size: 14px">-Team
                                                    DalalMaf</p>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <p class="small-title"> Pancard, GST & Bank Details</p>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label>Pancard <span style="color:#9b0b0b;"> * </span></label>
                                    <input type="file" class="form-control-input d-block w-100" id="pancard_img"
                                        name="pancard" {{ $pancard_disable }} <?php if (empty($data['get_vendor_kyc_detail']->pancard)) {
                                            echo 'required';
                                        } ?>>
                                    <span class="text-danger">{{ $errors->first('pancard') }}</span>
                                    @if ($data['get_vendor_kyc_detail'])
                                        <img id="preview_pancard_img"
                                            src="{{ asset('images/user_document/') }}/{{ Auth::user()->id }}/{{ $data['get_vendor_kyc_detail']->pancard }}"
                                            class="card-img-top mt-4" alt="...">
                                    @else
                                        <img id="preview_pancard_img"
                                            src="{{ asset('images/placeholder.jpg') }}"
                                            class="card-img-top mt-4" alt="...">
                                    @endif

                                    @if ($data['get_vendor_kyc_detail'])
                                        @if ($data['get_vendor_kyc_detail']->is_pancard == '2')
                                            <div class="card alert-danger text-center my-2">
                                                <div class="card-body py-2">
                                                    <p class="text-black text-left mb-2" style="font-size: 14px">Note:
                                                        {{ $data['get_vendor_kyc_detail']->admin_pancard_note }}</p>
                                                    <p class="text-black text-right m-0" style="font-size: 14px">-Team
                                                        DalalMaf</p>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 d-none">
                                <div class="mb-3">
                                    <label>Bank Passbook (First Page) <span style="color:#9b0b0b;"> * </span></label>
                                    <!--<input type="file" class="form-control" id="bank_document_img" name="passbook" {{ $bank_disable }} required>-->
                                    <span class="text-danger">{{ $errors->first('passbook') }}</span>
                                    @if ($data['get_vendor_kyc_detail'])
                                        <img id="preview_bank_document_img"
                                            src="{{ asset('images/user_document/') }}/{{ Auth::user()->id }}/{{ $data['get_vendor_kyc_detail']->passbook }}"
                                            class="card-img-top mt-4" alt="...">
                                    @else
                                        <img id="preview_bank_document_img"
                                            src="{{ asset('images/placeholder.jpg') }}"
                                            class="card-img-top mt-4" alt="...">
                                    @endif
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row mt-3" >
                                    <div class="col-lg-6 form-group">
                                        <div class="col-lg-12">
                                            <p class="small-title">GST</p>
                                        </div>
                                        <div class="col-lg-12 form-group">
                                            <div class="mb-3">

                                                <input class="form-control prop_title text-uppercase d-block" name="gst_number"
                                                    id="gst_number"  value="<?php if ($data['get_vendor_kyc_detail']) {
                                                        echo $data['get_vendor_kyc_detail']->gst_number;
                                                    } else {
                                                        echo old('gst_number');
                                                    } ?>" placeholder="GJACPD012345678"
                                                    {{ $gst_disable }}>
                                                @if ($data['get_vendor_kyc_detail'])
                                                    @if ($data['get_vendor_kyc_detail']->is_gst == '2')
                                                        <div class="card alert-danger text-center my-2">
                                                            <div class="card-body py-2">
                                                                <p class="text-black text-left mb-2" style="font-size: 14px">
                                                                    Note: {{ $data['get_vendor_kyc_detail']->admin_gst_note }}</p>
                                                                <p class="text-black text-right m-0" style="font-size: 14px">
                                                                    -Team DalalMaf</p>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="row mt-3">
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                        <div class="mb-3">
                                            <label>Bank Name <span style="color:#9b0b0b;">  </span></label>
                                            <input type="text" class="form-control prop_title d-block w-100"
                                                id="bank_name" name="bank_name" placeholder="Enter Bank Name"
                                                value="<?php if ($data['get_vendor_kyc_detail']) {
                                                    echo $data['get_vendor_kyc_detail']->bank_name;
                                                } else {
                                                    echo old('bank_name');
                                                } ?>" {{ $bank_detail_disable }}>
                                            <span class="text-danger">{{ $errors->first('bank_name') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                        <div class="mb-3">
                                            <label>Branch Name <span style="color:#9b0b0b;"> </span></label>
                                            <input type="text" class="form-control prop_title d-block w-100"
                                                id="branch_name" name="branch_name" placeholder="Enter Branch Name"
                                                value="<?php if ($data['get_vendor_kyc_detail']) {
                                                    echo $data['get_vendor_kyc_detail']->branch_name;
                                                } else {
                                                    echo old('branch_name');
                                                } ?>" {{ $bank_detail_disable }}>
                                            <span class="text-danger">{{ $errors->first('branch_name') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                        <div class="mb-3">
                                            <label>Account Number <span style="color:#9b0b0b;"> </span></label>
                                            <input type="text" class="form-control prop_title d-block w-100"
                                                id="account_number" name="account_number"
                                                placeholder="Enter Account Number" value="<?php if ($data['get_vendor_kyc_detail']) {
                                                    echo $data['get_vendor_kyc_detail']->account_number;
                                                } else {
                                                    echo old('account_number');
                                                } ?>"
                                                {{ $bank_detail_disable }}>
                                            <span class="text-danger">{{ $errors->first('account_number') }}</span>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                        <div class="mb-3">
                                            <label>IFSC Code <span style="color:#9b0b0b;"> </span></label>
                                            <input type="text" maxlength = "11"
                                                class="form-control prop_title d-block w-100" id="ifsc_code"
                                                name="ifsc_code" placeholder="Enter IFSC Code"
                                                value="<?php if ($data['get_vendor_kyc_detail']) {
                                                    echo $data['get_vendor_kyc_detail']->ifsc_code;
                                                } else {
                                                    echo old('ifsc_code');
                                                } ?>" {{ $bank_detail_disable }}>
                                            <span class="text-danger">{{ $errors->first('ifsc_code') }}</span>
                                            <span class="text-danger mt-2 d-none" id="ifsc_validate"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row d-none" id="progress">
                            <div class="col-lg-12 form-group">
                                <div class="progress">
                                    <div class="bar"></div>
                                    <div class="percent">0%</div>
                                </div>
                                <p class="text-black text-center my-2">Please wait for a while, your form is being
                                    submitted</p>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-lg-6 col-6 text-right">
                                <button class="btn btn-primary" id="submitKYC" type="submit">Submit</button>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</section>
@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

    var count=0;
    $('#ifsc_code').keyup(function(){
        count++
        if(count>11){
            $('#ifsc_validate').text('Maximum 11 characters')
            $('#ifsc_validate').addClass('d-block')
        }
    })

    if($('#ifsc_code').val().length < 11){
		$('#ifsc_code').keyup(function(){
            count++
            if(count>11){
                $('#ifsc_validate').text('Maximum 11 characters')
                $('#ifsc_validate').addClass('d-block')
			}
		})
	}

    $('INPUT[type="file"]').change(function () {
		// var ext = this.value.match(/\.(.+)$/)[1];
		var ext = this.value.split('.').pop();
		switch (ext) {
			case 'jpg':
			case 'jpeg':
			case 'png':
			case 'gif':
			$('#uploadButton').attr('disabled', false);
			break;
			default:
			alert('This is not an allowed file type.');
			this.value = '';
		}
	});

    $(document).ready(function (e)
    {
        $('#pancard_img').change(function () {
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#preview_pancard_img').attr('src', e.target.result);
                $("#has_pan_img").val('');
			}
            reader.readAsDataURL(this.files[0]);
		});
        $('#front_document_img').change(function () {
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#preview_front_document_img').attr('src', e.target.result);
                $("#has_pan_img").val('');
			}
            reader.readAsDataURL(this.files[0]);
		});
        $('#back_document_img').change(function () {
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#preview_back_document_img').attr('src', e.target.result);
                $("#has_pan_img").val('');
			}
            reader.readAsDataURL(this.files[0]);
		});
        $('#bank_document_img').change(function () {
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#preview_bank_document_img').attr('src', e.target.result);
                $("#has_pan_img").val('');
			}
            reader.readAsDataURL(this.files[0]);
		});
	});

    $(document).ready(function () {
        $('#vendorKycForm').on('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);
            $('#submitKYC').prop('disabled', true);

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {

                    if (response.status == 1) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#a30100'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Something went wrong.',
                            padding: '2em',
                            confirmButtonColor: '#a30100'
                        });
                    }
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Please fill out all required fields correctly.',
                        padding: '2em',
                        confirmButtonColor: '#a30100'
                    });
                    $('#submitKYC').prop('disabled', false);
                }
            });
        });
    });

</script>
@endsection
