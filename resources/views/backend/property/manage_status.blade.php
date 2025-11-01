@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl_style')

@section('content')
<style>
    .card-box p {
        color: #848484 !important;
        font-size: 13px;
    }

    .graf_head-right {
        display: flex;
        gap: 30px;
        row-gap: 30px;
        flex-wrap: wrap;
        row-gap: 20px;
        justify-content: end;
    }

      @media(max-width : 767px){
        .graf_head-right {

        justify-content: center;
    }
    }

    .deta_flor-main input[type="checkbox"][id^="myCheckbox"] {
        display: none;
    }

    .deta_flor-main label {
        display: block;
        position: relative;
        cursor: pointer;
        width: fit-content;
        margin: 0 auto;
    }

    .deta_flor-main label:before {
        color: white;
        content: " ";
        display: block;
        border-radius: 50%;
        position: absolute;
        top: -6px;
        left: -6px;
        width: 18px;
        height: 18px;
        text-align: center;
        line-height: 18px;
        transition-duration: 0.4s;
        transform: scale(0);
        font-size: 12px;
    }

    .deta_flor-main label img {
        transition-duration: 0.2s;
        transform-origin: 50% 50%;
    }

    :checked+label {
        border-color: #ddd;
    }

    :checked+label:before {
        content: "✓";
        background-color: var(--main-color);
        transform: scale(1);
    }

    .graf-box1 {
        background: #82CD47;
        width: 30px;
        height: 30px;
        padding: 6px;
        border-radius: 3px;
        display: inline-block;
    }

    .graf-box2 {
        background: #FE0000;
        width: 30px;
        height: 30px;
        padding: 6px;
        border-radius: 3px;
        display: inline-block;
    }

    .graf-box3 {
        background: #9BBEC8;
        width: 30px;
        height: 30px;
        padding: 6px;
        border-radius: 3px;
        display: inline-block;
    }


    .graf-box1-new {
        background: #82CD47;
        width: 20px;
        height: 20px;

        display: inline-block;
    }

    .graf-box2-new {
        background: #FE0000;
        width: 20px;
        height: 20px;

        display: inline-block;
    }

    .graf-box3-new {
        background: #9BBEC8;
        width: 20px;
        height: 20px;

        display: inline-block;
    }


    .graf_right-card {
        display: flex;
        gap: 5px;
        align-items: center;
    }

    .deta_flor-main {
        display: flex;
        gap: 20px;
        justify-content: center;
        text-align: center;
        margin: 10px 0px;
    }

    .color-title {
        font-size: 14px;
        font-weight: bold;
        margin-top: 10px;
    }

    .new-manage-box {
        background-color: white;
        border-radius: 18px;
        box-shadow: 1px 1px 14px 0px rgba(18, 38, 63, 0.26);
        padding: 20px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #000000;
        border: 1px solid #000000;
        color: white;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: white;

    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        background-color: black;
        color: white;

    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__display {
        font-size: 12px;
    }

    table td,
    .table th {
        font-size: 14px;
        border-top-width: 0px;
        border: 1px solid;
        border-color: #ebedf2 !important;
        padding: 0 25px !important;
        height: 60px;
        vertical-align: middle !important;
    }

    table thead {
        background-color: black;
        color: white;
    }

    table thead tr {
        text-align: center;
    }

    table tbody tr:hover {
        background-color: white !important;
    }

    .badge {
            box-shadow: 1px 1px 4px 0px rgba(9, 29, 61, 0.15);
    }
</style>
<div class="page-content new-manage-box">
    <div class="import-row-main">
        <div class="inner-table-main">
            <div class="add_graf-main">
                <div class="pro-live-main">
                    <div class="filter-deta">
                        <div class="graf_head">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-xl-4 col-md-6">
                                            <div class="graf_head-left">
                                                <div class="filter-deta">
                                                    <div class="form-group">
                                                        <label class="modal-label">Property Name</label>
                                                        <select class="modal-input select2" id="property_id"
                                                            name="property_id">
                                                            <option>Select property</option>
                                                            @foreach ($properties as $property)
                                                            <option value="{{ $property->id }}">
                                                                {{ $property->propertyContent ? $property->propertyContent->title : '' }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-6">
                                            <div class="form-group drop-incheak">
                                                <label class="modal-label">Property Wings</label>
                                                <select class="modal-input select2" id="property_wings"
                                                    name="property_wings" multiple>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="graf_head-right">
                                        <div class="graf_right-card">
                                            <span class="graf-box1-new"></span>
                                            <h3 class="color-title">Available</h3>
                                        </div>
                                        <div class="graf_right-card">
                                            <span class="graf-box2-new"></span>
                                            <h3 class="color-title">Sold</h3>
                                        </div>
                                        <div class="graf_right-card">
                                            <span class="graf-box3-new"></span>
                                            <h3 class="color-title">Hold</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="graf_table-main" id="floor-details">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $('.select2').select2({
        theme: 'bootstrap4',
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: $(this).data('placeholder'),
        allowClear: Boolean($(this).data('allow-clear')),
    });

    function checkboxDropdown(el) {
        var $el = $(el)

        function updateStatus(label, result) {
            if (!result.length) {
                label.html('Select Options');
            }
        };

        $el.each(function(i, element) {
            var $list = $(this).find('.dropdown-list'),
                $label = $(this).find('.dropdown-label'),
                $checkAll = $(this).find('.check-all'),
                $inputs = $(this).find('.check'),
                defaultChecked = $(this).find('input[type=checkbox]:checked'),
                result = [];

            updateStatus($label, result);
            if (defaultChecked.length) {
                defaultChecked.each(function() {
                    result.push($(this).next().text());
                    $label.html(result.join(", "));
                });
            }

            $label.on('click', () => {
                $(this).toggleClass('open');
            });

            $checkAll.on('change', function() {
                var checked = $(this).is(':checked');
                var checkedText = $(this).next().text();
                result = [];
                if (checked) {
                    result.push(checkedText);
                    $label.html(result);
                    $inputs.prop('checked', false);
                } else {
                    $label.html(result);
                }
                updateStatus($label, result);
            });

            $inputs.on('change', function() {
                var checked = $(this).is(':checked');
                var checkedText = $(this).next().text();
                if ($checkAll.is(':checked')) {
                    result = [];
                }
                if (checked) {
                    result.push(checkedText);
                    $label.html(result.join(", "));
                    $checkAll.prop('checked', false);
                } else {
                    let index = result.indexOf(checkedText);
                    if (index >= 0) {
                        result.splice(index, 1);
                    }
                    $label.html(result.join(", "));
                }
                updateStatus($label, result);
            });

            $(document).on('click touchstart', e => {
                if (!$(e.target).closest($(this)).length) {
                    $(this).removeClass('open');
                }
            });
        });
    };
    checkboxDropdown('.dropdown');

    function addPropertyWork() {
        $.get("", function(res) {
            $('body').find('#modal-view-render').html(res.view);
            $('#property_manual_work_modal').modal('show');
        });
    }

    $('#property_id').change(function() {
        var property_id = $(this).val();
        $.get("{{ url('admin/property-inventory/status/property-wing') }}/" + property_id, function(res) {
            $('#property_wings').html(res.output);
            setTimeout(function() {
                $('#property_wings').change();
            }, 100)
        });
    });

    $(document).on('change', '#property_wings', function() {
        var wing_id = $(this).val();
        var property_id = $('#property_id').val();

        $.post("{{route('admin.manage_status_property.property-floor-details')}}", {
                _token: "{{ csrf_token() }}",
                wing_id: wing_id,
                property_id: property_id
            },
            function(res) {
                $('#floor-details').html(res.view);
            });
    });
</script>
@endsection
