@extends("layouts.site")

@push("metatitle"){{ "Dashboard" }}@endpush
@push("title"){{ "Dashboard" }}@endpush

@section('body')
<div class="p-4">
	<h1>Dashboard</h1>
	<p>Welcome back to {{ config("app.name") }}, {{ Auth::user()->name }}!</p>
	<a href="/{{ Auth::user()->name }}">View your profile</a>
	<br>
	<a href="{{ route("profile.html.edit") }}">Customise your profile</a>
	<br>
	<a href="{{ route("folders") }}">Manage art folders</a>
	<br>
	<a href="{{ route("profile.edit") }}">Update your settings</a>
	<br>
	@include("components.logout-form")
</div>
@endsection