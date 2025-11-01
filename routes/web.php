<?php
	
	use Illuminate\Support\Facades\Route;
	
	/*
		|--------------------------------------------------------------------------
		| User Interface Routes
		|--------------------------------------------------------------------------
	*/
	Route::post('/visitor/leave', 'BackEnd\AdminController@leave')->name('visitor.leave');
	Route::post('/push-notification/store-endpoint', 'FrontEnd\PushNotificationController@store');
	
	Route::post('/update-visitor', 'BackEnd\AdminController@updateVisitor')->name('visitors.update');
	Route::post('/remove-visitor', 'BackEnd\AdminController@removeVisitor')->name('visitors.leave');
	
	// cron job for sending expiry mail
	Route::get('/subcheck', 'CronJobController@expired')->name('cron.expired');
	
	Route::get('/change-language', 'FrontEnd\MiscellaneousController@changeLanguage')->name('change_language');
	
	Route::post('/store-subscriber', 'FrontEnd\MiscellaneousController@storeSubscriber')->name('store_subscriber');
	
	Route::get('/offline', 'FrontEnd\HomeController@offline')->middleware('change.lang');
	
	Route::middleware('change.lang')->group(function () {
		Route::get('/', 'FrontEnd\HomeController@index')->name('index');
		
		Route::prefix('vendors')->group(function () {
			Route::get('/', 'FrontEnd\VendorController@index')->name('frontend.vendors');
			Route::post('contact/message', 'FrontEnd\VendorController@contact')->name('vendor.contact.message');
		});
		
		// Properties route
		Route::get('/location-search', 'FrontEnd\PropertyController@locationSearch')->name('frontend.location.search');
		Route::get('/properties', 'FrontEnd\PropertyController@index')->name('frontend.properties');
		Route::get('/properties/featured/all', 'FrontEnd\PropertyController@featuredAll')->name('frontend.properties.featured.all');
		Route::get('/state-cities', 'FrontEnd\PropertyController@getStateCities')->name('frontend.get_state_cities');
		Route::get('/cities', 'FrontEnd\PropertyController@getCities')->name('frontend.get_cities');
		Route::get('/areas', 'FrontEnd\PropertyController@getAreas')->name('frontend.get_areas');
		Route::get('/categories', 'FrontEnd\PropertyController@getCategories')->name('frontend.get_categories');
		Route::get('/property/{slug}', 'FrontEnd\PropertyController@details')->name('frontend.property.details');
		Route::post('/property-contact', 'FrontEnd\PropertyController@contact')->name('property_contact');
		Route::post('/contact-mail', 'FrontEnd\PropertyController@contactUser')->name('contact_user');
		// Properties route
		Route::get('/projects', 'FrontEnd\ProjectController@index')->name('frontend.projects');
		Route::get('/project/{slug}', 'FrontEnd\ProjectController@details')->name('frontend.projects.details');
		// property wishlist
		Route::get('addto/wishlist/{id}', 'FrontEnd\UserController@add_to_wishlist')->name('addto.wishlist');
		Route::get('remove/wishlist/{id}', 'FrontEnd\UserController@remove_wishlist')->name('remove.wishlist');
		Route::get('/wishlist/count', 'FrontEnd\UserController@wishListCount')->name('wishlist.count');
		
		Route::get('vendor/{username}', 'FrontEnd\VendorController@details')->name('frontend.vendor.details');
		Route::get('agent/{username}', 'FrontEnd\AgentController@details')->name('frontend.agent.details');
		
		Route::prefix('/blog')->group(function () {
			Route::get('', 'FrontEnd\BlogController@index')->name('blog');
			
			Route::get('/{slug}', 'FrontEnd\BlogController@show')->name('blog_details');
		});
		
		Route::get('/faq', 'FrontEnd\FaqController@faq')->name('faq');
		Route::get('/about-us', 'FrontEnd\HomeController@about')->name('about_us');
		Route::get('/pricing', 'FrontEnd\HomeController@pricing')->name('pricing');
		
		Route::prefix('/contact')->group(function () {
			Route::get('', 'FrontEnd\ContactController@contact')->name('contact');
			
			Route::post('/send-mail', 'FrontEnd\ContactController@sendMail')->name('contact.send_mail')->withoutMiddleware('change.lang');
		});
	});
	
	Route::post('/advertisement/{id}/count-view', 'FrontEnd\MiscellaneousController@countAdView');
	
	Route::prefix('login')->middleware(['guest:web', 'change.lang'])->group(function () {
		// user login via facebook route
		Route::prefix('/user/facebook')->group(function () {
			Route::get('', 'FrontEnd\UserController@redirectToFacebook')->name('user.login.facebook');
			
			Route::get('/callback', 'FrontEnd\UserController@handleFacebookCallback');
		});
		
		// user login via google route
		Route::prefix('/google')->group(function () {
			Route::get('', 'FrontEnd\UserController@redirectToGoogle')->name('user.login.google');
			
			Route::get('/callback', 'FrontEnd\UserController@handleGoogleCallback');
		});
	});
	
	Route::prefix('/user')->middleware(['guest:web', 'change.lang'])->group(function () {
		Route::prefix('/login')->group(function () {
			// user redirect to login page route
			Route::get('', 'FrontEnd\UserController@login')->name('user.login');
			Route::post('send-otp', 'FrontEnd\UserController@sendOtp')->name('send.otp');
			Route::post('verify-otp', 'FrontEnd\UserController@verifyOtp')->name('verify.otp');
		});
		// user login submit route
		Route::post('/login-submit', 'FrontEnd\UserController@loginSubmit')->name('user.login_submit')->withoutMiddleware('change.lang');
		
		// user forget password route
		Route::get('/forget-password', 'FrontEnd\UserController@forgetPassword')->name('user.forget_password');
		
		// send mail to user for forget password route
		Route::post('/send-forget-password-mail', 'FrontEnd\UserController@forgetPasswordMail')->name('user.send_forget_password_mail')->withoutMiddleware('change.lang');
		
		// reset password route
		Route::get('/reset-password', 'FrontEnd\UserController@resetPassword');
		
		// user reset password submit route
		Route::post('/reset-password-submit', 'FrontEnd\UserController@resetPasswordSubmit')->name('user.reset_password_submit')->withoutMiddleware('change.lang');
		
		
	});
	
	// user redirect to signup page route
	Route::get('user/signup', 'FrontEnd\UserController@signup')->name('user.signup');
	Route::post('user/signup-submit', 'FrontEnd\UserController@signupSubmit')->name('user.signup_submit');
		
	Route::prefix('/user')->middleware(['auth:web', 'account.status', 'change.lang'])->group(function () {
		// user redirect to dashboard route
		// Route::get('/dashboard', 'FrontEnd\UserController@redirectToDashboard')->name('user.dashboard');
		Route::get('/dashboard', 'FrontEnd\UserController@dashboard')->name('user.dashboard');
		Route::get('/wishlist', 'FrontEnd\UserController@wishlist')->name('user.wishlist');
		Route::get('/inquiry', 'FrontEnd\UserController@inquiry')->name('user.inquiry');
		  
		
		// signup verify route
		Route::get('/signup-verify/{token}', 'FrontEnd\UserController@signupVerify')->withoutMiddleware('change.lang');
		
		
		
		// edit profile route
		Route::get('/edit-profile', 'FrontEnd\UserController@editProfile')->name('user.edit_profile');
		
		// update profile route
		Route::post('/update-profile', 'FrontEnd\UserController@updateProfile')->name('user.update_profile')->withoutMiddleware('change.lang');
		
		// change password route
		Route::get('/change-password', 'FrontEnd\UserController@changePassword')->name('user.change_password');
		
		// update password route
		Route::post('/update-password', 'FrontEnd\UserController@updatePassword')->name('user.update_password')->withoutMiddleware('change.lang');
		
		Route::prefix('support-ticket')->group(function () {
			Route::get('/', 'FrontEnd\SupportTicketController@index')->name('user.support_ticket');
			Route::get('/create', 'FrontEnd\SupportTicketController@create')->name('user.support_ticket.create');
			Route::post('store', 'FrontEnd\SupportTicketController@store')->name('user.support_ticket.store');
			Route::get('message/{id}', 'FrontEnd\SupportTicketController@message')->name('user.support_ticket.message');
			Route::post('reply/{id}', 'FrontEnd\SupportTicketController@reply')->name('user.support_ticket.reply');
			Route::post('/zip-upload', 'FrontEnd\SupportTicketController@zip_file_upload')->name('user.support_ticket.zip_file.upload');
		});
		
		
		// user logout attempt route
		Route::get('/logout', 'FrontEnd\UserController@logoutSubmit')->name('user.logout')->withoutMiddleware('change.lang');
	});
	
	
	
	// service unavailable route
	Route::get('/service-unavailable', 'FrontEnd\MiscellaneousController@serviceUnavailable')->name('service_unavailable')->middleware('exists.down');
	
	/*
		|--------------------------------------------------------------------------
		| admin frontend route
		|--------------------------------------------------------------------------
	*/
	
	Route::prefix('/admin')->middleware('guest:admin')->group(function () {
		// admin redirect to login page route
		Route::get('/', 'BackEnd\AdminController@login')->name('admin.login');
		
		// admin login attempt route
		Route::post('/auth', 'BackEnd\AdminController@authentication')->name('admin.auth');
		
		// admin forget password route
		Route::get('/forget-password', 'BackEnd\AdminController@forgetPassword')->name('admin.forget_password');
		
		// send mail to admin for forget password route
		Route::post('/mail-for-forget-password', 'BackEnd\AdminController@forgetPasswordMail')->name('admin.mail_for_forget_password');
	});
	
	Route::post('/property/video-img-rmv', 'BackEnd\Property\PropertyController@videoImgrmv')->name('property.videoImgrmv');
	Route::post('/property/floor-img-rmv', 'BackEnd\Property\PropertyController@floorImgrmv')->name('property.floorImgrmv');
	
	/*
		|--------------------------------------------------------------------------
		| Custom Page Route For UI
		|--------------------------------------------------------------------------
	*/
	Route::get('/{slug}', 'FrontEnd\PageController@page')->name('dynamic_page')->middleware('change.lang');
	
	// fallback route
	Route::fallback(function () {
		return view('errors.404');
	})->middleware('change.lang');
	
	Route::prefix('property-management')->group(function () {
		Route::get('/properties', 'User\PropertyController@index')->name('user.property_management.properties');
		Route::get('/type', 'User\PropertyController@type')->name('user.property_management.type');
		Route::get('/create', 'User\PropertyController@create')->name('user.property_management.create_property');
		Route::post('/store', 'User\PropertyController@store')->name('user.property_management.store_property');
		Route::post('specification/delete', 'User\PropertyController@specificationDelete')->name('user.property_management.specification_delete');
		
		
		Route::post('/update_featured', 'User\PropertyController@updateFeatured')->name('user.property_management.update_featured');
		Route::post('update_status', 'User\PropertyController@updateStatus')->name('user.property_management.update_status');
		Route::get('edit-property/{id}', 'User\PropertyController@edit')->name('user.property_management.edit');
		Route::post('update/{id}', 'User\PropertyController@update')->name('user.property_management.update_property');
		Route::post('delete', 'User\PropertyController@delete')->name('user.property_management.delete_property');
		Route::post('bulk-delete', 'User\PropertyController@bulkDelete')->name('user.property_management.bulk_delete_property');
		Route::get('/get-areas', 'User\PropertyController@getAreas')->name('user.property_specification.get_areas');
		//#========== Property slider image
		Route::post('/img-store', 'User\PropertyController@imagesstore')->name('user.property.imagesstore');
		Route::post('/img-update', 'User\PropertyController@imagesstore')->name('user.property.imagesupdate');
		Route::post('/img-remove', 'User\PropertyController@imagermv')->name('user.property.imagermv');
		Route::post('/img-db-remove', 'User\PropertyController@imagedbrmv')->name('user.property.imgdbrmv');
		//#==========property slider image end
		
		Route::get('/get-states-cities', 'User\PropertyController@getStateCities')->name('user.property_specification.get_state_cities');
		Route::get('/get-cities', 'User\PropertyController@getCities')->name('user.property_specification.get_cities');
		
		
		
	});
	
	Route::get('/sent/inquiry', 'User\PropertyController@sentInquiry')->name('user.inquiry.index');
	
