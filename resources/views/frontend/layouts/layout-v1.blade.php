<!DOCTYPE html>
<html lang="xxx" dir="{{ $currentLanguageInfo->direction == 1 ? 'rtl' : '' }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="KreativDev">

    <meta name="keywords" content="@yield('metaKeywords')">
    <meta name="description" content="@yield('metaDescription')">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta property="og:type" content="website">
    @yield('og:tag')
    {{-- title --}}
    <title>@yield('pageHeading') {{ '| ' . $websiteInfo->website_title }}</title>
    {{-- fav icon --}}
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/img/' . $websiteInfo->favicon) }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/img/' . $websiteInfo->favicon) }}">
    @php
    $primaryColor = 'F57F4B';
    $secoundaryColor = '255056';
    // check, whether color has '#' or not, will return 0 or 1
    function checkColorCode($color)
    {
    return preg_match('/^#[a-f0-9]{6}/i', $color);
    }

    // if, primary color value does not contain '#', then add '#' before color value
    if (isset($primaryColor) && checkColorCode($primaryColor) == 0 && checkColorCode($secoundaryColor) == 0) {
    $primaryColor = '#' . $primaryColor;
    $secoundaryColor = '#' . $secoundaryColor;
    }

    // change decimal point into hex value for opacity
    function rgb($color = null)
    {
    if (!$color) {
    echo '';
    }
    $hex = htmlspecialchars($color);
    [$r, $g, $b] = sscanf($hex, '#%02x%02x%02x');
    echo "$r, $g, $b";
    }
    @endphp
    @includeIf('frontend.partials.styles.styles-v1')
    <style>
    :root {
        --color-primary: {
                {
                $primaryColor
            }
        }

        ;

        --color-primary-rgb: {
                {
                rgb(htmlspecialchars($primaryColor))
            }
        }

        ;

        --color-secondary: {
                {
                $secoundaryColor
            }
        }

        ;

        --color-secondary-rgb: {
                {
                rgb(htmlspecialchars($secoundaryColor))
            }
        }

        ;
    }

    .loader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 10000;
    }

    .spinner {
        border: 6px solid #f3f3f3;
        border-top: 6px solid #3498db;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .mobile-bottom-menu {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
        border-top: 1px solid #dcdcdc;
        box-shadow: 0 -3px 10px rgba(0, 0, 0, 0.1);
        z-index: 999;
        padding: 12px 0;
        gap: 25px;
    }

    .menu-item {
        text-align: center;
        text-decoration: none;
        color: #555;
        font-size: 10px;
        display: flex;
        flex-direction: column;
        align-items: center;
        transition: color 0.3s ease;
        margin: 0 1px;
        font-family:'Open Sans', sans-serif;
        font-weight:500;
    }

    .menu-item i {
        font-size: 18px;
        margin-bottom: 1px;
    }

    .menu-item.active,
    .menu-item:hover {
        color: #6c603c;
    }

    .floating-plus-btn {
        position: fixed;
        bottom: 128px;
        right: 8px;
        background: #6c603c;
        color: #fff;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);
        font-size: 18px;
        z-index: 999;
        transition: all 0.3s ease;
    }

    .floating-plus-btn:hover {
        background: #4f4626;
        transform: scale(1.05);
    }

    @media (min-width: 768px) {

        .mobile-bottom-menu,
        .floating-plus-btn {
            display: none;
        }
    }

    @media (max-width: 767px) {
        body {
            padding-bottom: 80px;
        }
    }

    :root {
        --bg: #0f1724;
        /* dark */
        --accent: #ffb86b;
        /* warm builder color */
        --muted: #9aa4b2;
    }

    .loader-wrap {
        position: fixed;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, 0.8);
        z-index: 9999;
        flex-direction: column;
        gap: 18px;
        padding: 24px;
    }

    .stage {
        width: 340px;
        max-width: 90%;
        height: 240px;
        display: grid;
        place-items: center;
    }

    svg {
        width: 100%;
        height: 100%;
        overflow: visible
    }

    .percent {
        font-family: Inter, system-ui, sans-serif;
        color: var(--muted);
        font-size: 14px;
    }

    .percent strong {
        color: var(--accent);
        font-size: 20px;
        margin-left: 8px
    }

    /* small shadowed card behind svg */
    .card {
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.02), rgba(255, 255, 255, 0.01));
        border-radius: 14px;
        padding: 18px;
        box-shadow: 0 8px 30px rgba(2, 6, 23, 0.6);
        backdrop-filter: blur(6px);
    }

    /* path base style */
    .house-path {
        fill: none;
        stroke: var(--muted);
        stroke-width: 4;
        stroke-linecap: round;
        stroke-linejoin: round
    }

    .house-path.reveal {
        stroke: var(--accent)
    }

    .fill-when-done {
        fill: transparent
    }

    /* subtle builder animation for complete parts */
    .done {
        fill: var(--accent);
        opacity: .15;
        transition: opacity .4s ease
    }

    /* loader small label */
    .label {
        color: var(--muted);
        font-size: 13px
    }

    /* hide when complete - user may remove this in integration */
    .hidden {
        opacity: 0;
        pointer-events: none;
        transform: translateY(-6px);
        transition: all .5s ease
    }
    </style>
</head>


<body dir="{{ $currentLanguageInfo->direction == 1 ? 'rtl' : '' }}">
    <!-- <div id="loader" class="loader">
			<div class="spinner"></div>
		</div> -->

    <div class="loader-wrap" id="loader">



        <div style="width : 50px;">
            <!-- SVG house: composed of multiple paths so we can "draw" them step-by-step -->
            <svg viewBox="0 0 200 140" id="houseSVG" aria-hidden>
                <!-- foundation / ground line -->
                <path id="p-foundation" class="house-path" d="M10 130 H190" />

                <!-- walls rectangle -->
                <path id="p-walls" class="house-path" d="M40 90 H160 V40 H40 Z" />

                <!-- roof -->
                <path id="p-roof" class="house-path" d="M20 55 L100 10 L180 55 Z" />

                <!-- chimney -->
                <path id="p-chimney" class="house-path" d="M130 18 V2" />

                <!-- door -->
                <path id="p-door" class="house-path" d="M92 90 V60 H108 V90" />

                <!-- left window -->
                <path id="p-win1" class="house-path" d="M52 60 H76 V76 H52 Z" />

                <!-- right window -->
                <path id="p-win2" class="house-path" d="M124 60 H148 V76 H124 Z" />

                <!-- decorative outline for the roof edge -->
                <path id="p-eave" class="house-path" d="M30 64 H170" />

                <!-- large fill groups (invisible fill shapes) that will fade in when part is done -->
                <rect id="f-walls" class="fill-when-done" x="40" y="40" width="120" height="50" rx="2" />
                <polygon id="f-roof" class="fill-when-done" points="20,55 100,10 180,55" />
                <rect id="f-door" class="fill-when-done" x="92" y="60" width="16" height="30" />
                <rect id="f-win1" class="fill-when-done" x="52" y="60" width="24" height="16" />
                <rect id="f-win2" class="fill-when-done" x="124" y="60" width="24" height="16" />

            </svg>
        </div>
        <img src="{{ asset('assets/img/image.png') }}" alt="" style="width: 200px;">
        <div class="percent">Loading <strong id="percentText">0%</strong></div>

    </div>
    <div class="mobile-bottom-menu">
        <a href="{{ route('index') }}" class="menu-item {{ request()->routeIs('index') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>

        <a href="{{ route('frontend.properties') }}"
            class="menu-item {{ request()->routeIs('frontend.properties') && !request()->has('purpose') ? 'active' : '' }}">
            <i class="fas fa-lightbulb"></i>
            <span>Properties</span>
        </a>

        <a href="{{ route('frontend.projects') }}"
            class="menu-item {{ request()->routeIs('frontend.projects') ? 'active' : '' }}">
            <i class="fas fa-building"></i>
            <span>Projects</span>
        </a>

        <a href="{{ route('frontend.properties',['purpose'=>'franchiese']) }}"
            class="menu-item {{ request('purpose') === 'franchiese' ? 'active' : '' }}">
            <i class="fas fa-heart"></i>
            <span>Franchiese</span>
        </a>

        <a href="{{ route('frontend.properties', ['purpose' => 'business_for_sale']) }}"
            class="menu-item {{ request('purpose') === 'business_for_sale' ? 'active' : '' }}">
            <i class="fas fa-user"></i>
            <span>Business for Sale</span>
        </a>
    </div>



    <a href="#" class="floating-plus-btn" id="sellRentBtn">
        <i class="fas fa-plus"></i>
    </a>

    @includeIf('frontend.partials.header.header-v1')

    @if (request()->routeIs('index'))
    @endif

    @yield('breadcrumb')

    @yield('content')

    @includeIf('frontend.partials.popups')
    @includeIf('frontend.partials.footer.footer-v1')
    {{-- cookie alert --}}
    @if (!is_null($cookieAlertInfo) && $cookieAlertInfo->cookie_alert_status == 1)
    @include('cookie-consent::index')
    @endif

    <!-- Go to Top -->
    <!-- <div class="go-top"><i class="fal fa-angle-double-up"></i></div> -->


    <!-- WhatsApp Chat Button -->
    <div id="WAButton"></div>
    <script>
        window.addEventListener('pageshow', function (e) {
            const cameFromBFCache =
                e.persisted ||
                (performance.getEntriesByType('navigation')[0]?.type === 'back_forward');

            if (cameFromBFCache) {
                // Force-close if browser restored it open
                const el = document.getElementById('otpVerificationModal');
                if (!el) return;

                // Bootstrap 5 API
                if (window.bootstrap?.Modal) {
                const modal = bootstrap.Modal.getOrCreateInstance(el);
                modal.hide();
                } else {
                // Bootstrap 4 / jQuery fallback
                $('#otpVerificationModal').modal('hide');
                }
            }
        });

    (function() {
        const steps = [{
                id: 'p-foundation',
                start: 0,
                end: 8,
                fillId: null
            },
            {
                id: 'p-walls',
                start: 8,
                end: 40,
                fillId: 'f-walls'
            },
            {
                id: 'p-roof',
                start: 40,
                end: 62,
                fillId: 'f-roof'
            },
            {
                id: 'p-chimney',
                start: 62,
                end: 68,
                fillId: null
            },
            {
                id: 'p-eave',
                start: 68,
                end: 74,
                fillId: null
            },
            {
                id: 'p-door',
                start: 74,
                end: 84,
                fillId: 'f-door'
            },
            {
                id: 'p-win1',
                start: 84,
                end: 92,
                fillId: 'f-win1'
            },
            {
                id: 'p-win2',
                start: 92,
                end: 100,
                fillId: 'f-win2'
            }
        ];

        const percentText = document.getElementById('percentText');
        const loader = document.getElementById('loader');

            // prepare SVG paths (if present)
            steps.forEach(s => {
                const el = document.getElementById(s.id);
                if (!el) return;
                const len = (el.getTotalLength && el.getTotalLength()) || 0;
                el.style.strokeDasharray = len;
                el.style.strokeDashoffset = len;
                el.dataset.total = len;
                el.classList.remove('reveal');
            });

            const clamp = (v, a, b) => Math.max(a, Math.min(b, v));

            window.updateLoaderProgress = function(percentage) {
                const p = clamp(Number(percentage) || 0, 0, 100);
                if (percentText) percentText.textContent = Math.round(p) + '%';

                steps.forEach(s => {
                    const el = document.getElementById(s.id);
                    if (!el) return;

                    const segStart = s.start,
                        segEnd = s.end;
                    let local = 0;
                    if (p <= segStart) local = 0;
                    else if (p >= segEnd) local = 1;
                    else local = (p - segStart) / (segEnd - segStart);

                    const len = Number(el.dataset.total) || 0;
                    const offset = Math.round(len * (1 - local));
                    el.style.strokeDashoffset = offset;

                    if (local > 0) el.classList.add('reveal');
                    else el.classList.remove('reveal');

                    if (local === 1 && s.fillId) {
                        const f = document.getElementById(s.fillId);
                        if (f) f.classList.add('done');
                    } else if (s.fillId) {
                        const f = document.getElementById(s.fillId);
                        if (f) f.classList.remove('done');
                    }
                });

                if (p >= 100) {
                    loader.classList.add('hidden');
                    setTimeout(() => {
                        loader.style.display = 'none';
                    }, 300);
                }
            };

            // --- Simulation while page loads ---
            let simulated = 0;
            let simInterval = null;

            function startSimulation() {
                // only start if not already at 100
                if (simInterval) return;
                simInterval = setInterval(() => {
                    // advance by a random small amount; slow down as it approaches 90-95
                    const step = (Math.random() * 5) + 2; // 2..7
                    simulated = Math.min(simulated + step, 95); // cap at 95 so final fill happens on load
                    updateLoaderProgress(simulated);
                }, 150);
            }

            function stopSimulation() {
                if (simInterval) {
                    clearInterval(simInterval);
                    simInterval = null;
                }
            }

            // Smoothly animate from current value to target (uses requestAnimationFrame)
            function animateTo(target, duration = 400) {
                const start = performance.now();
                const from = Number((percentText && parseInt(percentText.textContent)) || simulated) || 0;
                const delta = target - from;
                return new Promise(resolve => {
                    function frame(now) {
                        const t = Math.min((now - start) / duration, 1);
                        const eased = t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t; // easeInOut quad-ish
                        const value = from + delta * eased;
                        updateLoaderProgress(value);
                        if (t < 1) requestAnimationFrame(frame);
                        else resolve();
                    }
                    requestAnimationFrame(frame);
                });
            }

            // start simulation immediately
            startSimulation();

            // When page fully loaded => stop sim, animate to 100 and hide loader
            window.addEventListener('load', async () => {
                stopSimulation();

                // ensure we show a little of the progress before finishing
                // animate from current to 100 quickly so user sees the final change
                await animateTo(100, 450);

                // small delay for 100% to be readable
                setTimeout(() => {
                    loader.classList.add('hidden');
                    setTimeout(() => {
                        loader.style.display = 'none';
                    }, 300);
                }, 160);
            });
        })();

        // Send heartbeat every 30 seconds
		// setInterval(sendHeartbeat, 30000);
		// sendHeartbeat(); // send immediately on page load

		// // When tab/window closes, call leave endpoint
		// window.addEventListener('beforeunload', function() {
		// 	navigator.sendBeacon('{{ route('visitors.leave') }}');
		// });

		document.addEventListener("DOMContentLoaded", function() {

			// Initialize wishlist count
			updateWishlistHtml();

			// Function: Update Wishlist Count in Header
			function updateWishlistHtml() {
				$.get("{{ route('wishlist.count') }}", function(res) {
					if (res.status === 'success') {
						$('.wishlist-count-html').text(res.count);
					}
				});
			}

			// Handle Wishlist Button Click (Add / Remove)
			$(document).on('click', '.btn-wishlist', function(e) {
				e.preventDefault();

				const btn = $(this);
				const propertyId = btn.attr('data-id');
				const action = btn.attr('data-action'); // 'add' or 'remove'
				const url = btn.attr('data-url'); // endpoint for current action

				$.ajax({
					url: url,
					method: 'GET',
					data: {
						_token: "{{ csrf_token() }}",
						property_id: propertyId
					},
					beforeSend: function() {
						btn.prop('disabled', true);
					},
					success: function(response) {
						if (response.status === 'success') {

							// Toggle visual and data attributes
							if (action === 'add') {
								btn.addClass('wishlist-active')
									.attr('data-action', 'remove')
									.attr('title', 'Saved')
									.attr('data-url', btn.attr('data-remove-url')); // set next URL
							} else {
								btn.removeClass('wishlist-active')
									.attr('data-action', 'add')
									.attr('title', 'Add to Wishlist')
									.attr('data-url', btn.attr('data-add-url')); // set next URL
							}

							updateWishlistHtml();

							// Optional toast notification
							if (window.toastr) {
								toastr.success(response.message);
							}

						} else {
							if (window.toastr) {
								toastr.error(response.message || 'Action failed');
							}
						}
					},
					error: function(xhr) {
						if (xhr.status === 401) {
							if (window.toastr) toastr.warning('Please login to use wishlist.');
							$('#customerPhoneModal').modal('show');
						} else {
							if (window.toastr) toastr.error('Something went wrong.');
						}
					},
					complete: function() {
						btn.prop('disabled', false);
					}
				});
			});

		});

        document.getElementById("sellRentBtn").addEventListener("click", function(e) {
            e.preventDefault();
            const modal = document.getElementById("customerPhoneModal");
            if (modal) {
                const modalTrigger = new bootstrap.Modal(modal);
                modalTrigger.show();
            }
        });

    </script>

    @includeIf('frontend.partials.scripts.scripts-v1')
    @includeIf('frontend.partials.toastr')
</body>

</html>