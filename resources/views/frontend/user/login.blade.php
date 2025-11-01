<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

@php
$version = $basicInfo->theme_version;
@endphp
@extends('frontend.layouts.layout-v' . $version)
@section('pageHeading')
{{ !empty($pageHeading) ? $pageHeading->login_page_title : __('Login') }}
@endsection
@section('metaKeywords')
@if (!empty($seoInfo))
{{ $seoInfo->meta_keyword_login }}
@endif
@endsection

@section('metaDescription')
@if (!empty($seoInfo))
{{ $seoInfo->meta_description_login }}
@endif
@endsection

@section('content')

<!-- Authentication-area start -->
<!-- <div class="authentication-area ptb-100">
    <div class="container">
        <div class="login-container">
            <div class="login-left ">
                <div class="new-title-LRP">
                    @if (Session::has('success'))
                    <div class="alert alert-success">{{ __(Session::get('success')) }}</div>
                    @endif
                    @if (Session::has('error'))
                    <div class="alert alert-danger">{{ __(Session::get('error')) }}</div>
                    @endif
                    <form action="{{ route('user.login_submit') }}" method="POST">
                        @csrf
                        <div class="title">
                            <h4 class="mb-20">{{ __('Login') }}</h4>
                        </div>
                        <div class="form-group mb-30 input-box">
                            <img src="{{ asset('assets/front/images/new-images/user.png') }}" alt="user">
                            <input type="text" class="form-control" name="email"
                                placeholder="{{ __('Email') }}" required>
                            @error('email')
                            <p class="text-danger mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                       <div class="form-group mb-30 input-box position-relative">
                            <img src="{{ asset('assets/front/images/new-images/password.png') }}" alt="user">

                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="{{ __('Password') }}" required>

               
                            <span class="toggle-password" onclick="togglePassword('password','eyeIcon')">
                                <i class="fa fa-eye" id="eyeIcon"></i>
                            </span>

                            @error('password')
                                <p class="text-danger mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        @if ($bs->google_recaptcha_status == 1)
                        <div class="form-group mb-30">
                            {!! NoCaptcha::renderJs() !!}
                            {!! NoCaptcha::display() !!}

                            @error('g-recaptcha-response')
                            <p class="mt-1 text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        @endif
                        <div class="mb-10 d-flex flex-column flex-sm-row justify-content-between align-items-center">
                            <div class="link">
                                <a href="#">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                    <label class="form-check-label" for="remember">
                                        Remember me
                                    </label></a>
                            </div>
                            <div class="link">
                                <a href="{{ route('user.forget_password') }}">{{ __('Forgot password') . '?' }}</a>
                            </div>

                        </div>




                        <button type="submit" class="btn btn-lg btn-primary radius-md w-100"> {{ __('Login Now') }} </button>


                        <div>
                            <div class="link go-signup mt-3">
                                {{ __("don't have an account") . '?' }} <a
                                    href="{{ route('user.signup') }}"><span style="color: red;">{{ __('Sign Up Here') }}</span></a>
                                
                            </div>
                        </div>

                    </form>
                </div>
            </div>

           
            <div class="login-right ">
                <div class="promo-card">
                    <p class="promo-text">
                        Very good works are
                        waiting for you
                        <b>Login Now!!!</b>
                    </p>
                    <img src="{{ asset('assets/front/images/new-images/Woman.png') }}" alt="promo" class="promo-img">
                    <div class="promo-icon">
                        <img src="{{ asset('assets/front/images/new-images/Walt.png') }}" alt="" class="want-img">
                    </div>
                </div>
            </div>

        </div>
        
    </div>
</div> -->

<div class="new-login-page-design-wrapper">
    <!-- Left Side -->
    <div class="new-login-page-design-left col-lg-6">
        <div class="w-100" style="max-width: 450px;">
            <div class="mb-4 text-left">
                <h5 class="welcome-text">Welcome back to Dalal Maaf!</h5>
                <p class="text-muted mt-3" style="    font-size: 13px;">Log in to access your personalized dashboard, manage your listings, and explore properties tailored to your needs. Continue your real estate journey with just one click.</p>
            </div>
            <div class="new-title-LRP">
                 @if (Session::has('success'))
                    <div class="alert alert-success">{{ __(Session::get('success')) }}</div>
                    @endif
                    @if (Session::has('error'))
                    <div class="alert alert-danger">{{ __(Session::get('error')) }}</div>
                    @endif
                <form action="{{ route('user.login_submit') }}" method="POST">
                    @csrf
                    <!-- <div class="title">
                                    <h4 class="mb-20">{{ __('Login') }}</h4>
                                </div> -->
                    <label for="#" style="color: black; font-size: 14px;">Email</label>
                    <div class="form-group mb-30 input-box mt-2">
                        <img src="{{ asset('assets/front/images/new-images/user.png') }}" alt="user">
                          <input type="text" class="form-control" name="email"
                                placeholder="{{ __('Email') }}" required>
                            @error('email')
                            <p class="text-danger mt-2">{{ $message }}</p>
                            @enderror
                    </div>

                    <label for="#" style="color: black; font-size: 14px;">Password</label>
                    <div class="form-group mb-30 input-box position-relative mt-2">
                        <img src="{{ asset('assets/front/images/new-images/password.png') }}" alt="user">

                        <input type="password" class="form-control" id="password" name="password"
                                placeholder="{{ __('Password') }}" required>

                            <!-- Eye Icon -->
                            <span class="toggle-password" onclick="togglePassword('password','eyeIcon')">
                                <i class="fa fa-eye" id="eyeIcon"></i>
                            </span>

                            @error('password')
                                <p class="text-danger mt-2">{{ $message }}</p>
                            @enderror
                    </div>
                    @if ($bs->google_recaptcha_status == 1)
                        <div class="form-group mb-30">
                            {!! NoCaptcha::renderJs() !!}
                            {!! NoCaptcha::display() !!}

                            @error('g-recaptcha-response')
                            <p class="mt-1 text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        @endif
                    <div class="mb-10 d-flex flex-column flex-sm-row justify-content-between align-items-center">
                        <div class="link">
                            <a href="#">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" style="padding: 0px;">
                                <label class="form-check-label" for="remember">
                                    Remember me
                                </label></a>
                        </div>
                        <div class="link">
                            <a  href="{{ route('user.forget_password') }}">{{ __('Forgot password') . '?' }}</a>
                        </div>

                    </div>
                    <button type="submit" class="btn btn-lg  w-100 mt-3"> {{ __('Login Now') }} </button>
                    <div class="text-center my-3">OR</div>


                    <div>
                        <div class="link go-signup mt-3 text-center">
                            {{ __("Don't Have An Account") . '?' }} <a
                                href="{{ route('user.signup') }}"><span style="color: #6c603c;">{{ __('Sign Up Here') }}</span></a>
                        </div>
                    </div>
                </form>
            </div>


        </div>
    </div>

    <!-- Right Side -->
    <div class="new-login-page-design-right col-lg-6">
        <div class="w-100" style="max-width: 600px;">
            <img src="{{ asset('assets/front/images/acrs-imag/login-vector2.png') }}" alt="" style="width: 85%;">
            <h2 style="color: black; font-size : 40px;">Find your dream home, smart investment, or perfect rental with ease.</h2>
            <p class="new-login-page-design-testimonial" style="color: black;">Thousands of verified listings and expert insights—your trusted real estate partner.</p>
           
        </div>
    </div>
</div>


<section class="login-page-property-card-section">
  <div class="container my-5">
    <div class="row align-items-center justify-content-center gx-0 gy-4">
      
      <!-- Card 1 -->
      <div class="col-md-2 m-0 p-0 d-flex justify-content-center">
        <div class="how-it-works-card text-center">
          <div class="how-it-works__rounded-1"></div>
          <div class="how-it-works__rounded-2"></div>
          <div class="how-it-works_img-container text-primary">
           
           <i class="fa-solid fa-house new-icons-lgs"></i>
          
          </div>
          <h4>List Unlimited Properties</h4>
          <p>Add unlimited listings with maximum reach and visibility.</p>
        </div>
      </div>

      <!-- Line -->
      <div class="col-md-1 m-0 p-0 d-none d-md-flex justify-content-center">
        <div style="height:3px; width:100%; align-self:center; background : #6c603c;"></div>
      </div>

      <!-- Card 2 -->
      <div class="col-md-2 m-0 p-0 d-flex justify-content-center">
        <div class="how-it-works-card text-center">
          <div class="how-it-works__rounded-1"></div>
          <div class="how-it-works__rounded-2"></div>
          <div class="how-it-works_img-container text-primary">
           <i class="fa-solid fa-handshake-angle new-icons-lgs"></i>

          </div>
          <h4>Direct Buyer & Seller Connections</h4>
          <p>Connect instantly without brokers for faster, transparent real estate deals.</p>
        </div>
      </div>

      <!-- Line -->
      <div class="col-md-1 m-0 p-0 d-none d-md-flex justify-content-center">
        <div  style="height:3px; width:100%; align-self:center; background : #6c603c;"></div>
      </div>

      <!-- Card 3 -->
      <div class="col-md-2 m-0 p-0 d-flex justify-content-center">
        <div class="how-it-works-card text-center">
          <div class="how-it-works__rounded-1"></div>
          <div class="how-it-works__rounded-2"></div>
          <div class="how-it-works_img-container text-primary">
           <i class="fa-solid fa-thumbs-up new-icons-lgs"></i>
          </div>
          <h4>Verified Builders & Partners</h4>
          <p>Partner with trusted builders and agents for genuine property options.</p>
        </div>
      </div>

        <div class="col-md-1 m-0 p-0 d-none d-md-flex justify-content-center">
        <div  style="height:3px; width:100%; align-self:center; background : #6c603c;"></div>
      </div>

      <!-- Card 3 -->
      <div class="col-md-2 m-0 p-0 d-flex justify-content-center">
        <div class="how-it-works-card text-center">
          <div class="how-it-works__rounded-1"></div>
          <div class="how-it-works__rounded-2"></div>
          <div class="how-it-works_img-container text-primary">
           <i class="fa-solid fa-envelope new-icons-lgs"></i>
          </div>
          <h4>Instant Inquiries & Notifications</h4>
          <p>Receive real-time alerts to respond quickly and close deals.</p>
        </div>
      </div>


    </div>
  </div>

</section>

<div class="container">


            <div class="row gy-4">
                <div class="col-sm-3 col-6">
                    <div class="counter-item position-relative">
                        <h2 class="counter-item__number"> 100+ </h2>
                        <span class="counter-item__text"> Cities Covered </span>
                    </div>
                </div>
                <div class="col-sm-3 col-6">
                    <div class="counter-item position-relative">
                        <h2 class="counter-item__number"> 50,000+ </h2>
                        <span class="counter-item__text"> Happy Customer </span>
                    </div>
                </div>
                <div class="col-sm-3 col-6">
                    <div class="counter-item position-relative">
                        <h2 class="counter-item__number"> 24/4 </h2>
                        <span class="counter-item__text"> Customer Supports </span>
                    </div>
                </div>
                <div class="col-sm-3 col-6">
                    <div class="counter-item position-relative">
                        <h2 class="counter-item__number"> 5000+ </h2>
                        <span class="counter-item__text"> Trusted Agents </span>
                    </div>
                </div>
            </div>


        </div>


        <section class="login-testimonial-section">
  <div class="scroll-container-TESTIS">
    <div class="scroll-wrapper-TESTIS">

      <!-- Testimonials - Set 1 -->
      <div class="login-testimonial-card">
        <img  src="{{ asset('assets/front/images/new-images/image.png') }}" alt="John Doe">
        <h5 class="testimonial-name mt-3">Priya S.,</h5>
        <p class="testimonial-role">Home Buyer</p>
        <p class="testimonial-text">"Dala Maaf made finding my dream home so easy! The verified listings and quick support saved me a lot of time and stress."
</p>
        <div class="testimonial-stars">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star inactive"></i>
        </div>
      </div>

      <div class="login-testimonial-card">
                <img  src="{{ asset('assets/front/images/new-images/image.png') }}" alt="John Doe">

        <h5 class="testimonial-name mt-3">Rajesh K., </h5>
        <p class="testimonial-role">Investor</p>
        <p class="testimonial-text">"I’ve connected with trusted agents through Dala Maaf and made a smart investment. Their platform is reliable and user-friendly."
</p>
        <div class="testimonial-stars">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
        </div>
      </div>

      <div class="login-testimonial-card">
                        <img  src="{{ asset('assets/front/images/new-images/image.png') }}" alt="John Doe">

        <h5 class="testimonial-name mt-3">Sneha M., </h5>
        <p class="testimonial-role">Renter</p>
        <p class="testimonial-text">"Searching for rental properties used to be a headache, but Dala Maaf made it seamless. I found the perfect apartment in just a few days!"
</p>
        <div class="testimonial-stars">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star inactive"></i>
          <i class="fas fa-star inactive"></i>
          <i class="fas fa-star inactive"></i>
        </div>
      </div>

      <!-- Duplicate Testimonials for Seamless Loop -->
      <div class="login-testimonial-card">
                       <img  src="{{ asset('assets/front/images/new-images/image.png') }}" alt="John Doe">

        <h5 class="testimonial-name mt-3">Ankit P.,</h5>
        <p class="testimonial-role">Agent</p>
        <p class="testimonial-text">"Being an agent on Dala Maaf has helped me reach more clients and grow my business. The platform is professional and efficient."
</p>
        <div class="testimonial-stars">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star inactive"></i>
        </div>
      </div>

      <div class="login-testimonial-card">
                      <img  src="{{ asset('assets/front/images/new-images/image.png') }}" alt="John Doe">

        <h5 class="testimonial-name mt-3">Kavita R., </h5>
        <p class="testimonial-role">First-Time Buyer</p>
        <p class="testimonial-text">"I was nervous about buying my first property, but Dala Maaf guided me every step of the way. Their 24/7 support is amazing!"</p>
        <div class="testimonial-stars">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
        </div>
      </div>

      <div class="login-testimonial-card">
                     <img  src="{{ asset('assets/front/images/new-images/image.png') }}" alt="John Doe">

        <h5 class="testimonial-name mt-3">Ankits Flores</h5>
        <p class="testimonial-role">Buyer</p>
        <p class="testimonial-text">" agent on Dala Maaf has helped me reach more clients and grow my business"</p>
        <div class="testimonial-stars">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star inactive"></i>
          <i class="fas fa-star inactive"></i>
          <i class="fas fa-star inactive"></i>
        </div>
      </div>

    </div>
  </div>
</section>


<section class="new-login-highlight-section p-relative" >             
    <div class="new-login-highlight-img wow fadeInRight animated" data-animation="fadeInRight animated" data-delay=".2s" style="visibility: visible; animation-name: fadeInRight;"></div>
    <div class="container">
        <div class="row">
            <div class="col-xl-6">
                <div class="s-about-img p-relative">
                    <img src="{{ asset('assets/front/images/acrs-imag/login-acrs.png') }}" alt="img">
                </div>
            </div>
            <div class="col-xl-6">
                <div class="new-login-highlight-wrap">
                    <div class="new-login-highlight-title new-login-highlight-left mb-35 wow fadeInDown animated" data-animation="fadeInDown animated" data-delay=".2s" style="visibility: visible; animation-name: fadeInDown;">
                        <span>Best Place</span>
                        <span>For Sell Properties</span>
                    </div>
                    <div class="new-login-highlight-content wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s" style="visibility: visible; animation-name: fadeInUp;">
                        <p>Pellentesque habitant morbi tristique senectus et netus et fames acturpis egestas. Vestibulum tortor quam, feugiat vitae, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. mivitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien.</p>
                        
                        <div class="new-login-highlight-list mt-20 mb-20">
                            <ul>
                                <li>
                                   <i class="fas fa-bed" style="color : #6c603c;"></i>
                                    <span>3 Bedrooms.</span>
                                </li>
                                <li>
                                   <i class="fal fa-pencil-ruler" style="color : #6c603c;"></i>
                                    <span>Square Feet </span>
                                </li>
                                <li>
                                    <i class="fas fa-bath" style="color : #6c603c;"></i>
                                    <span>Bedrooms</span>
                                </li>
                                <li>
                                   <i class="fas fa-car" style="color : #6c603c;"></i>
                                    <span>Car parking</span>
                                </li>
                            </ul>
                        </div>									
                        <div class="new-login-highlight-btn ">
                            <a href="#" class="btn-cus-header" style="padding: 15px 30px;">Contact Us</a>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </div>
</section>


<!-- <section class="new-login-services-section"> 
  <div class="new-login-services-div">

    <div class="row"> 
      

      <div class="col-12 col-md-6"> 
     
        <div class="new-login-services-title">
          <h2>Our Services</h2>
          <p>Lorem ipsum dolor sit amet consectetur adipiscing<br>
              elit sed do eiusmod tempor.</p>
        </div>
  
      </div>
    
      <div class="col-12 col-md-3 new-login-services-feature new-login-services-square">
        <div class="new-login-services-icon new-login-services-bg-medium-blue new-login-services-color-dark-blue"><i class="fas fa-city"></i></div>
        <h5>Leasing Advisory</h5>
        <p>There are many variations of passages of lorem ipsum available. </p>
      </div>
     
      <div class="col-12 col-md-3 new-login-services-feature new-login-services-square">
        <div class="new-login-services-icon new-login-services-bg-medium-red new-login-services-color-dark-brown"><i class="fas fa-user-tag"></i></div>
        <h5>Strategy &amp; Consulting</h5>
        <p>There are many variations of passages of lorem ipsum available.</p>
      </div>
 
      
    </div>
  
    

    <div class="row" style="margin-top: 74px;"> 
      

      <div class="col-12 col-md-3 new-login-services-feature new-login-services-square">
        <div class="new-login-services-icon new-login-services-bg-medium-green new-login-services-color-dark-green"><i class="fas fa-door-open"></i></div>
        <h5>Space Enablement</h5>
        <p>There are many variations of passages of lorem ipsum available.</p>
      </div>

      <div class="col-12 col-md-3 new-login-services-feature new-login-services-square">
        <div class="new-login-services-icon new-login-services-bg-medium-purple"><i class="fas fa-walking"></i></div>
        <h5>Portfolio Services</h5>
        <p>There are many variations of passages of lorem ipsum available.</p>
      </div>
   
      <div class="col-12 col-md-3 new-login-services-feature new-login-services-square">
        <div class="new-login-services-icon new-login-services-bg-light-red new-login-services-color-dark-brown"><i class="fas fa-wind"></i></div>
        <h5>Facilities Management</h5>
        <p>There are many variations of passages of lorem ipsum available.</p>
      </div>
     
      <div class="col-12 col-md-3 new-login-services-feature new-login-services-square">
        <div class="new-login-services-icon new-login-services-bg-medium-green new-login-services-color-dark-green"><i class="fas fa-door-open"></i></div>
        <h5>Space Enablement</h5>
        <p>There are many variations of passages of lorem ipsum available.</p>
      </div>

      
    </div>

  </div>
</section> -->

@endsection


<script>
function togglePassword(inputId, eyeId) {
    const input = document.getElementById(inputId);
    const eyeIcon = document.getElementById(eyeId);

    if (input.type === "password") {
        input.type = "text";
        eyeIcon.classList.replace("fa-eye", "fa-eye-slash");
    } else {
        input.type = "password";
        eyeIcon.classList.replace("fa-eye-slash", "fa-eye");
    }
}
</script>
