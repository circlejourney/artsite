<div class="gallery">
@forelse($artworks as $artwork) 
	<a class="gallery-thumbnail" href="{{ route("art", ["path" => $artwork->path]) }}" title="{{ $artwork->title }}">
		<img src="{{ $artwork->getThumbnailURL() }}">
	</a>
@empty
		No art found.
@endforelse
</div>