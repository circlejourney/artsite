@forelse($artworks as $artwork) 
	<div>
		<a href="{{ route("art", ["path" => $artwork->path]) }}">
			<img src="{{ $artwork->getThumbnailURL() }}">
			{{ $artwork->title }}
		</a>
	</div>
@empty
		No art found.
@endforelse