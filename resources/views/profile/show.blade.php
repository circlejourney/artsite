@extends("layouts.site")

@push("metatitle"){{ $user->display_name }}@endpush

@section('body')
	<div class="profile-banner" style="background-image: url(/images/defaultbanner.png)"></div>
	
	<div class="profile-info">
		<img class="profile-avatar" src="{{ $avatar_url ?? '/images/user.png' }}">
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
		{!! $profile_html !!}
	</div>
	
	<div class="page-block">
		<h2>Gallery</h2>
		<div class="gallery">
		@foreach($artworks as $artwork) 
			<a class="gallery-thumbnail" href="{{ route('art', ["path" => $artwork->path]) }}">
				@if($artwork->thumbnail)
					<img src="{{ Storage::url($artwork->thumbnail) }}">
				@else
					<i class="far fa-newspaper"></i>
					<br>
					[Text only]
				@endif
			</a>
		@endforeach
		</div>
	</div>
	<div class="page-block row">
		<div class="col-12 col-md-6 profile-column">
			<div class="blog">
				<h2>Latest Blog</h2>
				<h2 class="blogpost-title">This is a blog title</h2>
				<div class="blogpost-byline">By user</div>
				<div></div>
			</div>
		</div>
		<div class="col-12 col-md-6 profile-column">
			<h2>Profile Comments</h2>
		</div>
	</div>
@endsection