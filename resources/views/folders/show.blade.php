@extends("layouts.profile", ["user" => $user, "metatitle" => $user->name."'s gallery"])

@push('head')
	<meta name="og:image" content="{{ $user->getAvatarURL() }}">
	<meta name="og:description" content="{{ $folder->title }} by {{ $user->name }} on {{ config("app.name") }}">
@endpush

@section('profile-body')
	@unless($childfolders->isEmpty())
	<div class="folder-section">
		<a class="collapse-link"
			href="#folder-wrapper"
			data-toggle="collapse"
			onclick="$(this).find('.collapse-arrow').toggleClass('upside-down')">
			Folders <i class="collapse-arrow fa fa-chevron-down upside-down"></i>
		</a>
		<div id="folder-wrapper" class="collapse show active">
			<div class="folder-row">
				<a class="folder" href="{{
					$folder->isTopFolder() ?
					route("folders.index", ["username" => $user->name])
					: route("folders.show", ["username" => $user->name, "folder" => $folder->id, "all"])
				}}">
					<div class="folder-thumbnail">
						<i class="fa fa-folder-tree"></i>
					</div>

					<div class="folder-title">
						Show all flattened
					</div>
				</a>

				@component("folders.folderrow", ["user" => $user, "folderlist" => $childfolders, "tag" => $tag ?? null]) @endcomponent
			</div>
		</div>
	</div>
	@endunless
	
	@unless($tags->isEmpty())
	<div class="tag-section">
		<a class="collapse-link"
			href="#tag-wrapper"
			data-toggle="collapse"
			onclick="$(this).find('.collapse-arrow').toggleClass('upside-down')">
			Tags <i class="collapse-arrow fa fa-chevron-down upside-down"></i>
		</a>
		<div id="tag-wrapper" class="collapse show active">
			@include("tags.taglist", ["user" => $user, "folder" => $folder, "tags" => $tags ?? null])
		</div>
	</div>
	@endunless

	<h3>
		{{ $folder->getDisplayName() }}{{ $all ? ": Showing all flattened" : "" }}
		
		@unless($folder->isTopFolder())
			<a class="folder-badge-link" href="{{
				route("folders.show", ["username" => $user->name, "folder" => $folder->parent()->first(), "tag" => $tag->name ?? null])
			}}">Go back to {{ $folder->parent()->first()->getDisplayName() }}</a>
		@endunless

	</h3>
	@include("layouts.gallery", ["artworks" => $artworks, "user" => $user])
@endsection