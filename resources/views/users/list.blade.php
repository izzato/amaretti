@extends('layouts.dashboard')

@section('head.top')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.2.5/css/select.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="/lib/datatables/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/lib/select2/css/select2.min.css">
@endsection

@section('content')
<div class="am-content">
    <div class="page-head">
        <h2 style="display: inline-block">All users</h2>

        <div class="pull-right" style="padding: 1.5em 0 0 0">
            <!-- <div class="btn-group btn-space" style="display: inline-block !important">
                <button type="button" class="btn btn-default">Live preview</button>
                <button type="button" data-toggle="dropdown" class="btn btn-default dropdown-toggle" aria-expanded="false"><span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>
                <ul role="menu" class="dropdown-menu dropdown-menu-right">
                    <li><a href="#">Visit page</a></li>
                </ul>
            </div> -->
            @can('create users')
            <div class="btn-group btn-space" style="display: inline-block !important">
                <a href="{{ route('users.create') }}" class="btn btn-success">Add user</a>
                <!-- <button type="button" data-toggle="dropdown" class="btn btn-success btn-shade2 dropdown-toggle" aria-expanded="false"><span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>
                <ul role="menu" class="dropdown-menu dropdown-menu-right">
                    <li><a href="#">Save as draft</a></li>
                    {{-- <li class="divider"></li>
                    <li><a href="#">Separated link</a></li> --}}
                </ul> -->
            </div>
            @endcan
        </div>

        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}">Dashboard</a></li>
            <!-- <li><a href="{{ route('projects.list') }}">Projects</a></li> -->
            <li class="active">Users</li>
        </ol>
    </div>
    <div class="main-content">
        @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @endif
        @include('flash::message')
        <div class="widget widget-fullwidth widget-small">
            <!-- <div class="widget-head">
                <div class="tools"><span class="icon s7-upload"></span><span class="icon s7-edit"></span><span class="icon s7-close"></span></div>
                <div class="title">Default</div>
            </div> -->
            <table id="users" class="table table-striped table-hover table-fw-widget responsive display nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr>
                    <th>Name</th>
                    <th class="hide_column">Email</th>
                    <th>Role</th>
                    <th class="hide_column">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $key => $user)
                    <tr>
                    <td class="title" data-sort="{{ $user->name }}"><img src="{{ $user->gravitar() }}" class="gravitar pull-left"><a href="{{ route('users.edit', $user) }}">{{ $user->name }}</a><div class="row-actions"><a href="{{ route('users.edit', $user) }}">Edit</a> | <a href="#" class="text-danger" onclick="event.preventDefault();window.app.$refs.removalModal.userId = {{ $user->id }}">Delete permanently</a><!-- | <a href="{{ route('users.show', $user) }}">View</a>--></div><form id="delete-{{ $key + 1 }}" action="{{ route('users.destroy', $user) }}" method="POST" style="display: none;">@method("DELETE")@csrf</form></td>
                    <td class="hide_column"><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                    <td>{{ ucfirst($user->getRoleNames()->first()) }}</td>
                    <td class="center hide_column" title="{{ array_first(['Created at' => $user->created_at, 'Updated at' => $user->updated_at], function ($value, $key) {return !is_null($value);}) }}" data-sort="{{ array_first(['Created at' => $user->created_at, 'Updated at' => $user->updated_at], function ($value, $key) {return !is_null($value);}) }}">{{ array_first(['Created at' => $user->created_at, 'Updated at' => $user->updated_at], function ($value, $key) {return !is_null($value);})->diffForHumans() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<user-removal-modal ref="removalModal"></user-removal-modal>
@endsection

@section('body.bottom')
<script src="/lib/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="/lib/datatables/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/select/1.2.5/js/dataTables.select.min.js" type="text/javascript"></script>
<script src="/lib/select2/js/select2.min.js" type="text/javascript"></script>
<!-- <script src="/lib/datatables/plugins/buttons/js/dataTables.buttons.js" type="text/javascript"></script>
<script src="/lib/datatables/plugins/buttons/js/buttons.html5.js" type="text/javascript"></script>
<script src="/lib/datatables/plugins/buttons/js/buttons.flash.js" type="text/javascript"></script>
<script src="/lib/datatables/plugins/buttons/js/buttons.print.js" type="text/javascript"></script>
<script src="/lib/datatables/plugins/buttons/js/buttons.colVis.js" type="text/javascript"></script>
<script src="/lib/datatables/plugins/buttons/js/buttons.bootstrap.js" type="text/javascript"></script> -->
<script>
    
    $.extend( true, $.fn.dataTable.defaults, {
      dom:
        "<'row am-datatable-header'<'col-sm-6'l><'col-sm-6'f>>" +
        "<'row am-datatable-body'<'col-sm-12'tr>>" +
        "<'row am-datatable-footer'<'col-sm-5'i><'col-sm-7'p>>"
    } );

    $("#users").dataTable({
        // columnDefs: [ {
        //     orderable: true,
        //     className: 'select-checkbox',
        //     targets:   0
        // } ],
        // select: {
        //     style:    'os',
        //     selector: 'td:first-child'
        // },
        order: [[ 0, "asc" ]],
        fnDrawCallback: function(oSettings) {
        if (oSettings._iDisplayLength > oSettings.fnRecordsDisplay()) {
            $(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
        } else {
            $(oSettings.nTableWrapper).find('.dataTables_paginate').show();
        }
    }
    });
</script>
@endsection
