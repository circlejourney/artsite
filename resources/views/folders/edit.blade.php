@extends("layouts.site")

@section('body')
	<div class="page-block">
		<form method="POST">
			@csrf
			@method("PUT")
			<input name="title" value="{{ old("title", $folder->title) }}" placeholder="Folder name">
			<label for="parent_folder">Parent folder</label>

			<select id="parent_folder" class="form-control" name="parent_folder">
				<option value="">[None]</option>
				@foreach($folderlist as $listfolder)
					<option value="{{ $listfolder["id"] }}"
						@if($folder->parent_folder_id == $listfolder["id"] || $listfolder["id"] == old("parent_folder"))
							selected="selected"
						@endif
						@if(in_array($listfolder["id"], $childkeys) || $listfolder["id"] == $folder["id"]) disabled @endif
						>
						{!! str_repeat("&ensp;", $listfolder["depth"]-1) !!} {{ $listfolder["title"] }}
					</option>
				@endforeach
			</select>

			<button class="button-pill">Update</button>
		</form>
	</div>
@endsection