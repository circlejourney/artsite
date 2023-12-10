@extends("layouts.site")

@section('body')
	<div class="page-block">
		<h1>Manage Artworks [WIP]</h1>
		@foreach($artworks as $artwork)
			<div>
				<a href="{{ route("art", ["path" => $artwork->path]) }}">
					<img src="{{ $artwork->getThumbnailURL() }}">
					{{ $artwork->title }}
				</a>
			</div>
		@endforeach
	</div>
@endsection