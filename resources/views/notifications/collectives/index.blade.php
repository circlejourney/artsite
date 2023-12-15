@extends('layouts.site')

@section('body')
    <h1>Collective notifications</h1>
    @include("notifications.notification-menu")
    @foreach($collective_notifications as $collective_notification)
        @include("notifications.collectives.form")
    @endforeach
@endsection