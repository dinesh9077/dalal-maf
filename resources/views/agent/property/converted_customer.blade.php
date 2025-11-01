@extends('agent.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Inventory Customer') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('agent.dashboard') }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Inventory Customer') }}</a>
            </li>


        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card-title d-inline-block">{{ __('All Inventory Customers') }}</div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($prtStatus) == 0)
                                <h3 class="text-center mt-2">{{ __('NO CUSTOMERS FOUND') . '!' }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3" id="basic-datatables">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">{{ __('Customer') }}</th>
                                                <th scope="col">{{ __('Property') }}</th>
                                                <th scope="col">{{ __('Wings') }}</th>
                                                <th scope="col">{{ __('Floors') }}</th>
                                                <th scope="col">{{ __('Flat') }}</th>
                                                <th scope="col">{{ __('Status') }}</th>
                                                <th scope="col">{{ __('Created At') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                          @foreach ($prtStatus as $status)

                                              <tr>
                                                  <td>{{ $loop->iteration }}</td>
                                                  <td>{{ optional($status->customer)->name }}</td>
                                                  <td><a href="{{ route('agent.property_inventory.view',$status->property_id) }}"
                                                          target="_blank">
                                                          {{ strlen(optional($status->property->propertyContent)->title) > 100 ? mb_substr(optional($status->property->propertyContent)->title, 0, 100, 'utf-8') . '...' : optional($status->property->propertyContent)->title }}
                                                      </a></td>
                                                  {{-- <td>{{ optional($status->property->propertyContent)->title }}</td> --}}
                                                  <td>{{ optional($status->wing)->wing_name }}</td>
                                                  <td>{{ optional($status->floors)->floor_name }}</td>
                                                  <td>{{ optional($status->Units)->flat_no }}</td>
                                                  <td>{{ $status->property_status }}</td>
                                                  <td>{{ $status->created_at->format('d-m-Y') }}</td>
                                              </tr>
                                          @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-footer"></div>
            </div>
        </div>
    </div>

@endsection
