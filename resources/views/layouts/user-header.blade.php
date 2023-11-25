<div class="profile-banner" style="background-image: url(/images/defaultbanner.png)"></div>
	
<div class="profile-info">
	<img class="profile-avatar" src="{{ $user->getAvatarURL() ?? '/images/user.png' }}">
	<div class="profile-details">
		<div class="display-name">{!! $user->display_name !!}</div>
		<div class="display-username">{{ "@" . $user->name }} {!! $user->getFlairHTML() !!}</div>
	</div>
	<div class="profile-interact">
		<a class="button-pill" href="#">Follow</a>
		<a class="button-circle" href="#">
			<i class="far fa-envelope"></i>
		</a>
		<a class="button-circle" href="#">
			<i class="fa fa-ellipsis"></i>
		</a>
	</div>
</div>

<ul class="profile-menu">
	<li>
		<a href="#">Profile</a>
	</li>
	<li>
		<a href="{{ route("folders.index", ["username" => $user->name]) }}">Gallery</a>
	</li>
	<li>
		<a href="#">Favorites</a>
	</li>
	<li>
		<a href="#">Blog</a>
	</li>
	<li>
		<a href="#">Commissions</a>
	</li>
	<li>
		<a href="#">Stats</a>
	</li>
</ul>