<style>
	#deleteConfirmModal input {
	border-radius: 10px;
	border: 1px solid #D8E0F0;
	background: #FFF;
	box-shadow: 0px 1px 2px 0px rgba(184, 200, 224, 0.22);
	font-size: 14px;
	color: #7D8592;
	width: 100%;
}
</style>
<!-- Delete Modal Component -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
			<form id="deleteFormModal" method="post" action="">
				<div class="modal-body">
					<p id="deleteMessage">Are you sure you want to delete this item?</p>
					<div class="raw">
						<label class="label-main">To Confirm deletion, type "DELETE". Deleting instance cannot be undone.</label>
						<input type="text" class="modal-input" id="confirm_delete_input" placeholder="DELETE" autocomplete="off" >
						<span class="text-danger" id="delete_error_msg"></span>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-danger" >Delete</button>
				</div>
			</form>
        </div>
    </div>
</div>
