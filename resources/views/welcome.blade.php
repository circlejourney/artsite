@extends('layouts.site')

@push("metatitle"){{ "Home" }}@endpush
@push("title"){{ "Home" }}@endpush
@push("metadescription"){{ "A cool art site with groups and more." }}@endpush

@section("body")
<div class="p-4">
    <h1>Art Site</h1>
    <p>Welcome to Art Site{{ Auth::user() ? ", " . Auth::user()->name : "" }}! Do nostrud occaecat dolor proident incididunt minim ad pariatur excepteur et cupidatat minim culpa.</p>
    
    @unless(Auth::user())
    <p>
        <a href="{{ route("login") }}">Log in</a>
		<br>
		<a href="{{ route("register") }}">Register</a>
    </p>
    @endif
</div>
@endsection