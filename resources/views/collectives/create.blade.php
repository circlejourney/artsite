@extends("layouts.site")

@push("head")
	<script>
		function updateURLPreview() {
			$("#url-preview").html( $("#url").val() );
		}
	</script>
@endpush

@section('body')
	<h1>Create Collective</h1>
	<form method="POST">
		@csrf
		<div>
			Collective URL - the URL slug to view this group, should only contain alphanumeric characters, underscores and hyphens.
		</div>
		<div>{{ config("app.url") }}/co/<b id="url-preview">{{ old("url", "collective-url") }}</b>
		</div>
		<input type="text" name="url" id="url" placeholder="Collective URL" oninput="updateURLPreview()">
		<div>Display name - Can contain any text except HTML tags.</div>
		<input type="text" name="display_name" placeholder="Collective's display name">
		<br>
		Privacy Level:
		<input type="radio" name="privacy_level_id" value="1" id="privacy-1" checked> <label for="privacy-1">Public</label>
		<input type="radio" name="privacy_level_id" value="2" id="privacy-2"> <label for="privacy-2">Logged-in only</label>
		<input type="radio" name="privacy_level_id" value="5" id="privacy-5"> <label for="privacy-5">Members only</label>
		<button>Create</button>
	</form>
@endsection