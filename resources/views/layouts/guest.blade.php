@extends("layouts.site")

@push("metatitle"){{ "Login" }}@endpush
@push("title"){{ "Log into your account" }}@endpush

@section('body')    
    <div class="p-4">

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>
@endsection