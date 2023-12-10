@extends("layouts.site")

@section('body')
	<div class="page-block">
		<h1>Admin dashboard</h1>
		@include("components.admin-links", ["roles" => $roles])
	</div>
@endsection