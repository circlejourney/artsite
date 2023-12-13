@extends("layouts.site")

@section('body')
	<div class="page-block">
		<h1>Manage Art Folder: {{ $folder->title }}</h1>
		<div class="row">
			<div class="col-12 col-md-4">
				@include("folders.folderlist", ["folderlist" => $folderlist, "selected" => $folder->id, "manage" => true])
			</div>

			<div class="col">
				<form method="POST">
					@csrf
					@method("PUT")
					<label for="title">Folder name</label>
					<input class="form-control" name="title" id="title" value="{{ old("title", $folder->title) }}" placeholder="Folder name">
					
					<label for="parent_folder">Parent folder</label>
					@component("components.folder-select", ["folderlist" => $folderlist, "selected" => $folder->parent->id, "forbidden" => $childkeys])@endcomponent
					
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