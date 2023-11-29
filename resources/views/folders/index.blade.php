@extends("layouts.profile", ["user" => $user, "metatitle" => $user->name."'s gallery"])

@push('head')	
	<meta name="og:image" content="{{ $user->getAvatarURL() }}">
	<meta name="og:description" content="{{ $user->name }}'s gallery on {{ config("app.name") }}">
@endpush

@section('profile-body')
	@if(!$folderlist->isEmpty())
	<div class="folder-section">
		<a class="collapse-link"
			href="#folder-wrapper"
			data-toggle="collapse"
			onclick="$(this).find('.collapse-arrow').toggleClass('upside-down')">
			Folders <i class="collapse-arrow fa fa-chevron-down upside-down"></i>
		</a>
		<div id="folder-wrapper" class="collapse show active">
			@include("folders.folderrow", ["user"=>$user, "folderlist" => $folderlist])
		</div>
	</div>
	@endif
	
	@unless($tags->isEmpty())
	<div class="tag-section">
		<a class="collapse-link"
			href="#tag-wrapper"
			data-toggle="collapse"
			onclick="$(this).find('.collapse-arrow').toggleClass('upside-down')">
			Tags <i class="collapse-arrow fa fa-chevron-down upside-down"></i>
		</a>
		<div id="tag-wrapper" class="collapse show active">
			@include("tags.taglist", ["user" => $user, "tags" => $tags])
		</div>
	</div>
	@endunless
	
	@include("layouts.gallery", ["artworks" => $artworks, "user" => $user])
@endsection