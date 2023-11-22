@extends("layouts.site")
@push("metatitle"){{ "Manage Folders" }}@endpush
@section('body')
	<div class="page-block">
		<h1>Manage Art Folders</h1>
		<div class="row">

			<div class="col-12 col-md-6">
				<h2>Edit folders</h2>
				<div class="folder-list">
					@foreach($folderlist as $folder)
					<div>
						<a style="margin-left: {{ ($folder->depth-1)*1.2 }}rem" href="{{ route("folders.edit", ["folder" => $folder->id]) }}">
							@if($folder->depth > 1) &#x2937; @endif
							{{ $folder->title }}
						</a>
					</div>
					@endforeach
				</div>
			</div>
			
			<div class="col-12 col-md-6">
				<h2>Create new folder</h2>
				<form method="POST">
					@csrf
					<input name="title" class="form-control" placeholder="Folder name" required>
					<label for="parent_folder">Parent folder</label>
					@include("components.folder-select", ["folderlist" => $folderlist])
					<button class="button-pill">Create folder</button>
				</form>
			</div>
	</div>
@endsection