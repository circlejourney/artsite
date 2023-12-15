@extends("layouts.profile", ["user" => $user, "metatitle" => $user->name . " - Invite to collective"])

@push('head')
	<meta property="og:image" content="{{ $user->getAvatarURL() }}">
	<meta property="og:description" content="{{ $user->name }} on {{ config("app.name") }}">
@endpush

@section('profile-body')
    <h1>Invite {{ $user->name }} to a collective</h1>
    (this form does not currently work)
    <form method="POST">
        @csrf
        <select>
            @foreach(auth()->user()->collectives as $collective)
                <option value="{{ $collective->id }}">{{ $collective->display_name }}</option>
            @endforeach
        </select>
        <button>Invite</button>
    </form>
@endsection