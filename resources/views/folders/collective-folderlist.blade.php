<div>
	@foreach($folderlist as $listfolder)
	<div class="folder">
		<a style="margin-left: {{ ($listfolder["depth"]-1)*1.2 }}rem"
			href="{{
				isset($manage) ? route("collectives.folders.edit", ["collective" => $collective, "folder" => $listfolder["folder"]->id])
				: (
					isset($tag) ?
					route("collectives.folders.show", ["collective" => $collective, "folder" => $listfolder["folder"]->id, "tag" => $tag->name])
					: (
						$listfolder["folder"]->id == $user->top_folder_id ?
						route("collectives.folders.index", ["collective" => $collective->name])
						: route("collectives.folders.show", ["username" => $collective->name, "folder" => $listfolder["folder"]->id])
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