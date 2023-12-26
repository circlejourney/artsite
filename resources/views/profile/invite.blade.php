@extends("layouts.profile", ["user" => $user, "metatitle" => $user->name . " - Invite to collective"])

@push('head')
	<meta property="og:image" content="{{ $user->getAvatarURL() }}">
	<meta property="og:description" content="{{ $user->name }} on {{ config("app.name") }}">
@endpush

@section('profile-body')
    <h1>Invite {{ $user->name }} to a collective</h1>
    <p>
        (this form does not currently work)
    </p>
    <form method="POST">
        @csrf
        <select class="form-control" name="collective">
            @foreach($collectives as $collective)
                <option value="{{ $collective->id }}">{{ $collective->display_name }}</option>
            @endforeach
        </select>
        <textarea class="form-control" name="invite_message"></textarea>
        <button>Invite</button>
    </form>
@endsection