<?php
	
	use Illuminate\Support\Facades\Route;
	
	/*
		|--------------------------------------------------------------------------
		| Agent Routes
		|--------------------------------------------------------------------------
	*/
	
	
	Route::prefix('agent')->middleware('change.lang')->group(function () {
		Route::get('/login', 'Agent\AgentController@login')->name('agent.login')->middleware('guest:agent');
		Route::post('/login/submit', 'Agent\AgentController@authentication')->name('agent.login_submit');
		
		Route::get('/forget-password', 'Agent\AgentController@forget_passord')->name('agent.forget.password');
		Route::post('/send-forget-mail', 'Agent\AgentController@forget_mail')->name('agent.forget.mail');
		Route::get('/reset-password', 'Agent\AgentController@reset_password')->name('agent.reset.password');
		Route::post('/update-forget-password', 'Agent\AgentController@update_password')->name('agent.update-forget-password');
	});
	
	Route::prefix('agent')->middleware('auth:agent')->group(function () 
	{
		Route::get('/dashboard', 'Agent\AgentController@dashboard')->name('agent.dashboard');
		Route::get('/change-password', 'Agent\AgentController@change_password')->name('agent.change_password');
		Route::post('/update-password', 'Agent\AgentController@updated_password')->name('agent.update_password');

		Route::get('/edit-profile', 'Agent\AgentController@edit_profile')->name('agent.edit.profile');
		Route::post('/phone/send-otp', 'Agent\AgentController@sendPhoneOtp')->name('agent.phone.send-otp');
		Route::post('/phone/resend-otp', 'Agent\AgentController@resendPhoneOtp')->name('agent.phone.resend-otp');
		Route::post('/phone/verify-otp', 'Agent\AgentController@verifyPhoneOtp')->name('agent.phone.verify-otp');
		Route::post('/profile/update', 'Agent\AgentController@update_profile')->name('agent.update_profile');
		Route::get('/logout', 'Agent\AgentController@logout')->name('agent.logout');
		
		Route::get('/wishlist', 'Agent\AgentController@wishlist')->name('agent.wishlist');
		Route::get('/inquiry', 'Agent\AgentController@inquiry')->name('agent.inquiry');
		
		// change agent-panel theme (dark/light) route
		Route::post('/change-theme', 'Agent\AgentController@changeTheme')->name('agent.change_theme');
		
		Route::middleware('vendorHasPackage')->group(function () 
		{
			// Property Management
			Route::prefix('property-management')->group(function () {
				Route::get('/properties', 'Agent\PropertyController@index')->name('agent.property_management.properties');
				Route::get('/type', 'Agent\PropertyController@type')->name('agent.property_management.type');
				Route::get('/create', 'Agent\PropertyController@create')->name('agent.property_management.create_property');
				Route::post('/store', 'Agent\PropertyController@store')->name('agent.property_management.store_property')->middleware('packageLimitsCheck:property,store');
				
				Route::post('/update_featured', 'Agent\PropertyController@updateFeatured')->name('agent.property_management.update_featured');
				Route::post('update_status', 'Agent\PropertyController@updateStatus')->name('agent.property_management.update_status');
				Route::get('edit-property/{id}', 'Agent\PropertyController@edit')->name('agent.property_management.edit');
				Route::post('update/{id}', 'Agent\PropertyController@update')->name('agent.property_management.update_property')->middleware('packageLimitsCheck:property,update');
				Route::post('delete', 'Agent\PropertyController@delete')->name('agent.property_management.delete_property');
				Route::post('bulk-delete', 'Agent\PropertyController@bulkDelete')->name('agent.property_management.bulk_delete_property');
				Route::post('specification/delete', 'Agent\PropertyController@specificationDelete')->name('agent.property_management.specification_delete');
				//#========== Property slider image
				Route::post('/img-store', 'Agent\PropertyController@imagesstore')->name('agent.property.imagesstore')->middleware('packageLimitsCheck:property,store');
				Route::post('/img-update', 'Agent\PropertyController@imagesstore')->name('agent.property.imagesupdate')->middleware('packageLimitsCheck:property,update');
				Route::post('/img-remove', 'Agent\PropertyController@imagermv')->name('agent.property.imagermv');
				Route::post('/img-db-remove', 'Agent\PropertyController@imagedbrmv')->name('agent.property.imgdbrmv');
				//#==========property slider image end
				
				Route::get('/get-states-cities', 'Agent\PropertyController@getStateCities')->name('agent.property_specification.get_state_cities');
				Route::get('/get-cities', 'Agent\PropertyController@getCities')->name('agent.property_specification.get_cities');
				//============ if agent is under admin ==================
				Route::post('/property-store', 'Agent\PropertyController@store')->name('agent.admin.property_management.store_property');
				Route::post('/property-img-store', 'Agent\PropertyController@imagesstore')->name('agent.admin.property.imagesstore');
				Route::post('/property-update/{id}', 'Agent\PropertyController@update')->name('agent.admin.property_management.update_property');
			});
			
			// Project Management route start
			Route::prefix('project-management')->group(function () {
				
				Route::get('/projects', 'Agent\ProjectController@index')->name('agent.project_management.projects');
				Route::get('/create', 'Agent\ProjectController@create')->name('agent.project_management.create_project');
				
				Route::post('/store', 'Agent\ProjectController@store')->name('agent.project_management.store_project')->middleware('packageLimitsCheck:project,store');
				
				Route::post('/update_status', 'Agent\ProjectController@updateStatus')->name('agent.project_management.update_status');
				Route::get('/edit-project/{id}', 'Agent\ProjectController@edit')->name('agent.project_management.edit');
				Route::post('/update/{id}', 'Agent\ProjectController@update')->name('agent.project_management.update_project')->middleware('packageLimitsCheck:project,update');
				Route::post('/delete', 'Agent\ProjectController@destroy')->name('agent.project_management.delete_project');
				Route::post('/bulk-delete', 'Agent\ProjectController@bulkDestroy')->name('agent.project_management.bulk_delete_project');
				
				Route::post('specification/delete', 'Agent\ProjectController@specificationDelete')->name('agent.project_management.specification_delete');
				//#========== project gallery image
				Route::post('/gallery-img-store', 'Agent\ProjectController@galleryImagesStore')->name('agent.project.gallery_image_store')->middleware('packageLimitsCheck:project,update');
				Route::post('/img-remove', 'Agent\ProjectController@galleryImageRmv')->name('agent.project.gallery_imagermv');
				Route::post('/img-db-remove', 'Agent\ProjectController@galleryImageDbrmv')->name('agent.project.gallery_imgdbrmv');
				//#========== project slider image end
				
				//#========== project gallery image ========
				Route::post('/floor-plan-img-store', 'Agent\ProjectController@floorPlanImagesStore')->name('agent.project.floor_plan_image_store');
				Route::post('/floor-plan-img-remove', 'Agent\ProjectController@floorPlanImageRmv')->name('agent.project.floor_plan_imagermv');
				Route::post('/floor-plan-img-db-remove', 'Agent\ProjectController@floorPlanImageDbrmv')->name('agent.project.floor_plan_imgdbrmv');
				//#========== project slider image end
				
				// Project type routes
				Route::prefix('type')->group(function () {
					Route::get('/{id}', 'Agent\TypeController@index')->name('agent.project_management.project_types');
					Route::post('/store', 'Agent\TypeController@store')->name('agent.project_management.project_type.store')->middleware('packageLimitsCheck:projectType,store');
					Route::post('/update', 'Agent\TypeController@update')->name('agent.project_management.project_type.update')->middleware('packageLimitsCheck:projectType,update');
					
					Route::post('/delete', 'Agent\TypeController@delete')->name('agent.project_management.delete_type');
					
					Route::post('/bulk-delete', 'Agent\TypeController@bulkDelete')->name('agent.project_management.bulk_delete_type');
					Route::post('/type-store', 'Agent\TypeController@store')->name('agent.admin.project_management.project_type.store');
					Route::post('/type-update', 'Agent\TypeController@update')->name('agent.admin.project_management.project_type.update');
				});
				
				//============ if agent is under admin ==================
				Route::post('/project-store', 'Agent\ProjectController@store')->name('agent.admin.project_management.store_project');
				Route::post('/project-update/{id}', 'Agent\ProjectController@update')->name('agent.admin.project_management.update_project');
				Route::post('/gallery-img-store', 'Agent\ProjectController@galleryImagesStore')->name('agent.project.gallery_image_store');
			});
			// Project Management Route End
			
			// Project Inventory route start
			Route::prefix('property-inventory')->group(function () {
				Route::get('/properties', 'Agent\PropertyController@index')->name('agent.property_inventory.properties');
				Route::get('/type', 'Agent\PropertyController@type')->name('agent.property_inventory.type');
				Route::get('/create', 'Agent\PropertyController@create')->name('agent.property_inventory.create_property');
				
				Route::post('/store', 'Agent\PropertyController@store')->name('agent.property_inventory.store_property')->middleware('packageLimitsCheck:property,store');
				Route::post('/update_featured', 'Agent\PropertyController@updateFeatured')->name('agent.property_inventory.update_featured');
				Route::post('update_status', 'Agent\PropertyController@updateStatus')->name('agent.property_inventory.update_status');
				
				Route::get('edit-property/{id}', 'Agent\PropertyController@edit')->name('agent.property_inventory.edit');
				Route::post('update/{id}', 'Agent\PropertyController@update')->name('agent.property_inventory.update_property')->middleware('packageLimitsCheck:property,update');
				Route::post('specification/delete', 'Agent\PropertyController@specificationDelete')->name('agent.property_inventory.specification_delete');
				
				Route::get('/view/{id}', 'Agent\PropertyController@view')->name('agent.property_inventory.view');
				Route::get('/wings/{property_id}', 'Agent\PropertyController@propertyFullWings')->name('agent.property_inventory.wings');
				Route::post('/wings/store', 'Agent\PropertyController@propertyFullWingsStore')->name('agent.property_inventory.wings.store');
				Route::post('/wings/floors', 'Agent\PropertyController@propertyFullWingsFloors')->name('agent.property_inventory.wings-floors');
				Route::post('/wing/delete', 'Agent\PropertyController@propertyFullWingDelete')->name('agent.property_inventory.wing-delete');
				Route::post('/floors/store', 'Agent\PropertyController@propertyFullFloorsStore')->name('agent.property_inventory.floor.store');
				Route::post('/floors/partial', 'Agent\PropertyController@propertyFullFloorsPartial')->name('agent.property_inventory.floor.partial');
				Route::post('/floor/delete', 'Agent\PropertyController@propertyFullFloorDelete')->name('agent.property_inventory.floor.delete');
				Route::post('/copy/floors/{floor_id}', 'Agent\PropertyController@propertyFloorCopy')->name('agent.property_inventory.copy.floor');
				Route::post('/flat/store', 'Agent\PropertyController@propertyFullFlatStore')->name('agent.property_inventory.flat.store');
				Route::post('/flat/delete', 'Agent\PropertyController@propertyFullFlatDelete')->name('agent.property_inventory.flat.delete');
				Route::post('/floor/status/change', 'Agent\PropertyController@propertyFloorStatusChanges')->name('agent.property_inventory.floor.status_change');
				Route::post('/floor/status/store', 'Agent\PropertyController@propertyWorkAssignStore')->name('agent.property_inventory.floor.status_store');
				Route::post('/add/customer', 'Agent\PropertyController@addCustomer')->name('agent.property_inventory.add.customer');
				Route::get('/status/property-wing/{property_id}', 'Agent\PropertyController@propertyByPropertyWing')->name('agent.manage.status.property.wings');
				Route::post('/property-floor-details', 'Agent\PropertyController@propertyByFloorDetails')->name('agent.manage_status_property.property-floor-details');
				Route::get('/converted/customer', 'Agent\PropertyController@convertedCustomer')->name('agent.property_inventory.converted_customer');
				Route::get('/manage/status', 'Agent\PropertyController@manageStatus')->name('agent.property_inventory.manage_status_property');
				
				
				Route::post('delete', 'Agent\PropertyController@delete')->name('agent.property_inventory.delete_property');
				Route::post('bulk-delete', 'Agent\PropertyController@bulkDelete')->name('agent.property_inventory.bulk_delete_property');
				
				Route::post('/property-store', 'Agent\PropertyController@store')->name('agent.admin.property_inventory.store_property');
				Route::post('/property-update/{id}', 'Agent\PropertyController@update')->name('agent.admin.property_inventory.update_property');
			});
			// Project Inventory Route End
			
			// customer Management
			Route::prefix('customer-management')->group(function () {
				Route::get('/', 'Agent\AccountController@customerList')->name('agent.customer_management.list');
			});
			
			
			Route::get('/sales-bill/index', 'Agent\AccountController@salesBillListing')->name('agent.sales-bill.index');
			Route::get('/accounting', 'Agent\AccountController@index')->name('agent.accounting');
			Route::post('/sales/bill/store', 'Agent\AccountController@salesBillStore')->name('account.sales-bill.store');
			Route::get('/account/sales-bill/customer-details/{customer_id}', 'Agent\AccountController@customerDetails')->name('account.sales-bill.customer.details');
			Route::post('/account/sales-bill/item/{count}', 'Agent\AccountController@salesBillItem')->name('account.sales-bill.item');
			Route::get('/account/sales-bill/invoice-view/{id}', 'Agent\AccountController@salesBillInvoiceView')->name('account.invoice.view');
			Route::get('/account/sales-bill/export/{id}', 'Agent\AccountController@salesBillExport')->name('account.invoice.export');
			Route::get('/account/sales-bill/record-payment/{id}', 'Agent\AccountController@salesBillPayment')->name('account.record.payment');
			Route::post('/account/sales-bill/record-payment-store', 'Agent\AccountController@purchaseBillPaymentStore')->name('account.record.payment.store');
			Route::get('/account/sales-bill/record-view-payment/{id}', 'Agent\AccountController@salesBillViewPayment')->name('account.record.view.payment');
			Route::get('/account/sales-bill/edit/{id}', 'Agent\AccountController@salesBillEdit')->name('account.sales-bill.edit');
			Route::post('/account/sales-bill/update/{id}', 'Agent\AccountController@salesBillUpdate')->name('account.sales-bill.update');
			Route::post('/account/sales-bill/delete/{id}', 'Agent\AccountController@salesBillDelete')->name('account.sales-bill.delete');
			Route::post('/account/sales-bill/stop-recurring/{id}', 'Agent\AccountController@salesBillStopRecurring')->name('account.sales-bill.stop.recurring');
			
			
			Route::prefix('unit-type')->group(function () {
				Route::get('/add', 'Agent\PropertyController@unitTypeAdd')->name('agent.unit_type.add');
				Route::post('/store', 'Agent\PropertyController@unitTypeStore')->name('agent.unit_type.store');
			});
			
			// property messages
			Route::get('/messages', 'Agent\PropertyController@messages')->name('agent.property_message.index');
			Route::post('/message-delete', 'Agent\PropertyController@destroyMessage')->name('agent.property_message.delete');
		});
	});
