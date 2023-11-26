@extends("layouts.site")

@section('body')
	<h1>Invites</h1>
	
	@if($user->hasRole("founder"))
		<p>As a founder, you may generate an infinite number of invite codes.</p>
	@else
		<p>You have {{ $user->invite_credits }} invite generations available.</p>
	@endif
	
	@if($user->invite_credits > 0 || $user->hasRole("founder"))
	<form method="POST">
		@csrf
		<button class="button-pill">
			Generate
		</button>
	</form>
	@endif

	@if($user->invites->isNotEmpty())
	<div>
		@forelse($user->invites as $invite)
			<div>{{ $invite->id }}</div>
		@empty
		@endforelse
	</div>
	@endif
	
@endsection