@php
$version = $basicInfo->theme_version;
@endphp
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
<style>
    .blog-card {
        border: 1px solid #e5e5e5;
        overflow: hidden;
        transition: all 0.3s ease;
        background: #fff;
        height: 100%;
        padding: 15px;

    }

    .blog-card:hover {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);

    }

    .blog-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .blog-card-body {
        padding: 15px;
    }

    .blog-card h5 {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .blog-card p {
        font-size: 14px;
        color: #555;
        margin-bottom: 10px;
    }

    .show-more {
        font-weight: 600;
        font-size: 14px;
        color: #000;
        text-decoration: none;
    }

    .author-box {
        display: flex;
        align-items: center;
        border-top: 1px solid #e5e5e5;
        padding: 10px 15px;
    }

    .author-box img {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        margin-right: 10px;
    }

    .author-info small {
        font-size: 12px;
        color: #777;
    }

    .new-lable-blogs {
       background: rgb(250, 244, 228);
        color: black;
        padding: 5px 10px;
        /* border-radius: 5px; */

        font-size: 12px;
    }

                .new-main-navbar {
    background-color: #6c603c;
}

</style>



@extends("frontend.layouts.layout-v$version")

@section('pageHeading')
{{ !empty($pageHeading) ? $pageHeading->project_page_title : __('Projects') }}
@endsection

@section('metaKeywords')
@if (!empty($seoInfo))
{{ $seoInfo->meta_keywords_projects }}
@endif
@endsection

@section('metaDescription')
@if (!empty($seoInfo))
{{ $seoInfo->meta_description_projects }}
@endif
@endsection

@section('content')
@includeIf('frontend.partials.breadcrumb', [
'breadcrumb' => $bgImg->breadcrumb,
'title' => !empty($pageHeading) ? $pageHeading->project_page_title : __('Projects'),
'subtitle' => __('Projects'),
])

<div class="projects-area" style="padding: 10px 0px;">

<a href="https://wa.me/9925133440" target="_blank">
    <div class="whatsapp-btn" data-aos="fade-up">
        <img src="{{ asset('assets/front/images/new-images/whatsapp.png') }}" alt="WhatsApp">
    </div>
</a>

    
    <div class="container" data-aos="fade-up">
        <div class="row">
            <div class="col-12">
                <div class="product-sort-area mb-0" data-aos="fade-up">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <form action="{{ route('frontend.projects') }}" method="GET">
                                <div class="project-filter-form radius-md pb-10">
                                    <div class="row">
                                        <div class="col-lg-3 mb-10">
                                            <input type="search" name="title" class="form-control"
                                                placeholder="{{ __('Search By Title') }}"
                                                value="{{ request()->input('title') }}">
                                        </div>
                                        <div class="col-lg-3 mb-10">
                                            <input type="search" name="location" class="form-control"
                                                placeholder="{{ __('Search By Location') }}"
                                                value="{{ request()->input('location') }}">
                                        </div>
                                         <div class="col-lg-3 mb-10">
                                             <select class="form-control" name="vendor_id">
                                                 <option value="">{{ __('Select Vendor') }}</option>
                                                 @foreach($vendor as $vendors)
                                                 <option value="{{ $vendors->id }}" {{ request()->input('vendor_id') == $vendors->id ? 'selected' : '' }}>{{ $vendors->username }}</option>
                                                 @endforeach
                                             </select>
                                        </div> 
                                        <div class="col-lg-3 mb-10">
                                            <button class="btn btn-lg btn-primary w-100" style="background : #6c603c;" type="submit">
                                                <i class="fal fa-search"></i> {{ __('Search') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-4 mb-0">
                            <ul class="product-sort-list text-lg-end list-unstyled">
                                <li class="item">
                                    <form action="{{ route('frontend.projects') }}" method="GET" onchange="submit();">
                                        <div class="sort-item d-flex align-items-center">
                                            <label class="color-dark me-2">{{ __('Sort By') }}:</label>
                                            <select class="nice-select" name="sort">
                                                <option value="new">{{ __('Newest') }} </option>
                                                <option value="old">{{ __('Oldest') }} </option>
                                                <option value="high-to-low">{{ __('High to Low') }}</option>
                                                <option value="low-to-high">{{ __('Low to High') }}</option>

                                            </select>
                                        </div>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- <div class="row">
                    @forelse ($projects as $project)
                    <div class="col-lg-4 col-sm-6" data-aos="fade-up" data-aos-delay="100">
                        <a href="{{ route('frontend.projects.details', ['slug' => $project->slug]) }}">
                            <div class="card mb-30 product-default">
                                <div class="card-img">
                                    <div class="lazy-container ratio ratio-1-3">
                                        <img class="lazyload"
                                            data-src="{{ asset('assets/img/project/featured/' . $project->featured_image) }}">
                                    </div>
                                    <span class="label">
                                        @if ($project->status == 0)
                                        {{ __('Under Construction') }}
                                        @elseif($project->status == 1)
                                        {{ __('Complete') }}
                                        @endif
                                    </span>
                                </div>
                                <div class="card-text text-center p-3">
                                    <h3 class="card-title product-title color-white mb-1">
                                        {{ $project->title }}

                                    </h3>
                                    <span class="location icon-start">
                                        <i class="fal fa-map-marker-alt"></i>{{ $project->address }}</span>
                                    <br>
                                    <span class="price"> {{ symbolPrice($project->min_price) }}
                                        {{ !empty($project->max_price) ? ' - ' . symbolPrice($project->max_price) : '' }}

                                    </span>


                                    @if ($project->agent)
                                    <a class="color-medium"
                                        href="{{ route('frontend.agent.details', ['username' => $project->agent->username]) }}"
                                        target="_self">
                                        <div class="user rounded-pill mt-10">
                                            <div class="user-img lazy-container ratio ratio-1-1 rounded-pill">
                                                <img class="lazyload"
                                                    data-src="{{ $project->agent->image ? asset('assets/img/agents/' . $project->agent->image) : asset('assets/img/blank-user.jpg') }}"
                                                    src="{{ $project->agent->image ? asset('assets/img/agents/' . $project->agent->image) : asset('assets/img/blank-user.jpg') }}">
                                            </div>
                                            <div class="user-info">
                                                <span>{{ $project->agent->username }}</span>
                                            </div>
                                        </div>
                                    </a>
                                    @elseif ($project->vendor)
                                    <a class="color-medium"
                                        href="{{ route('frontend.vendor.details', ['username' => $project->vendor->username]) }}"
                                        target="_self">
                                        <div class="user rounded-pill mt-10">
                                            <div class="user-img lazy-container ratio ratio-1-1 rounded-pill">
                                                <img class="lazyload"
                                                    data-src="{{ $project->vendor->photo ? asset('assets/admin/img/vendor-photo/' . $project->vendor->photo) : asset('assets/img/blank-user.jpg') }}"
                                                    src="{{ $project->vendor->photo ? asset('assets/admin/img/vendor-photo/' . $project->vendor->photo) : asset('assets/img/blank-user.jpg') }}">
                                            </div>
                                            <div class="user-info">
                                                <span>{{ $project->vendor->username }}</span>
                                            </div>
                                        </div>
                                    </a>
                                    @elseif($project->vendor_id == 0)
                                    @php
                                    $admin = App\Models\Admin::where('role_id', null)
                                    ->with('adminInfo')
                                    ->first();
                                    @endphp

                                    <a class="color-medium"
                                        href="{{ route('frontend.vendor.details', ['username' => $admin->username, 'admin' => 'true']) }}"
                                        target="_self">
                                        <div class="user rounded-pill mt-10">
                                            <div class="user-img lazy-container ratio ratio-1-1 rounded-pill">
                                                <img class=" lazyload"
                                                    data-src="{{ $admin->image ? asset('assets/img/admins/' . $admin->image) : asset('assets/img/blank-user.jpg') }}"
                                                    src="{{ $admin->image ? asset('assets/img/admins/' . $admin->image) : asset('assets/img/blank-user.jpg') }}">
                                            </div>
                                            <div class="user-info">
                                                <span>{{ $admin->username }}</span>
                                            </div>
                                        </div>
                                    </a>
                                    @endif

                                </div>
                            </div>
                        </a>
                    </div>
                    @empty
                    <div class="col-lg-12">
                        <h3 class="text-center mt-5"> {{ __('No Project Found') }}</h3>
                    </div>
                    @endforelse

                </div> -->

                <div class="row">
                    @forelse ($projects as $project)
                    <div class="col-lg-4 col-md-6 mt-4">
                        <a href="{{ route('frontend.projects.details', ['slug' => $project->slug]) }}">
                            <div class="blog-card " style="position : relative;">
                                <img class="lazyload" data-src="{{ asset('assets/img/project/featured/' . $project->featured_image) }}">
                                <span class="label new-lable-blogs" style="    position: absolute;left: 25px;top: 25px;">
                                    @if ($project->status == 0)
                                    {{ __('Under Construction') }}
                                    @elseif($project->status == 1)
                                    {{ __('Complete') }}
                                    @endif
                                </span>

                                <div class="blog-card-body">

                                    <h5 class="mt-2"> {{ $project->title }}</h5>
                                    <p><i class="fal fa-map-marker-alt mr-2"></i> {{ $project->address }}</p>
                                    <a href="#" class="show-more"> {{ symbolPrice($project->min_price) }} {{ !empty($project->max_price) ? '- ' . symbolPrice($project->max_price) : '' }}</a>
                                </div>
                                @if ($project->agent)

                                <!-- <a class="color-medium"
                                    href="{{ route('frontend.agent.details', ['username' => $project->agent->username]) }}"
                                    target="_self"> -->
                                    <div class="author-box">
                                        <img class="lazyload"
                                            data-src="{{ $project->agent->image ? asset('assets/img/agents/' . $project->agent->image) : asset('assets/img/blank-user.jpg') }}"
                                            src="{{ $project->agent->image ? asset('assets/img/agents/' . $project->agent->image) : asset('assets/img/blank-user.jpg') }}">
                                        <div class="author-info">
                                            <strong>{{ $project->agent->username }}</strong><br>
                                            <!-- <small>12 months ago</small> -->
                                        </div>
                                    </div>
                                <!-- </a> -->
                                @elseif ($project->vendor)
                                <!-- <a class="color-medium"
                                    href="{{ route('frontend.vendor.details', ['username' => $project->vendor->username]) }}"
                                    target="_self"> -->
                                    <div class="author-box">
                                        <img class="lazyload"
                                            data-src="{{ $project->vendor->photo ? asset('assets/admin/img/vendor-photo/' . $project->vendor->photo) : asset('assets/img/blank-user.jpg') }}"
                                            src="{{ $project->vendor->photo ? asset('assets/admin/img/vendor-photo/' . $project->vendor->photo) : asset('assets/img/blank-user.jpg') }}">
                                        <div class="author-info">
                                            <strong>{{ $project->vendor->username }}</strong><br>
                                            <!-- <small>12 months ago</small> -->
                                        </div>
                                    </div>
                                <!-- </a> -->
                                @elseif($project->vendor_id == 0)
                                @php
                                $admin = App\Models\Admin::where('role_id', null)
                                ->with('adminInfo')
                                ->first();
                                @endphp
                                <!-- <a class="color-medium"
                                    href="{{ route('frontend.vendor.details', ['username' => $admin->username, 'admin' => 'true']) }}"
                                    target="_self"> -->
                                    <div class="author-box">
                                        <img class=" lazyload"
                                            data-src="{{ $admin->image ? asset('assets/img/admins/' . $admin->image) : asset('assets/img/blank-user.jpg') }}"
                                            src="{{ $admin->image ? asset('assets/img/admins/' . $admin->image) : asset('assets/img/blank-user.jpg') }}">
                                        <div class="author-info">
                                            <strong>{{ $admin->username }}</strong><br>
                                            <!-- <small>12 months ago</small> -->
                                        </div>
                                    </div>
                                <!-- </a> -->
                                @endif
                            </div>

                    </div>
                    @empty
                    <div class="col-lg-12">
                        <h3 class="text-center mt-5"> {{ __('No Project Found') }}</h3>
                    </div>
                    @endforelse
                </div>

                <div class="pagination mb-10 justify-content-center">
                    {{ $projects->links() }}

                </div>
                <!-- @if (!empty(showAd(3)))
                <div class="text-center mt-4">
                    {!! showAd(3) !!}
                </div>
                @endif -->
            </div>
        </div>
    </div>
</div>
@endsection