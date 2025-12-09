<div class="add-modal-main modal fade" id="createModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true" style="z-index:99999">
    <div class="modal-dialog modal-dialog-centered  ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Create New Customer</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="ajaxForm" action="{{ route('admin.customer_management.store') }}" method="post"
                enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="modal-form">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Name *</label>
                                    <input class="form-control" type="text" name="name" id="name" required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Email *</label>
                                    <input class="form-control" type="email" name="email" id="email" required>
                                </div>
                            </div> 
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Phone *</label>
                                    <input class="form-control" type="text" name="phone_number" id="phone_number" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                        {{ __('Close') }}
                    </button>
                    <button id="submitBtn" type="button" class="btn btn-primary btn-sm">
                        {{ __('Save') }}
                    </button>
                </div>
            </form>
        </div>
    </div> 
</div>
