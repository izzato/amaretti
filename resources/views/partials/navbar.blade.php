<nav class="navbar navbar-default navbar-fixed-top am-top-header">
    <div class="container-fluid">
        <div class="navbar-header">
            <div class="page-title">
                <span>
                    <a href="{{ url('home') }}" style="color: white">{{ config('app.name') }}</a>
                </span>
            </div>
            <a href="{{ url('home') }}" class="navbar-brand"></a>
        </div>

        <a href="#" data-toggle="collapse" data-target="#am-navbar-collapse" class="am-toggle-top-header-menu collapsed">
            <span class="icon s7-angle-down"></span>
        </a>
        @if(request()->is('home*') && 1==2)
        <a href="#" class="am-toggle-right-sidebar"><span class="icon s7-menu2"></span></a>
        @endif
        <div id="am-navbar-collapse" class="collapse navbar-collapse">
        @auth<ul class="nav navbar-nav am-user-nav">
            <li style="padding-top: 33px;" id="status-indicator">
            </li>
            <li class="dropdown dropdown-right"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="dropdown-toggle"><img src="{{ auth()->user()->gravitar() }}"><span class="user-name">{{ auth()->user()->name }}</span><span class="angle-down s7-angle-down"></span></a>
                <ul role="menu" class="dropdown-menu">
                    <li><a href="{{ route('users.edit', auth()->user()) }}"> <span class="icon s7-user"></span>My account</a></li>
                    <li><a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"> <span class="icon s7-power"></span>Sign Out</a></li>
                </ul>
            </li>
        </ul>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
        @endauth
        <ul class="nav navbar-nav am-top-menu">
            <li><a href="{{ url('/') }}">View site</a></li>
        </ul>
        <ul class="nav navbar-nav am-icons-nav">
            @guest
            <li><a class="nav-link" href="{{ route('login') }}">Login</a></li>
            @endguest
        </ul>
        </div>
    </div>
</nav>