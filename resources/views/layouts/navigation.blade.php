<nav class="p-2">
    <div class="nav-left d-flex justify-content-center">
        <a href="#nav-menu" class="menu-toggle" data-toggle="collapse"><i class="fa fa-bars"></i></a>
        <a href="/" class="logo">Logo</a>
        <form class="search-form p-1" method="get" action="">
            <button>
                <i class="fa fa-magnifying-glass"></i>
            </button>
            <input type="text" name="search" placeholder="Search">
        </form>

        <a class="nav-button text-uppercase" href="#">
            <i class="nav-button-icon fa fa-arrow-up-from-bracket"></i>
            <span class="nav-button-label">Submit</span>
        </a>
        <div class="collapse-container">
            <div class="collapse" id="nav-menu">
                @if(Auth::user())

                    <a class="menu-item" href="/{{ Auth::user()->name }}">{{ Auth::user()->name }}</a>
					<a class="menu-item" href="{{route('profile.html.edit')}}">Edit profile</a>
                    <a class="menu-item" href="{{route('profile.edit')}}">Settings</a>
                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <a
                            class="menu-item"
                            href="{{route('logout')}}"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            Log Out
                        </a>
                    </form>

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
