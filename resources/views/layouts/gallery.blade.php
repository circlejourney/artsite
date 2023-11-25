<div class="gallery">
@forelse($artworks as $artwork) 
	<a class="gallery-thumbnail" href="{{ route("art", ["path" => $artwork->path]) }}">
		<img src="{{ $artwork->getThumbnailURL() }}">
		{{ $artwork->title }}
	</a>
@empty
		No art found.
@endforelse
</div>