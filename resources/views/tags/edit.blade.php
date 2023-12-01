@extends("layouts.site")

@push('head')
	<script src="/src/ace/ace.js"></script>
	<script>
		$(window).on("load", function(){
			startAceEditor("#editor", "#text");
			updatePreview($("#thumbnail")[0], $(".image-preview")[0]);
		})
	</script>
@endpush

@section('body')
	<h1>Edit tag highlight: {{ $tag->name }}</h1>
	<form method="POST" enctype="multipart/form-data">
		@csrf
		@if($highlight = $tag->tag_highlight)
			@method("PATCH")
		@else
			@method("POST")
		@endif
		<input type="hidden" name="text" id="text" value="{{ old("text", $highlight->text ?? "") }}">
		Update thumbnail
		<input type="file" id="thumbnail" name="thumbnail"
			onchange="updatePreview(this, $(this).siblings('.image-preview')[0])"
			value="{{ old('thumbnail', $highlight ? $highlight->getThumbnailURL() : "" ) }}">
		<img class="image-preview"
			@if($highlight && $highlight->thumbnail)
				src="{{ $highlight->getThumbnailURL() }}"
			@endif>
		<div id="editor"></div>
		<button>Update</button>
	</form>
@endsection