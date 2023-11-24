<select id="parent_folder" class="form-control" name="parent_folder">
	<option value="">[None]</option>
	@foreach($folderlist as $i => $folder)
		<option value={{ $folder["id"] }}
		@selected(old('active', isset($selected) && $selected == $i))
		>{!! str_repeat("&ensp;", $folder["depth"]-1) !!} {{ $folder["depth"] > 1 ? "↳" : "" }} {{ $folder["title"] }}</option>
	@endforeach
</select>