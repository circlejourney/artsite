@extends("layouts.site", ["metatitle" => "Artwork tagged #".$tagName])

@push('head')
	<meta property="og:image" content="{{ $artworks->first()->getThumbnailURL() }}">
@endpush

@section('body')
	<div class="page-block">
		<h1>Artwork tagged #{{$tagName}}</h1>
		@include("layouts.gallery", ["artworks" => $artworks])
	</div>
@endsection