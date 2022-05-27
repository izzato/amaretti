<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta name="robots" content="noindex,nofollow">

        @include('partials.meta')

        @yield('head.top')

        {{-- Main Stylesheet --}}
        <link rel="stylesheet" href="{{asset('css/style.css')}}">

        {{-- Modernizr Plugin --}}
        <script src="{{asset('js/modernizr-custom.js')}}"></script>
        {{-- END Modernizr Plugin --}}

        @yield('head.bottom')

        <!--[if lt IE 9]>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        @yield('body.top')

        @yield('content')

        @yield('body.bottom')
    </body>
</html>