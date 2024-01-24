@extends("layouts.site")

@section("body")
    <h1>
        <a href="{{ route("collectives.show", ["collective" => $collective]) }}">
            {{ $collective->display_name }}
        </a>: Dashboard</h1>
    <ul>
        <li>
            <a href="{{ route("collectives.folders.manage", [ "collective" => $collective ]) }}">Manage folders</a>
        </li>
        <li>
            <a href="{{ route("collectives.art.manage", [ "collective" => $collective ]) }}">Add, remove and manage art</a>
        </li>
    </ul>
@endsection