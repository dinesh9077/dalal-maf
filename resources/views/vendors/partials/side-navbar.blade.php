<div class="sidebar sidebar-style-2"
    data-background-color="{{ Session::get('vendor_theme_version') == 'light' ? 'white' : 'dark2' }}">
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <div class="user">
                <div class="avatar-sm float-left mr-2">
                    @if (Auth::guard('vendor')->user()->photo != null)
                        <img src="{{ asset('assets/admin/img/vendor-photo/' . Auth::guard('vendor')->user()->photo) }}"
                            alt="Vendor Image" class="avatar-img rounded-circle">
                    @else
                        <img src="{{ asset('assets/img/blank-user.jpg') }}" alt=""
                            class="avatar-img rounded-circle">
                    @endif
                </div>

                <div class="info">
                    <a data-toggle="collapse" href="#adminProfileMenu" aria-expanded="true">
                        <span>
                            {{ Auth::guard('vendor')->user()->username }}
                            <span class="user-level">{{ Auth::guard('vendor')->user()->user_type }}</span>
                            <!-- <span class="caret"></span> -->
                        </span>
                    </a>

                    <div class="clearfix"></div>

                    <!-- <div class="collapse in" id="adminProfileMenu">
                        <ul class="nav">
                            <li>
                                <a href="{{ route('vendor.edit.profile') }}">
                                    <span class="link-collapse">{{ __('Edit Profile') }}</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('vendor.change_password') }}">
                                    <span class="link-collapse">{{ __('Change Password') }}</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('vendor.logout') }}">
                                    <span class="link-collapse">{{ __('Logout') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div> -->
                </div>
            </div>


            <ul class="nav nav-primary">
                {{-- search --}}
                <div class="row mb-3">
                    <div class="col-12">
                        <form>
                            <div class="form-group py-0">
                                <input name="term" type="text" class="form-control sidebar-search ltr"
                                    placeholder="Search Menu Here...">
                            </div>
                        </form>
                    </div>
                </div>

                {{-- dashboard --}}
                <li class="nav-item @if (request()->routeIs('vendor.dashboard')) active @endif">
                    <a href="{{ route('vendor.dashboard') }}">
                        <i class="la flaticon-paint-palette"></i>
                        <p>{{ __('Dashboard') }}</p>
                    </a>
                </li>


                @if ($userCurrentPackage)
                    <li
                        class="nav-item
                     @if (request()->routeIs('vendor.property_management.properties')) active
                     @elseif(request()->routeIs('vendor.featured_property.index')) active
                      @elseif(request()->routeIs('vendor.property_management.create_property')) active
                      @elseif(request()->routeIs('vendor.property_management.type')) active
                      @elseif(request()->routeIs('vendor.property_management.edit')) active @endif">
                        <a data-toggle="collapse" href="#propertyManagement">
                            <i class="fas fa-home"></i>
                            <p>{{ __('Property Management') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="propertyManagement"
                            class="collapse
                              @if (request()->routeIs('vendor.property_management.properties')) show
                              @elseif(request()->routeIs('vendor.property_management.type')) show
                              @elseif(request()->routeIs('vendor.property_management.create_property')) show
                              @elseif(request()->routeIs('vendor.property_management.edit')) show @endif
                              ">
                            <ul class="nav nav-collapse">

                                <li
                                    class="{{ request()->routeIs('vendor.property_management.create_property') || request()->routeIs('vendor.property_management.type') ? 'active' : '' }}">
                                    <a href="{{ route('vendor.property_management.type') }}">
                                        <span class="sub-item">{{ __('Add Property') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="{{ request()->routeIs('vendor.property_management.properties') || request()->routeIs('vendor.property_management.edit') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('vendor.property_management.properties', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('Manage Properties') }} </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                            {{-- Property inventory --}}

                    <li class="nav-item
                     @if (request()->routeIs('vendor.property_inventory.create_property')) active
                     @elseif (request()->routeIs('vendor.property_inventory.properties')) active
                     @elseif (request()->routeIs('vendor.property_inventory.edit')) active
                     @elseif (request()->routeIs('vendor.property_inventory.settings')) active
                      @elseif(request()->routeIs('vendor.property_inventory.type')) active
                      @elseif(request()->routeIs('vendor.property_inventory.manage_status_property')) active @endif">
                        <a data-toggle="collapse" href="#inventoryManagement">
                            <i class="far fa-home"></i>
                            <p>{{ __('Property inventory') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="inventoryManagement"
                            class="collapse
                              @if (request()->routeIs('vendor.property_inventory.create_property')) show
                              @elseif (request()->routeIs('vendor.property_inventory.type')) show
                              @elseif (request()->routeIs('vendor.property_inventory.properties')) show
                              @elseif (request()->routeIs('vendor.property_inventory.settings')) show
                              @elseif (request()->routeIs('vendor.property_inventory.edit')) show
                              @elseif (request()->routeIs('vendor.property_inventory.manage_status_property')) show
                              @endif
                              ">
                            <ul class="nav nav-collapse">
                                <li
                                    class="{{ request()->routeIs('vendor.property_inventory.create_property') || request()->routeIs('vendor.property_inventory.type') ? 'active' : '' }}">
                                    <a href="{{ route('vendor.property_inventory.type') }}">
                                        <span class="sub-item">{{ __('Add Inventory') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="{{ request()->routeIs('vendor.property_inventory.properties') ||
                                    request()->routeIs('vendor.property_inventory.edit')
                                        ? 'active'
                                        : '' }}">
                                    <a
                                        href="{{ route('vendor.property_inventory.properties', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('Manage Inventory') }}</span>
                                    </a>
                                </li>
                                 <li
                                    class="{{ request()->routeIs('vendor.property_inventory.manage_status_property') ? 'active' : '' }}">
                                    <a href="{{ route('vendor.property_inventory.manage_status_property') }}">
                                        <span class="sub-item">{{ __('Manage Inventory Status') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="{{ request()->routeIs('vendor.property_inventory.converted_customer') ? 'active' : '' }}">
                                    <a href="{{ route('vendor.property_inventory.converted_customer') }}">
                                        <span class="sub-item">{{ __('Converted Customer') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                  {{-- end property management  --}}
                @endif
                {{-- @if ($userCurrentPackage)
                    <li class="nav-item  @if (request()->routeIs('vendor.property_message.index')) active @endif">
                        <a href="{{ route('vendor.property_message.index') }}">
                            <i class="fas fa-comment"></i>
                            <p>{{ __('Property Messages') }}</p>
                        </a>
                    </li>
                @endif --}}
                <li class="nav-item  @if (request()->routeIs('vendor.inquiry.index')) active @endif">
                    <a href="{{ route('vendor.inquiry.index') }}">
                        <i class="fas fa-comment"></i>
                        <p>{{ __('Sent Inquiry') }}</p>
                    </a>
                </li>
                <li class="nav-item  @if (request()->routeIs('vendor.wishlist')) active @endif">
                    <a href="{{ route('vendor.wishlist') }}">
                        <i class="fas fa-heart"></i>
                        <p>{{ __('Wishlist') }}</p>
                    </a>
                </li>

                @if ($userCurrentPackage)
                    <li
                        class="nav-item
                     @if (request()->routeIs('vendor.project_management.projects')) active
                     @elseif (request()->routeIs('vendor.project_management.create_project')) active
                     @elseif (request()->routeIs('vendor.project_management.edit')) active @elseif(request()->routeIs('vendor.project_management.project_types')) active @endif">
                        <a data-toggle="collapse" href="#projectManagement">
                            <i class="fas fa-city"></i>
                            <p>{{ __('Project Management') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="projectManagement"
                            class="collapse
                                @if (request()->routeIs('vendor.project_management.create_project')) show
                                @elseif (request()->routeIs('vendor.project_management.projects')) show
                                @elseif (request()->routeIs('vendor.project_management.edit')) show
                                @elseif(request()->routeIs('vendor.project_management.project_types')) show @endif
                                ">
                            <ul class="nav nav-collapse">

                                <li
                                    class="{{ request()->routeIs('vendor.project_management.create_project') ? 'active' : '' }}">
                                    <a href="{{ route('vendor.project_management.create_project') }}">
                                        <span class="sub-item">{{ __('Add Project') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="{{ request()->routeIs('vendor.project_management.edit') || request()->routeIs('vendor.project_management.projects') || request()->routeIs('vendor.project_management.project_types') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('vendor.project_management.projects', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('Manage Projects') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    {{-- Project Management end  --}}

                    {{-- Start Customer  --}}
                    <li class="nav-item  @if (request()->routeIs('vendor.customer_management.list')) active @endif">
                        <a href="{{ route('vendor.customer_management.list') }}">
                            <i class="fal fa-users-cog"></i>
                            <p> {{ 'Inventory Customers' }} </p>
                        </a>
                    </li>
                   {{-- end Customer  --}}

                    <li class="nav-item  @if (request()->routeIs('vendor.agent_management.index')) active @endif">
                        <a href="{{ route('vendor.agent_management.index') }}">
                            <i class="fal fa-users-cog"></i>
                            <p>{{ __('Staffs') }}</p>
                        </a>
                    </li>

                    {{-- Start Account --}}
                      <li class="nav-item @if (request()->routeIs('vendor.sales-bill.index')) active @endif">
                          <a href="{{ route('vendor.sales-bill.index') }}">
                              <i class="fal fa-university"></i>
                              <p>{{ 'Account' }}</p>
                          </a>
                      </li>
                    {{-- end Account  --}}
                    @php
                        $support_status = DB::table('support_ticket_statuses')->first();
                    @endphp
                    @if ($support_status->support_ticket_status == 'active')
                        {{-- Support Ticket --}}
                        <li
                            class="nav-item @if (request()->routeIs('vendor.support_tickets')) active
                              @elseif (request()->routeIs('vendor.support_tickets.message')) active
                              @elseif (request()->routeIs('vendor.support_ticket.create')) active @endif">
                            <a data-toggle="collapse" href="#support_ticket">
                                <i class="la flaticon-web-1"></i>
                                <p>{{ __('Support Tickets') }}</p>
                                <span class="caret"></span>
                            </a>

                            <div id="support_ticket"
                                class="collapse
                                  @if (request()->routeIs('vendor.support_tickets')) show
                                  @elseif (request()->routeIs('vendor.support_tickets.message')) show
                                  @elseif (request()->routeIs('vendor.support_ticket.create')) show @endif">
                                <ul class="nav nav-collapse">

                                    <li
                                        class="{{ request()->routeIs('vendor.support_tickets') && empty(request()->input('status')) ? 'active' : '' }}">
                                        <a href="{{ route('vendor.support_tickets') }}">
                                            <span class="sub-item">{{ __('All Tickets') }}</span>
                                        </a>
                                    </li>
                                    <li
                                        class="{{ request()->routeIs('vendor.support_ticket.create') ? 'active' : '' }}">
                                        <a href="{{ route('vendor.support_ticket.create') }}">
                                            <span class="sub-item">{{ __('Add a Ticket') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endif
                @endif


                {{-- dashboard --}}
                <li
                    class="nav-item
                  @if (request()->routeIs('vendor.plan.extend.index')) active
                  @elseif (request()->routeIs('vendor.plan.extend.checkout')) active @endif">
                    <a href="{{ route('vendor.plan.extend.index') }}">
                        <i class="fal fa-lightbulb-dollar"></i>
                        <p>{{ __('Buy Plan') }}</p>
                    </a>
                </li>

                <li class="nav-item @if (request()->routeIs('vendor.payment_log')) active @endif">
                    <a href="{{ route('vendor.payment_log') }}">
                        <i class="fas fa-list-ol"></i>
                        <p>{{ __('Payment Logs') }}</p>
                    </a>
                </li>
                {{-- <li class="nav-item @if (request()->routeIs('vendor.edit.profile')) active @endif">
                    <a href="{{ route('vendor.edit.profile') }}">
                        <i class="fal fa-user-edit"></i>
                        <p>{{ __('Edit Profile') }}</p>
                    </a>
                </li>
                <li class="nav-item @if (request()->routeIs('vendor.change_password')) active @endif">
                    <a href="{{ route('vendor.change_password') }}">
                        <i class="fal fa-key"></i>
                        <p>{{ __('Change Password') }}</p>
                    </a>
                </li>

                <li class="nav-item @if (request()->routeIs('vendor.logout')) active @endif">
                    <a href="{{ route('vendor.logout') }}">
                        <i class="fal fa-sign-out"></i>
                        <p>{{ __('Logout') }}</p>
                    </a>
                </li> --}}
            </ul>
        </div>
    </div>
</div>
