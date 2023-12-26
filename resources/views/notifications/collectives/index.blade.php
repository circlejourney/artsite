@extends('layouts.site')

@section("sidebar")	
    <div class="sidebar">
	    @include('notifications.notification-menu', ["active" => "collectives"])
    </div>
@endsection

@section('body')
    <h1>Collective notifications</h1>
    @forelse($collective_notifications as $collective_notification)
        @if($collective_notification->type == "co-join")
            @include("notifications.collectives.form")
        @else
            @include("notifications.notification-item", ["notification" => $collective_notification])
        @endif
    @empty
        <p>No notifications found.</p>
    @endforelse
@endsection