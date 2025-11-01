@php
    $version = $basicInfo->theme_version;
@endphp
@extends("frontend.layouts.layout-v$version")
@section('pageHeading')
    {{ __('Change Password') }}
@endsection


@section('content')
  


    <!--====== Start Dashboard Section ======-->
    <div class="user-dashboard  pb-60"  style="margin-top: 150px;">
        <div class="container">
            <div class="row gx-xl-5">
                @includeIf('frontend.user.side-navbar')
                <div class="col-lg-6">
                    <div class="user-profile-details mb-40">
                        <div class="account-info radius-md" >
                            <div class="title">
                                <h4>{{ __('Change Password') }}</h4>
                            </div>
                            <div class="edit-info-area mt-30 new-title-LRP-des">
                                @if (Session::has('success'))
                                    <div class="alert alert-success">{{ Session::get('success') }}</div>
                                @endif
                                
                                <form action="{{ route('user.update_password') }}" method="POST">
                                    @csrf
                                    <div class="form-group mb-20 ">
                                           <img src="{{ asset('assets/front/images/new-images/password.png') }}" alt="user">
                                        <input type="password" class="form-control"
                                            placeholder="{{ __('Current Password') . '*' }}"  name="current_password" required>
                                        @error('current_password')
                                            <p class="text-danger mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-20">
                                           <img src="{{ asset('assets/front/images/new-images/password.png') }}" alt="user">

                                        <input type="password" class="form-control" placeholder="{{ __('New Password') }}"
                                            name="new_password" required>
                                        @error('new_password')
                                            <p class="text-danger mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-20">
                                           <img src="{{ asset('assets/front/images/new-images/password.png') }}" alt="user">

                                        <input type="password" class="form-control"
                                            placeholder="{{ __('Confirm Password') }}" name="new_password_confirmation"
                                            required>
                                    </div>
                                    <div class="mb-15">
                                        <div class="form-button">
                                            <button type="submit"
                                                class="btn btn-lg btn-primary">{{ __('Submit') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--====== End Dashboard Section ======-->
@endsection
