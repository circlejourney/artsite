@extends("layouts.site", ["metatitle" => $metatitle ?? $user->display_name])

@section('body')
	@include('layouts.user-header', ["user" => $user])
	@yield('profile-body')
@endsection