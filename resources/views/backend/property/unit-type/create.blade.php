<div class="add-modal-main modal fade" id="createModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true" style="z-index:99999">
    <div class="modal-dialog modal-dialog-centered  ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Create New Unit Type</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="ajaxForm" action="{{ route('admin.property_specification.store_unit_type') }}" method="post"
                enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="modal-form">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Unit Type *</label>
                                    <input class="form-control" type="text" name="unit_name" id="unit_name" required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>{{ __('Status') }} *</label>
                                    <select name="status" class="form-control" id="status">
                                        <option value="1">{{ __('Active') }}</option>
                                        <option value="0">{{ __('Inactive') }}</option>
                                    </select>
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
