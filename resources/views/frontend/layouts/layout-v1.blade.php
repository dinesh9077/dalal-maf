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
            --color-primary: {{ $primaryColor }};
            --color-primary-rgb: {{ rgb(htmlspecialchars($primaryColor)) }};
            --color-secondary: {{ $secoundaryColor }};
            --color-secondary-rgb: {{ rgb(htmlspecialchars($secoundaryColor)) }};
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
		  z-index: 9999;
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
		  0% { transform: rotate(0deg); }
		  100% { transform: rotate(360deg); }
		}
    </style>
</head>


<body dir="{{ $currentLanguageInfo->direction == 1 ? 'rtl' : '' }}">
	<div id="loader" class="loader">
	  <div class="spinner"></div>
	</div>

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
    <div class="go-top"><i class="fal fa-angle-double-up"></i></div>


    <!-- WhatsApp Chat Button -->
    <div id="WAButton"></div>
    <script>
		window.addEventListener("load", function() {
		  document.getElementById("loader").style.display = "none";
		});
		function sendHeartbeat() {
            fetch('{{ route("visitors.update") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
		}

        // Send heartbeat every 30 seconds
        setInterval(sendHeartbeat, 30000);
        sendHeartbeat(); // send immediately on page load

        // When tab/window closes, call leave endpoint
        window.addEventListener('beforeunload', function () {
            navigator.sendBeacon('{{ route("visitors.leave") }}');
        });
		
		document.addEventListener("DOMContentLoaded", function ()
		{
			updateWishlistHtml();
			function updateWishlistHtml() {
				$.get("{{ route('wishlist.count') }}", function (res) {
					if (res.status === 'success') { 
						$('.wishlist-count-html').text(res.count);
					}
				});
			}
	
			$(document).on('click', '.btn-wishlist', function (e) {
				e.preventDefault();

				const btn = $(this);
				const propertyId = btn.attr('data-id');
				const action = btn.attr('data-action'); // 'add' or 'remove'
				const url = btn.attr('data-url'); // current endpoint (from DOM attr)

				$.ajax({
					url: url,
					method: 'get', // safer than GET for state change
					data: {
						_token: "{{ csrf_token() }}",
						property_id: propertyId
					},
					beforeSend: function () {
						btn.prop('disabled', true);
					},
					success: function (response) {
						if (response.status === 'success') {
							// toggle visual state
							if (action === 'add') {
								btn.addClass('wishlist-active')
								   .attr('data-action', 'remove')
								   .attr('title', 'Saved');
								// set next URL to remove
								btn.attr('data-url', btn.attr('data-remove-url'));
							} else {
								btn.removeClass('wishlist-active')
								   .attr('data-action', 'add')
								   .attr('title', 'Add to Wishlist');
								// set next URL to add
								btn.attr('data-url', btn.attr('data-add-url'));
							}
							updateWishlistHtml()
							// optional toast
							if (window.toastr) {
								toastr.success(response.message);
							}
						} else {
							if (window.toastr) toastr.error(response.message || 'Action failed');
						}
					},
					error: function (xhr) {
						if (xhr.status === 401) {
							if (window.toastr) toastr.warning('Please login to use wishlist.');
							$('#customerPhoneModal').modal('show');
						} else {
							if (window.toastr) toastr.error('Something went wrong.');
						}
					},
					complete: function () {
						btn.prop('disabled', false);
					}
				});
			});
		});
		 
    </script>
    @includeIf('frontend.partials.scripts.scripts-v1')
    @includeIf('frontend.partials.toastr')
</body>

</html>
