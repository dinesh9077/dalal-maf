<!-- Page title start-->
<!-- <div class="page-title-area header-next">
    <img class="lazyload blur-up bg-img" src="{{ asset('assets/img/' . $breadcrumb) }}">
    <div class="container">
        <div class="content text-center">
            <h1 class="color-white"> {{ !empty($title) ? $title : '' }}</h1>
            <ul class="list-unstyled">
                <li class="d-inline-block"><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
                <li class="d-inline-block"> >> </li>
                <li class="d-inline-block active">{{ !empty($subtitle) ? $subtitle : '' }}</li>
            </ul>
        </div>
    </div>
</div> -->
<!-- Page title end-->

<div class=" header-next " >
<div class="section-title title-center title-inline mb-40 aos-init aos-animate" style="margin-top : 150px !important;">
    <div class="title" style="font-weight : bolder !important;">
    {{ !empty($title) ? $title : '' }}
    </div>
</div>
</div>
