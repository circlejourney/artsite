@extends("layouts.site")

@section('body')
	<div class="page-block">
		<h1>{{$user->name}}'s Folders</h1>
		<ul>
		@foreach($user->folders as $folder)
			@if($folder->id !== $user->top_folder_id) 
				<li><a href="{{ route("folders.show", ["username"=>$user->name, "folder"=>$folder]) }}">
					{{ $folder->title }}
				</a></li>
			@endif
		@endforeach
		</ul>
	</div>
@endsection