<div class="gallery">
@forelse($artworks as $artwork) 
	<a class="gallery-thumbnail" href="{{ route("art", ["path" => $artwork->path]) }}" title="{{ $artwork->title }}">
		<img src="{{ $artwork->getThumbnailURL() }}">
		@if(($imagecount = sizeof($artwork->images)) > 1)
		<div class="gallery-thumbnail-badge">
			<i class="fa fa-images"></i> {{ $imagecount }}
		</div>
		@endif
	</a>
@empty
	No art found.
@endforelse
</div>