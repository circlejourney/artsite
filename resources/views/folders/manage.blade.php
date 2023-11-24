@extends("layouts.site")
@push("metatitle"){{ "Manage Folders" }}@endpush
@section('body')
	<div class="page-block">
		<h1>Manage Art Folders</h1>
		<div class="row">

			<div class="col-12 col-md-4">
				<h2>Edit folders</h2>
				@include("folders.folderlist-manage", ["folderlist" => $folderlist])
			</div>
			
			<div class="col">
				<h2>Create new folder</h2>
				<form method="POST">
					@csrf
					<input name="title" class="form-control" placeholder="Folder name" required>
					<label for="parent_folder">Parent folder</label>
					@component("components.folder-select", ["folderlist" => $folderlist])@endcomponent
					<button class="button-pill">Create folder</button>
				</form>
			</div>
	</div>
@endsection