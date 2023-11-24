<div class="folder-list">
	@foreach($folderlist as $folder)
	<div>
		<a
			style="margin-left: {{ ($folder["depth"]-1)*1.2 }}rem"
			href="{{ route("folders.edit", ["folder" => $folder["id"]]) }}"
			@if(isset($selected) && $folder["id"] == $selected) class="link-current" @endif>
			@if($folder["depth"] > 1) &#x2937; @endif
			{{ $folder["title"] }}
		</a>
	</div>
	@endforeach
</div>