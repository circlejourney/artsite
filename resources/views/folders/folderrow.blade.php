<div class="folder-row">
	@foreach($folderlist as $folder)
		<a class="folder" href="{{
			isset($tag) ?
				route("folders.show", ["username" => $user->name, "folder" => $folder, "tag" => $tag])
				:
				route("folders.show", ["username" => $user->name, "folder" => $folder])
		}}">
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
</div>