<div class="request-loader">
    <img src="{{ asset('assets/img/loaders.gif') }}">
</div>

<header class="header-area header-1 @if (!request()->routeIs('index')) header-static @endif" data-aos="slide-down">

    <div class="mobile-menu">
        <div class="container" style="padding: 10px;">
            @if (!empty($websiteInfo->logo))
            <a href="{{ route('index') }}">
                <img src="{{ asset('assets/img/' . $websiteInfo->logo) }}" style="width : 170px;">
            </a>
            @endif
            <div class="mobile-menu-wrapper">
            </div>
        </div>
    </div>

    @php
    $user = Auth::guard('web')->user();
    $vendor = Auth::guard('vendor')->user();
    $agent = Auth::guard('agent')->user();

    // Determine auth type
    if ($vendor) {
    $authType = 'vendor';
    $authUser = $vendor;
    $dashboardRoute = route('vendor.dashboard');
    $logoutRoute = route('vendor.logout');
    } elseif ($user) {
    $authType = 'user';
    $authUser = $user;
    $dashboardRoute = route('user.dashboard');
    $logoutRoute = route('user.logout');
    } elseif ($agent) {
    $authType = 'agent';
    $authUser = $agent;
    $dashboardRoute = route('agent.dashboard');
    $logoutRoute = route('agent.logout');
    } else {
    $authType = 'guest';
    $authUser = null;
    }

    // First letter for avatar
    $initial = $authUser ? strtoupper(substr($authUser->username ?? 'U', 0, 1)) : null;

    // Post Property route
    if ($authType === 'vendor' && $vendor->email) {
    $postPropertyRoute = route('vendor.property_management.type');
    } elseif ($authType === 'user' && $user->email) {
    $postPropertyRoute = route('user.property_management.type');
    } elseif ($authType === 'agent' && $agent->email) {
    $postPropertyRoute = route('agent.property_management.type');
    } else {
    $postPropertyRoute = route('user.signup');
    }
    @endphp

    <div class="main-responsive-nav ">
        <div class="container">
            <div class="logo">
                @if (!empty($websiteInfo->logo))
                <a href="{{ route('index') }}">
                    <img src="{{ asset('assets/img/' . $websiteInfo->logo) }}">
                </a>
                @endif
            </div>
            <button class="menu-toggler new-menu-toggals" type="button">
                <span></span>
                <span></span>
                <span></span>
            </button>
            @if ($authType === 'guest')
            <button type="button" class="style__postContainerTab d-block d-sm-none " data-bs-toggle="modal"
                data-bs-target="#customerPhoneModal" data-action="login" id="openCustomerPhoneModal"
                style="line-height:20px;">
                <span class="style__postTab">{{ __('Sign In') }}</span>
            </button>
            @else
            <a class="style__postContainerTab p-sn-dds" href="{{ $postPropertyRoute }}">
                <span class="style__postTab">{{ __('Post Property') }}</span>
            </a>
            @endif
        </div>
    </div>

    
    <div id="mainNavbar" class="main-navbar new-main-navbar navbar-transparent">
        <div class="container">
            <nav class="navbar navbar-expand-lg">

                @if (!empty($websiteInfo->logo))
                <a href="{{ route('index') }}" class="navbar-brand" style="    width: 140px;">
                    <img src="{{ asset('assets/img/' . $websiteInfo->logo) }}">
                </a>
                @endif

                @php use Illuminate\Support\Str; @endphp

                <div class="collapse navbar-collapse">
                    @php $menuDatas = json_decode($menuInfos); @endphp
                    <ul id="mainMenu" class="navbar-nav mobile-item mx-auto">
                        @foreach ($menuDatas as $menuData)
                        @php
                        $href = get_href($menuData);

                        // Special route handling
                        if ($menuData->text == 'Franchiese') {
                        $href = route('frontend.properties', ['purpose' => 'franchiese']);
                        } elseif ($menuData->text == 'Business For Sale') {
                        $href = route('frontend.properties', ['purpose' => 'business_for_sale']);
                        }

                        // Detect active menu by URL or query
                        $currentUrl = url()->full();
                        $isActive =
                        $currentUrl === $href ||
                        (Str::contains($currentUrl, 'franchiese') && Str::contains($href, 'franchiese')) ||
                        (Str::contains($currentUrl, 'business_for_sale') &&
                        Str::contains($href, 'business_for_sale'))
                        ? 'active'
                        : '';
                        @endphp

                        @if (!property_exists($menuData, 'children'))
                        <li class="nav-item">
                            <a class="nav-link {{ $isActive }}" href="{{ $href }}">
                                {{ $menuData->text }}
                            </a>
                        </li>
                        @else
                        @php
                        $childMenuDatas = $menuData->children;
                        $hasActiveChild = collect($childMenuDatas)->contains(function ($child) use (
                        $currentUrl,
                        ) {
                        return $currentUrl === get_href($child);
                        });
                        $parentActive = $hasActiveChild ? 'active' : '';
                        @endphp

                        <li class="nav-item {{ $parentActive }}">
                            <a class="nav-link toggle {{ $parentActive }}" href="{{ $href }}">
                                {{ $menuData->text }} <i class="fal fa-angle-down"></i>
                            </a>
                            <ul class="menu-dropdown">
                                @foreach ($childMenuDatas as $childMenuData)
                                @php
                                $child_href = get_href($childMenuData);
                                $childActive = $currentUrl === $child_href ? 'active' : '';
                                @endphp
                                <li class="nav-item">
                                    <a class="nav-link {{ $childActive }}" href="{{ $child_href }}">
                                        {{ $childMenuData->text }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                        @endif
                        @endforeach
                    </ul>
                </div>



                <div class="more-option mobile-item d-flex align-items-center gap-3">


                    {{-- Account / Sign In --}}
                    <div class="item">
                        @if ($authType === 'guest')
                        <button type="button" class="style__postContainerTab" id="openCustomerPhoneModal"
                            data-bs-toggle="modal" data-bs-target="#customerPhoneModal" data-action="login">
                            <span class="style__postTab">{{ __('Sign In') }}</span>
                        </button>
                        @elseif ($authType === 'user' && empty($user->username))
                        <a href="{{ route('user.signup') }}" class="style__postContainerTab">
                            <span class="style__postTab">{{ __('Sign Up') }}</span>
                        </a>
                        @else
                        <div class="dropdown">
                            <button type="button" class="style__postContainerTab" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <span class="style__postTab">{{ $initial }}</span>
                            </button>
                            <ul class="dropdown-menu" style="border-radius: 10px;">
                                <li><a class="dropdown-item" href="{{ $dashboardRoute }}">{{ __('Dashboard') }}</a></li>
                                <li><a class="dropdown-item" href="{{ $logoutRoute }}">{{ __('Logout') }}</a>
                                </li>
                            </ul>
                        </div>
                        @endif
                    </div>


                    {{-- Post Property --}}
                    <div class="item">
                        @if ($authType === 'guest')
                        <button type="button" class="style__postContainerTab" data-bs-toggle="modal"
                            data-bs-target="#customerPhoneModal" data-action="post_property">
                            <span class="style__postTab">{{ __('Post Property') }}</span>
                        </button>
                        @else
                        <a class="style__postContainerTab" href="{{ $postPropertyRoute }}">
                            <span class="style__postTab">{{ __('Post Property') }}</span>
                        </a>
                        @endif
                    </div>

                    {{-- Wishlist Icon --}}
                    <div class="item position-relative">
                        @if ($authType != 'guest')
                        <a href="{{ $authType === 'user'
                                ? route('user.wishlist')
                                : ($authType === 'agent'
                                    ? route('vendor.wishlist')
                                    : route('vendor.wishlist')) }}" class="btn-wishlist-header position-relative"
                            title="{{ __('My Wishlist') }}">
                            <i class="fas fa-heart text-danger"></i>
                            <span class="wishlist-count-html">0</span>
                        </a>
                        @endif
                    </div>
                </div>

                {{-- Small CSS for better UI --}}
                <style>
                .more-option {
                    display: flex;
                    align-items: center;
                    gap: 12px;
                }

                .btn-wishlist-header {
                    background: transparent;
                    border: none;
                    position: relative;
                    font-size: 20px;
                    cursor: pointer;
                    color: #333;
                }

                .btn-wishlist-header:hover i {
                    color: #e74c3c;
                }

                .wishlist-count-html {
                    position: absolute;
                    top: -5px;
                    right: -8px;
                    background: #e74c3c;
                    color: #fff;
                    border-radius: 50%;
                    font-size: 11px;
                    padding: 2px 5px;
                    min-width: 16px;
                    text-align: center;
                    line-height: 1;
                }

                .main-navbar.navbar-transparent {
                    background: linear-gradient(180deg, rgba(0, 0, 0, 0.9), rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0)) !important;
                    transition: background 0.3s ease;
                    height:110px;
                }

                .main-navbar.navbar-scrolled {
                    background: #6c603c !important;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                }

                .main-navbar {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    z-index: 1030;
                }
                </style>


            </nav>
        </div>
    </div>
</header>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.getElementById('mainNavbar');
    const scrollThreshold = 50;

    if (navbar) {
        function checkScroll() {
            if (window.scrollY > scrollThreshold) {
                navbar.classList.remove('navbar-transparent');
                navbar.classList.add('navbar-scrolled');
            } else {
                navbar.classList.add('navbar-transparent');
                navbar.classList.remove('navbar-scrolled');
            }
        }

        checkScroll();

        window.addEventListener('scroll', checkScroll);
    }
});
</script>
@include('frontend.partials.loginmodel')