@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Partner Kyc Request') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('admin.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Partner Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Partner Kycs') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-6">
              <div class="card-title">{{ __('Partners Kyc') }}</div>
            </div>

            {{-- <div class="col-6 ">
              <form class="float-right" action="{{ route('admin.vendor_management.vendor_kyc') }}" method="GET">
                <input name="info" type="text" class="form-control min-230"
                  placeholder="Search By Username or Email ID"
                  value="{{ !empty(request()->input('info')) ? request()->input('info') : '' }}">
              </form>
            </div> --}}
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($vendors) == 0)
                <h3 class="text-center">{{ __('NO VENDOR KYC REQUEST FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>

                        <th scope="col">{{ __('Username') }}</th>
                        <th scope="col">{{ __('Email ID') }}</th>
                        <th scope="col">{{ __('Phone') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($vendors as $vendor)
                        <tr>

                          <td>{{ $vendor->username }}</td>
                          <td>{{ $vendor->email }}</td>
                          <td>{{ empty($vendor->phone) ? '-' : $vendor->phone }}</td>
                          <td>
                            <a href="{{ route('admin.vendor_management.vendor_kyc_details', ['id' => $vendor->id]) }}"
                              class="btn btn-sm btn-info">
                              {{ __('Edit') }}
                            </a>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
          </div>
        </div>

        <div class="card-footer">
          {{-- <div class="row">
            <div class="d-inline-block mx-auto">
              {{ $vendors->appends(['info' => request()->input('info')])->links() }}
            </div>
          </div> --}}
        </div>
      </div>
    </div>
  </div>
@endsection
