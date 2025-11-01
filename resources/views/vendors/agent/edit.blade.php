<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Edit Staff') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="ajaxEditForm" class="modal-form" action="{{ route('vendor.agent_management.update_agent') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="in_id" name="id">

                    <div class="form-group">
                        <label for="">{{ __('Image') . '*' }}</label>
                        <br>
                        <div class="thumb-preview">
                            <img src="" alt="admin image" class="in_image uploaded-img">
                        </div>

                        <div class="mt-3">
                            <div role="button" class="btn btn-primary btn-sm upload-btn">
                                {{ __('Choose Image') }}
                                <input type="file" class="img-input" name="image">
                            </div>
                        </div>
                        <p class="mt-2 mb-0 text-danger" id="editErr_image"></p>
                    </div>


                    <div class="row no-gutters">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">{{ __('Username') . '*' }}</label>
                                <input type="text" id="in_username" class="form-control" name="username"
                                    placeholder="Enter Username">
                                <p id="editErr_username" class="mt-2 mb-0 text-danger em"></p>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">{{ __('Email') . '*' }}</label>
                                <input type="email" id="in_email" class="form-control" name="email"
                                    placeholder="Enter Email">
                                <p id="editErr_email" class="mt-2 mb-0 text-danger em"></p>
                            </div>
                        </div>
						<!--<div class="col-lg-12">
                            <div class="form-group">
                                <label for="">{{ __('Phone') . '*' }}</label>
                                <input type="text" class="form-control" name="phone" placeholder="Enter Phone">
                                <p id="err_phone" class="mt-2 mb-0 text-danger em"></p>
                            </div>
                        </div>-->
                    </div>

                    <div class="row no-gutters">
                      <div class="col-lg-12">
                          <div class="form-group">
                              <label for="">{{ __('Permissions')  }}</label>
                              <div class="selectgroup selectgroup-pills">

                                  <label class="selectgroup-item">
                                      <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                          value="property_management"
                                          {{ in_array('property_management', $agent->permissions ?? []) ? 'checked' : '' }}>
                                      <span class="selectgroup-button">{{ __('Property Management') }}</span>
                                  </label>

                                  <label class="selectgroup-item">
                                      <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                          value="property_inventory"
                                          {{ in_array('property_inventory', $agent->permissions ?? []) ? 'checked' : '' }}>
                                      <span class="selectgroup-button">{{ __('Property Inventory') }}</span>
                                  </label>

                                  <label class="selectgroup-item">
                                      <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                          value="accounting"
                                          {{ in_array('accounting', $agent->permissions ?? []) ? 'checked' : '' }}>
                                      <span class="selectgroup-button">{{ __('Accounting') }}</span>
                                  </label>

                                  <label class="selectgroup-item">
                                      <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                          value="project"
                                          {{ in_array('project', $agent->permissions ?? []) ? 'checked' : '' }}>
                                      <span class="selectgroup-button">{{ __('Project') }}</span>
                                  </label>

                              </div>
                          </div>
                      </div>
                    </div>

                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn save-close-btn" data-dismiss="modal">
                    {{ __('Close') }}
                </button>
                <button id="updateBtn" type="button" class="btn save-close-btn">
                    {{ __('Update') }}
                </button>
            </div>
        </div>
    </div>
</div>
