@extends("layouts.site")

@section('body')
	@include('layouts.user-header', ["user" => $user])
	@yield('profile-body')
@endsection