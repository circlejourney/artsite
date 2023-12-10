@extends("layouts.site")
@section('body')
	<h1>{{ $collective->display_name }}</h1>
	<div>
		The collectives feature is a WIP...
		<h2>Members</h2>
		@foreach($collective->members as $member)
			<div>
				{{ $member->name }}
			</div>
		@endforeach
		<h2>Folders</h2>
		<div>{{ $collective->topFolder->title }}</div>
	</div>
@endsection