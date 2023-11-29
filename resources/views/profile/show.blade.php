@extends("layouts.profile", ["user" => $user, "metatitle" => $user->name])

@push('head')
	<meta name="og:image" content="{{ $user->getAvatarURL() }}">
	<meta name="og:description" content="{{ $user->name }} on {{ config("app.name") }}">
@endpush

@section('profile-body')
	@if($highlights->count() > 0)
	<div class="profile-highlight">
		@foreach($highlights as $highlight)
		<a href="{{ route("art", ["path" => $highlight->path]) }}">
			<img src="{{ $highlight->getImageURL(0) }}">
		</a>
		@endforeach
	</div>
	@else
		@if($user == auth()->user())
		<div class="owner-only">
			<h2><i class="fa fa-lock"></i> (Private notice) No highlight images found.</h2>
			<div>To highlight images, go to the <a href="{{ route("art.manage", ["user" => $user]) }}">Manage Art dashboard page</a>.</div>
		</div>
		@endif
	@endif
	
	<div class="profile-custom @if($user->customised){{ "customised" }}@endif">
		{!! $profile_html !!}
	</div>
	
	<div class="gallery-section">
		<h2>Latest Art</h2>
		@include("layouts.gallery", ["artworks" => $artworks, "user" => $user])
	</div>
	<div class="post-section row">
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