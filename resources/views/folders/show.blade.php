@extends("layouts.site")

@section('body')
	<div class="page-block">
		<h1>{{$folder->title}}</h1>
		@foreach($folder->artworks as $artwork) 
			<img src="{{ $artwork->getThumbnailURL() }}">
			{{ $artwork->title }}
		@endforeach
	</div>
@endsection