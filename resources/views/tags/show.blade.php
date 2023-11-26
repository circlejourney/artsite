@extends("layouts.profile", ["user" => $user])

@section('profile-body')
	<div class="page-block">
		<h1>{{ $user->name }}'s tag: {{ $tag->id }}</h1>
		@include("layouts.gallery", ["artworks" => $artworks, "user" => $user])
	</div>
@endsection