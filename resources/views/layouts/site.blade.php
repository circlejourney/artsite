<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config("app.name") }}@isset($metatitle) - {{ $metatitle }} @endisset</title>
    <meta name="description" content="@stack("metadescription")">

    <!-- JQUERY -->
    <script src="/src/jquery/jquery-3.7.1.min.js"></script>
    
    <!-- BOOTSTRAP -->
    <link rel="stylesheet" href="/src/bootstrap/bootstrap.min.css">
    <script src="/src/bootstrap/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="/src/bootstrap-fix.css">

    <!-- FONT AWESOME -->
    <script src="https://kit.fontawesome.com/77ce6977ef.js" crossorigin="anonymous"></script>

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&display=swap" rel="stylesheet">

    <!-- SITE CSS AND JS -->
    <link rel="stylesheet" href="/src/site.css?v={{ filemtime("src/site.css") }}">
    <script src="/src/site.js?v={{ filemtime("src/site.js") }}"></script>

	<meta property="og:title" content="{{ config("app.name") }}@isset($metatitle) - {{ $metatitle }} @endisset">
    <meta name="robots" content="noai, noimageai">
    @stack("head")

</head>
<body>
    @include("layouts.navigation")
    
    <div class="main-wrapper">
        @yield("sidebar")

        <main>
            @if ($errors->any())
                {!! implode('', $errors->all('<div class="alert alert-danger m-4">:message</div>')) !!}
            @endif
            
            @if (session()->has('success'))
                <div class="alert alert-success m-4">{!! session()->get('success') !!}</div>
            @endif
            
            
            @if (session()->has('status'))
                <div class="alert alert-info m-4">{!! session()->get('status') !!}</div>
            @endif

            @yield("body")
        </main>
    </div>

	@include("layouts.footer")
</body>
</html>