@extends('layouts.site')

@section("sidebar")	
    <div class="sidebar">
	    @include('notifications.notification-menu', ["active" => "collectives"])
    </div>
@endsection

@section('body')
    <h1>Collective notifications</h1>
    @forelse($notifications as $notification)
        @if($notification->type == "co-join")
            @include("notifications.collectives.form", ["read" => $notification->recipients()->where("recipient_id", auth()->user()->id)->first()->pivot->read])
        @elseif($notification->type == "co-invite")
            @include("notifications.collectives.invite-form", ["read" => $notification->recipients()->where("recipient_id", auth()->user()->id)->first()->pivot->read])
        @else
            @include("notifications.notification-item", ["notification" => $notification, "read" => $notification->recipients()->where("recipient_id", auth()->user()->id)->first()->pivot->read])
        @endif
    @empty
        <p>No notifications found.</p>
    @endforelse

    <form class="read" action="{{ route("notifications.put-read") }}">
        @csrf
        @foreach($notifications as $notification)
            <input type="hidden" name="read[]" value="{{ $notification->id }}">
        @endforeach
    </form>

@endsection