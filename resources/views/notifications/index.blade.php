@extends('layouts.site')

@section('sidebar')
	<div class="sidebar">
		@include('notifications.notification-menu', ["active" => $active])
	</div>
@endsection

@section('body')
	<h1>{{ Str::of($active)->replace("-", " ")->title() }}</h1>
		
	@if($notifications->count() > 0)
		<div class="notification-wrapper">
			<div class="notification-manage-wrapper">
				<div>
					<button class="notification-manage-button button-blob" onclick="$('.real-checkbox').prop('checked', true)">Select All</button>
					<button class="notification-manage-button button-blob" onclick="$('.real-checkbox').prop('checked', false)">Deselect All</button>
				</div>
				<form id="notification-manage" method="POST">
					@csrf
					<button class="notification-manage-button button-blob" name="_method" value="delete">Delete selected</button>
					<button class="notification-manage-button button-blob" name="_method" value="put">Mark selected as read</button>
				</form>
			</div>

			@foreach($notifications as $notification)
				@php
					$read = $notification->pivot->read;
				@endphp
				@if($notification->type == "art-invite")
					@include("notifications.art-invite-form", ["read" => $read])
				@elseif($notification->type == "co-join" || $notification->type == "co-invite")
					@include("notifications.collective-form", ["read" => $read])
				@else
					@include("notifications.notification-item", ["read" => $read])
				@endif
			@endforeach
		</div>

	@else
		<p>
			No notifications found.
		</p>
	@endif

@endsection