<div>
	@foreach($folderlist as $listfolder)
	<div class="folder">
		<a style="margin-left: {{ ($listfolder["depth"]-1)*1.2 }}rem"
			href="{{
				isset($manage) ? route("folders.edit", ["folder" => $listfolder["folder"]->id])
				: (
					isset($tag) ?
					route("folders.show", ["username" => $user->name, "folder" => $listfolder["folder"]->id, "tag" => $tag->name])
					: (
						$listfolder["folder"]->id == $user->top_folder_id ?
						route("folders.index", ["username" => $user->name])
						: route("folders.show", ["username" => $user->name, "folder" => $listfolder["folder"]->id])
					)
				)
			}}"
			@if(isset($selected) && $listfolder["folder"]->id == $selected) class="link-current" @endif>
			@if($listfolder["depth"] > 1) &#x2937; @endif
			{{ $listfolder["folder"]->title }}
		</a>
	</div>
	@endforeach
</div>