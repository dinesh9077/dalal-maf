@php
$version = $basicInfo->theme_version;
@endphp
@extends("frontend.layouts.layout-v$version")
@section('pageHeading')
@if (!empty($pageHeading))
{{ $pageHeading->vendor_signup_page_title ? $pageHeading->vendor_signup_page_title : __('Signup') }}
@else
{{ __('Signup') }}
@endif
@endsection
@section('metaKeywords')
@if (!empty($seoInfo))
{{ $seoInfo->meta_keywords_vendor_signup }}
@endif
@endsection

@section('metaDescription')
@if (!empty($seoInfo))
{{ $seoInfo->meta_description_vendor_signup }}
@endif
@endsection

@section('content')

<style>   .login-new-faq-sec-button:not(.collapsed) {
      background-color: white !important;
      box-shadow: none !important;
    }
    .login-new-faq-sec-body {
      padding: 0px 20px 20px 20px;
    }
    
    .new-lable-radio {
    cursor: pointer;
}

.radio-box {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 15px;
    border: 2px solid transparent;
    border-radius: 10px;
    transition: 0.3s;
}

.radio-box img{
    width: 30px;
}

.new-lable-radio input:checked + .radio-box {
    border: 2px solid #6c603c; /* selected border color */
    box-shadow: 0 0 8px rgba(255, 102, 0, 0.5);
    background: #fff8f3; /* हल्का सा background highlight */
}

    
    </style>


<div class="authentication-area authentivation-area-gradient pt-80 ">
    <div class="container" style="position: relative;">

        <div class="login-container">

            <div class="login-right ">
                <div class="postProperty_mb16__2PNyT">
                    <div class="new-left-title-sale-on">Join  <span class="postProperty_blueColor__3_QsX"> DalalMaf </span> today and explore
                       online faster a smarter way to buy, sell, or rent properties.
                    </div>
                </div>
                <span class="postProperty_USPsContainer__m80R2"><span class="postProperty_tickGreen___dOl6"></span><span class="postProperty_USPs__3JtoO">Quick & Easy Signup</span></span>
                <span class="postProperty_USPsContainer__m80R2"><span class="postProperty_tickGreen___dOl6"></span><span class="postProperty_USPs__3JtoO">Access to verified property listings</span></span>
                <span class="postProperty_USPsContainer__m80R2"><span class="postProperty_tickGreen___dOl6"></span><span class="postProperty_USPs__3JtoO">Connect directly with buyers, sellers & renters</span><span class="postProperty_star__JryVi">*</span></span>
                <span class="postProperty_USPsContainer__m80R2"><span class="postProperty_tickGreen___dOl6"></span><span class="postProperty_USPs__3JtoO">Safe and transparent platform</span><span class="postProperty_star__JryVi">*</span></span>
                <span class="postProperty_USPsContainer__m80R2"><span class="postProperty_tickGreen___dOl6"></span><span class="postProperty_USPs__3JtoO">Get real-time updates and enquiries</span><span class="postProperty_star__JryVi">*</span></span>
                <img src="https://static.99acres.com/universalapp/img/Desktop_Animation_compress.gif" class="postProperty_ppfTabImg__2WDMp">
                <div class="postProperty_bottomText__2SJ3N">* Available with Owner Assist Plans</div>
            </div>

            <div class="login-left " style="padding: 14px 21px;">
                <div class="new-title-LRP">
                    @if (Session::has('success'))
                    <div class="alert alert-success">{{ __(Session::get('success')) }}</div>
                    @endif
                    <form action="{{ route('vendor.signup_submit') }}" method="POST">
                        @csrf
                        <div class="title">
                            <h4 class="mb-20">{{ __('Start posting your property, it’s free') }}</h4>
                        </div>


                        <div class="form-group mb-20 input-box">
                            <!-- <img src="{{ asset('assets/front/images/new-images/user.png') }}" alt="user"> -->
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                                placeholder="{{ __('FullName') . '*' }}" required>
                            @error('name')

                            <p class="text-danger mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group mb-20 input-box">
                            <!-- <img src="{{ asset('assets/front/images/new-images/user.png') }}" alt="user"> -->
                            <input type="text" class="form-control" value="{{ old('username') }}" name="username"
                                placeholder="{{ __('Username') . '*' }}" required>
                            @error('username')
                            <p class="text-danger mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group mb-20 input-box">
                            <!-- <img src="{{ asset('assets/front/images/new-images/mail.png') }}" alt="user"> -->
                            <input type="text" class="form-control" value="{{ old('email') }}" name="email"
                                placeholder="{{ __('Email') . '*' }}" required>
                            @error('email')
                            <p class="text-danger mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group mb-20 input-box position-relative">
                            <!-- <img src="{{ asset('assets/front/images/new-images/password.png') }}" alt="user"> -->

                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="{{ __('Password') . '*' }}" required>

                            <!-- Eye Icon -->
                            <span class="toggle-password" onclick="togglePassword('password','eyeIcon1')">
                                <i class="fa fa-eye" id="eyeIcon1"></i>
                            </span>

                            @error('password')
                            <p class="text-danger mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group mb-20 input-box position-relative">
                            <!-- <img src="{{ asset('assets/front/images/new-images/password.png') }}" alt="user"> -->

                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation" value="{{ old('password_confirmation') }}"
                                placeholder="{{ __('Confirm Password') . '*' }}" required>

                            <!-- Eye Icon -->
                            <span class="toggle-password" onclick="togglePassword('password_confirmation','eyeIcon2')">
                                <i class="fa fa-eye" id="eyeIcon2"></i>
                            </span>

                            @error('password_confirmation')
                            <p class="text-danger mt-2">{{ $message }}</p>
                            @enderror
                        </div>


                        @if ($recaptchaInfo->google_recaptcha_status == 1)
                        <div class="form-group mb-30">
                            {!! NoCaptcha::renderJs() !!}
                            {!! NoCaptcha::display() !!}

                            @error('g-recaptcha-response')
                            <p class="mt-1 text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        @endif

                         <div class="mb-20 input-box d-flex" style="gap:20px;">
                            <label class="new-lable-radio">
                                <input type="radio" name="usertype" value="Builder" checked style="display: none;">
                                <div class="radio-box">
                                    <img src="{{ asset('assets/front/images/acrs-imag/builder-1.png') }}" alt="Builder">
                                    <h6 style="    margin: 0px;">Builder</h6>
                                </div>
                            </label>

                            <label class="new-lable-radio">
                                <input type="radio" name="usertype" value="Owner" style="display: none;">
                                <div class="radio-box">
                                    <img src="{{ asset('assets/front/images/acrs-imag/owner-1.png') }}" alt="Owner">
                                    <h6 style="    margin: 0px;">Owner</h6>
                                </div>
                            </label>
                        </div>



                        <button type="submit" class="btn btn-lg  w-100"> {{ __('Signup') }} </button>
                        <div>
                            <div class="link go-signup mt-3">
                                {{ __('Already have an account') . '?' }} <a
                                    href="{{ route('vendor.login') }}"><span style="color: red;">{{ __('Log in') }}</span></a>

                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>



    <div class="know-more-div">
        <span class="caption_strong_large">Know More</span>
       <i class="fa fa-angle-down" style="margin-left: 10px;"></i>
    </div>

    </div>
</div>

<section class="steps-section">
    <p class="lead">HOW TO POST</p>
    <h2 class="">Post Your Property in<br>3 Simple Steps</h2>
    <div class="row justify-content-center mt-4">
      <div class="col-12 col-md-4 mb-3 d-flex align-items-stretch">
        <div class="step-box w-100">
          <span class="step-icon">
            <!-- home icon/emoji - or place SVG as needed -->
            <img  src="{{ asset('assets/front/images/acrs-imag/3.png') }}"   alt="Property Details Icon" style="width : 48px;"/>
          </span>
          <div class="step-title"><span class="step-no">01.</span> Create Your Free Account</div>
          <div class="step-desc">
            Sign up on DalalMaf in just a few seconds with your basic details.
          </div>
        </div>
      </div>
      <div class="col-12 col-md-4 mb-3 d-flex align-items-stretch">
        <div class="step-box w-100">
          <span class="step-icon">
            <img src="{{ asset('assets/front/images/acrs-imag/2.png') }}"   alt="Photos & Videos Icon" style="width : 48px;"/>
          </span>
          <div class="step-title"><span class="step-no">02.</span>  Add Property Details</div>
          <div class="step-desc">
           Enter property type, location, photos, and pricing to make your listing stand out.
          </div>
        </div>
      </div>
      <div class="col-12 col-md-4 mb-3 d-flex align-items-stretch">
        <div class="step-box w-100">
          <span class="step-icon">
            <img  src="{{ asset('assets/front/images/acrs-imag/1.png') }}"   alt="Pricing & Ownership Icon" style="width : 48px;"/>
          </span>
          <div class="step-title"><span class="step-no">03.</span> Publish & Connect</div>
          <div class="step-desc">
           Post your property and start getting enquiries from verified buyers and renters instantly.
          </div>
        </div>
      </div>
    </div>

    <button  class="Property-btn-ace"><span>Begin to Post your Property</span></button>
</section>


   <section class="steps-section property-sign-details">
    <!-- Background Image -->
    <img class="property-bg-img" src="{{ asset('assets/front/images/acrs-imag/property-details-bg.png') }}" alt="Building Background">
    <!-- Card Overlay -->
    <div class="property-overlay-card">
      <div class="property-card-title">
       Dala Maaf is transforming real estate across India. With thousands of listings, trusted agents, and happy users, we make buying, selling, and renting simple and reliable
      </div>
      <div class="property-card-numbers">
        <div class="property-card-num-box">
          <div class="property-key">OVER</div>
          <div class="property-value">10,000+</div>
          <div class="property-label"> Verified properties Listings across residential & commercial </div>
        </div>
        <div class="property-card-num-box">
          <div class="property-key">OVER</div>
          <div class="property-value">100+</div>
          <div class="property-label">Cities Covered with a growing presence every month</div>
        </div>
        <div class="property-card-num-box">
          <div class="property-key">OVER</div>
          <div class="property-value">24/7</div>
          <div class="property-label">Customer Assistance for hassle-free property suppor this is </div>
        </div>
      </div>
    </div>
  </section>


  <div class="steps-section">
    <div class="resntyl-posted-section">
            <!-- Heading -->
            <div class="resntyl-main-heading">
              Recently Posted <br /> Properties
            </div>

            <!-- Slider -->
            <div class="resntyl-slider-container">
              <div class="resntyl-slider-track">

                <!-- Slide 1 -->
                <a class="resntyl-sliding-item"
                  href="https://www.99acres.com/residential-land-plot-for-sale-in-nawabpet-vikarabad-road-hyderabad-67761-sq-yard-spid-G85486960"
                  target="_blank">
                  <div class="resntyl-image-container">
                    <img src="{{ asset('assets/front/images/acrs-imag/resentyle-home.png') }}" alt="Property" class="resntyl-image" />
                  </div>
                  <div>
                    <div class="resntyl-main-title" >
                      <span class="resntyl-name-title">Priya S., Home Buyer</span>
                    Dala Maaf made finding my dream home so easy! The verified....
                    </div>
                    <div class="resntyl-time-text">Today</div>
                  </div>
                </a>

                <!-- Slide 2 -->
                <a class="resntyl-sliding-item" href="#" target="_blank">
                  <div class="resntyl-image-container">
                    <img src="{{ asset('assets/front/images/acrs-imag/resentyle-home.png') }}" alt="Property" class="resntyl-image" />
                  </div>
                  <div>
                    <div class="resntyl-main-title">
                      <span class="resntyl-name-title">Rajesh K., Investor</span>
                      I’ve connected with trusted agents through Dala Maaf....
                    </div>
                    <div class="resntyl-time-text">Yesterday</div>
                  </div>
                </a>

                <!-- Slide 3 -->
                <a class="resntyl-sliding-item" href="#" target="_blank">
                  <div class="resntyl-image-container">
                    <img src="{{ asset('assets/front/images/acrs-imag/resentyle-home.png') }}" alt="Property" class="resntyl-image" />
                  </div>
                  <div>
                    <div class="resntyl-main-title">
                      <span class="resntyl-name-title">Sneha M., Renter</span>
                    Searching for rental properties used to be a headache....
                    </div>
                    <div class="resntyl-time-text">2 Days Ago</div>
                  </div>
                </a>

                 <a class="resntyl-sliding-item" href="#" target="_blank">
                  <div class="resntyl-image-container">
                    <img src="{{ asset('assets/front/images/acrs-imag/resentyle-home.png') }}" alt="Property" class="resntyl-image" />
                  </div>
                  <div>
                    <div class="resntyl-main-title">
                      <span class="resntyl-name-title">Ankit P., Agent</span>
                  Being an agent on Dala Maaf has helped me reach more....
                    </div>
                    <div class="resntyl-time-text">2 Days Ago</div>
                  </div>
                </a>



 <a class="resntyl-sliding-item" href="#" target="_blank">
                  <div class="resntyl-image-container">
                    <img src="{{ asset('assets/front/images/acrs-imag/resentyle-home.png') }}" alt="Property" class="resntyl-image" />
                  </div>
                  <div>
                    <div class="resntyl-main-title">
                      <span class="resntyl-name-title">Kavita R., First-Time Buyer</span>
                  "I was nervous about buying my first property...
                    </div>
                    <div class="resntyl-time-text">2 Days Ago</div>
                  </div>
                </a>

              </div>

              <div class="resntyl-slider-pagination" style="margin-top: 30px;">
                <span class="resntyl-dot active"></span>
                <span class="resntyl-dot"></span>
                <span class="resntyl-dot"></span>
              </div>
            </div>
    </div>
  </div>


    <div class="Testimonial-section-ace">

      <div class="Testimonial-section-ace-title">
        <div class="Testimonial-section-ace-logo">

          <img width="80" height="65"
            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAABBCAYAAABGmyOTAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAPJSURBVHgB7Zo9bBNBEIVn1paQcIKo4CRokhrqUJM+1KFOTx9610lNevehTh/qUENDkOUOsImEfDvs7jnBR3zO3s7c5k7ar4iU+x2/nTc7t3cAQtD0cp9m4wMajwfQIehqvEW/x0f0a/wEAkAQwIoHqPYX/01Aq0PczCbQcqx4kNPQqDBwcaP6gA+zT3WuwRaQZpd7AOrgv61BwcTEZRzScSHe8g4c4UY28r0OS0CaftsF7L2rPqBeMLFw4ik9ND+/wrZ4DhpOfFwULGA5/dfewjuYGNwt3s2RXqUoSED/IOoFEwOafje2xS3vE3J9go+enVbtri2gm2U39bG/eP7BNI3tEszfPagNnuIgO1m5B2pirHsIml5BMNXBNEm5Uwi6wkoX1RKQH8T6YJrC1WttZlz+lSbQV0f4IPt8vUV5n2qDEBHPgq6GhjavdXD30Po9iGDintPQNN4711u8BJQN4joWdRYlA3uwH1Svq0D8stzf+mWgfBAjE0Tj/aFxzWsg2gUpyFj4J5QS6U4BxYNQdB5FvMI1b0EKKx6Zup1ls+XNawV0LYt0ED+UQDH3QNI1BDMn3oqSsz4DNyWDWD2CTSDuGsgrn6T6VacUM2RI01mBcosLcZ5EnGtEFpqKej14fla1uzoDnQWEKCaNKCszLvukXFPMuGvr9UoBXfZJWcAjCFGkarYtOTkM7zpsdQZKZZ8tvh5BSCGafaBHPn3qLQFls88vCDGksg/pDDeq694ytzNQLPtogoN4Ky9i2eesq7xLTmkWFs0+07JATDQIuYZOzSq6t2vKGdiHFyCBtUBE6y5arpfAJcA1ZQFz/QYkqGEBEVQu1K/q2nHfCFi8paqx1F1F5Oxb3JSxwLvAZp/nxLHMvwxUOT8Iyx8VdcnerVOKtC46yDVLFu7tABcyTfPj7CvEJBcaeOpfQABOQCL7OYZAEYb5R4gN9gQmPrPEFlh2igy8mguIB8GjGHw7qYEnHfycXghIyB9Fa9/Yk8cU+JOehTHwixrY4weidNzaZ8FcIPt4A188iRBt85fP1CT4LdsMZkELrYjbwAVhxnk7iHLvTDnghXnZXvutX+3PNBpAwRw69UFkiXsWz6JMA823wT1QNND3j51EGv86oBFa4hwjID6FLtIS5xgB1UPoItSWDCS9AV0E2yIgdjUDsS01kLpZAxFa4Rzv7wPbRzuc02EB20ESkEkSkEkSkEkSkEkSkEkSkEkSkEkSkEkSkEkSkEkSkEkSkEkSkEkSkEkSkEkSkEkSkEkSkEkSkEkSkEkSkEkSkMlfklqi+1iIHhIAAAAASUVORK5CYII=">
          <h2 class="Testimonial-section-ace-label">TESTIMONIALS</h2>
        </div>
        <div class="Testimonial-section-ace-heading spacer50">
          This is what other Owners &amp; <br> Dealers have to say...
        </div>
      </div>

      <div class="Testimonial-section-ace-slider">
        <button class="Testimonial-section-ace-arrow left">&#8592;</button>
        <div class="Testimonial-section-ace-wrapper">
          <div class="Testimonial-section-ace-track" id="testimonialSliderTrack">
            <div class="Testimonial-section-ace-card">
              <h3>Priya S., Home Buyer</h3>
              <div class="Testimonial-section-ace-role">Owner, Hyderabad</div>
              <p>"Dala Maaf made finding my dream home so easy! The verified listings and quick support saved me a lot of time and stress."
</p>
            </div>

            <div class="Testimonial-section-ace-card">
              <h3>Rajesh K., Investor</h3>
              <div class="Testimonial-section-ace-role">Owner, Bangalore</div>
             <p>"I’ve connected with trusted agents through Dala Maaf and made a smart investment. Their platform is reliable and user-friendly."
</p>
            </div>

            <div class="Testimonial-section-ace-card">
              <h3>Sneha M., Renter</h3>
              <div class="Testimonial-section-ace-role">Property Agent, </div>
            <p>"Searching for rental properties used to be a headache, but Dala Maaf made it seamless. I found the perfect apartment in just a few days!"
</p></div>
          </div>



        </div>
        <button class="Testimonial-section-ace-arrow right">&#8594;</button>
      </div>

    </div>


    <div class="addition-benefits-section">
        <div class="addition-benefits-label">ADDITIONAL BENEFITS</div>
        <div class="addition-benefits-partition">
            <div>
                <h2 class="addition-benefits-title">
                    <main>
                        <span style="display: block;">Everything dalalmaf does to sell or</span>
                        <span>rent out your property faster...</span>
                    </main>
                </h2>
                <div class="addition-benefits-listings">
                    <div class="addition-benefits-caption">Post free property ads ondalalmaf, India’s No. 1
                        property portal, to find genuine buyers and tenants. If you are the owner of a house, flat,
                        apartment, villa, or any other residential property, you can conveniently post property for rent
                        or sale on our digital platform. Also, find your ideal tenants and buyers quickly to lease or
                        sell your land, office space, shop, showroom, or any other commercial real estate. Whether you
                        are a property owner, builder or broker, you can rent or sell property online ondalalmaf
                        with ease.</div>
                    <div class="addition-benefits-caption">99acres.com is one of the most trustworthy portals buyers
                        and tenants online for flats, independent houses, offices, shops, showrooms, warehouses, land
                        and factories. What makesdalalmaf unique is our high-quality website traffic and reach to
                        millions of households across India and abroad, who are looking to buy or rent residential or
                        commercial properties across India.</div>
                </div>
               <button class="Property-btn-ace-add-ben" ><span>Begin to Post your Property</span></button>
            </div>
        </div>
    </div>


    <div class="addition-benefits-section">
        <div class="py-5 login-new-faq-sec-container text-start">
          <h2 class="fw-bold mb-4 login-new-faq-sec-title">Frequently Asked Questions</h2>

          <div class="accordion login-new-faq-sec-accordion" id="loginNewFaqAccordion">

            <!-- FAQ 1 (Open by default) -->
            <div class="accordion-item login-new-faq-sec-item">
              <h2 class="accordion-header login-new-faq-sec-header" id="faq1-heading">
                <button class="accordion-button login-new-faq-sec-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="true" aria-controls="faq1">
                 What is Dala Maaf?
                </button>
              </h2>
              <div id="faq1" class="accordion-collapse collapse show" aria-labelledby="faq1-heading">
                <div class="accordion-body login-new-faq-sec-body">
                 Dala Maaf is a trusted real estate platform connecting buyers, sellers, renters, and agents with verified residential and commercial properties across India.
                </div>
              </div>
            </div>

            <!-- FAQ 2 (Open by default) -->
            <div class="accordion-item login-new-faq-sec-item">
              <h2 class="accordion-header login-new-faq-sec-header" id="faq2-heading">
                <button class="accordion-button login-new-faq-sec-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" aria-expanded="true" aria-controls="faq2">
                How do I buy or rent a property on Dala Maaf?
                </button>
              </h2>
              <div id="faq2" class="accordion-collapse collapse show" aria-labelledby="faq2-heading">
                <div class="accordion-body login-new-faq-sec-body">
                Simply search for your desired property, filter by location, type, or budget, and connect directly with verified agents or owners.

                </div>
              </div>
            </div>

            <!-- FAQ 3 (Closed by default) -->
            <div class="accordion-item login-new-faq-sec-item">
              <h2 class="accordion-header login-new-faq-sec-header" id="faq3-heading">
                <button class="accordion-button login-new-faq-sec-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" aria-expanded="false" aria-controls="faq3">
               Are the listings verified?
                </button>
              </h2>
              <div id="faq3" class="accordion-collapse collapse" aria-labelledby="faq3-heading">
                <div class="accordion-body login-new-faq-sec-body">
                 Yes! Every property on Dala Maaf is carefully verified to ensure authenticity and transparency.
                </div>
              </div>
            </div>

            <!-- FAQ 4 (Closed by default) -->
            <div class="accordion-item login-new-faq-sec-item">
              <h2 class="accordion-header login-new-faq-sec-header" id="faq4-heading">
                <button class="accordion-button login-new-faq-sec-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4" aria-expanded="false" aria-controls="faq4">
                 Can I list my property on Dala Maaf?
                </button>
              </h2>
              <div id="faq4" class="accordion-collapse collapse" aria-labelledby="faq4-heading">
                <div class="accordion-body login-new-faq-sec-body">
                  Absolutely. You can easily create an account, upload property details, and reach thousands of potential buyers or renters.
                </div>
              </div>
            </div>



          </div>
        </div>
    </div>


<!-- Authentication-area end -->
@endsection

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
    function togglePassword(inputId, eyeId) {
        const input = document.getElementById(inputId);
        const eyeIcon = document.getElementById(eyeId);

        if (input.type === "password") {
            input.type = "text";
            eyeIcon.classList.remove("fa-eye");
            eyeIcon.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            eyeIcon.classList.remove("fa-eye-slash");
            eyeIcon.classList.add("fa-eye");
        }
    }


  // ====================== Resently Posted

  $(document).ready(function () {
  const $track = $('.resntyl-slider-track');
  const $slides = $('.resntyl-sliding-item');
  const $dots = $('.resntyl-slider-pagination .resntyl-dot');

  let index = 0;
  const slideCount = $slides.length;

  function updateSlide() {
    const offset = index * 55; // since min-width is 55%
    $track.css('transform', `translateX(-${offset}%)`);

    // Update dots
    $dots.removeClass('active');
    $dots.eq(index % $dots.length).addClass('active');
  }

  setInterval(function () {
    index = (index + 1) % slideCount;
    updateSlide();
  }, 4000);

  updateSlide(); // initialize
});

    // ================


    $(document).ready(function() {
    const $sliderTrack = $('#testimonialSliderTrack');
    const $leftBtn = $('.Testimonial-section-ace-arrow.left');
    const $rightBtn = $('.Testimonial-section-ace-arrow.right');

    let curIndex = 0;

    function getCardWidth() {
        const $card = $sliderTrack.find('.Testimonial-section-ace-card').first();
        const gap = 24; // same as CSS gap
        return $card.outerWidth(true) + gap;
    }

    function getVisibleCards() {
        const width = $(window).width();
        if (width <= 700) return 1;
        if (width <= 980) return 2;
        return 2.5;
    }

    function scrollToIndex(index) {
        const scrollAmount = getCardWidth() * index;
        $sliderTrack.css('transform', `translateX(-${scrollAmount}px)`);
    }

    function getMaxIndex() {
        const totalCards = $sliderTrack.find('.Testimonial-section-ace-card').length;
        const visibleCards = getVisibleCards();
        return Math.max(0, totalCards - Math.floor(visibleCards));
    }

    $leftBtn.on('click', function() {
        curIndex = Math.max(curIndex - 1, 0);
        scrollToIndex(curIndex);
    });

    $rightBtn.on('click', function() {
        const maxIndex = getMaxIndex();
        curIndex = Math.min(curIndex + 1, maxIndex);
        scrollToIndex(curIndex);
    });

    $(window).on('resize', function() {
        const maxIndex = getMaxIndex();
        curIndex = Math.min(curIndex, maxIndex);
        scrollToIndex(curIndex);
    });
});


</script>
