@extends('layouts.site')

@section('body')
	<h1>Notifications</h1>
	<ul>
		<?php error_log($user->notifications) ?>
		@foreach($user->notifications as $notification)
			<li>
				<a href="{{ route("user", ["username" => $notification->sender->name]) }}">
					{{ $notification->sender->name }}
				</a>
				favorited your artwork 
				<a href="{{ route("art", ["path" => $notification->artwork->path]) }}">
					{{ $notification->artwork->title }}
				</a>
			</li>
		@endforeach
	</ul>
@endsection