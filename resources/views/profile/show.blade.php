@extends("layouts.profile", ["user" => $user, "metatitle" => $user->name])

@section('profile-body')
	@if(($highlights = $user->getArtWithTag("highlight")->slice(0,3))->count()>0)
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
			<div>To highlight images, simply tag some artwork with <code>highlight</code> and they will show up here. Note that all art tagged with <code>highlight</code> <i>will</i> appear here regardless of privacy level.</div>
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