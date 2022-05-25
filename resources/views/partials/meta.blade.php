    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">

    <link rel="dns-prefetch" href="//maps.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
@if (!empty($project))
    <title>{{ $project->title }} | {{ config('app.name') }}</title>
    <meta name="description" content="{{ $project->meta_description }}">
    <meta property="og:title" content="{{ $project->title }} | {{ config('app.name') }}">
    <meta property="og:description" content="{{ $project->meta_description }}">
    <meta name="keywords" content="{{ $project->meta_keywords }}">
@else
    <title>@yield('title') | {{ config('app.name') }}</title>
@endif
    <meta name="author" content="{{ config('app.name') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="og:locale" content="en_US">
@if (!empty($project))
    <meta itemprop="name" content="{{ $project->title }}">
    <meta itemprop="description" content="{{ $project->excerpt }}">
    <meta itemprop="image" content="{{ 'featured image' }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ 'featured image' }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ $project->title }} | {{ config('app.name') }}">
    <meta name="twitter:description" content="{{ $project->meta_description }}">
    <meta name="twitter:image" content="{{ 'featured image' }}">
@else
    <meta property="og:image" content="{{ 'img/seo-main-screen.jpg' }}">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ config('app.name') }}">
@endif
    <link rel="canonical" href="{{ Request::url() }}">
    <meta property="og:url" content="{{ Request::url() }}">

    <link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/apple-touch-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon-152x152.png">
    <link rel="icon" type="image/png" href="/favicon-196x196.png" sizes="196x196">
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
    <link rel="icon" type="image/png" href="/favicon-128.png" sizes="128x128">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <meta name="msapplication-TileColor" content="#FFFFFF">
    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="msapplication-TileImage" content="/mstile-144x144.png">
    <meta name="msapplication-square70x70logo" content="/mstile-70x70.png">
    <meta name="msapplication-square150x150logo" content="/mstile-150x150.png">
    <meta name="msapplication-wide310x150logo" content="/mstile-310x150.png">
    <meta name="msapplication-square310x310logo" content="/mstile-310x310.png">
    {{-- The following lines are for the app colour --}}
    {{-- Chrome, Firefox OS and Opera --}}
    <meta content="#FFF" name="theme-color">
    {{-- Windows Phone --}}
    <meta content="#FFF" name="msapplication-navbutton-color">
    {{-- iOS Safari --}}
    <meta content="#FFF" name="apple-mobile-web-app-status-bar-style">
