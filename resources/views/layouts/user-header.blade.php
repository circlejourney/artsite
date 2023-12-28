<div class="profile-banner" style="background-image: url({{ $user->getBannerURL() }})"></div>
	
<div class="profile-info">
	<img class="profile-avatar" src="{{ $user->getAvatarURL() }}">
	<div class="profile-details">
		<div class="display-name">{!! $user->display_name !!}</div>
		<div class="display-username">{{ "@" . $user->name }} {!! $user->getFlairHTML() !!}</div>
	</div>
	@auth
	<div class="profile-interact">
		@if(auth()->user()->id != $user->id)
		<form method="POST" action="{{ route("follow", ["user" => $user]) }}">
			@csrf
			<button class="follow-button button-pill" href="#" onclick="follow()">
				@if(auth()->user()->follows->doesntContain($user))
					Follow
				@else
					Unfollow
				@endif
			</button>
		</form>
		@endif
		
		<a class="button-circle" href="{{ route("messages.create", ["username" => $user->name]) }}">
			<i class="far fa-envelope"></i>
		</a>
			
		<div>
			<a class="button-circle" href="#" data-toggle="dropdown">
				<i class="fa fa-ellipsis"></i>
			</a>
			<ul class="dropdown-menu profile-dropdown">
				@if(Auth::user() == $user)
					<li class="dropdown-item">
						<a href="{{ route("profile.edit") }}">
							<i class="fa fa-pencil"></i> Edit profile
						</a>
					</li>
				@else
					<li class="dropdown-item">
						<a href="{{ route("user.invite", ["user" => $user]) }}">
							<i class="fa fa-user-group"></i> Invite to join collective
						</a>
					</li>
				@endif
			</ul>
		</div>
	</div>
	@endauth
</div>

<ul class="profile-menu">
	<li>
		<a href="{{ route("user", ["username" => $user->name]) }}">Profile</a>
	</li>
	<li>
		<a href="{{ route("folders.index", ["username" => $user->name]) }}">Gallery</a>
	</li>
	<li>
		<a href="{{ route("faves", ["username" => $user->name]) }}">Favorites</a>
	</li>
	<li>
		<a class="text-muted">Blog</a>
	</li>
	<li>
		<a class="text-muted">Commissions</a>
	</li>
	<li>
		<a href="{{ route("stats", ["username" => $user->name]) }}">Stats</a>
	</li>
</ul>