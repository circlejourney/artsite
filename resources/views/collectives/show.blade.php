@extends("layouts.site")
@section('body')
	<h1>{{ $collective->display_name }}</h1>

	@auth
		@if(auth()->user()->collectives->pluck("id")->doesntContain($collective->id))
		<p>
			<a class="btn btn-primary" data-toggle="modal" href="#request-join">Request to Join</a>
		</p>
		@endif
	@endauth
	
	<h2>Members</h2>
	@foreach($collective->members as $member)
		<div>
			<a href="{{ route("user", ["username" => $member->name]) }}">
				{{ $member->name }}
			</a>
		</div>
	@endforeach

	<h2>Latest Art</h2>
	@include("layouts.gallery", ["artworks" => $artworks])
	@php
		$folderlist = $collective->folders	
	@endphp
	<div class="folder-section">
		<a class="collapse-link"
			href="#folder-wrapper"
			data-toggle="collapse"
			onclick="$(this).find('.collapse-arrow').toggleClass('upside-down')">
			Folders <i class="collapse-arrow fa fa-chevron-down upside-down"></i>
		</a>
		<div id="folder-wrapper" class="folder-row collapse show active">
			@include("folders.collective-folderrow", ["collective"=>$collective, "folderlist" => $folderlist])
		</div>
	</div>


	@auth
	<div class="modal" id="request-join" tabindex="-1">
		<div class="modal-dialog">
		  	<form class="modal-content" method="POST">
				@csrf
				<div class="modal-header">
				<h5 class="modal-title">Request to join {{ $collective->display_name }}</h5>
				<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">x</button>
				</div>
				<div class="modal-body">
				<p>Request message (optional):</p>
				<textarea class="form-control" name="join_message" id="join_message"></textarea>
				</div>
				<div class="modal-footer">
				<button class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button class="btn btn-primary">Submit</button>
				</div>
			</form>
		</div>
	</div>

		@if(($member = $collective->members()->where("user_id", auth()->user()->id)->withPivot("role_id")->first()))
			<h2>Manage</h2>
			<ul>
				<li>
					<a href="{{ route("collectives.dashboard", ["collective" => $collective]) }}">Dashboard</a>
				</li>
				<li>
					<a href="{{ route("collectives.folders.manage", [ "collective" => $collective ]) }}">Manage folders</a>
				</li>
				<li>
					<a href="{{ route("collectives.art.manage", [ "collective" => $collective ]) }}">Add, remove and manage art</a>
				</li>
			</ul>
			<p>
				<a href="{{ route("collectives.leave", ["collective" => $collective]) }}" class="button-pill bg-danger">Leave collective</a>
			</p>
			
			@if($member->pivot->role_id <= 2)
				<p>
					<a href="{{ route("collectives.delete", ["collective" => $collective]) }}" class="button-pill bg-danger">Delete collective</a>
				</p>
			@endif
		@endif
	@endauth
	  
@endsection