@extends("layouts.site")

@section('body')
	<div class="page-block">
		<h1>Manage Art Folders</h1>
		<form method="POST">
			@csrf
			<input name="title">
			<label for="parent_folder">
			<select id="parent_folder" name="parent_folder" placeholder="Folder title" required>
				<option value="">[Top folder]</option>
				@foreach($folders as $folder)
					<option value="{{ $folder->id }}">{{ $folder->title }}</option>
				@endforeach
			</select>
			<button>Create folder</button>
		</form>
	</div>

	<div>
		@foreach($folders as $folder)
		<a href="{{ route("folders.edit", ["id" => $folder->id]) }}">
			{{ $folder->title }}
		</a>
		@endforeach
	</div>
@endsection