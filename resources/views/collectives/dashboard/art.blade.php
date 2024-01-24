@extends('layouts.site')

@section('body')
    <h1>
        <a href="{{ route("collectives.show", ["collective" => $collective]) }}">
            {{ $collective->display_name }}
        </a>: Manage Artwork</h1>
    <form class="row" method="POST">
        @csrf
        <div class="col">
            <div class="gallery justify-content-center">
            @php $user = auth()->user() @endphp
            @foreach($user->artworks as $artwork)
                <div class="gallery-thumbnail">
                    <label for="select-{{ $artwork->id }}">
                        <img src="{{ $artwork->getThumbnailURL() }}">
                    </label>
                    <a href="{{ route("art", ["path" => $artwork->path]) }}">
                        View artwork
                    </a>
                    <div>
                        <input type="hidden" name="select[{{ $artwork->id }}]" value="0">

                        <input type="checkbox" name="select[{{ $artwork->id }}]" value="1" id="select-{{ $artwork->id }}">
                        <label for="select-{{ $artwork->id }}">
                            Select
                        </label>
                        
                        @php
                            $in_folder = $artwork->folders->filter(function($i) use($collective) {
                                return $collective->folders()->where("id", $i->id)->exists();
                            });
                        @endphp
                        @if($in_folder->count() > 0)
                            <div class="font-weight-bold">
                                Currently in folder: {{ $in_folder->pluck("title")->join(", ") }}
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
            </div>
        </div>
        
        <div class="col-md-3">
            <h2>Move selected art to gallery</h2>
            @include("components.folder-select", ["folderlist" => $folders, "hide_none" => true])
            <button name="_method" value="PUT">Update</button>
            <h2>Remove selected art</h2>
            <button name="_method" value="DELETE">
                Remove
            </button>
        </div>
    </form>
@endsection