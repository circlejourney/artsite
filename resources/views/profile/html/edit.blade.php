@extends("layouts.site")

@push("metatitle"){{ "Edit HTML" }}@endpush
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

		});

		function beforePost() {
			$("#profile_html").val(editor.getValue());
		}

		function updatePreview(selector) {
			if(!event.target.files) return false;
			const file = event.target.files[0];
			const reader = new FileReader();
			reader.readAsDataURL(file);
			reader.onload = function(){
				$(event.target.closest(selector)).attr("src", this.result);
			}
		}
	</script>
@endpush

@section("body")
	<div class="p-4">
		<h1>Edit Profile HTML</h1>
		
		<h2>Avatar</h2>
		<img class="image-preview" src="{{ old("avatar", $user->avatar_url) ?? '/images/user.png' }}">

		<h2>Profile HTML</h2>
		<form method="POST" enctype="multipart/form-data">
			@csrf
			@method("PATCH")
			<input type="file" name="avatar" onchange="updatePreview(this, $('.image-preview')[0])">
			<input type="hidden" id="profile_html" name="profile_html" value="{{ old('profile_html', $user->profile_html) }}">
			<div id="editor"></div>
			<button class="button-pill" onclick="beforePost()">Update</button>
		</form>
	</div>
	<div class="profile-custom customised"></div>
@endsection