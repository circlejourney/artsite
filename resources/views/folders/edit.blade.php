@extends("layouts.site")

@section('body')
	<div class="page-block">
		<h1>Manage Art Folder: {{ $folder->title }}</h1>
		<div class="row">
			<div class="col-12 col-md-4">
				@include("components.manage-folderlist", ["folderlist" => $folderlist, "selected" => $folder->id])
			</div>

			<div class="col">
				<form method="POST">
					@csrf
					@method("PUT")
					<label for="title">Folder name</label>
					<input class="form-control" name="title" id="title" value="{{ old("title", $folder->title) }}" placeholder="Folder name">
					
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
					
					<x-privacy-select :selected="$folder->privacy_level_id" />

					<button class="button-pill">Update</button>
				</form>

				<form method="POST">
					@csrf
					@method("DELETE")
					<button class="button-pill bg-danger">Delete</button>
				</form>
			</div>
		</div>
	</div>
@endsection