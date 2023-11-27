@extends("layouts.site")
@section('body')
	<h1>{{ $collective->display_name }}</h1>
	<form method="POST">
		@csrf 
		@method("PATCH")
		<div>
			Collective URL - the URL slug to view this group, should only contain alphanumeric characters, underscores and hyphens.
		</div>
		<div>{{ config("app.url") }}/co/<b id="url-preview">collective-url</b>
		</div>
		<input type="text" name="url" placeholder="Collective URL" value="{{ old( "url", $collective->url ) }}">
		<div>Display name - Can contain any text except HTML tags.</div>
		<input type="text" name="display_name" placeholder="Collective's display name" value="{{ old( "display_name", $collective->display_name ) }}">
		<br>
		
		Privacy Level:
		<input type="radio" name="privacy_level_id" value="1" id="privacy-1" @checked(old("active", $collective->privacy_level_id==1))>
		<label for="privacy-1">Public</label>
		<input type="radio" name="privacy_level_id" value="2" id="privacy-2" @checked(old("active", $collective->privacy_level_id==2))>
		<label for="privacy-2">Logged-in only</label>
		<input type="radio" name="privacy_level_id" value="5" id="privacy-5" @checked(old("active", $collective->privacy_level_id==5))>
		<label for="privacy-5">Members only</label>
		
		<button>Update</button>
	</form>
@endsection