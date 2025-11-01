<div class="sidebar sidebar-style-2"
data-background-color="{{ Session::get('agent_theme_version') == 'light' ? 'white' : 'dark2' }}">
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <div class="user">
                <div class="avatar-sm float-left mr-2">
                    @if (Auth::guard('agent')->user()->image != null)
					<img src="{{ asset('assets/img/agents/' . Auth::guard('agent')->user()->image) }}"
					alt="Agent Image" class="avatar-img rounded-circle">
                    @else
					<img src="{{ asset('assets/img/blank-user.jpg') }}" alt=""
					class="avatar-img rounded-circle">
                    @endif
				</div>
				
                <div class="info">
                    <a data-toggle="collapse" href="#adminProfileMenu" aria-expanded="true">
                        <span>
                            {{ Auth::guard('agent')->user()->username }}
                            <span class="user-level">{{ __('Staff') }}</span>
                            <span class="caret"></span>
						</span>
					</a>
					
                    <div class="clearfix"></div>
					
                    <div class="collapse in" id="adminProfileMenu">
                        <ul class="nav">
                            <li>
                                <a href="{{ route('agent.edit.profile') }}">
                                    <span class="link-collapse">{{ __('Edit Profile') }}</span>
								</a>
							</li>
							
                            <li>
                                <a href="{{ route('agent.change_password') }}">
                                    <span class="link-collapse">{{ __('Change Password') }}</span>
								</a>
							</li>
							
                            <li>
                                <a href="{{ route('agent.logout') }}">
                                    <span class="link-collapse">{{ __('Logout') }}</span>
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			
			
            <ul class="nav nav-primary">
                {{-- search --}}
                <div class="row mb-3">
                    <div class="col-12">
                        <form>
                            <div class="form-group py-0">
                                <input name="term" type="text" class="form-control sidebar-search ltr"
								placeholder="Search Menu Here...">
							</div>
						</form>
					</div>
				</div>
				
                {{-- dashboard --}}
                <li class="nav-item @if (request()->routeIs('agent.dashboard')) active @endif">
                    <a href="{{ route('agent.dashboard') }}">
                        <i class="la flaticon-paint-palette"></i>
                        <p>{{ __('Dashboard') }}</p>
					</a>
				</li>
				
                @if ($userCurrentPackage)
                @if(in_array('property_management', Auth::guard('agent')->user()->permissions ?? []))
				<li
				class="nav-item
				@if (request()->routeIs('agent.property_management.create_property')) active
				@elseif (request()->routeIs('agent.property_management.properties')) active
				@elseif (request()->routeIs('agent.property_management.edit')) active
				@elseif (request()->routeIs('agent.property_management.type')) active @endif">
					<a data-toggle="collapse" href="#propertyManagement">
						<i class="fal fa-home"></i>
						<p>{{ __('Property Management') }}</p>
						<span class="caret"></span>
					</a>
					
					<div id="propertyManagement"
					class="collapse
					@if (request()->routeIs('agent.property_management.create_property')) show
					@elseif (request()->routeIs('agent.property_management.properties')) show
					@elseif (request()->routeIs('agent.property_management.edit')) show
					@elseif (request()->routeIs('agent.property_management.type')) show @endif
					">
						<ul class="nav nav-collapse">
							
							<li
							class="{{ request()->routeIs('agent.property_management.create_property') || request()->routeIs('agent.property_management.type') ? 'active' : '' }}">
								<a href="{{ route('agent.property_management.type') }}">
									<span class="sub-item">{{ __('Add Property') }}</span>
								</a>
							</li>
							
							<li
							class="@if (request()->routeIs('agent.property_management.properties')) active
							@elseif (request()->routeIs('agent.property_management.edit')) active @endif">
								<a
								href="{{ route('agent.property_management.properties', ['language' => $defaultLang->code]) }}">
									<span class="sub-item">{{ __('Manage Properties') }}</span>
								</a>
							</li>
						</ul>
					</div>
				</li>
				@endif
				
				{{-- <li class="nav-item  @if (request()->routeIs('agent.property_message.index')) active @endif">
					<a href="{{ route('agent.property_message.index') }}">
						<i class="fas fa-comment"></i>
						<p>{{ __('Property Messages') }}</p>
					</a>
				</li> --}}
				
				{{-- Property inventory --}}
                @if(in_array('property_inventory', Auth::guard('agent')->user()->permissions ?? []))
                @php
				$unreadCountInventory = Helper::unreadProeprties('full');
				
				$add_unread__inventory_class = "";
				if($unreadCountInventory != 0)
				{
				$add_unread__inventory_class = 'text-danger-glow blink';
				}
                @endphp
				
				<li class="nav-item
				@if (request()->routeIs('agent.property_inventory.create_property')) active
				@elseif (request()->routeIs('agent.property_inventory.properties')) active
				@elseif (request()->routeIs('agent.property_inventory.edit')) active
				@elseif (request()->routeIs('agent.property_inventory.settings')) active
				@elseif(request()->routeIs('agent.property_inventory.type')) active
				@elseif(request()->routeIs('agent.property_inventory.manage_status_property')) active
				@elseif(request()->routeIs('agent.property_inventory.converted_customer')) active @endif">
					<a data-toggle="collapse" href="#inventoryManagement">
						<i class="far fa-home"></i>
						<p>{{ __('Property inventory') }}</p><i class="{{ $add_unread__inventory_class }}" style="margin-left: 10px;"><b>{{ $unreadCountInventory != 0 ?  $unreadCountInventory : '' }}</b></i>
						<span class="caret"></span>
					</a>
					
					<div id="inventoryManagement"
					class="collapse
					@if (request()->routeIs('agent.property_inventory.create_property')) show
					@elseif (request()->routeIs('agent.property_inventory.type')) show
					@elseif (request()->routeIs('agent.property_inventory.properties')) show
					@elseif (request()->routeIs('agent.property_inventory.settings')) show
					@elseif (request()->routeIs('agent.property_inventory.edit')) show
					@elseif (request()->routeIs('agent.property_inventory.manage_status_property')) show
					@elseif (request()->routeIs('agent.property_inventory.converted_customer')) show
					@endif
					">
						<ul class="nav nav-collapse">
							{{-- <li
							class="{{ request()->routeIs('agent.property_inventory.settings') ? 'active' : '' }}">
								<a href="{{ route('agent.property_inventory.settings') }}">
									<span class="sub-item">{{ __('Settings') }}</span>
								</a>
							</li> --}}
							<li
							class="{{ request()->routeIs('agent.property_inventory.create_property') || request()->routeIs('agent.property_inventory.type') ? 'active' : '' }}">
								<a href="{{ route('agent.property_inventory.type') }}">
									<span class="sub-item">{{ __('Add Inventory') }}</span>
								</a>
							</li>
							
							<li
							class="{{ request()->routeIs('agent.property_inventory.properties') ||
							request()->routeIs('agent.property_inventory.edit')
							? 'active'
							: '' }}">
								<a
								href="{{ route('agent.property_inventory.properties', ['language' => $defaultLang->code]) }}">
									<span class="sub-item">{{ __('Manage Inventory') }}</span><i class="{{ $add_unread__inventory_class }}" style="margin-left: 10px;"><b>{{ $unreadCountInventory != 0 ?  $unreadCountInventory : '' }}</b></i>
								</a>
							</li>
							<li
							class="{{ request()->routeIs('agent.property_inventory.manage_status_property') ? 'active' : '' }}">
								<a href="{{ route('agent.property_inventory.manage_status_property') }}">
									<span class="sub-item">{{ __('Manage Inventory Status') }}</span>
								</a>
							</li>
							<li
							class="{{ request()->routeIs('agent.property_inventory.converted_customer') ? 'active' : '' }}">
								<a href="{{ route('agent.property_inventory.converted_customer') }}">
									<span class="sub-item">{{ __('Converted Customer') }}</span>
								</a>
							</li>
						</ul>
					</div>
				</li>
				@endif
				
                {{-- end property inventory  --}}
				
                {{-- Start Customer  --}}
                @if(in_array('property_inventory', Auth::guard('agent')->user()->permissions ?? []))
				<li class="nav-item  @if (request()->routeIs('agent.customer_management.list')) active @endif">
					<a href="{{ route('agent.customer_management.list') }}">
						<i class="fal fa-users-cog"></i>
						<p> {{ 'Inventory Customers' }} </p>
					</a>
				</li>
				@endif
                {{-- end Customer  --}}
				
                {{-- Start Account --}}
				@if(in_array('accounting', Auth::guard('agent')->user()->permissions ?? []))
				<li class="nav-item @if (request()->routeIs('agent.sales-bill.index')) active @endif">
					<a href="{{ route('agent.sales-bill.index') }}">
						<i class="fal fa-university"></i>
						<p>{{ 'Account' }}</p>
					</a>
				</li>
				@endif
                {{-- end Account  --}}
				
				{{-- Project management  start --}}
				@if(in_array('project', Auth::guard('agent')->user()->permissions ?? []))
				<li
				class="nav-item
				@if (request()->routeIs('agent.project_management.projects')) active
				@elseif (request()->routeIs('agent.project_management.create_project')) active
				@elseif (request()->routeIs('agent.project_management.edit')) active  @elseif(request()->routeIs('agent.project_management.project_types')) active @endif">
					<a data-toggle="collapse" href="#projectManagement">
						<i class="fal fa-city"></i>
						<p>{{ __('Project Management') }}</p>
						<span class="caret"></span>
					</a>
					
					<div id="projectManagement"
					class="collapse
					@if (request()->routeIs('agent.project_management.create_project')) show
					@elseif (request()->routeIs('agent.project_management.projects')) show
					@elseif (request()->routeIs('agent.project_management.edit')) show
					@elseif(request()->routeIs('agent.project_management.project_types')) show @endif
					">
						<ul class="nav nav-collapse">
							
							<li
							class="{{ request()->routeIs('agent.project_management.create_project') ? 'active' : '' }}">
								<a href="{{ route('agent.project_management.create_project') }}">
									<span class="sub-item">{{ __('Add Project') }}</span>
								</a>
							</li>
							
							<li
							class="{{ request()->routeIs('agent.project_management.projects') ||
							request()->routeIs('agent.project_management.project_types') ||
							request()->routeIs('agent.project_management.edit')
							? 'active'
							: '' }}">
								<a
								href="{{ route('agent.project_management.projects', ['language' => $defaultLang->code]) }}">
									<span class="sub-item">{{ __('Manage Projects') }}</span>
								</a>
							</li>
						</ul>
					</div>
				</li>
				@endif
				
				<li class="nav-item @if (request()->routeIs('aagent.inquiry')) active @endif">
                    <a href="{{ route('agent.inquiry') }}">
                        <i class="fas fa-comment"></i>
                        <p>{{ __('Sent Inquiery') }}</p>
					</a>
				</li>
				
				<li class="nav-item @if (request()->routeIs('agent.wishlist')) active @endif">
                    <a href="{{ route('agent.wishlist') }}">
                        <i class="fas fa-heart"></i>
                        <p>{{ __('My Wishlists') }}</p>
					</a>
				</li> 
				
				{{-- Project Management end  --}}
                @endif
                {{-- <li class="nav-item @if (request()->routeIs('agent.edit.profile')) active @endif">
                    <a href="{{ route('agent.edit.profile') }}">
                        <i class="fal fa-user-edit"></i>
                        <p>{{ __('Edit Profile') }}</p>
					</a>
				</li>
                <li class="nav-item @if (request()->routeIs('agent.change_password')) active @endif">
                    <a href="{{ route('agent.change_password') }}">
                        <i class="fal fa-key"></i>
                        <p>{{ __('Change Password') }}</p>
					</a>
				</li>
				
                <li class="nav-item @if (request()->routeIs('agent.logout')) active @endif">
                    <a href="{{ route('agent.logout') }}">
                        <i class="fal fa-sign-out"></i>
                        <p>{{ __('Logout') }}</p>
					</a>
				</li> --}}
			</ul>
		</div>
	</div>
</div>
