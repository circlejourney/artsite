@extends("layouts.site")

@section('body')
	<div class="page-block">
		<h1>{{ $user->name }}'s tag: {{ $tag->id }}</h1>
		@include("layouts.gallery", ["artworks" => $artworks])
	</div>
@endsection