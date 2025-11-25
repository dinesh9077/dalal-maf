<div class="main-header">
    <!-- Logo Header Start -->
    <div class="logo-header"
        data-background-color="{{ 'white2'}}">

        @if (!empty($websiteInfo->logo))
            <a href="{{ route('index') }}" class="logo" target="_blank">
               <img src="{{ asset('assets/front/images/new-images/Logo-black.png') }}" alt="logo" class="navbar-brand"
                    width="150">
            </a>
        @endif

        <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse"
            data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon">
                <i class="icon-menu"></i>
            </span>
        </button>
        <button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>

        <div class="nav-toggle">
            <button class="btn btn-toggle toggle-sidebar">
                <i class="icon-menu"></i>
            </button>
        </div>
    </div>
    <!-- Logo Header End -->

    <!-- Navbar Header Start -->
    <nav class="navbar navbar-header navbar-expand-lg"
        data-background-color="{{ 'white2' }}">
        <div class="container-fluid">
            <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">


                <li class="nav-item dropdown hidden-caret">
                    <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
                        <div class="avatar-sm">
                            @if (Auth::guard('web')->user()->image != null)
                                <img src="{{ asset('assets/img/users/' . Auth::guard('web')->user()->image) }}"
                                    alt="Vendor Image" class="avatar-img rounded-circle">
                            @else
                                <img src="{{ asset('assets/img/blank-user.jpg') }}" alt=""
                                    class="avatar-img rounded-circle">
                            @endif
                        </div>
                    </a>

                    <ul class="dropdown-menu dropdown-user animated fadeIn">
                        <div class="dropdown-user-scroll scrollbar-outer">
                            <li>
                                <div class="user-box">
                                    <div class="avatar-lg">
                                        @if (Auth::guard('web')->user()->image != null)
                                            <img src="{{ asset('assets/img/users/' . Auth::guard('web')->user()->image) }}"
                                                alt="User Image" class="avatar-img rounded-circle">
                                        @else
                                            <img src="{{ asset('assets/img/blank-user.jpg') }}" alt=""
                                                class="avatar-img rounded-circle">
                                        @endif
                                    </div>

                                    <div class="u-text">
                                        <h4>
                                            {{ Auth::guard('web')->user()->username }}
                                        </h4>
                                        <p class="text-muted">{{ Auth::guard('web')->user()->email }}</p>
                                    </div>
                                </div>
                            </li>

                            <li>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('user.edit_profile') }}">
                                    {{ __('Edit Profile') }}
                                </a>

                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('user.change_password') }}">
                                    {{ __('Change Password') }}
                                </a>

                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('user.logout') }}">
                                    {{ __('Logout') }}
                                </a>
                            </li>
                        </div>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <!-- Navbar Header End -->
</div>
