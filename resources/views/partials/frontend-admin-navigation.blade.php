@can('view dashboard')
<nav id="admin-navigation" class="navbar navbar-inverse navbar-static-top">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="{{ url('/home') }}">Dashboard</a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-left">
            @if(!empty($project))
            <li><a href="{{ route('projects.edit', $project) }}">Edit project</a></li>
            @endif
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Add <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="{{ route('projects.create') }}">Project</a></li>
                    <li><a href="{{ route('proposals.create') }}">Proposal</a></li>
                    <li><a href="{{ route('users.create') }}">User</a></li>
                </ul>
            </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            @if(!empty($project) && $project->published_at && $project->published_at->gt(now()))
            <li style="padding-top: 1.2em">
                <span class="label label-success">This project is scheduled to be published on {{ $project->published_at }}</span>
            </li>
            @elseif(!empty($project) && !$project->published_at)
            <li style="padding-top: 1.2em">
                <span class="label label-warning">This project is unpublished and is not visible to regular users</span>
            </li>
            @endif
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="{{ route('users.edit', auth()->user()) }}">
                            My account
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div><!--/.nav-collapse -->
</nav>
@endcan