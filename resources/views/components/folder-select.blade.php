<select id="parent_folder" class="form-control" name="parent_folder">
	<option value="">[None]</option>
	@foreach($folderlist as $listfolder)
		<option value={{ $listfolder["folder"]->id }}
		@selected(isset($selected) && $selected == $listfolder["folder"]->id)
		@disabled(isset($forbidden) && in_array($listfolder["folder"]->id, $forbidden))
		>{!! str_repeat("&ensp;", $listfolder["depth"]-1) !!} {{ $listfolder["depth"] > 1 ? "↳" : "" }} {{ $listfolder["folder"]->title }}</option>
	@endforeach
</select>