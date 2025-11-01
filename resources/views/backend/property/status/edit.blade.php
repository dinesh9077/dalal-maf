<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Edit Status') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="ajaxEditForm" class="modal-form"
                    action="{{ route('admin.inquiry.update_status') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="in_id" name="id">

                     <div class="form-group">
                        <label for="">{{ __('Inquiry Status') . '*' }}</label>
                        <input type="text" class="form-control" name="name" id="in_inquiry_status"
                            placeholder="Enter Inquiry Status">
                        <p id="err_name" class="mt-2 mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label for="">{{ __('Status') . '*' }}</label>
                        <select name="status" class="form-control" id="status">
                            <option selected disabled>{{ __('Select Status') }}</option>
                            <option value="active">{{ __('Active') }}</option>
                            <option value="inactive">{{ __('Deactive') }}</option>
                        </select>
                        <p id="err_status" class="mt-2 mb-0 text-danger em"></p>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                    {{ __('Close') }}
                </button>
                <button id="updateBtn" type="button" class="btn btn-primary btn-sm">
                    {{ __('Update') }}
                </button>
            </div>
        </div>
    </div>
</div>
