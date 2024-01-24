<select id="parent_folder" class="form-control" name="parent_folder">
	@if(!isset($hide_none) || $hide_none == false)
		<option value="">[None]</option>
	@endif
	@foreach($folderlist as $listfolder)
		<option value={{ $listfolder["folder"]->id }}
		@selected(isset($selected) && $selected == $listfolder["folder"]->id)
		@disabled(isset($forbidden) && in_array($listfolder["folder"]->id, $forbidden))
		>{!! str_repeat("&ensp;", $listfolder["depth"]-1) !!} {{ $listfolder["depth"] > 1 ? "↳" : "" }} {{ $listfolder["folder"]->title }}</option>
	@endforeach
</select>