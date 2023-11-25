@extends("layouts.site")

@section('body')
	@include("layouts.user-header", ["user" => $user])
	
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
				@include("folders.folderrow", ["folderlist" => $childfolders, "tag" => $tag])
			</div>
		</div>
		@endunless
		
		@unless($tags->isEmpty())
		<div class="tag-section">
			<a class="collapse-link"
				href="#tag-wrapper"
				data-toggle="collapse"
				onclick="$(this).find('.collapse-arrow').toggleClass('upside-down')">
				Tags <i class="collapse-arrow fa fa-chevron-down"></i>
			</a>
			<div id="tag-wrapper" class="collapse">
				@include("tags.taglist", ["user" => $user, "folder" => $folder, "tags" => $tags])
			</div>
		</div>
		@endunless

		<h3>
			{{ $folder->getDisplayName() }}
			
			@unless($folder->isTopFolder())
				<a class="folder-badge-link" href="{{
					isset($tag) ?
					route("folders.tagged", ["username" => $user->name, "folder" => $folder->parent()->first(), "tag" => $tag])
					: route("folders.show", ["username" => $user->name, "folder" => $folder->parent()->first(), "tags" => $tag])
				}}">Go back to {{ $folder->parent()->first()->getDisplayName() }}</a>
			@endunless

		</h3>
		@include("layouts.gallery", ["artworks" => $artworks])
	</div>
@endsection