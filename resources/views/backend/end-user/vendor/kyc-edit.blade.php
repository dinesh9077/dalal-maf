@extends('backend.layout')

@section('content')
   <div class="row">
        <div class="col-md-12">
            <div class="box kyc-details">
                <div class="box-header with-border">
                    <h3 class="box-title">Vendor KYC Details</h3>
                </div>

                {{ csrf_field() }}

                <div class="row align-items-start my-3">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-8">
                        <div class="aadhar-card">
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Aadhar Card Number <span style="color:#9b0b0b;"> * </span></label>
                                    <input class="form-control prop_title text-uppercase" id="aadhar_card_number"
                                        placeholder="Enter Aadhar Card Number"
                                        value="<?php if ($vendor) {
                                            echo $vendor->aadhar_card_number;
                                        } else {
                                            echo old('aadhar_card_number');
                                        } ?>"oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                        disabled>
                                </div>
                            </div>
                            <div class="d-flex">
                                <div class="form-group mb-0">
                                    <label>Aadhar Card Front <span style="color:#9b0b0b;"> * </span></label>
                                    @if ($vendor->aadhar_front)
                                        <a class="upload-img"
                                            href="{{ asset('images/user_document/') }}/{{ $vendor->user_id }}/{{ $vendor->aadhar_front }}"
                                            target="_blank">
                                            @if (strpos($vendor->aadhar_front, 'pdf'))
                                                <img src="{{ asset('images/property/pdf_document.png') }}"
                                                    style="height: 50px; width: 50px;">
                                            @else
                                                <img id="preview_front_document_img"
                                                    src="{{ asset('images/user_document/') }}/{{ $vendor->user_id }}/{{ $vendor->aadhar_front }}"
                                                    class="card-img-top" alt="...">
                                            @endif
                                        </a>
                                    @else
                                        <img id="preview_front_document_img"
                                            src="{{ asset('images/placeholder.jpg') }}" class="card-img-top"
                                            alt="...">
                                    @endif
                                </div>
                                <div class="form-group mb-0">
                                    <label>Aadhar Card Back <span style="color:#9b0b0b;"> * </span></label>
                                    @if ($vendor->aadhar_back)
                                        <a class="upload-img"
                                            href="{{ asset('images/user_document/') }}/{{ $vendor->user_id }}/{{ $vendor->aadhar_back }}"
                                            target="_blank">
                                            @if (strpos($vendor->aadhar_back, 'pdf'))
                                                <img src="{{ asset('images/property/pdf_document.png') }}"
                                                    style="height: 50px; width: 50px;">
                                            @else
                                                <img id="preview_back_document_img"
                                                    src="{{ asset('images/user_document/') }}/{{ $vendor->user_id }}/{{ $vendor->aadhar_back }}"
                                                    class="card-img-top" alt="...">
                                            @endif
                                        </a>
                                    @else
                                        <img id="preview_back_document_img"
                                            src="{{ asset('images/property/no_image.png') }}" class="card-img-top"
                                            alt="...">
                                    @endif
                                </div>
                            </div>

                            <div class="status-approve">
                                <div class="btn-group">
                                    @if ($vendor->is_aadhar != 1)
                                        <button type="button" class="btn-approve" onclick="approveChange('document','1');">
                                            <span>
                                                <img src='{{ asset('images/confirm.svg') }}' alt="Approve">
                                            </span> Approve
                                        </button>
                                    @endif
                                    @if ($vendor->is_aadhar != 2)
                                    <button type="button" class="btn-reject" onclick="rejectChange('document','2');">
                                        <span>
                                            <img src='{{ asset('images/reject.svg') }}' alt="Reject">
                                        </span> Reject</button>
                                    </button>
                                    @endif
                                </div>


                                <div class="approve-title">
                                    @if ($vendor->is_aadhar == 0)
                                        <h4 id="card-group" class="btn-pending ml-4">&nbsp; Pending</h4>
                                    @elseif($vendor->is_aadhar == 1)
                                        <h4 id="card-group" class="btn-approved ml-4">&nbsp; Approved</h4>
                                    @elseif($vendor->is_aadhar == 2)
                                        <h4 id="card-group" class="btn-reject">&nbsp; Reject</h4>
                                    @elseif($vendor->is_aadhar == 3)
                                        <h4 id="card-group" class="btn-pending ml-4">&nbsp; Pending</h4>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                @if ($vendor->is_aadhar == '2')
                                    <div class="btn-reject">
                                        <div class="box-body">
                                            <p class="note-danger">Note: {{ $vendor->admin_document_note }}</p>
                                            <p class="font-16 text-right">-Team DalalMaf</p>
                                        </div>
                                    </div>
                                @endif
                                @if ($vendor->is_aadhar == '1' && !empty($vendor->admin_document_note))
                                    <div class="btn-approve">
                                        <div class="box-body">
                                            <p class="note-success">Note: {{ $vendor->admin_document_note }}</p>
                                            <p class="font-16 text-right">-Team DalalMaf</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4">
                        <div class="pan-card">
                            <div class="form-group mb-0">
                                <label>Pancard <span style="color:#9b0b0b;"> * </span></label>
                                @if ($vendor->pancard)
                                    <a class="upload-img"
                                        href="{{ asset('images/user_document/') }}/{{ $vendor->user_id }}/{{ $vendor->pancard }}"
                                        target="_blank">
                                        @if (strpos($vendor->pancard, 'pdf'))
                                            <img src="{{ asset('images/property/pdf_document.png') }}"
                                                style="height: 50px; width: 50px;">
                                        @else
                                            <img id="preview_pancard_img"
                                                src="{{ asset('images/user_document/') }}/{{ $vendor->user_id }}/{{ $vendor->pancard }}"
                                                class="card-img-top" alt="...">
                                        @endif
                                    </a>
                                @else
                                    <img id="preview_pancard_img" src="{{ asset('images/property/no_image.png') }}"
                                        class="card-img-top" alt="...">
                                @endif
                            </div>

                            <div class="status-approve">
                                <div class="btn-group">
                                    @if ($vendor->is_pancard != 1)
                                        <button type="button" class="btn-approve"
                                            onclick="approveChange('pancard','1');">
                                            <span>
                                                <img src='{{ asset('images/confirm.svg') }}'
                                                    alt="Approve">
                                            </span> Approve
                                        </button>
                                    @endif
                                    @if ($vendor->is_pancard != 2)
                                      <button type="button" class="btn-reject" onclick="rejectChange('pancard','2');">
                                          <span>
                                              <img src='{{ asset('images/reject.svg') }}' alt="Reject">
                                          </span> Reject
                                      </button>
                                    @endif
                                </div>
                                <div class="approve-title">
                                    @if ($vendor->is_pancard == 0)
                                        <h4 id="card-group" class="btn-pending ml-4">&nbsp; Pending</h4>
                                    @elseif($vendor->is_pancard == 1)
                                        <h4 id="card-group" class="btn-approved ml-4">&nbsp; Approved</h4>
                                    @elseif($vendor->is_pancard == 2)
                                        <h4 id="card-group" class="btn-reject">&nbsp; Reject</h4>
                                    @elseif($vendor->is_pancard == 3)
                                        <h4 id="card-group" class="btn-pending ml-4">&nbsp; Pending</h4>
                                    @endif
                                </div>
                            </div>
                            @if ($vendor->is_pancard == '2')
                                <div class="form-group">
                                    <div class="btn-reject">
                                        <div class="box-body">
                                            <p class="note-danger">Note: {{ $vendor->admin_pancard_note }}</p>
                                            <p class="font-16 text-right">-Team DalalMaf</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($vendor->is_pancard == '1' && !empty($vendor->admin_pancard_note))
                                <div class="btn-approve">
                                    <div class="box-body">
                                        <p class="note-success">Note: {{ $vendor->admin_pancard_note }}</p>
                                        <p class="font-16 text-right">-Team DalalMaf</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row align-items-start">

                    <div class="col-12 col-sm-12 col-md-12 col-lg-8">
                        <div class="gst-title h-100">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label>Bank Name </label>
                                        <input type="text" class="form-control-custom" id="bank_name"
                                            name="bank_name" placeholder="Enter Bank Name"
                                            value="<?php if ($vendor) {
                                                echo $vendor->bank_name;
                                            } ?>">
                                        <span class="text-danger">{{ $errors->first('bank_name') }}</span>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label>Branch Name </label>
                                        <input type="text" class="form-control-custom" id="branch_name"
                                            name="branch_name" placeholder="Enter Branch Name"
                                            value="<?php if ($vendor) {
                                                echo $vendor->branch_name;
                                            } ?>">
                                        <span class="text-danger">{{ $errors->first('branch_name') }}</span>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label>Account Number</label>
                                        <input type="text" class="form-control-custom" id="account_number"
                                            name="account_number" placeholder="Enter Account Number"
                                            value="<?php if ($vendor) {
                                                echo $vendor->account_number;
                                            } ?>">
                                        <span class="text-danger">{{ $errors->first('account_number') }}</span>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label>IFSC Code </label>
                                        <input type="text" class="form-control-custom" id="ifsc_code"
                                            name="ifsc_code" placeholder="Enter IFSC Code" value="<?php if ($vendor) {
                                                echo $vendor->ifsc_code;
                                            } ?>">
                                        <span class="text-danger">{{ $errors->first('ifsc_code') }}</span>
                                    </div>
                                </div>

                                @if ($vendor->is_passbook == '2')
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                        <div class="btn-reject">
                                            <div class="box-body">
                                                <p class="note-danger">Note: {{ $vendor->admin_bank_note }}</p>
                                                <p class="font-16 text-right">-Team DalalMaf</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($vendor->is_passbook == '1')
                                    <div class="btn-approve">
                                        <div class="box-body">
                                            <p class="note-success">Note: {{ $vendor->admin_bank_note }}</p>
                                            <p class="font-16 text-right">-Team DalalMaf</p>
                                        </div>
                                    </div>
                                @endif

                            </div>

                        </div>
                    </div>

                    @if(!empty($vendor->gst_number))
                        <div class="col-12 col-sm-12 col-md-12 col-lg-4">
                            <div class="gst-title">
                                <div class="form-group">
                                    <label>GST No. (Optional) <span style="color:#9b0b0b;"> * </span></label>
                                    <input type="text" class="form-control-custom" id="gst_number" name="gst_number"
                                        placeholder="Enter GST Number" value="<?php if ($vendor) {
                                            echo $vendor->gst_number;
                                        } ?>" disabled>
                                    <span class="text-danger">{{ $errors->first('gst_number') }}</span>
                                </div>
                                <div class="form-group mb-0">
                                    @if ($vendor->is_gst == '2')
                                        <div class="btn-reject">
                                            <div class="box-body">
                                                <p class="note-danger">Note: {{ $vendor->admin_gst_note }}</p>
                                                <p class="font-16 text-right">-Team DalalMaf</p>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($vendor->is_gst == '1' && !empty($vendor->admin_gst_note))
                                        <div class="btn-approve">
                                            <div class="box-body">
                                                <p class="note-success">Note: {{ $vendor->admin_gst_note }}</p>
                                                <p class="font-16 text-right">-Team DalalMaf</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="status-approve mb-lg-3">
                                    <div class="btn-group">
                                        @if ($vendor->is_gst != 1)
                                            <button type="button" class="btn-approve" onclick="approveChange('gst','1');">
                                                <span>
                                                    <img src='{{ asset('images/confirm.svg') }}'
                                                        alt="Approve">
                                                </span> Approve</button></button>
                                        @endif
                                        @if ($vendor->is_gst != 2)
                                        <button type="button" class="btn-reject" onclick="rejectChange('gst','2');">
                                            <span>
                                                <img src='{{ asset('images/reject.svg') }}' alt="Reject">
                                            </span> Reject
                                        </button>
                                        @endif
                                    </div>
                                    <div class="approve-title">
                                        @if ($vendor->is_gst == 0)
                                            <h4 id="card-group" class="btn-pending ml-4">&nbsp; Pending</h4>
                                        @elseif($vendor->is_gst == 1)
                                            <h4 id="card-group" class="btn-approved ml-4">&nbsp; Approved</h4>
                                        @elseif($vendor->is_gst == 2)
                                            <h4 id="card-group" class="btn-reject">&nbsp; Rejected</h4>
                                        @elseif($vendor->is_gst == 3)
                                            <h4 id="card-group" class="btn-pending ml-4">&nbsp; Pending</h4>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="col-12 mt-5">
                        <form class="p-20" action="{{ route('admin.vendor_management.status_change') }}" method="POST">
                            {{ csrf_field() }}
                            <div class="row my-3 justify-content-center">
                                <input type="hidden" name="id" value="<?php if (!empty($vendor)) {
                                    echo $vendor->id;
                                } ?>">
                                <input type="hidden" name="final_submit" value="final_submit">
                                <button type="submit" class="btn btn-primary">Submit All Details</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade host-payout" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 500px">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approveModalLabel">Approve Document</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <form class="p-20" action="{{ route('admin.vendor_management.status_change') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <input type="hidden" name="statusname" id="statusname">
                                    <input type="hidden" name="code" id="code">
                                    <input type="hidden" name="id" value="<?php if(!empty($vendor)){ echo $vendor->id; }?>">
                                    <div class="form-group">
                                        <label>Note </label>
                                        <textarea type="text" class="form-control" id="approve_note" name="note"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="approve_document_note_button_show_hide">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade host-payout" id="rejectModal" tabindex="-1" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 500px">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Reject Document</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <form class="p-20" action="{{ route('admin.vendor_management.status_change') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <input type="hidden" name="statusname" id="statusname_reject">
                                <input type="hidden" name="code" id="code_reject">
                                <input type="hidden" name="id" value="<?php if(!empty($vendor)){ echo $vendor->id; }?>">
                                <div class="form-group">
                                    <label>Note <span>*</span></label>
                                    <textarea type="text" class="form-control" id="reject_note" onkeyup="rejectDocumnetNote()" name="note" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="reject_document_note_button_show_hide" style="display:none;">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
          function approveChange(status,code)
        {
            $('#statusname').val(status);
            $('#code').val(code);
            $('#approveModal').modal('show');
        }

        function rejectChange(status,code)
        {
            $('#statusname_reject').val(status);
            $('#code_reject').val(code);
            $('#rejectModal').modal('show');
        }

        var modal = document.getElementById("myModal");

        // Get the image and insert it inside the modal - use its "alt" text as a caption
        var preview_pancard_img = document.getElementById("preview_pancard_img");
        var front_document_img = document.getElementById("front_document_img");
        var back_document_img = document.getElementById("back_document_img");
        var bank_document_img = document.getElementById("bank_document_img");
        var modalImg = document.getElementById("img01");
        var captionText = document.getElementById("caption");

        preview_pancard_img.onclick = function(){
            modal.style.display = "block";
            modalImg.src = this.src;
            captionText.innerHTML = this.alt;
        }

        front_document_img.onclick = function(){
            modal.style.display = "block";
            modalImg.src = this.src;
            captionText.innerHTML = this.alt;
        }

        back_document_img.onclick = function(){
            modal.style.display = "block";
            modalImg.src = this.src;
            captionText.innerHTML = this.alt;
        }

        bank_document_img.onclick = function(){
            modal.style.display = "block";
            modalImg.src = this.src;
            captionText.innerHTML = this.alt;
        }

        function modelclose()
        {
            modal.style.display = "none";
        }

        function rejectDocumnetNote()
        {
            var reject_note = $("#reject_note").val();

            if(reject_note)
            {
                $("#reject_document_note_button_show_hide").show();
            }
            else
            {
                $("#reject_document_note_button_show_hide").hide();
            }
        }
    </script>
    @endsection
