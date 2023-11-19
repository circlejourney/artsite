@extends("layouts.site")

@push("metatitle"){{ $user->display_name }}@endpush

@section('body')
	@isset($user)
        <div class="profile-banner" style="background-image: url(/images/defaultbanner.png)"></div>
        
        <div class="profile-info">
            <img class="profile-avatar" src="{{ $user->avatar_url ?? '/images/user.png' }}">
            <div class="profile-details">
                <div class="display-name">{{ $user->display_name }}</div>
                <div class="display-username">{{ "@" . $user->name }}</div>
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
				<a href="#">Gallery</a>
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

		<div class="profile-highlight">
			<img class="" src="/images/300.png">
			<img src="/images/300.png">
			<img src="/images/300.png">
		</div>
		
		<div class="profile-custom @if($user->customised){{ "customised" }}@endif">
			{!! $user->profile_html !!}
		</div>
	@else
	User does not exist.
	@endif
@endsection