@extends('users.layout')
@section('content')
  <!--====== Start Dashboard Section ======-->
  <div class="page-header">
    <h4 class="page-title">{{ __('Wishlists') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('user.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Wishlists') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">

              @if (count($wishlists) == 0)
                <h3 class="text-center mt-2">{{ __('NO WISHLIST FOUND ') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                          <th>{{ __('Serial') }}</th>
                          <th>{{ __('Property title') }}</th>
                          <th>{{ __('Action') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($wishlists as $item)
                        @php
                            $content = DB::table('property_contents')
                                ->where([
                                    ['property_id', $item->property_id],
                                    ['language_id', $language->id],
                                ])
                                ->select('title', 'slug')
                                ->first();
                        @endphp
                        @if (!is_null($content))
                            <tr>
                                <td>#{{ $loop->iteration }}</td>
                                <td><a
                                        href="{{ route('frontend.property.details', [$content->slug, $item->property_id]) }}">{{ $content->title }}</a>
                                </td>
                                <td>
                                    <a href="{{ route('frontend.property.details', [$content->slug, $item->property_id]) }}"
                                        class="btn"><i class="fas fa-eye"></i>
                                        {{ __('View') }}</a>
                                    <a href="{{ route('remove.wishlist', $item->property_id) }}"
                                        class="btn"><i class="fas fa-times"></i>
                                        {{ __('Remove') }}</a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                  </tbody>
                  </table>
                </div>
              @endif
            </div>
          </div>
        </div>

        <div class="card-footer">
          {{-- {{ $wishlists->links() }} --}}
        </div>
      </div>
    </div>
  </div>
  <!--====== End Dashboard Section ======-->
@endsection
