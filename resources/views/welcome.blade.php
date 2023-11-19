@extends('layouts.site')

@push("metatitle"){{ "Home" }}@endpush
@push("title"){{ "Home" }}@endpush
@push("metadescription"){{ "A cool art site with groups and more." }}@endpush

@section("body")
    <h1>Art Site</h1>
    <p>Welcome to the art site homepage!</p>
    
    @unless(Auth::user())
    <p>
        <a href="/login">Log in or register</a>
    </p>
    @endif

@endsection