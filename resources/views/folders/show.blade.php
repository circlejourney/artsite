@extends("layouts.profile", ["user" => $user, "metatitle" => $user->name."'s gallery"])

@section('profile-body')
	<div class="page-block">

		@unless($childfolders->isEmpty())
		<div class="folder-section">
			<a class="collapse-link"
				href="#folder-wrapper"
				data-toggle="collapse"
				onclick="$(this).find('.collapse-arrow').toggleClass('upside-down')">
				Folders <i class="collapse-arrow fa fa-chevron-down upside-down"></i>
			</a>
			<div id="folder-wrapper" class="collapse show active">
				@include("folders.folderrow", ["folderlist" => $childfolders, "tag" => $tag ?? null])
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
			{{ $folder->getDisplayName() }}
			
			@unless($folder->isTopFolder())
				<a class="folder-badge-link" href="{{
					route("folders.show", ["username" => $user->name, "folder" => $folder->parent()->first(), "tag" => $tag ?? null])
				}}">Go back to {{ $folder->parent()->first()->getDisplayName() }}</a>
			@endunless

		</h3>
		@include("layouts.gallery", ["artworks" => $artworks, "user" => $user])
	</div>
@endsection