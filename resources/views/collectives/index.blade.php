@extends('layouts.site')

@section('body')
    <h1>Collectives</h1>
    <ul>
        @foreach($collectives as $collective) 
            <li>
                <a href="{{ route("collectives.show", [ "collective" => $collective->url ]) }}">
                    {{$collective->display_name }}
                </a>
            </li>
        @endforeach
    </ul>
    <a href="{{ route("collectives.create") }}">Create a collective</a>
@endsection