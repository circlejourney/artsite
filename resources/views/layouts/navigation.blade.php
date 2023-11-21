<nav class="p-2">
    <div class="nav-left">
        <a href="#nav-menu" class="menu-toggle" data-toggle="collapse"><i class="fa fa-bars"></i></a>
        <a href="/" class="logo">Logo</a>
		<div class="d-none d-lg-block">
			@include("components.search-form")
		</div>

        <a class="nav-button text-uppercase" href="/works/new">
            <i class="nav-button-icon fa fa-arrow-up-from-bracket"></i>
            <span class="nav-button-label d-none d-lg-flex">Submit</span>
        </a>

        <div class="collapse-container">
            <div class="collapse" id="nav-menu">
				<div class="menu-form-container d-lg-none">
					@include("components.search-form")
				</div>
                @if(Auth::user())

                    <a class="menu-item" href="/{{ Auth::user()->name }}">{{ Auth::user()->name }}</a>
					<a class="menu-item" href="{{route('profile.html.edit')}}">Edit profile</a>
                    <a class="menu-item" href="{{route('profile.edit')}}">Settings</a>
                    <!-- Authentication -->
                    @include("components.logout-form")

                @else

                    <a class="menu-item" href="{{ route("login") }}">Login</a>
                    <a class="menu-item" href="{{ route("register") }}">Register</a>

                @endif
            </div>
        </div>
    </div>

    <div class="nav-right">
        <a href="#" class="menu-toggle-circle"><i class="far fa-fw fa-bell"></i></a>
        <a href="#" class="menu-toggle-circle"><i class="far fa-fw fa-envelope"></i></a>
    </div>
</nav>
