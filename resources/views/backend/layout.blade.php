<!DOCTYPE html>
<html>

<head>
  {{-- required meta tags --}}
  <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

  {{-- csrf-token for ajax request --}}
  <meta name="csrf-token" content="{{ csrf_token() }}">

  {{-- title --}}
  <title>{{ __('Admin') . ' | ' . $websiteInfo->website_title }}</title>

  {{-- fav icon --}}
  <link rel="shortcut icon" type="image/png" href="{{ asset('assets/img/' . $websiteInfo->favicon) }}">

  {{-- include styles --}}
  @includeIf('backend.partials.styles')

  {{-- additional style --}}
  @yield('style')
</head>

<body data-background-color="{{ $settings->admin_theme_version == 'light' ? 'white' : 'dark' }}">
  {{-- loader start --}}
  <div class="request-loader">
    <img src="{{ asset('assets/img/loader.gif') }}" alt="loader">
  </div>
  {{-- loader end --}}

  <div class="wrapper">
    {{-- top navbar area start --}}
    @includeIf('backend.partials.top-navbar')
    {{-- top navbar area end --}}

    {{-- side navbar area start --}}
    @includeIf('backend.partials.side-navbar')
    {{-- side navbar area end --}}

    <div class="main-panel">
      <div class="content">
        <div class="page-inner">
          @yield('content')
        </div>
      </div>

      {{-- footer area start --}}
      @includeIf('backend.partials.footer')
      {{-- footer area end --}}
    </div>
  </div>

  {{-- include scripts --}}
  @includeIf('backend.partials.scripts')
  @include('backend.delete-modal')
  {{-- additional script --}}
  @yield('variables')
  @yield('script')
	<script>
    function sendHeartbeat() {
        fetch('{{ route("visitors.update") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => {
            if (!res.ok) {
                // prevent JS crash
                console.warn("Heartbeat request failed:", res.status);
            }
        })
        .catch(err => {
            // prevent JS crash
            console.warn("Heartbeat error ignored:", err);
        });
    }

    // When tab/window closes, call leave endpoint
    // window.addEventListener('beforeunload', function () {
    //     navigator.sendBeacon('{{ route("visitors.leave") }}');
    // });

    // setInterval(sendHeartbeat, 30000);
		// sendHeartbeat();

    let modalOpen = false;

		function closemodal() {
			setTimeout(function() {
				modalOpen = false;
			}, 1000)
		}

</script>
  @include('backend.toast-msg')
  <div id="modal-view-render"> </div>
</body>

</html>
