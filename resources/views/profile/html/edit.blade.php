@extends("layouts.site")

@push("metatitle"){{ "Edit HTML" }}@endpush

@section("body")
	<h1>Edit Profile HTML</h1>
	<form method="POST">
		@csrf
		@method("PATCH")
		<?php error_log(old('profilehtml', $user->profilehtml)) ?>
		<textarea id="profilehtml" name="profilehtml">{{ old('profilehtml', $user->profilehtml) }}</textarea>
		<button>Update</button>
	</form>
@endsection