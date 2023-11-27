@extends("layouts.site", ["metatitle" => "Customise Profile"])
@push("head")
	<script src="/src/ace/ace.js"></script>
	<script>
		let editor;

		$(window).on("load", function(){
			editor = startAceEditor("#editor", "#profile_html", ".profile-custom");
		})

		function beforePost() {
			$("#profile_html").val(editor.getValue());
		}

	</script>
@endpush

@section("body")
	<div class="page-block">
		<h1>Edit Profile HTML</h1>

		<form method="POST" enctype="multipart/form-data">
			@csrf
			@method("PATCH")
			
			<h2>Avatar</h2>
			<input type="file" name="avatar" id="avatar" onchange="updatePreview(this, $('.avatar-image-preview'))">
			<br>
			<img class="image-preview avatar-image-preview" src="{{ $user->getAvatarURL() }}">

			<h2>Banner</h2>
			<div>Draggable cropping will be added soon!</div>
			<input type="file" name="banner" id="banner" onchange="updatePreview(this, $('.banner-image-preview'), true)">
			<div class="profile-banner banner-image-preview" style="background-image: url({{ $user->getBannerURL() }})"></div>

			<h2>Highlight images</h2>
			<div>To add images to your highlights at the top of your profile, simply tag the desired artwork with <code>highlight</code>.</div>

			<h2>Profile HTML</h2>
			<input type="hidden" id="profile_html" name="profile_html" value="{{ old('profile_html', $profile_html) }}">
			<div id="editor"></div>
			<button class="button-pill" onclick="beforePost()">Update</button>
			
		</form>
	</div>
	<div class="profile-custom customised"></div>
@endsection