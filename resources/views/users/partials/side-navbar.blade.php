<div class="sidebar sidebar-style-2"
    data-background-color="{{'white2' }}">
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <div class="user">
                <div class="avatar-sm float-left mr-2">
                    @if (Auth::guard('web')->user()->image != null)
                        <img src="{{ asset('assets/img/users/' . Auth::guard('web')->user()->image) }}" alt="Web Image"
                            class="avatar-img rounded-circle">
                    @else
                        <img src="{{ asset('assets/img/blank-user.jpg') }}" alt=""
                            class="avatar-img rounded-circle">
                    @endif
                </div>

                <div class="info">
                    <a data-toggle="collapse" href="#adminProfileMenu" aria-expanded="true">
                        <span>
                            {{ Auth::guard('web')->user()->username }}
                            <span class="user-level">{{ Auth::guard('web')->user()->user_type }}</span>
                            <span class="caret"></span>
                        </span>
                    </a>

                    <div class="clearfix"></div>

                    <div class="collapse in" id="adminProfileMenu">
                        <ul class="nav">
                            <li>
                                <a href="{{ route('user.edit_profile') }}">
                                    <span class="link-collapse">{{ __('Edit Profile') }}</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('user.change_password') }}">
                                    <span class="link-collapse">{{ __('Change Password') }}</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('user.logout') }}">
                                    <span class="link-collapse">{{ __('Logout') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
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
                <li class="nav-item @if (request()->routeIs('user.dashboard')) active @endif">
                    <a href="{{ route('user.dashboard') }}">
                        <i class="la flaticon-paint-palette"></i>
                        <p>{{ __('Dashboard') }}</p>
                    </a>
                </li>

                <li
                    class="nav-item
                     @if (request()->routeIs('user.property_management.create_property')) active
                     @elseif (request()->routeIs('user.property_management.properties')) active
                     @elseif (request()->routeIs('user.property_management.edit')) active
                     @elseif (request()->routeIs('user.property_management.type')) active @endif">
                    <a data-toggle="collapse" href="#propertyManagement">
                        <i class="fal fa-home"></i>
                        <p>{{ __('Property Management') }}</p>
                        <span class="caret"></span>
                    </a>

                    <div id="propertyManagement"
                        class="collapse
              @if (request()->routeIs('user.property_management.create_property')) show
               @elseif (request()->routeIs('user.property_management.properties')) show
               @elseif (request()->routeIs('user.property_management.edit')) show
               @elseif (request()->routeIs('user.property_management.type')) show @endif
              ">
                        <ul class="nav nav-collapse">

                            <li
                                class="{{ request()->routeIs('user.property_management.create_property') || request()->routeIs('user.property_management.type') ? 'active' : '' }}">
                                <a href="{{ route('user.property_management.type') }}">
                                    <span class="sub-item">{{ __('Add Property') }}</span>
                                </a>
                            </li>

                            <li
                                class="@if (request()->routeIs('user.property_management.properties')) active
            @elseif (request()->routeIs('user.property_management.edit')) active @endif">
                                <a href="{{ route('user.property_management.properties') }}">
                                    <span class="sub-item">{{ __('Manage Properties') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item  @if (request()->routeIs('user.inquiry.index')) active @endif">
                    <a href="{{ route('user.inquiry.index') }}">
                        <i class="fas fa-comment"></i>
                        <p>{{ __('Sent Inquiry') }}</p>
                    </a>
                </li>

                <li class="nav-item @if (request()->routeIs('user.support_ticket')) active
                      @elseif (request()->routeIs('user.support_ticket.message')) active
                      @elseif (request()->routeIs('user.support_ticket.create')) active @endif">
                    <a data-toggle="collapse" href="#support_ticket">
                        <i class="la flaticon-web-1"></i>
                        <p>{{ __('Support Tickets') }}</p>
                        <span class="caret"></span>
                    </a>

                    <div id="support_ticket"
                        class="collapse
                          @if (request()->routeIs('user.support_ticket')) show
                          @elseif (request()->routeIs('user.support_ticket.message')) show
                          @elseif (request()->routeIs('user.support_ticket.create')) show @endif">
                        <ul class="nav nav-collapse">

                            <li
                                class="{{ request()->routeIs('user.support_ticket') && empty(request()->input('status')) ? 'active' : '' }}">
                                <a href="{{ route('user.support_ticket') }}">
                                    <span class="sub-item">{{ __('All Tickets') }}</span>
                                </a>
                            </li>
                            <li
                                class="{{ request()->routeIs('user.support_ticket.create') ? 'active' : '' }}">
                                <a href="{{ route('user.support_ticket.create') }}">
                                    <span class="sub-item">{{ __('Add a Ticket') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item  @if (request()->routeIs('user.wishlist')) active @endif">
                    <a href="{{ route('user.wishlist') }}">
                        <i class="fas fa-heart"></i>
                        <p>{{ __('My Wishlists ') }}</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
