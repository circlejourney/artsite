@extends("layouts.site", ["metatitle" => $collective->display_name."'s gallery"])

@push('head')
	<meta property="og:description" content="{{ $folder->title }} by {{ $collective->display_name }} on {{ config("app.name") }}">
@endpush

@section('body')
	@unless($childfolders->isEmpty())
	<div class="folder-section">
		<a class="collapse-link"
			href="#folder-wrapper"
			data-toggle="collapse"
			onclick="$(this).find('.collapse-arrow').toggleClass('upside-down')">
			Subfolders <i class="collapse-arrow fa fa-chevron-down upside-down"></i>
		</a>
		<div id="folder-wrapper" class="collapse show active">
			<div class="folder-row">
				<a class="folder" href="{{
					route("collectives.folders.show", ["collective" => $collective, "folder" => $folder->id, "all"])
				}}">
					<div class="folder-thumbnail">
						<i class="fa fa-folder-tree"></i>
					</div>

					<div class="folder-title">
						Show all flattened
					</div>
				</a>

				@component("folders.collective-folderrow", ["collective" => $collective, "folderlist" => $childfolders, "tag" => $tag ?? null]) @endcomponent
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
			@include("tags.collective-taglist", ["collective" => $collective, "folder" => $folder, "tags" => $tags ?? null, "selected" => $tag ?? null])
		</div>
	</div>

	@if($tag && $tag->tag_highlight)
		<div id="tag-info-{{ $tag->id }}" class="tag-info py-2 collapse @if(isset($tag) && $tag->id = $tag->tag_highlight->tag_id) show @endif">
			{!! $tag->tag_highlight->text !!}
		</div>
	@endif
	@endunless

	<h3>
		{{ $folder->getDisplayName() }}{{ $all ? ": Showing all flattened" : "" }}
		
		@unless($folder->isTopFolder())
			<a class="folder-badge-link" href="{{
				route("collectives.folders.show", ["collective" => $collective->url, "folder" => $folder->parent, "tag" => $tag->name ?? null])
			}}">Go back to {{ $folder->parent()->first()->getDisplayName() }}</a>
		@endunless

	</h3>
	@include("layouts.gallery", ["artworks" => $artworks])
@endsection