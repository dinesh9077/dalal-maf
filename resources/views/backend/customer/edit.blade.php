<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content"> 
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Edit Customer') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="ajaxEditForm" class="modal-form"
                    action="{{ route('admin.customer_management.update') }}" method="post">
                    @csrf
                    <input type="hidden" id="in_id" name="id">  
                    <div class="form-group">
                        <label for="">{{ __('Name') . '*' }}</label>
                        <input class="form-control" type="text" name="name" id="in_name" required>
                    </div>   
                    <div class="form-group">
                        <label for="">{{ __('Email') . '*' }}</label>
                        <input class="form-control" type="email" name="email" id="in_email" required>
                    </div>  
                    <div class="form-group">
                        <label for="">{{ __('Phone Number') . '*' }}</label>
                        <input class="form-control" type="text" name="phone_number" id="in_phone_number" required>
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
