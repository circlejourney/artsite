@extends("layouts.site")

@push("head")
	<script>
		function addImageInput(){
			$("#image-inputs").append("<input type='file' name='images'></input>");
		}
	</script>
@endpush

@section('body')
	<form method="POST" enctype="multipart/form-data">
		@csrf
		<div id="image-inputs">
			<span><input type="file" name="images"></span>
		</div>
		<a class='button-pill' onclick="addImageInput()">+</a>

		<input type="text" name="title" placeholder="Title">
		<textarea name="text" placeholder="HTML text"></textarea>
		<input type="text" name="artist" placeholder="Collaborator 1">
		<input type="text" name="artist" placeholder="Collaborator 2">
		<button class='button-pill'>Submit</button>
	</form>
@endsection