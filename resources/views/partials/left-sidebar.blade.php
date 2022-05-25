<div class="am-left-sidebar">
    <div class="content">
    <div class="am-logo"></div>
    <ul class="sidebar-elements">
        <li class="parent  {{ request()->is('home') ? 'active' : '' }}"><a href="{{ route('home') }}"><i class="icon s7-monitor"></i><span>Dashboard</span></a></li>
        @can(['list projects', 'create projects', 'edit projects', 'trash projects', 'delete projects'])
        <li class="parent {{ request()->is('projects*') ? 'active' : '' }}"><a href="{{ route('projects.list') }}"><i class="icon s7-albums"></i><span>Projects</span></a>
            <ul class="sub-menu">
                @can('list projects')
                <li>
                    <a href="{{ route('projects.list') }}">All projects</a>
                </li>
                @endcan
                @can('create projects')
                <li>
                    <a href="{{ route('projects.create') }}">Add project</a>
                </li>
                @endcan
            </ul>
        </li>
        @endcan
        @can(['list projects', 'create projects', 'edit projects', 'trash projects', 'delete projects'])
        <li class="parent {{ request()->is('proposals*') ? 'active' : '' }}"><a href="{{ route('proposals.list') }}"><i class="icon s7-bookmarks"></i><span>Proposals</span></a>
            <ul class="sub-menu">
                @can('list projects')
                <li>
                    <a href="{{ route('proposals.list') }}">All proposals</a>
                </li>
                @endcan
                @can('create projects')
                <li>
                    <a href="{{ route('proposals.create') }}">Add proposal</a>
                </li>
                @endcan
            </ul>
        </li>
        @endcan
        <li class="parent {{ request()->is('users*') ? 'active' : '' }}"><a href="{{ route('users.list') }}"><i class="icon s7-users"></i><span>Users</span></a>
            <ul class="sub-menu">
                @can('list users')
                <li>
                    <a href="{{ route('users.list') }}">All users</a>
                </li>
                @endcan
                @can('create users')
                <li>
                    <a href="{{ route('users.create') }}">Add user</a>
                </li>
                @endcan
                <li>
                    <a href="{{ route('users.edit', auth()->user()) }}">Your profile</a>
                </li>
            </ul>
        </li>
    </ul>
    {{-- Sidebar bottom content --}}
    </div>
</div>