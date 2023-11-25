@extends("layouts.site", ["metatitle" => "Customise Profile"])
@push("head")
	<link rel="stylesheet" href="/src/ace.css">
	<script src="/src/ace/ace.js"></script>
	<script>
		let editor;
		$(window).on("load", ()=>{

			editor = ace.edit("editor");
			editor.setTheme("ace/theme/katzenmilch");
			editor.setShowPrintMargin(false);
			editor.session.setMode("ace/mode/html");
			editor.session.setUseWrapMode(true);
			editor.session.on("change", function() {
				const wait = startIdle();
				editor.session.on("change", function cancel(){
					clearTimeout(wait);
					editor.session.off("change", cancel);
				})
			});

			editor.setValue($("#profile_html").val());
			$(".profile-custom").html(
				sanitise_html($("#profile_html").val())
			);

			function startIdle() {
				const timeout = setTimeout(function() {
					const html = editor.getValue();
					$(".profile-custom").html(sanitise_html(html));
					$("#profile_html").val(html);
				}, 500);
				return timeout;
			}

			if($("#avatar").val()) updatePreview( $("#avatar")[0], ".avatar-image-preview");
			if($("#banner").val()) updatePreview( $("#banner")[0], ".banner-image-preview", true);

		});

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

			<h2>Profile HTML</h2>
			<input type="hidden" id="profile_html" name="profile_html" value="{{ old('profile_html', $profile_html) }}">
			<div id="editor"></div>
			<button class="button-pill" onclick="beforePost()">Update</button>
		</form>
	</div>
	<div class="profile-custom customised"></div>
@endsection