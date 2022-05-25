<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @yield('head.top')

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
        }

        body {
            background: url(/images/blurry.jpg) top no-repeat fixed;
            background-size: cover;
            padding-top: 48px;
            padding-left: 0;
        }

        .mt-4.text-center a {
            color: #fff !important;
        }

        .justify-content-center {
            display: flex;
            align-items: center;
            height: 70%;
        }

        .brand {
            margin: 0 auto;
            display: block;
            text-align: center;
        }

        .brand img {
            width: 100%;
            padding: 5em 0 3em 0;
            max-width: 300px;
        }

        .wrapper {
            width: 400px;
        }

        .card {
            border-color: transparent;
            box-shadow: 0 0 40px rgba(0,0,0,.05);
            padding: 10px;
            margin: 0 10px;
        }

        .form-control {
            border-width: 2.3px;
        }

        .lh-2 {
            line-height: 2.5;
        }
    </style>
    @yield('head.bottom')
</head>
<body>
    @yield('body.top')
    <div id="app">
        @yield('content')
    </div>
    @yield('body.bottom')
</body>
</html>
