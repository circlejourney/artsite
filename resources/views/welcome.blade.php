@extends('layouts.site')

@push("metatitle"){{ "Home" }}@endpush
@push("title"){{ "Home" }}@endpush
@push("metadescription"){{ "A cool art site with groups and more." }}@endpush

@section("body")
<div class="p-4">
    <h1>Welcome to {{ config("app.name") }}!</h1>
	<p>A cool art site with groups, galleries, and lots of placeholder content. Do nostrud occaecat dolor proident incididunt minim ad pariatur excepteur et cupidatat minim culpa.</p>
    
    @unless(Auth::check())
    <p>
        <a href="{{ route("login") }}">Log in</a>
		<br>
		<a href="{{ route("register") }}">Register</a>
    </p>
    @endif
</div>
@endsection