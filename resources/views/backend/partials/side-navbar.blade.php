<style>
    .sidebar.sidebar-style-2 .nav.nav-primary>.nav-item.active>a::before,
    .sidebar.sidebar-style-2 .nav.nav-primary>.nav-item>a:hover::before {
        color: white;
    }

    .main-title {
        font-size: 13px;
        color: #494949;
        font-weight: 550;
        letter-spacing: 1px;
        margin: 12px 20px;
        width: fit-content;
        padding-bottom: 4px;
        display: flex;
        align-items: center;
        border-bottom: 1px solid #494949;

    }

    /* .main-title::before {
    content: "•";
    font-size: 18px;
    color: #494949;
    margin-right: 8px;
} */
</style>

<div class="sidebar sidebar-style-2"
    data-background-color="{{ $settings->admin_theme_version == 'light' ? 'white' : 'dark2' }}">
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            @php
                if (!is_null($roleInfo)) {
                    $rolePermissions = json_decode($roleInfo->permissions);
                }
            @endphp

            <ul class="nav nav-primary">
                <!-- {{-- search --}} -->
                <div class="row mb-3">
                    <div class="col-12">
                        <form action="">
                            <div class="form-group py-0">
                                <input name="term" type="text" class="form-control sidebar-search ltr"
                                    placeholder="Search Menu Here..." style="border-radius: 12px;">
                            </div>
                        </form>
                    </div>
                </div>

                <!-- ================================================================================================================== -->
                <!-- ================================================================================================================== -->

                <h1 class="main-title">General</h1>
                <!-- {{-- Start dashboard --}} -->
                <li class="nav-item @if (request()->routeIs('admin.dashboard')) active @endif">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="la flaticon-paint-palette"></i>
                        <p>{{ 'Dashboard' }}</p>
                    </a>
                </li>
                <!-- {{-- end dashboard --}} -->


                <!-- {{-- start Inquiry management --}} -->
                @php
                    $inquiryUnread = Helper::unreadInquiries();

                    $inquiry_unread_class = '';
                    if ($inquiryUnread != 0) {
                        $inquiry_unread_class = 'text-danger-glow blink';
                    }
                @endphp
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Property Messages', $rolePermissions)))
                    <li
                        class="nav-item @if (request()->routeIs('admin.property_message.index')) active @elseif(request()->routeIs('admin.status.index')) active @endif">
                        <a data-toggle="collapse" href="#inquiryManagement">
                            <i class="fal fa-receipt"></i>
                            <p>{{ 'Inquiry Management' }}</p><i class="{{ $inquiry_unread_class }}"
                                style="margin-left: 10px;"><b>{{ $inquiryUnread != 0 ? $inquiryUnread : '' }}</b></i>
                            <span class="caret"></span>
                        </a>

                        <div id="inquiryManagement"
                            class="collapse
                                        @if (request()->routeIs('admin.property_message.index')) show @elseif (request()->routeIs('admin.status.index')) show @endif">
                            <ul class="nav nav-collapse">
                                <li class="{{ request()->routeIs('admin.property_message.index') ? 'active' : '' }}">
                                    <a href="{{ route('admin.property_message.index') }}">
                                        <span class="sub-item">{{ 'Property Inquiry' }}</span><i
                                            class="{{ $inquiry_unread_class }}"
                                            style="margin-left: 10px;"><b>{{ $inquiryUnread != 0 ? $inquiryUnread : '' }}</b></i>
                                    </a>
                                </li>

                                <li class="{{ request()->routeIs('admin.status.index') ? 'active' : '' }}">
                                    <a href="{{ route('admin.status.index') }}">
                                        <span class="sub-item">{{ 'Status' }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
                <!-- {{-- end Inquiry management --}} -->


                <!-- ================================================================================================================== -->
                <!-- ================================================================================================================== -->

                <h1 class="main-title">Owner & Builder</h1>



                <!-- {{-- start user management --}} -->
                @php
                    $userUnread = Helper::unreadUsers();

                    $user_unread_class = '';
                    if ($userUnread != 0) {
                        $user_unread_class = 'text-danger-glow blink';
                    }
                @endphp


                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('User Management', $rolePermissions)))
                    <li
                        class="nav-item @if (request()->routeIs('admin.user_management.registered_users')) active
                                @elseif (request()->routeIs('admin.user_management.registered_user.create')) active
                                @elseif (request()->routeIs('admin.user_management.registered_user.edit')) active
                                @elseif (request()->routeIs('admin.user_management.user.change_password')) active
                                @elseif (request()->routeIs('admin.user_management.subscribers')) active
                                @elseif (request()->routeIs('admin.user_management.mail_for_subscribers')) active @endif">
                        <a data-toggle="collapse" href="#user">
                            <i class="la flaticon-users"></i>
                            <p>{{ 'Owner' }}</p><i class="{{ $user_unread_class }}"
                                style="margin-left: 10px;"><b>{{ $userUnread != 0 ? $userUnread : '' }}</b></i>
                            <span class="caret"></span>
                        </a>

                        <div id="user"
                            class="collapse
                                        @if (request()->routeIs('admin.user_management.registered_users')) show
                                        @elseif (request()->routeIs('admin.user_management.registered_user.create')) show
                                        @elseif (request()->routeIs('admin.user_management.registered_user.edit')) show
                                        @elseif (request()->routeIs('admin.user_management.user.change_password')) show
                                        @elseif (request()->routeIs('admin.user_management.subscribers')) show
                                        @elseif (request()->routeIs('admin.user_management.mail_for_subscribers')) show @endif">
                            <ul class="nav nav-collapse">
                                <li
                                    class="@if (request()->routeIs('admin.user_management.registered_users')) active
                                                @elseif (request()->routeIs('admin.user_management.user.change_password')) active
                                            @elseif (request()->routeIs('admin.user_management.registered_user.edit'))
                                            active @endif ">
                                    <a href="{{ route('admin.user_management.registered_users') }}">
                                        <span class="sub-item">{{ 'Registered Owner' }}</span> <i
                                            class="{{ $user_unread_class }}"
                                            style="margin-left: 10px;"><b>{{ $userUnread != 0 ? $userUnread : '' }}</b></i>
                                    </a>

                                </li>

                                <li class="@if (request()->routeIs('admin.user_management.registered_user.create')) active @endif ">
                                    <a href="{{ route('admin.user_management.registered_user.create') }}">
                                        <span class="sub-item">{{ 'Add Owner' }}</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>
                @endif
                <!-- {{-- end user management --}} -->


                <!-- {{-- start Partner management --}} -->
                @php
                    $partnerUnread = Helper::unreadPartners();

                    $partner_unread_class = '';
                    if ($partnerUnread != 0) {
                        $partner_unread_class = 'text-danger-glow blink';
                    }
                @endphp
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Vendors Management', $rolePermissions)))
                    <li
                        class="nav-item @if (request()->routeIs('admin.vendor_management.registered_vendor')) active
                                                @elseif (request()->routeIs('admin.vendor_management.add_vendor')) active
                                                @elseif (request()->routeIs('admin.vendor_management.vendor_details')) active
                                                @elseif (request()->routeIs('admin.edit_management.vendor_edit')) active
                                                @elseif (request()->routeIs('admin.vendor_management.settings')) active
                                                @elseif (request()->routeIs('admin.vendor_management.vendor.change_password')) active
                                                @elseif (request()->routeIs('admin.vendor_management.vendor.vendor_kyc')) active
                                                @elseif (request()->routeIs('admin.vendor_management.vendor_kyc')) active @endif">
                        <a data-toggle="collapse" href="#vendor">
                            <i class="la flaticon-users"></i>
                            <p>{{ 'Builder' }}</p><i class="{{ $partner_unread_class }}"
                                style="margin-left: 10px;"><b>{{ $partnerUnread != 0 ? $partnerUnread : '' }}</b></i>
                            <span class="caret"></span>
                        </a>

                        <div id="vendor"
                            class="collapse
                                                @if (request()->routeIs('admin.vendor_management.registered_vendor')) show
                                                @elseif (request()->routeIs('admin.vendor_management.vendor_details')) show
                                                @elseif (request()->routeIs('admin.edit_management.vendor_edit')) show
                                                @elseif (request()->routeIs('admin.vendor_management.add_vendor')) show
                                                @elseif (request()->routeIs('admin.vendor_management.settings')) show
                                                @elseif (request()->routeIs('admin.vendor_management.vendor.change_password')) show
                                                @elseif (request()->routeIs('admin.vendor_management.vendor_kyc')) show @endif">
                            <ul class="nav nav-collapse">
                                {{-- <li class="@if (request()->routeIs('admin.vendor_management.settings')) active @endif">
                                    <a href="{{ route('admin.vendor_management.settings') }}">
                                        <span class="sub-item">{{ 'Settings' }}</span>
                                    </a>
                                </li> --}}
                                <li
                                    class="@if (request()->routeIs('admin.vendor_management.registered_vendor')) active
                                                            @elseif (request()->routeIs('admin.vendor_management.vendor_details')) active
                                                            @elseif (request()->routeIs('admin.edit_management.vendor_edit')) active
                                                            @elseif (request()->routeIs('admin.vendor_management.vendor.change_password')) active @endif">
                                    <a href="{{ route('admin.vendor_management.registered_vendor') }}">
                                        <span class="sub-item">{{ 'Registered Builder' }}</span><i
                                            class="{{ $partner_unread_class }}"
                                            style="margin-left: 10px;"><b>{{ $partnerUnread != 0 ? $partnerUnread : '' }}</b></i>
                                    </a>
                                </li>
                                <li class="@if (request()->routeIs('admin.vendor_management.add_vendor')) active @endif">
                                    <a href="{{ route('admin.vendor_management.add_vendor') }}">
                                        <span class="sub-item">{{ 'Add Builder' }}</span>
                                    </a>
                                </li>
                                <li class="@if (request()->routeIs('admin.vendor_management.vendor_kyc')) active @endif">
                                    <a href="{{ route('admin.vendor_management.vendor_kyc') }}">
                                        <span class="sub-item">{{ 'Builder KYC' }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
                <!-- {{-- end Partner management --}} -->



                <!-- ================================================================================================================== -->
                <!-- ================================================================================================================== -->


                <h1 class="main-title">Property Master</h1>

                <!-- {{-- Property specifications --}} -->
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Property Features', $rolePermissions)))
                    <li
                        class="nav-item @if (request()->routeIs('admin.property_specification.categories')) active
                            @elseif (request()->routeIs('admin.property_specification.countries')) active
                            @elseif (request()->routeIs('admin.property_specification.settings')) active
                            @elseif (request()->routeIs('admin.property_specification.states')) active
                            @elseif (request()->routeIs('admin.property_specification.cities')) active
                            @elseif (request()->routeIs('admin.property_specification.areas')) active @endif">
                        <a data-toggle="collapse" href="#propertySpecification">
                            <i class="far fa-file-alt"></i>
                            <p>{{ __('Property Setting') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="propertySpecification"
                            class="collapse
                            @if (request()->routeIs('admin.property_specification.categories')) show
                            @elseif (request()->routeIs('admin.property_specification.settings')) show
                            @elseif (request()->routeIs('admin.property_specification.countries')) show
                            @elseif (request()->routeIs('admin.property_specification.states')) show
                            @elseif (request()->routeIs('admin.property_specification.cities')) show
                            @elseif(request()->routeIs('admin.property_specification.amenities'))  show
                            @elseif(request()->routeIs('admin.property_specification.cities'))  show
                            @elseif(request()->routeIs('admin.property_specification.unit-type'))  show
                            @elseif(request()->routeIs('admin.property_specification.areas'))  show @endif">
                            <ul class="nav nav-collapse">
                                <li
                                    class="{{ request()->routeIs('admin.property_specification.settings') ? 'active' : '' }}">
                                    <a href="{{ route('admin.property_specification.settings') }}">
                                        <span class="sub-item">{{ __('Settings') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="{{ request()->routeIs('admin.property_specification.categories') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('admin.property_specification.categories', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('Categories') }}</span>
                                    </a>
                                </li>


                                <li
                                    class="{{ request()->routeIs('admin.property_specification.amenities') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('admin.property_specification.amenities', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('Amenities') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="{{ request()->routeIs('admin.property_specification.unit-type') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('admin.property_specification.unit-type', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('Unit Type') }}</span>
                                    </a>
                                </li>
                                @if ($settings->property_country_status == 1)
                                    <li
                                        class="{{ request()->routeIs('admin.property_specification.countries') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('admin.property_specification.countries', ['language' => $defaultLang->code]) }}">
                                            <span class="sub-item">{{ __('Countries') }}</span>
                                        </a>
                                    </li>
                                @endif
                                @if ($settings->property_state_status == 1)
                                    <li
                                        class="{{ request()->routeIs('admin.property_specification.states') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('admin.property_specification.states', ['language' => $defaultLang->code]) }}">
                                            <span class="sub-item">{{ __('States') }}</span>
                                        </a>
                                    </li>
                                @endif
                                <li
                                    class="{{ request()->routeIs('admin.property_specification.cities') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('admin.property_specification.cities', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('Cities') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="{{ request()->routeIs('admin.property_specification.areas') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('admin.property_specification.areas', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('Area') }}</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>
                @endif
                <!-- {{-- end property specifications  --}} -->

                <!-- {{-- Property management --}} -->
                @php
                    $unreadCount = Helper::unreadProeprties('partial');

                    $add_unread_class = '';
                    if ($unreadCount != 0) {
                        $add_unread_class = 'text-danger-glow blink';
                    }
                @endphp
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Property Management', $rolePermissions)))
                    <li
                        class="nav-item
                        @if (request()->routeIs('admin.property_management.create_property')) active
                        @elseif (request()->routeIs('admin.property_management.properties')) active
                        @elseif (request()->routeIs('admin.property_management.edit')) active
                        @elseif (request()->routeIs('admin.property_management.settings')) active
                        @elseif(request()->routeIs('admin.property_management.type')) active @endif">
                        <a data-toggle="collapse" href="#carManagement">
                            <i class="far fa-home"></i>
                            <p>{{ __('Properties') }} </p><i class="{{ $add_unread_class }}"
                                style="margin-left: 10px;"><b>{{ $unreadCount != 0 ? $unreadCount : '' }}</b></i>
                            <span class="caret"></span>
                        </a>

                        <div id="carManagement"
                            class="collapse
                                @if (request()->routeIs('admin.property_management.create_property')) show
                                @elseif (request()->routeIs('admin.property_management.type')) show
                                @elseif (request()->routeIs('admin.property_management.properties')) show
                                @elseif (request()->routeIs('admin.property_management.settings')) show
                                @elseif (request()->routeIs('admin.property_management.edit')) show @endif
                                ">
                            <ul class="nav nav-collapse">
                                <!-- <li
                                    class="{{ request()->routeIs('admin.property_management.settings') ? 'active' : '' }}">
                                    <a href="{{ route('admin.property_management.settings') }}">
                                        <span class="sub-item">{{ __('Settings') }}</span>
                                    </a>
                                </li> -->
                                <li
                                    class="{{ request()->routeIs('admin.property_management.create_property') || request()->routeIs('admin.property_management.type') ? 'active' : '' }}">
                                    <a href="{{ route('admin.property_management.type') }}">
                                        <span class="sub-item">{{ __('Add Property') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="{{ request()->routeIs('admin.property_management.properties') ||
                                    request()->routeIs('admin.property_management.edit')
                                        ? 'active'
                                        : '' }}">
                                    <a
                                        href="{{ route('admin.property_management.properties', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('Manage Properties') }}</span> <i
                                            class="{{ $add_unread_class }}"
                                            style="margin-left: 10px;"><b>{{ $unreadCount != 0 ? $unreadCount : '' }}</b></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
                <!-- {{-- end property management  --}} -->

                <!-- {{-- Project management  start --}} -->
                @php
                    $projectUnread = Helper::unreadProjects();

                    $project_unread_class = '';
                    if ($projectUnread != 0) {
                        $project_unread_class = 'text-danger-glow blink';
                    }
                @endphp
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Project Management', $rolePermissions)))
                    <li
                        class="nav-item
                                @if (request()->routeIs('admin.project_management.projects')) active
                                @elseif (request()->routeIs('admin.project_management.create_project')) active
                                @elseif (request()->routeIs('admin.project_management.project_types')) active
                                @elseif (request()->routeIs('admin.project_management.settings')) active
                                @elseif (request()->routeIs('admin.project_management.edit')) active @endif">
                        <a data-toggle="collapse" href="#projectManagement">
                            <i class="fal fa-building"></i>
                            <p>{{ __('Projects') }}</p><i class="{{ $project_unread_class }}"
                                style="margin-left: 10px;"><b>{{ $projectUnread != 0 ? $projectUnread : '' }}</b></i>
                            <span class="caret"></span>
                        </a>

                        <div id="projectManagement"
                            class="collapse
                                            @if (request()->routeIs('admin.project_management.create_project')) show
                                            @elseif (request()->routeIs('admin.project_management.projects')) show
                                            @elseif (request()->routeIs('admin.project_management.settings')) show
                                            @elseif (request()->routeIs('admin.project_management.edit')) show
                                            @elseif (request()->routeIs('admin.project_management.project_types')) show @endif
                                            ">
                            <ul class="nav nav-collapse">

                                <!-- <li
                                    class="{{ request()->routeIs('admin.project_management.settings') ? 'active' : '' }}">
                                    <a href="{{ route('admin.project_management.settings') }}">
                                        <span class="sub-item">{{ __('Settings') }}</span>
                                    </a>
                                </li> -->

                                <li
                                    class="{{ request()->routeIs('admin.project_management.create_project') ? 'active' : '' }}">
                                    <a href="{{ route('admin.project_management.create_project') }}">
                                        <span class="sub-item">{{ __('Add Project') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="{{ request()->routeIs('admin.project_management.projects') ||
                                    request()->routeIs('admin.property_management.edit') ||
                                    request()->routeIs('admin.project_management.project_types')
                                        ? 'active'
                                        : '' }}">
                                    <a
                                        href="{{ route('admin.project_management.projects', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('Manage Projects') }}</span><i
                                            class="{{ $project_unread_class }}"
                                            style="margin-left: 10px;"><b>{{ $projectUnread != 0 ? $projectUnread : '' }}</b></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
                <!-- {{-- Project Management end  --}} -->

                <!-- ================================================================================================================== -->
                <!-- ================================================================================================================== -->


                <h1 class="main-title">Inventory & Accounting</h1>

                <!-- {{-- Property inventory --}} -->
                @php
                    $unreadCountInventory = Helper::unreadProeprties('full');

                    $add_unread__inventory_class = '';
                    if ($unreadCountInventory != 0) {
                        $add_unread__inventory_class = 'text-danger-glow blink';
                    }
                @endphp
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Property Inventory', $rolePermissions)))
                    <li
                        class="nav-item  {{ request()->routeIs('admin.property_inventory.create_property') || request()->routeIs('admin.property_inventory.type') ? 'active' : '' }}">
                        <a href="{{ route('admin.property_inventory.type') }}">
                            <i class="la flaticon-paint-palette"></i>
                            <p>{{ __('Add Full Property') }}</p>
                        </a>
                    </li>

                    <li
                        class=" nav-item  {{ request()->routeIs('admin.property_inventory.properties') || request()->routeIs('admin.property_inventory.edit') ? 'active' : '' }}">
                        <a
                            href="{{ route('admin.property_inventory.properties', ['language' => $defaultLang->code]) }}">
                            <i class="la flaticon-paint-palette"></i>
                            <p>{{ __(' Manage Full Property') }}</p>
                            <i class="{{ $add_unread__inventory_class }}"
                                style="margin-left: 10px;"><b>{{ $unreadCountInventory != 0 ? $unreadCountInventory : '' }}</b></i>
                        </a>
                    </li>
                    <li
                        class="nav-item  {{ request()->routeIs('admin.property_inventory.manage_status_property') ? 'active' : '' }}">
                        <a href="{{ route('admin.property_inventory.manage_status_property') }}">
                            <i class="la flaticon-paint-palette"></i>
                            <p>{{ __('Property Live Status') }}</p>
                        </a>
                    </li>
                    <li
                        class=" nav-item  {{ request()->routeIs('admin.property_inventory.converted_customer') ? 'active' : '' }}">
                        <a href="{{ route('admin.property_inventory.converted_customer') }}">
                            <i class="la flaticon-paint-palette"></i>
                            <p>{{ __('Purchased Customers ') }}</p>
                        </a>
                    </li>
                @endif
                <!-- {{-- end property inventory  --}} -->

                <!-- {{-- Start Customer  --}} -->
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Agent', $rolePermissions)))
                    <li class="nav-item  @if (request()->routeIs('admin.customer_management.index')) active @endif">
                        <a href="{{ route('admin.customer_management.index') }}">
                            <i class="fal fa-users-cog"></i>
                            <p> {{ 'All Customer List' }} </p>
                        </a>
                    </li>
                @endif
                <!-- {{-- end Customer  --}} -->


                <!-- {{-- Start Account --}} -->
                <li class="nav-item @if (request()->routeIs('admin.sales-bill.index')) active @endif">
                    <a href="{{ route('admin.sales-bill.index') }}">
                        <i class="fal fa-university"></i>
                        <p>{{ 'Account' }}</p>
                    </a>
                </li>
                <!-- {{-- end Account  --}} -->


                <!-- ================================================================================================================== -->
                <!-- ================================================================================================================== -->


                <h1 class="main-title">Package & Payement</h1>

                <!-- {{-- start package management --}} -->
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Package Management', $rolePermissions)))
                    <li class="nav-item  {{ request()->routeIs('admin.package.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.package.index') }}">
                            <i class="fal fa-receipt"></i>
                            <p class="sub-item">{{ 'Package Management' }}</p>
                        </a>
                    </li>
                @endif
                <!-- {{-- end package management  --}} -->

                <!-- {{-- start Online / ofline Gateways  --}} -->
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Payment Gateways', $rolePermissions)))
                    <li
                        class="nav-item {{ request()->routeIs('admin.payment_gateways.online_gateways') ? 'active' : '' }}">
                        <a href="{{ route('admin.payment_gateways.online_gateways') }}">
                            <i class="la flaticon-paypal"></i>
                            <p>{{ 'Online Gateways' }}</p>
                        </a>
                    </li>

                    <li
                        class="nav-item {{ request()->routeIs('admin.payment_gateways.offline_gateways') ? 'active' : '' }}">
                        <a href="{{ route('admin.payment_gateways.offline_gateways') }}">
                            <i class="la flaticon-paypal"></i>
                            <p>{{ 'Offline Gateways' }}</p>
                        </a>
                    </li>


                    </li>
                @endif
                <!-- {{-- EndOnline / oflinGateways  --}} -->

                <!-- {{-- Start payment log --}} -->
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Payment Log', $rolePermissions)))
                    <li class="nav-item @if (request()->routeIs('admin.payment-log.index')) active @endif">
                        <a href="{{ route('admin.payment-log.index') }}">
                            <i class="fas fa-list-ol"></i>
                            <p>{{ 'Payment History' }}</p>
                        </a>
                    </li>
                @endif
                <!-- {{-- End  payment log --}} -->


                <!-- ================================================================================================================== -->
                <!-- ================================================================================================================== -->

                <h1 class="main-title">Admin Management</h1>

                <!-- {{-- Start admin --}} -->
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Admin Management', $rolePermissions)))
                    <li
                        class="nav-item @if (request()->routeIs('admin.admin_management.role_permissions')) active
                            @elseif (request()->routeIs('admin.admin_management.role.permissions')) active
                            @elseif (request()->routeIs('admin.admin_management.registered_admins')) active @endif">
                        <a data-toggle="collapse" href="#admin">
                            <i class="fal fa-users-cog"></i>
                            <p>{{ 'Admin Management' }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="admin"
                            class="collapse
                            @if (request()->routeIs('admin.admin_management.role_permissions')) show
                            @elseif (request()->routeIs('admin.admin_management.role.permissions')) show
                            @elseif (request()->routeIs('admin.admin_management.registered_admins')) show @endif">
                            <ul class="nav nav-collapse">
                                <li
                                    class="@if (request()->routeIs('admin.admin_management.role_permissions')) active
                                @elseif (request()->routeIs('admin.admin_management.role.permissions')) active @endif">
                                    <a href="{{ route('admin.admin_management.role_permissions') }}">
                                        <span class="sub-item">{{ 'Role & Permissions' }}</span>
                                    </a>
                                </li>

                                <li
                                    class="{{ request()->routeIs('admin.admin_management.registered_admins') ? 'active' : '' }}">
                                    <a href="{{ route('admin.admin_management.registered_admins') }}">
                                        <span class="sub-item">{{ 'Registered Admins' }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
                <!-- {{-- end  admin --}} -->

                <!-- ================================================================================================================== -->
                <!-- ================================================================================================================== -->

                <h1 class="main-title">Support & Resources</h1>


                <!-- {{-- Support Tickets --}} -->
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Support Tickets', $rolePermissions)))
                    <li
                        class="nav-item @if (request()->routeIs('admin.support_ticket.setting')) active
                            @elseif (request()->routeIs('admin.support_tickets')) active
                            @elseif (request()->routeIs('admin.support_tickets.message')) active active @endif">
                        <a data-toggle="collapse" href="#support_ticket">
                            <i class="la flaticon-web-1"></i>
                            <p>{{ 'Support Tickets' }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="support_ticket"
                            class="collapse
                            @if (request()->routeIs('admin.support_ticket.setting')) show
                            @elseif (request()->routeIs('admin.support_tickets')) show
                            @elseif (request()->routeIs('admin.support_tickets.message')) show @endif">
                            <ul class="nav nav-collapse">
                                <li class="@if (request()->routeIs('admin.support_ticket.setting')) active @endif">
                                    <a href="{{ route('admin.support_ticket.setting') }}">
                                        <span class="sub-item">{{ 'Setting' }}</span>
                                    </a>
                                </li>
                                <li
                                    class="{{ request()->routeIs('admin.support_tickets') && empty(request()->input('status')) ? 'active' : '' }}">
                                    <a href="{{ route('admin.support_tickets') }}">
                                        <span class="sub-item">{{ 'All Tickets' }}</span>
                                    </a>
                                </li>
                                <li
                                    class="{{ request()->routeIs('admin.support_tickets') && request()->input('status') == 1 ? 'active' : '' }}">
                                    <a href="{{ route('admin.support_tickets', ['status' => 1]) }}">
                                        <span class="sub-item">{{ 'Pending Tickets' }}</span>
                                    </a>
                                </li>
                                <li
                                    class="{{ request()->routeIs('admin.support_tickets') && request()->input('status') == 2 ? 'active' : '' }}">
                                    <a href="{{ route('admin.support_tickets', ['status' => 2]) }}">
                                        <span class="sub-item">{{ 'Open Tickets' }}</span>
                                    </a>
                                </li>
                                <li
                                    class="{{ request()->routeIs('admin.support_tickets') && request()->input('status') == 3 ? 'active' : '' }}">
                                    <a href="{{ route('admin.support_tickets', ['status' => 3]) }}">
                                        <span class="sub-item">{{ 'Closed Tickets' }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
                <!-- {{-- end support tickets  --}} -->

                <!-- {{-- Start blog --}} -->
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Blog Management', $rolePermissions)))
                    <li
                        class="nav-item @if (request()->routeIs('admin.blog_management.categories')) active
                                        @elseif (request()->routeIs('admin.blog_management.blogs')) active
                                        @elseif (request()->routeIs('admin.blog_management.create_blog')) active
                                        @elseif (request()->routeIs('admin.blog_management.edit_blog')) active @endif">
                        <a data-toggle="collapse" href="#blog">
                            <i class="fal fa-blog"></i>
                            <p>{{ 'Blog Management' }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="blog"
                            class="collapse
                                    @if (request()->routeIs('admin.blog_management.categories')) show
                                    @elseif (request()->routeIs('admin.blog_management.blogs')) show
                                    @elseif (request()->routeIs('admin.blog_management.create_blog')) show
                                    @elseif (request()->routeIs('admin.blog_management.edit_blog')) show @endif">
                            <ul class="nav nav-collapse">
                                <li
                                    class="{{ request()->routeIs('admin.blog_management.categories') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('admin.blog_management.categories', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ 'Categories' }}</span>
                                    </a>
                                </li>



                                <li
                                    class="@if (request()->routeIs('admin.blog_management.blogs')) active
                                            @elseif (request()->routeIs('admin.blog_management.create_blog')) active
                                            @elseif (request()->routeIs('admin.blog_management.edit_blog')) active @endif">
                                    <a
                                        href="{{ route('admin.blog_management.blogs', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ 'Posts' }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
                <!-- {{-- End blog --}} -->

                <!-- {{-- Start faq --}} -->
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('FAQ Management', $rolePermissions)))
                    <li class="nav-item {{ request()->routeIs('admin.faq_management') ? 'active' : '' }}">
                        <a href="{{ route('admin.faq_management', ['language' => $defaultLang->code]) }}">
                            <i class="la flaticon-round"></i>
                            <p>{{ 'FAQ Management' }}</p>
                        </a>
                    </li>
                @endif
                <!-- {{-- End faq --}} -->


                <!-- ================================================================================================================== -->
                <!-- ================================================================================================================== -->

                <h1 class="main-title">Basic Setting</h1>


                <!-- {{-- Start basic settings --}} -->
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Basic Settings', $rolePermissions)))
                    <li
                        class="nav-item {{ request()->routeIs('admin.basic_settings.general_settings') ? 'active' : '' }}">
                        <a href="{{ route('admin.basic_settings.general_settings') }}">
                            <i class="fal fa-layer-group"></i>
                            <p>{{ 'General Settings' }}</p>
                        </a>
                    </li>


                    <li
                        class="nav-item {{ request()->routeIs('admin.home_page.hero_section.static_version') ? 'active' : '' }}">
                        <a
                            href="{{ route('admin.home_page.hero_section.static_version', ['language' => $defaultLang->code]) }}">
                            <i class="fal fa-layer-group"></i>
                            <p>Banner Section</p>
                        </a>
                    </li>

                    <li
                        class="nav-item {{ request()->routeIs('admin.basic_settings.contact_page') ? 'active' : '' }}">
                        <a href="{{ route('admin.basic_settings.contact_page') }}">
                            <i class="fal fa-layer-group"></i>
                            <p>{{ 'Contact Page' }}</p>
                        </a>
                    </li>

                    <!-- <li class="submenu">
                                        <a data-toggle="collapse" href="#mail-settings">
                                            <i class="fal fa-layer-group"></i>
                                            <p>{{ 'Email Settings' }}</p>
                                            <span class="caret"></span>
                                        </a>

                                        <div id="mail-settings"
                                            class="collapse
                                            @if (request()->routeIs('admin.basic_settings.mail_from_admin')) show
                                            @elseif (request()->routeIs('admin.basic_settings.mail_to_admin')) show
                                            @elseif (request()->routeIs('admin.basic_settings.mail_templates')) show
                                            @elseif (request()->routeIs('admin.basic_settings.edit_mail_template')) show @endif">
                                            <ul class="nav nav-collapse subnav">
                                                <li
                                                    class="{{ request()->routeIs('admin.basic_settings.mail_from_admin') ? 'active' : '' }}">
                                                    <a href="{{ route('admin.basic_settings.mail_from_admin') }}">
                                                        <p>{{ 'Mail From Admin' }}</p>
                                                    </a>
                                                </li>

                                                <li
                                                    class="{{ request()->routeIs('admin.basic_settings.mail_to_admin') ? 'active' : '' }}">
                                                    <a href="{{ route('admin.basic_settings.mail_to_admin') }}">
                                                        <p>{{ 'Mail To Admin' }}</p>
                                                    </a>
                                                </li>

                                                <li
                                                    class="@if (request()->routeIs('admin.basic_settings.mail_templates')) active
                                                @elseif (request()->routeIs('admin.basic_settings.edit_mail_template')) active @endif">
                                                    <a href="{{ route('admin.basic_settings.mail_templates') }}">
                                                        <p>{{ 'Mail Templates' }}</p>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li> -->

                    <!-- <li class="nav-item {{ request()->routeIs('admin.basic_settings.breadcrumb') ? 'active' : '' }}">
                                        <a href="{{ route('admin.basic_settings.breadcrumb') }}">
                                            <i class="fal fa-layer-group"></i>
                                            <p >{{ 'Breadcrumb' }}</p>
                                        </a>
                                    </li> -->


                    <!-- <li class="nav-item {{ request()->routeIs('admin.basic_settings.page_headings') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('admin.basic_settings.page_headings', ['language' => $defaultLang->code]) }}">
                                            <i class="fal fa-layer-group"></i>
                                            <p >{{ 'Page Headings' }}</p>
                                        </a>
                                    </li> -->

                    <!-- <li class="nav-item {{ request()->routeIs('admin.basic_settings.plugins') ? 'active' : '' }}">
                                        <a href="{{ route('admin.basic_settings.plugins') }}">
                                            <i class="fal fa-layer-group"></i>
                                            <p>{{ 'Plugins' }}</p>
                                        </a>
                                    </li> -->

                    <!-- <li class="nav-item {{ request()->routeIs('admin.basic_settings.seo') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('admin.basic_settings.seo', ['language' => $defaultLang->code]) }}">
                                            <i class="fal fa-layer-group"></i>
                                            <p>{{ 'SEO Informations' }}</p>
                                        </a>
                                    </li> -->

                    <!-- <li class="nav-item {{ request()->routeIs('admin.basic_settings.maintenance_mode') ? 'active' : '' }}">
                                        <a href="{{ route('admin.basic_settings.maintenance_mode') }}">
                                            <i class="fal fa-layer-group"></i>
                                            <p>{{ 'Maintenance Mode' }}</p>
                                        </a>
                                    </li> -->

                    <!-- <li class="nav-item {{ request()->routeIs('admin.basic_settings.cookie_alert') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('admin.basic_settings.cookie_alert', ['language' => $defaultLang->code]) }}">
                                            <i class="fal fa-layer-group"></i>
                                            <p >{{ 'Cookie Alert' }}</p>
                                        </a>
                                    </li> -->

                    <li
                        class="nav-item {{ request()->routeIs('admin.basic_settings.social_medias') ? 'active' : '' }}">
                        <a href="{{ route('admin.basic_settings.social_medias') }}">
                            <i class="fal fa-layer-group"></i>
                            <p>{{ 'Social Medias' }}</p>
                        </a>
                    </li>
                @endif
                <!-- {{-- End  basic settings --}} -->



                <!-- {{-- Start menu builder --}} -->
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Menu Builder', $rolePermissions)))
                    <li class="nav-item @if (request()->routeIs('admin.menu_builder')) active @endif">
                        <a href="{{ route('admin.menu_builder', ['language' => $defaultLang->code]) }}">
                            <i class="fal fa-bars"></i>
                            <p>{{ 'Menu Builder' }}</p>
                        </a>
                    </li>
                @endif
                <!-- {{-- End menu builder --}} -->



                <!-- {{-- Start footer --}} -->
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Footer', $rolePermissions)))
                    <li
                        class="nav-item @if (request()->routeIs('admin.footer.logo_and_image')) active
                                    @elseif (request()->routeIs('admin.footer.content')) active
                                    @elseif (request()->routeIs('admin.footer.quick_links')) active @endif">
                        <a data-toggle="collapse" href="#footer">
                            <i class="fal fa-shoe-prints"></i>
                            <p>{{ 'Footer' }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="footer"
                            class="collapse @if (request()->routeIs('admin.footer.logo_and_image')) show
                                        @elseif (request()->routeIs('admin.footer.content')) show
                                        @elseif (request()->routeIs('admin.footer.quick_links')) show @endif">
                            <ul class="nav nav-collapse">
                                <li class="{{ request()->routeIs('admin.footer.logo_and_image') ? 'active' : '' }}">
                                    <a href="{{ route('admin.footer.logo_and_image') }}">
                                        <span class="sub-item">{{ 'Logo & Image' }}</span>
                                    </a>
                                </li>

                                <li class="{{ request()->routeIs('admin.footer.content') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('admin.footer.content', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ 'Content' }}</span>
                                    </a>
                                </li>

                                <li class="{{ request()->routeIs('admin.footer.quick_links') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('admin.footer.quick_links', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ 'Quick Links' }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
                <!-- {{-- End footer --}} -->



                <!-- {{-- Start custom page --}} -->
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Custom Pages', $rolePermissions)))
                    <li
                        class="nav-item @if (request()->routeIs('admin.custom_pages')) active
                                            @elseif (request()->routeIs('admin.custom_pages.create_page')) active
                                            @elseif (request()->routeIs('admin.custom_pages.edit_page')) active @endif">
                        <a href="{{ route('admin.custom_pages', ['language' => $defaultLang->code]) }}">
                            <i class="la flaticon-file"></i>
                            <p>{{ 'Custom Pages' }}</p>
                        </a>
                    </li>
                @endif
                <!-- {{-- End custom page --}} -->

                <!-- ================================================================================================================== -->
                <!-- ================================================================================================================== -->

                <!-- {{-- Start Agnet  --}} -->
                <!-- @php
                    $stafftUnread = Helper::unreadStaffs();

                    $staff_unread_class = '';
                    if ($stafftUnread != 0) {
                        $staff_unread_class = 'text-danger-glow blink';
                    }
                @endphp
                    @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Agent', $rolePermissions)))
<li class="nav-item  @if (request()->routeIs('admin.agent_management.index')) active @endif">
                            <a href="{{ route('admin.agent_management.index') }}">
                                <i class="fal fa-users-cog"></i>
                                <p> {{ 'Staffs' }} </p><i class="{{ $staff_unread_class }}" style="margin-left: 10px;"><b>{{ $stafftUnread != 0 ? $stafftUnread : '' }}</b></i>
                            </a>
                        </li>
@endif -->
                <!-- {{-- end agent  --}} -->


            </ul>
        </div>
    </div>
</div>
