@foreach($folderlist as $folder)
	<a class="folder" href="{{ route("collectives.folders.show", ["collective" => $collective, "folder" => $folder]) }}">
		<div class="folder-thumbnail"
			@if($folder->artworks->isNotEmpty())
				style="background-image: url({{ $folder->artworks()->first()->getThumbnailURL() }})"
			@endif
		>
		</div>

		<div class="folder-title">
			{{ $folder->getDisplayName() }}
		</div>
	</a>
@endforeach