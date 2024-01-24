@extends("layouts.site", ["metatitle" => $collective->display_name."'s gallery"])

@push('head')	
	<meta property="og:description" content="{{ $collective->display_name }}'s gallery on {{ config("app.name") }}">
	<script>
		function toggleView(togglerClass, toggleableClass, toShow, collapsibleClass, toCollapse) {
			let toggleOn, toggleOff, action;
			const clicked = $(event.target).closest(togglerClass)[0];
			
			$(collapsibleClass+":not("+toCollapse+")").collapse("hide");

			if($(clicked).hasClass("selected")) {
				action = "off";
				toggleOn = toggleableClass + ':not(' + toShow + ')';
				$(toCollapse).collapse("hide");
			} else {
				action = "on";
				toggleOff = toggleableClass + ':not(' + toShow + ')';
				toggleOn = toggleableClass + toShow;
				$(toCollapse).collapse("show");
			}
			
			$(togglerClass).removeClass("selected");
			if(action=="on") $(clicked).addClass("selected");
			$(toggleOff).animate({ 'width': 0, 'opacity': 0 }, 200, function(){ $(this).css('display', 'none') });
			$(toggleOn).css('display', 'flex').animate({ 'width': 224, 'opacity': 1 }, 200); 
		}
	</script>
@endpush

@section('body')
	<h1>
        <a href="{{ route("collectives.show", ["collective" => $collective]) }}">
            {{ $collective->display_name }}
        </a>: Gallery</h1>
	@if(!$folderlist->isEmpty())
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
			@if(!$tag)
				@foreach($tags as $listtag) 
					<div class="tag" onclick="toggleView('.tag', '.gallery-thumbnail', '.{{ $listtag->name }}', '.tag-info', '#tag-info-{{ $listtag->id }}')">
						{{ $listtag->name }}
						@if($listtag->tag_highlight)
							<i class="fa fa-info-circle pl-2 py-2" data-toggle="tooltip" title="This tag has meta information"></i>
						@endif
					</div>
				@endforeach
			@else
				@include("tags.collective-taglist", ["collective" => $collective, "tags" => $tags, "selected" => $tag ?? null])
			@endif
		</div>
		
		@foreach($tags as $listtag)
			<div
				id="tag-info-{{ $listtag->id }}"
				class="py-2 tag-info collapse
				@if($tag && $tag->id == $listtag->id) show @endif">
				@if(!$tag)
					<a class="folder-badge-link"
						href="{{ route("collectives.folders.index", ["collective" => $collective, "tag" => $listtag->name ?? null]) }}"
						data-toggle="tooltip"
						title="Tag permalink"
						onclick="event.stopPropagation()">
						<i class="fa fa-tag ml-2"></i> Tag permalink
					</a>
				@endif
				@if($listtag->tag_highlight)
					{!! $listtag->tag_highlight->text !!}
				@endif
			</div>
		@endforeach
	</div>
	@endunless
	
	@include("layouts.gallery", ["artworks" => $artworks])
@endsection