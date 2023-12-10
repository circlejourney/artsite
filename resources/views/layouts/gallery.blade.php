<div class="gallery">
@forelse($artworks as $artwork) 
	<a class="gallery-thumbnail @if(!$artwork->thumbnail) empty @endif {{ $artwork->tags()->pluck("name")->join(" ") }}" href="{{ route("art", ["path" => $artwork->path]) }}" title="{{ $artwork->title }}">
		@if($artwork->thumbnail)
			<img src="{{ $artwork->getThumbnailURL() }}">
		@else
		<h3>
			<i class="fa fa-font"></i> {{ $artwork->title }}
		</h3>
		<span>
			{!! Str::of($artwork->getPlainText())->words(10) !!}
		</span>
		@endif
		<div class="gallery-thumbnail-badgerow">
			@if(($artistcount = sizeof($artwork->users)) > 1)
				<div class="gallery-thumbnail-badge"
					data-toggle="tooltip"
					@if(isset($user))
						title="With {{ $artwork->users()->get()->pluck("name")->reject($user->name)->join(", ") }}"
					@else
						title="{{ $artwork->users()->get()->pluck("name")->join(", ") }}"
					@endif
					>
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