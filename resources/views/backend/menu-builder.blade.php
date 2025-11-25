@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl-style')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Menu Builder') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Menu Builder') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-10">
                            <div class="card-title">{{ __('Menu Builder') }}</div>
                        </div>

                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card border-primary mb-3">
                                <div class="card-header bg-primary text-white">{{ __('Built-In Menus') }}</div>

                                <div class="card-body">
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            {{ __('Home') }} <a href="" data-text="{{ __('Home') }}"
                                                data-type="home"
                                                class="addToMenus btn btn-primary btn-sm float-right">{{ __('Add To Menus') }}</a>
                                        </li>
                                        <li class="list-group-item">
                                            {{ __('Properties') }} <a href="" data-text="{{ __('Properties') }}"
                                                data-type="properties"
                                                class="addToMenus btn btn-primary btn-sm float-right">{{ __('Add To Menus') }}</a>
                                        </li>
                                        <li class="list-group-item">
                                            {{ __('Projects') }} <a href="" data-text="{{ __('Projects') }}"
                                                data-type="projects"
                                                class="addToMenus btn btn-primary btn-sm float-right">{{ __('Add To Menus') }}</a>
                                        </li>
                                        <li class="list-group-item">
                                            {{ __('Pricing') }} <a href="" data-text="{{ __('Pricing') }}"
                                                data-type="pricing"
                                                class="addToMenus btn btn-primary btn-sm float-right">{{ __('Add To Menus') }}</a>
                                        </li>
                                        <li class="list-group-item">
                                            {{ __('Contact') }} <a href="" data-text="{{ __('Contact') }}"
                                                data-type="contact"
                                                class="addToMenus btn btn-primary btn-sm float-right">{{ __('Add To Menus') }}</a>
                                        </li>


                                        <li class="list-group-item">
                                            {{ __('Partner') }} <a href="" data-text="{{ __('Partner') }}"
                                                data-type="vendors"
                                                class="addToMenus btn btn-primary btn-sm float-right">{{ __('Add To Menus') }}</a>
                                        </li>


                                        <li class="list-group-item">
                                            {{ __('Blog') }} <a href="" data-text="{{ __('Blog') }}"
                                                data-type="blog"
                                                class="addToMenus btn btn-primary btn-sm float-right">{{ __('Add To Menus') }}</a>
                                        </li>

                                        <li class="list-group-item">
                                            {{ __('FAQ') }} <a href="" data-text="{{ __('FAQ') }}"
                                                data-type="faq"
                                                class="addToMenus btn btn-primary btn-sm float-right">{{ __('Add To Menus') }}</a>
                                        </li>


                                        <li class="list-group-item">
                                            {{ __('About Us') }} <a href="" data-text="{{ __('About Us') }}"
                                                data-type="about-us"
                                                class="addToMenus btn btn-primary btn-sm float-right">{{ __('Add To Menus') }}</a>
                                        </li>

                                        @foreach ($customPages as $customPage)
                                            <li class="list-group-item">
                                                {{ $customPage->title }} <span
                                                    class="badge badge-warning ml-1">{{ __('Custom Page') }}</span>
                                                <a href="" data-text="{{ $customPage->title }}"
                                                    data-type="{{ $customPage->slug }}" data-custom="yes"
                                                    class="addToMenus btn btn-primary btn-sm float-right mt-3 mt-md-0">{{ __('Add To Menus') }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card border-primary mb-3">
                                <div class="card-header bg-primary text-white">{{ __('Add') . '/' . __('Edit Menu') }}
                                </div>

                                <div class="card-body">
                                    <form id="menu-builder-form" class="form-horizontal">
                                        <input type="hidden" class="item-menu" name="type">

                                        <div id="withUrl">
                                            <div class="form-group">
                                                <label for="target">{{ __('Target') }}</label>
                                                <select name="target" id="target" class="form-control item-menu">
                                                    <option value="_self">{{ __('Self') }}</option>
                                                    <option value="_blank">{{ __('Blank') }}</option>
                                                    <option value="_top">{{ __('Top') }}</option>
                                                    <option value="_project">{{ __('Project') }}</option>
                                                </select>
                                            </div>

                                            <div class="form-group project-group" style="display: none;">
                                                <label for="projectEntity">Entity</label>
                                                <select id="projectEntity" name="vendor_id" class="form-control item-menu" disabled>
                                                <option value="">-- Select Developer/Partner --</option>
                                                </select>
                                            </div>

                                            <div class="form-group text-group">
                                                <label for="text">{{ __('Text') }}</label>
                                                <input type="text" type="text" class="form-control item-menu" name="text"
                                                    placeholder="Enter Menu Name">
                                            </div>

                                            <div class="form-group">
                                                <label for="href">{{ __('URL') }}</label>
                                                <input type="url" class="form-control item-menu ltr" name="href"
                                                    placeholder="Enter Menu URL">
                                            </div>
                                        </div>

                                        <div id="withoutUrl" class="dis-none">
                                            <div class="form-group">
                                                <label for="text">{{ __('Text') }}</label>
                                                <input type="text" class="form-control item-menu" name="text"
                                                    placeholder="Enter Menu Name">
                                            </div>

                                            <div class="form-group">
                                                <label for="target">{{ __('Target') }}</label>
                                                <select name="target" class="form-control item-menu">
                                                    <option value="_self">{{ __('Self') }}</option>
                                                    <option value="_blank">{{ __('Blank') }}</option>
                                                    <option value="_top">{{ __('Top') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="card-footer">
                                    <button type="button" id="btn-add" class="btn btn-primary btn-sm mr-2"><i
                                            class="fas fa-plus"></i> {{ __('Add') }}</button>
                                    <button type="button" id="btn-update" class="btn btn-success btn-sm" disabled><i
                                            class="fas fa-sync-alt"></i> {{ __('Update') }}</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card border-primary mb-3">
                                <div class="card-header bg-primary text-white">{{ __('Website Menus') }}</div>

                                <div class="card-body">
                                    <ul id="myMenuEditor" class="sortableLists list-group"></ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 text-center">
                            <button id="btn-menu-update" class="btn btn-success">
                                {{ __('Update') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('assets/js/jquery-menu-editor.min.js') }}"></script>

    <script>
        'use strict';

        let allMenus = {!! json_encode($menuData) !!};
        let langId = {{ $language->id }};
        const menuBuilderUrl = "{{ route('admin.menu_builder.update_menus') }}";


        $(function () {
            var menuBuilderForm = $('#menu-builder-form');
            var $target         = menuBuilderForm.find('#target');
            var $projectEntity  = menuBuilderForm.find('#projectEntity');

            function showProjectMode()
            {
                menuBuilderForm.find('.text-group').hide();
                menuBuilderForm.find('.project-group').show();

                // Reset + disable while loading
                $projectEntity.prop('disabled', true)
                .empty()
                .append('<option value="">Loading...</option>');

                $.ajax({
                    url: "{{ route('admin.menu_builder.load-builder') }}",
                    method: 'GET',
                    dataType: 'json',
                    success: function (res) {
                        $projectEntity.empty().append('<option value="">-- Select Developer/Partner --</option>');
                        if (res.items && res.items.length) {
                        res.items.forEach(function (item) {
                            // value = id, label = username (adjust if you use name instead)
                            $projectEntity.append(
                            $('<option>', { value: item.id, text: item.username })
                            );
                        });
                        $projectEntity.prop('disabled', false);
                        } else {
                        $projectEntity.append('<option value="">No items found</option>').prop('disabled', true);
                        }
                    },
                    error: function (xhr) {
                        console.error(xhr.responseText || xhr.statusText);
                        $projectEntity.prop('disabled', true).empty().append('<option value="">Load failed</option>');
                    }
                });
            }

            function showManualMode() {
                menuBuilderForm.find('.project-group').hide();
                menuBuilderForm.find('.text-group').show();
            }

            // Handle target change
            $target.on('change', function () {
                const selectedValue = $(this).val();
                if (selectedValue === '_project') {
                  showProjectMode();
                } else {
                    showManualMode();
                    menuBuilderForm.find('input[name="text"]').val('');
                    menuBuilderForm.find('input[name="href"]').val('');
                }
            });

            // When a project entity is chosen → set Text + URL
            $projectEntity.on('change', function () {
                const selectedId   = $(this).val(); // the ID
                const entityName   = $(this).find('option:selected').text(); // the label

                if (!selectedId) {
                    // nothing chosen
                    menuBuilderForm.find('input[name="text"]').val('');
                    menuBuilderForm.find('input[name="href"]').val('');
                    return;
                }

                // Fill underlying inputs (even if the group is hidden)
                menuBuilderForm.find('input[name="text"]').val(entityName);
                menuBuilderForm.find('input[name="href"]').val("{{ url('/projects') }}?vendor_id=" + encodeURIComponent(selectedId));
            });

            // Initialize state on load (in case the select already has a value)
            if ($target.val() === '_project') {
                showProjectMode();
            } else {
                showManualMode();
            }

            // $('.btnEdit').click(function()
            // {
            //     setTimeout(() => {
            //         var target = menuBuilderForm.find("#target").val();
            //        if (target === '_project') {
            //         showProjectMode();
            //         }

            //     }, 1000);
            // });

            $(document).on("click", ".btnEdit", function (e) {
                e.preventDefault();

                var $li = $(this).closest("li");
                var t   = $li.data();
                if(t.target === "_project")
                {
                    showProjectMode();
                    setTimeout(() => {
                        $projectEntity.val(t.vendor_id).trigger('change');
                    }, 500);
                }
                else
                {
                    showManualMode();
                }

            });
        });

    </script>

    <script type="text/javascript" src="{{ asset('assets/js/menu-builder.js') }}"></script>
@endsection
