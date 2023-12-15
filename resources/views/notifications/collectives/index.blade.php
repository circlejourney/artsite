@extends('layouts.site')

@section('body')
    <h1>Collective notifications</h1>
    @include("notifications.notification-menu")
    @foreach($collective_notifications as $collective_notification)
        @if($collective_notification->type == "co-join")
            @include("notifications.collectives.form")
        @else
            @include("notifications.notification-item", ["notification" => $collective_notification])
        @endif
    @endforeach
@endsection