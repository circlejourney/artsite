<select id="parent_folder" class="form-control" name="parent_folder">
	<option value="">[None]</option>
	@foreach($folderlist as $listfolder)
		<option value={{ $listfolder["folder"]->id }}
		@if(isset($selected) && $selected == $listfolder["folder"]->id) selected="selected" @endif
		>{!! str_repeat("&ensp;", $listfolder["depth"]-1) !!} {{ $listfolder["depth"] > 1 ? "↳" : "" }} {{ $listfolder["folder"]->title }}</option>
	@endforeach
</select>