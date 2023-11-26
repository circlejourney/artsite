<div class="gallery">
@forelse($artworks as $artwork) 
	<a class="gallery-thumbnail" href="{{ route("art", ["path" => $artwork->path]) }}" title="{{ $artwork->title }}">
		<img src="{{ $artwork->getThumbnailURL() }}">
		<div class="gallery-thumbnail-badgerow">
			@if(($artistcount = sizeof($artwork->users)) > 1)
				<div class="gallery-thumbnail-badge" data-toggle="tooltip" title="With {{ $artwork->users()->get()->pluck("name")->reject($user->name)->join(", ") }}">
					<i class="fa fa-user-group"></i>
				</div>
			@endif

			@if(($imagecount = sizeof($artwork->images)) > 1)
				<div class="gallery-thumbnail-badge">
					<i class="fa fa-images"></i> {{ $imagecount }}
				</div>
			@endif
		</div>
	</a>
@empty
	No art found.
@endforelse
</div>