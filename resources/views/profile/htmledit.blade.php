@extends("layouts.site")

@push("title"){{ "Edit HTML" }}@endpush

@section("body")
	<textarea id="profilehtml" name="profilehtml">{{ old('profilehtml', $user->profilehtml) }}</textarea>
@endsection