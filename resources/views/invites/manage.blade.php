@extends("layouts.site")

@section('body')
	<h1>Generate invite</h1>
	
	<p>You have {{ $user->invite_credits }} invite generations available.</p>
	@if($user->invite_credits > 0)
	<form method="POST">
		@csrf
		<button>
			Generate
		</button>
	</form>
	@endif

	<div>
		@forelse($user->invites as $invite)
			<div>{{ $invite->id }}</div>
		@empty
		@endforelse
	</div>
@endsection