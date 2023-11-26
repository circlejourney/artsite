@extends("layouts.profile", ["user" => $user, "metatitle" => $user->name])

@section('profile-body')
	<div class="profile-highlight">
		<img class="" src="/images/300.png">
		<img src="/images/300.png">
		<img src="/images/300.png">
	</div>
	
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