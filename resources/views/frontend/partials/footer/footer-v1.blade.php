<!-- Footer-area start -->
@if ($footerSectionStatus == 1)

<footer class="footer-area" style="background-color : #F9F6EE;">
    <div class="footer-top">
        <div class="container">

            <div class="row">
                <div class="col-lg-7">
                    <div class="footer-left-text">
                        <h3 class="header-title-footer">India’s Trusted Real Estate Platform
                        </h3>
                        <p class="header-description">{{ !empty($footerInfo) ? $footerInfo->about_company : '' }}</p>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="footer-right-btn">
                        @if (!empty($basicInfo->footer_logo))
                        <a href="{{ route('index') }}">
                            <img src="{{ asset('assets/img/' . $basicInfo->footer_logo) }}">
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row gx-xl-5 justify-content-xl-between footer-details-top">

                <div class="col-lg col-md-3 col-sm-12">
                    <div class="footer-widget">
                        <h4>{{ __('Company') }}</h4>
                        @if (count($quickLinkInfos) == 0)
                        <h6 class="">{{ __('No Link Found') . '!' }}</h6>
                        @else
                        <ul class="footer-links">
                            @php
                            $currentUrl = url()->current(); // Gets the full current URL
                            @endphp

                            @foreach ($quickLinkInfos as $quickLinkInfo)
                            @php
                            // Base URL
                            $baseUrl = url('/'); // like http://127.0.0.1:8006

                            // Construct full target URL from the link
                            $targetUrl = $baseUrl . '/' . ltrim($quickLinkInfo->url, '/');

                            // If current URL contains 'user' and matches this link (like user/dashboard -> dashboard)
                            if (Str::contains($currentUrl, '/user/') && Str::endsWith($currentUrl, $quickLinkInfo->url)) {
                            // Replace '/user/' with '/'
                            $targetUrl = str_replace('/user/', '/', $currentUrl);
                            }
                            @endphp

                            <li>
                                <a href="{{ $targetUrl }}">{{ $quickLinkInfo->title }}</a>
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                </div>
                @php
                $customPages = App\Models\CustomPage\PageContent::where('slug','privacy-policy')->first();
                $customPagesTerms = App\Models\CustomPage\PageContent::where('slug','terms-&-condition')->first();
                @endphp
                <div class="col-lg col-md-3 col-sm-12">
                    <div class="footer-widget">
                        <h4>{{ __('Solutions') }}</h4>
                        <ul class="footer-links">
                            <li>
                                <a
                                    href="{{route('frontend.projects')}}">{{ 'Projects' }}</a>
                            </li>
                            <li>
                                <a
                                    href="{{ route('vendor.property_management.type') }}">{{ 'Post Properties' }}</a>
                            </li>
                            <li>
                                @if($customPagesTerms)
                                <a
                                    href="{{ url($customPagesTerms->slug)}}">{{ 'Terms & Condition ' }}</a>
                                @else
                                <a
                                    href="#">{{ 'Terms & Condition ' }}</a>
                                @endif
                            </li>

                            <li>
                                @if($customPages)
                                <a
                                    href="{{ url($customPages->slug)}}">{{ 'Privacy Policy ' }}</a>
                                @else
                                <a
                                    href="#">{{ 'Privacy Policy ' }}</a>
                                @endif
                            </li>

                        </ul>
                    </div>
                </div>
                <div class="col-lg col-md-6 col-sm-12">
                    <div class="footer-widget">
                        <h4>{{ __('Connect With us') }}</h4>
                        <ul class="footer-links">
                            @if (!empty($basicInfo->contact_number))
                            <li>

                                <h1 class="my-2 mew-lines-add">Call Now</h1>
                                <a
                                    href="tel:{{ $basicInfo->contact_number }}">{{ $basicInfo->contact_number }}</a>
                            </li>
                            @endif
                            @if (!empty($basicInfo->email_address))
                            <li>

                                <h1 class="my-2 mew-lines-add">Existing Clients</h1>
                                <a
                                    href="mailto:{{ $basicInfo->email_address }}">{{ $basicInfo->email_address }}</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="col-lg col-md-6 col-sm-12">
                    <div class="footer-widget">
                        <h4>{{ __('Join our Community') }}</h4>
                        <div class="footer-right-logo sub-sec">
                            <input type="text" name="" placeholder="Enter Your mail ">
                            <button class="sub-btn">Subscribe</button>
                        </div>
                    </div>
                    <div class="social-links mx-2">
                        <h1 class="SL-links-title">keep in touch</h1>
                        @if (count($socialMediaInfos) > 0)
                        <div class="social-links-btn">
                            @foreach ($socialMediaInfos as $socialMediaInfo)
                            <a href="{{ $socialMediaInfo->url }}" target="_blank"><i
                                    class="{{ $socialMediaInfo->icon }}"></i></a>
                            @endforeach
                        </div>
                        @endif
                    </div>

                </div>
            </div>
            <div class="row mt-4">
                <div class="col-lg-12 col-md-4 col-sm-12 new-footer-appy">
                    <div class="app-logos mt-4" style="display : flex; gap: 15px;">
                        <a href="https://play.google.com/store" target="_blank">
                            <img src="{{ asset('assets/front/images/acrs-imag/playstore (1).png') }}" alt="playstore">
                        </a>
                        <a href="https://play.google.com/store" target="_blank">
                            <img src="{{ asset('assets/front/images/acrs-imag/appstore.png') }}" alt="appstore" style="    height: 45px;">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copy-right-area ">
        <div class="container">
            <div class="copy-right-content" style="color: black;">
                <span> {!! @$footerInfo->copyright_text !!} </span>
            </div>
        </div>
    </div>
</footer>
@endif
<!-- Footer-area end-->