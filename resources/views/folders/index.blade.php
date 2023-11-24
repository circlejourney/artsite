@extends("layouts.site")

@section('body')
	<div class="page-block">
		<h1>{{$user->name}}'s Folders</h1>
		<ul>
		@foreach($folder->children as $childfolder)
			<li><a href="{{ route("folders.show", ["username"=>$user->name, "folder"=>$childfolder]) }}">
				{{ $childfolder->title }}
			</a></li>
		@endforeach
		</ul>
	</div>
@endsection