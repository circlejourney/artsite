<div>
	@foreach($folderlist as $listfolder)
	<div>
		<a style="margin-left: {{ ($listfolder["depth"]-1)*1.2 }}rem"
			href="{{ $listfolder["id"] == $user->top_folder_id ? route("folders.index", ["username" => $user->name]) : route("folders.show", ["username" => $user->name, "folder" => $listfolder["id"]]) }}"
			@if($listfolder["id"] == $selected) class="link-current" @endif>
			@if($listfolder["depth"] > 1) &#x2937; @endif
			{{ $listfolder["title"] }}
		</a>
	</div>
	@endforeach
</div>