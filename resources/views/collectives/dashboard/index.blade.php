@extends("layouts.site")

@section("body")
    <h1>Collective {{ $collective->display_name }}: Dashboard</h1>
    <ul>
        <li>
            <a href="{{ route("collectives.folders.manage", [ "collective" => $collective ]) }}">Manage folders</a>
        </li>
        <li>
            <a href="{{ route("collectives.art.manage", [ "collective" => $collective ]) }}">Add, remove and manage art</a>
        </li>
    </ul>
@endsection