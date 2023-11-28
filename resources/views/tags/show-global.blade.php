@extends("layouts.site")

@section('body')
	<div class="page-block">
		<h1>Global feed for tag: {{ $tagID }}</h1>
		@include("layouts.gallery", ["artworks" => $artworks])
	</div>
@endsection