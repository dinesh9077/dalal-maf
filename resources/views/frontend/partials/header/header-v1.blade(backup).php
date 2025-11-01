
<div class="request-loader">
    <img src="{{ asset('assets/img/loaders.gif') }}">
</div>

<header class="header-area header-1 @if (!request()->routeIs('index')) header-static @endif" data-aos="slide-down">

    <div class="mobile-menu">
        <div class="container" style="padding: 10px;">
            @if (!empty($websiteInfo->logo))
                    <a href="{{ route('index') }}" >
                        <img src="{{ asset('assets/img/' . $websiteInfo->logo) }}" style="width : 170px;">
                    </a>
            @endif
            <div class="mobile-menu-wrapper" s>
            </div>
        </div>
    </div>

    <div class="main-responsive-nav">
        <div class="container">
            <div class="logo">
                @if (!empty($websiteInfo->logo))
                    <a href="{{ route('index') }}">
                        <img src="{{ asset('assets/img/' . $websiteInfo->logo) }}">
                    </a>
                @endif
            </div>
            <button class="menu-toggler" type="button">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>

    <div class="main-navbar new-main-navbar">
        <div class="container">
            <nav class="navbar navbar-expand-lg" >
             
                @if (!empty($websiteInfo->logo))
                    <a href="{{ route('index') }}" class="navbar-brand"  style="    width: 175px;">
                        <img src="{{ asset('assets/img/' . $websiteInfo->logo) }}">
                    </a>
                @endif
             
                <div class="collapse navbar-collapse">
                    @php $menuDatas = json_decode($menuInfos); @endphp

                    <ul id="mainMenu" class="navbar-nav mobile-item mx-auto">
                        @foreach ($menuDatas as $menuData)
                            @php $href = get_href($menuData); @endphp
                            @if (!property_exists($menuData, 'children'))
                              @php
                                $href = $menuData->text == 'Franchiese' ? route('frontend.properties',['purpose'=>'franchiese']) : $href;
                                $href = $menuData->text == 'Business For Sale' ? route('frontend.properties',['purpose'=>'business_for_sale']) : $href;
                              @endphp
                              <li class="nav-item">
                                  <a class="nav-link" href="{{ $href }}">{{ $menuData->text }}</a>
                              </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link toggle" href="{{ $href }}">{{ $menuData->text }}<i
                                            class="fal fa-angle-down"></i></a>
                                    <ul class="menu-dropdown">
                                        @php $childMenuDatas = $menuData->children; @endphp
                                        @foreach ($childMenuDatas as $childMenuData)
                                            @php $child_href = get_href($childMenuData); @endphp
                                            <li class="nav-item">
                                                <a class="nav-link"
                                                    href="{{ $child_href }}">{{ $childMenuData->text }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                        @endforeach


                    </ul>
                </div>
                <div class="more-option mobile-item">
                  
                    <div class="item">
                        <div class="dropdown">
                            <button class="btn-cus-header dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                @if (!Auth::guard('web')->check())
                                    {{ __('Customer') }}
                                @else
                                    {{ Auth::guard('web')->user()->username }}
                                @endif
                            </button>
                            <ul class="dropdown-menu" style="border-radius: 10px;">
                                @if (!Auth::guard('web')->check())
                                    <li><a class="dropdown-item"
                                            href="{{ route('user.login') }}">{{ __('Login') }}</a></li>
                                    <li><a class="dropdown-item"
                                            href="{{ route('user.signup') }}">{{ __('Signup') }}</a></li>
                                @else
                                    <li><a class="dropdown-item"
                                            href="{{ route('user.dashboard') }}">{{ __('Dashboard') }}</a></li>
                                    <li><a class="dropdown-item"
                                            href="{{ route('user.logout') }}">{{ __('Logout') }}</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="item">
                        <div class="dropdown">
                            <button class="btn-cus-header dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                @if (!Auth::guard('vendor')->check())
                                    {{ __('Post Properties') }}
                                @else
                                    {{ Auth::guard('vendor')->user()->username }}
                                @endif
                            </button>
                            <ul class="dropdown-menu" style="border-radius: 10px;">
                                @if (!Auth::guard('vendor')->check())
                                    <li><a class="dropdown-item"
                                            href="{{ route('vendor.login') }}">{{ __('Login') }}</a></li>
                                   
                                @else
                                    <li><a class="dropdown-item"
                                            href="{{ route('vendor.property_management.type') }}">{{ __('Post Property ') }}</a></li>

                                    <li><a class="dropdown-item"
                                            href="{{ route('vendor.dashboard') }}">{{ __('Dashboard') }}</a></li>

                                    <li><a class="dropdown-item"
                                            href="{{ route('vendor.logout') }}">{{ __('Logout') }}</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</header>
<!-- Header End -->


<!-- 
.style__postContainerTab {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 164px;
    height: 32px;
    background: #fff;
    border: 1px solid #e5f3fd;
    border-radius: 8px;
    cursor: pointer;
    position: relative;
    transition: spring .3s ease
}

.style__postTab::before {
    content: "";
    position: absolute;
    top: -4px;
    left: -4px;
    right: -4px;
    bottom: -4px;
    border: 1px solid hsla(0,0%,100%,.5647058824);
    border-radius: 10px;
    opacity: 0;
    pointer-events: none
}

.style__postContainerTab::before {
    content: "";
    position: absolute;
    top: -7px;
    left: -7px;
    right: -7px;
    bottom: -7px;
    border: 1px solid hsla(0,0%,100%,.5647058824);
    border-radius: 12px;
    opacity: 0;
    pointer-events: none
}

.style__postContainerTab::after {
    content: "";
    position: absolute;
    top: -10px;
    left: -10px;
    right: -10px;
    bottom: -10px;
    border: 1px solid hsla(0,0%,100%,.5647058824);
    border-radius: 14px;
    opacity: 0;
    pointer-events: none
}

.style__postContainerTab:hover::before {
    animation: style__spring .8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    opacity: .5
}

.style__postContainerTab:hover::after {
    animation: style__spring .8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    opacity: .2
}

.style__postContainerTab:hover .style__postTab::before {
    animation: style__spring .8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    opacity: 1
} -->