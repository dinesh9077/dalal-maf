<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Areas') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="ajaxForm" class="modal-form create"
                    action="{{ route('admin.property_specification.store_area') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf

                    @if ($settings->property_country_status == 1)
                        <div class="form-group">
                            <label for="">{{ __('Country') . '*' }}</label>
                            <select name="country" class="form-control" id="country">
                                <option selected disabled>{{ __('Select a Country') }}</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->countryContent?->name }}</option>
                                @endforeach
                            </select>
                            <p id="err_country" class="mt-2 mb-0 text-danger em"></p>
                        </div>
                    @endif

                    @if ($settings->property_state_status == 1)
                        <div class="form-group" id="city">
                            <label for="">{{ __('City') . '*' }}</label>
                            <select name="city_id" class="form-control js-example-basic-single3">
                                <option selected disabled value="">{{ __('Select a City') }}</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->cityContent?->name }} </option>
                                @endforeach

                            </select>
                            <p id="err_state" class="mt-2 mb-0 text-danger em"></p>
                        </div>
                    @endif
                    <div class="form-group">
                        <label for="">{{ __('Name') . '*' }}</label>
                        <input type="text" class="form-control" name="name"
                            placeholder="Enter Area name">
                        <p id="err_name" class="mt-2 mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label for="">{{ __('Status') . '*' }}</label>
                        <select name="status" class="form-control">
                            <option selected disabled>{{ __('Select Area Status') }}</option>
                            <option value="1">{{ __('Active') }}</option>
                            <option value="0">{{ __('Deactive') }}</option>
                        </select>
                        <p id="err_status" class="mt-2 mb-0 text-danger em"></p>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                    {{ __('Close') }}
                </button>
                <button id="submitBtn" type="button" class="btn btn-primary btn-sm">
                    {{ __('Save') }}
                </button>
            </div>
        </div>
    </div>
</div>
