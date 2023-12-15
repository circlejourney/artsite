@extends('layouts.site')

@section('body')
    <h1>Collectives</h1>
    @foreach($collectives as $collective) 
        <li>
            <a href="{{ route("collectives.show", [ "collective" => $collective->url ]) }}">
                {{$collective->display_name }}
            </a>
        </li>
    @endforeach
@endsection