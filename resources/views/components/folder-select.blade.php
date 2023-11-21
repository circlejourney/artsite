<select id="parent_folder" class="form-control" name="parent_folder">
	<option value="">[None]</option>
	@foreach($folderlist as $folder)
		<option value="{{ $folder->id }}">{!! str_repeat("&ensp;", $folder->depth-1) !!} {{ $folder->title }}</option>
	@endforeach
</select>