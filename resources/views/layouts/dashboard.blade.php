<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="robots" content="noindex,nofollow">

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    @yield('head.top')

    {{-- Styles --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <link rel="stylesheet" href="{{ asset('lib/stroke-7/style.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/jquery.nanoscroller/css/nanoscroller.css') }}">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    @include('partials.favicon')

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    @yield('head.bottom')
</head>
<body>
    @yield('body.top')

    <div class="am-wrapper" id="app">
        @include('partials.navbar')
        @include('partials.left-sidebar')
        <div class="am-content">
            <div role="alert" class="alert alert-info alert-icon alert-important navigation-alert">
                <div class="icon"><span class="s7-info"></span></div>
                <div class="message">
                    <p>Pull from the left side to navigate</p>
                </div>
            </div>
        </div>
        @yield('content')
    </div>
    @yield('body.middle')
    <script src="{{ asset('lib/jquery/jquery.min.js')}}"></script>
    <script src="{{ asset('lib/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <script src="{{ asset('js/app.js')}}"></script>
    <script src="{{ asset('js/dashboard.js')}}"></script>

    <script src="{{ asset('lib/jquery.nanoscroller/javascripts/jquery.nanoscroller.min.js')}}"></script>
    <script>
        $(document).ready(function(){
            App.init({
                // openLeftSidebarOnClick: true
            });
        });
        $('div.alert').not('.alert-important').delay(3000).fadeOut(350);
    </script>
    @if(config('app.env') == 'production')
    <script>
    (function(w,d,v2){w.chaport = { app_id : '' };v2=w.chaport;v2._q=[];v2._l={};v2.q=function(){v2._q.push(arguments)};v2.on=function(e,fn){if(!v2._l[e])v2._l[e]=[];v2._l[e].push(fn)};var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://app.chaport.com/javascripts/insert.js';var ss=d.getElementsByTagName('script')[0];ss.parentNode.insertBefore(s,ss)})(window, document);
    </script>
    @endif
    @yield('body.bottom')
</body>
</html>