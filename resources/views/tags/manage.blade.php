@extends("layouts.site")

@section('body')
<h1>Manage tags</h1>
<ul class="list-group">
	@php
		$user = auth()->user();
	@endphp
	@foreach($tags as $tag)
	<li class="list-group-item">
		
		<h2 class="no-margin">
			<a href="{{ route("folders.index", ["username" => $user->name, "tag" => $tag->name]) }}">
				#{{ $tag->name }}
			</a>
		</h2>
		@if($highlight = $tag->tag_highlight)
			<a href="{{ route("tags.edit", ["tag" => $tag->name]) }}">Edit tag highlight</a>
			@if($highlight->thumbnail)
				<div>
					<img class="tag-highlight-image" src="{{ $highlight->getThumbnailURL() }}">
				</div>
			@endif
			@if($highlight->text)
				<div>Custom info:</div>
				<div class="bg-light p-2">
					{!! Str::of($highlight->text)->words(40) !!}
				</div>
			@endif
		@else
			<a href="{{ route("tags.edit", ["tag" => $tag->name]) }}">Create tag highlight</a>
		@endif
	</li>
	@endforeach
</ul>
@endsection