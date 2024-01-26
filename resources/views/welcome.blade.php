@extends('layouts.site', ["metatitle" => "Home"])
@push("title"){{ "Home" }}@endpush
@push("metadescription"){{ "A cool art site with collabs, collectives and more." }}@endpush

@section("body")
	@auth
		<h1>Hello <x-nametag :user="auth()->user()" />!</h1>
			<p>
				<a href="{{ route("dashboard")}}">Go to dashboard</a>
			</p>
	@endauth
	
	<x-spacer/>

	<div class="card hero">
		<div class="hero-text p-5">
			<h1>Art for Artists</h1>
			<p>{{ config("app.name") }} is a cool art site with collabs, collectives, galleries, and lots of placeholder content. Do nostrud occaecat dolor proident incididunt minim ad penis pariatur excepteur et cupidatat minim culpa.</p>
			<a class="button-blob" href="#">Do the thing <i class="fa fa-arrow-right"></i></a>
			<div class="hero-gallery">
				@foreach($random_artworks as $random_art)
				<a href="{{ route("art", ["path" => $random_art->path]) }}">
					<img src="{{ $random_art->getThumbnailURL() }}">
				</a>
				@endforeach
			</div>
		</div>
		<div class="hero-image-wrapper">
			<img class="hero-image" src="https://dummyimage.com/600x500">
		</div>
	</div>

	<x-spacer/>

	<div>
		<h2>Trending Art</h2>
		@include("layouts.gallery", ["artworks" => $popular_artworks])
	
		<div class="w-100 d-flex justify-content-center">
			<a href="#" class="button-blob">See More</a>
		</div>
	</div>

	<x-spacer/>

	<div>
		<h2>Why choose Art Site?</h2>
		<div class="row" style="row-gap: 1rem;">
			<div class="col-12 col-md-4">
				<x-card class="text-center">
					<h3>
						Privacy
					</h3>
				</x-card>
			</div>
			
			<div class="col-12 col-md-4">
				<x-card class="text-center">
					<h3>Collectives</h3>
				</x-card>
			</div>
			
			<div class="col-12 col-md-4">
				<x-card class="text-center">
					<h3>No AI</h3>
				</x-card>
			</div>

			<div class="col-12 col-md-4">
				<x-card class="text-center">
					<h3>Filter</h3>
				</x-card>
			</div>

			<div class="col-12 col-md-4">
				<x-card class="text-center">
					<h3>Multi Image</h3>
				</x-card>
			</div>
			
			<div class="col-12 col-md-4">
				<x-card class="text-center">
					<h3>Customization</h3>
				</x-card>
			</div>
		</div>
	</div>

	<x-spacer/>

	<h2>Top Collectives</h2>
	<div class="row row-gap-1">
	@foreach(range(0,3) as $i)
		<div class="col-12 col-md-6">
			<x-card>
				<div class="row">
					<div class="col-auto">
						<img src="/images/300.png" height="200">
					</div>
					<div class="col">
						Do est eu laborum laboris est reprehenderit aliqua dolor.
					</div>
				</div>
			</x-card>
		</div>
	@endforeach
	</div>
	
	<x-spacer/>

	<h2>FAQ</h2>

	<ul class="list-group">
		@foreach(range(0,4) as $i)
		<li class="list-group-item">Ipsum reprehenderit est enim exercitation cillum consectetur id.</li>
		@endforeach
	</ul>

	<x-spacer/>

	<div class="card hero">
		
		<div class="hero-image-wrapper">
			<img class="hero-image-top" src="https://dummyimage.com/600x300">
		</div>

		<div class="hero-text p-5">
			<h1>Discover Art!</h1>
			<div class="d-flex">
				<div class="mr-4">Est id officia aliqua aliquip nostrud sint proident consectetur sit fugiat minim. Quis aute reprehenderit commodo nulla exercitation eu exercitation ullamco sunt sit in aliqua est adipisicing.</div>
				
				<div>
				<a class="button-blob nowrap" href="#">Sign Up <i class="fa fa-arrow-right"></i></a>
				</div>
			</div>
		</div>

	</div>
@endsection