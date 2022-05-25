<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        @include('partials.meta')

        @yield('head.top')

        {{-- CSS Files --}}

        {{-- Font Awesome Icons --}}
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

        {{-- Lightbox --}}
        <link rel="stylesheet" href="{{asset('css/lightbox.css')}}">

        {{-- Uikit --}}
        <link rel="stylesheet" href="{{asset('css/uikit.min.css')}}">

        {{-- Bootstrap Framework --}}
        <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">

        {{-- Main Stylesheet --}}
        <link rel="stylesheet" href="{{asset('css/style.css')}}">

        {{-- End CSS Files --}}

        {{-- Modernizr Plugin --}}
        <script src="{{asset('js/modernizr-custom.js')}}"></script>
        {{-- END Modernizr Plugin --}}

        {{-- temp Stylesheet --}}
        <link rel="stylesheet" href="{{ asset('css/temp_layouts_main.css') }}">

        @yield('head.bottom')

        <!--[if lt IE 9]>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        {{-- Loading Screen --}}
        <div id="loader-wrapper">
            
            {{-- Loading Image --}}
            <div class="loader-img"></div>
            {{-- END Loading Image --}}
            
            {{-- Loading Screen Split --}}
            <div class="loader-section section-left"></div>
            <div class="loader-section section-right"></div>
            {{-- End Loading Screen Split --}}
            
        </div> 
        {{-- End Loading Screen --}}

        @yield('body.top')
        @include('partials.frontend-admin-navigation')

        {{-- Main Container --}}
        <div class="container theme-container">

            {{-- All Sections --}}
            <div class="sections">

                {{-- Navigation Starts --}}
                <nav class="navigation navbar navbar-default navbar-fixed-top @can('view dashboard') with-admin-navigation @endcan">
                    {{-- Navigation Heading --}}
                    <div class="navbar-header">

                        {{-- Mobile Navigation Button --}}
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                            <span class="sr-only"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        {{-- End Mobile Navigation Button --}}

                        {{-- Navigation Logo --}}
                        <a class="navbar-brand" href="{{ url('/') }}"><img src="{{ asset('images/amio-logo.png') }}" alt="Amaretti"></a>
                        {{-- End Navigation Logo --}}
                    </div>
                    {{-- End Navigation Heading --}}

                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        {{-- Navigation List --}}
                        <ul class="nav navbar-nav normal">
                            <li><a href="{{url('/')}}">home</a></li>
                            <li><a href="{{url('projects')}}">projects</a></li>
                            <li><a href="#">contact</a></li>
                        </ul>
                    </div>

                </nav>
                {{-- Navigation Ends --}}

                @yield('content')

                @include('partials.footer')

            </div>
            {{-- End All Sections --}}

        </div>
        {{-- End Main Container --}}

        {{-- JavaScript Files --}}

        {{-- Essential JS Files --}}
        <script type="text/javascript" src="{{asset('js/jquery-2.2.4.min.js')}}"></script>

        {{-- Bootstrap --}}
        <script type="text/javascript" src="{{asset('js/bootstrap.min.js')}}"></script>

        {{-- uikit --}}
        <script type="text/javascript" src="{{asset('js/uikit.min.js')}}"></script>

        {{-- Custom Code --}}
        <script type="text/javascript" src="{{asset('js/custom.js')}}"></script>

        {{-- JS Plugins --}}

        {{-- Grid --}}
        <script type="text/javascript" src="{{asset('js/grid.min.js')}}"></script>

        {{-- Responsive Slider --}}
        <script type="text/javascript" src="{{asset('js/responsiveslides.min.js')}}"></script>

        {{-- Lightbox --}}
        <script type="text/javascript" src="{{asset('js/lightbox-plus-jquery.min.js')}}"></script>

        {{-- End JavaSript Files --}}
        @yield('body.bottom')
    </body>
</html>