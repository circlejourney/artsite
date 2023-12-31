@extends('layouts.site', ["metatitle" => "Home"])
@push("title"){{ "Home" }}@endpush
@push("metadescription"){{ "A cool art site with collabs, collectives and more." }}@endpush

@section("body")
	@auth
		<h1>Hello <x-nametag :user="auth()->user()" />!</h1>
	@else
    	<h1>Welcome to {{ config("app.name") }}!</h1>
	@endauth
	
	<p>A cool art site with collabs, collectives, galleries, and lots of placeholder content. Do nostrud occaecat dolor proident incididunt minim ad penis pariatur excepteur et cupidatat minim culpa.</p>
    
    @auth
		<p>
			<a href="{{ route("dashboard")}}">Go to dashboard</a>
		</p>
    @else
		<p>
			<a href="{{ route("login") }}">Log in</a>
			<br>
			<a href="{{ route("register") }}">Register</a>
		</p>
	@endauth
@endsection