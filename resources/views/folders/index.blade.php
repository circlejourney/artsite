@extends("layouts.site")

@section('body')
	@include("layouts.user-header", ["user" => $user])

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
			Tags <i class="collapse-arrow fa fa-chevron-down"></i>
		</a>
		<div id="tag-wrapper" class="collapse">
			@include("tags.taglist", ["user" => $user, "tags" => $tags])
		</div>
	</div>
	@endunless
	
	@include("layouts.gallery", ["artworks" => $artworks])
@endsection