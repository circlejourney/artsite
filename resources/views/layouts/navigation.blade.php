<nav class="p-2">
    <div class="nav-left">
        <a href="#nav-menu" class="menu-toggle" data-toggle="collapse"><i class="fa fa-bars"></i></a>
        <a href="{{ route('home') }}" class="logo">Logo</a>
		<a href="{{ route('home') }}" class="logo-small">L</a>
		<div class="d-none d-lg-block">
			@include("components.search-form")
		</div>

        <a class="nav-button text-uppercase" href="{{ route("art.create") }}">
            <i class="nav-button-icon fa fa-arrow-up-from-bracket"></i>
            <span class="nav-button-label d-none d-lg-flex">Submit</span>
        </a>

        <div class="collapse-container">
            <div class="collapse" id="nav-menu">
				<div class="menu-form-container d-lg-none">
					@include("components.search-form")
				</div>
                @if(Auth::check())
                    <a class="menu-item" href="{{route('dashboard')}}">Dashboard</a>
					<a class="menu-item" href="{{route('profile.html.edit')}}">Customise profile</a>
                    <!-- Authentication -->
                    @include("components.logout-form")
                @endif
            </div>
        </div>
    </div>

    <div class="nav-right">
		
		@auth
			<form class="notifications" action="{{ route("notifications.get_count") }}">
				@csrf
				<a href="{{ route("notifications") }}" class="notification-button menu-toggle-circle">
					<i class="far fa-fw fa-bell"></i>
					<div class="notification-badge number-badge badge badge-primary d-none"></div>
				</a>
			</form>
			<a href="{{ route("messages") }}" class="menu-toggle-circle">
				<i class="far fa-fw fa-envelope"></i>
				@php
					$unread = auth()->user()->messages->reject(function($i){ return $i->read; })->count();
				@endphp
				@if($unread > 0)
					<div class="notification-badge number-badge badge badge-primary">{{ $unread }}</div>
				@endif
			</a>
			<a href="{{ route("user", Auth::user()->name) }}" class="menu-toggle-circle" style="background-image: url({{ Auth::user()->getAvatarURL() }})">
			</a>
			

		@else
			<a class="nav-button" href="{{ route("login") }}"><span class="nav-button-label">Login</span></a>
			<a class="nav-button" href="{{ route("register") }}"><span class="nav-button-label">Register</span></a>
		@endauth
		
    </div>
</nav>
