@extends('layouts.dashboard')

@section('head.top')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.2.5/css/select.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="/lib/datatables/css/dataTables.bootstrap.min.css">
@endsection

@section('content')
<div class="am-content">
    <div class="page-head">
        <h2 style="display: inline-block">{{ request()->query('status') === 'trashed' ? 'Trashed projects' : 'All projects' }}</h2>

        <div class="pull-right" style="padding: 1.5em 0 0 0">
            <!-- <div class="btn-group btn-space" style="display: inline-block !important">
                <button type="button" class="btn btn-default">Live preview</button>
                <button type="button" data-toggle="dropdown" class="btn btn-default dropdown-toggle" aria-expanded="false"><span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>
                <ul role="menu" class="dropdown-menu dropdown-menu-right">
                    <li><a href="#">Visit page</a></li>
                </ul>
            </div> -->
            @can('trash projects')
            @if(request()->query('status') === 'trashed')
            <div class="btn-group btn-space" style="display: inline-block !important">
                <a href="{{ route('projects.list') }}" class="btn btn-default">All projects</a>
            </div>
            @else
            <div class="btn-group btn-space" style="display: inline-block !important">
                <a href="{{ route('projects.list', ['status' => 'trashed']) }}" class="btn btn-danger">Trash</a>
            </div>
            @endif
            @endcan
            @can('create projects')
            <div class="btn-group btn-space" style="display: inline-block !important">
                <a href="{{ route('projects.create') }}" class="btn btn-success">Add project</a>
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
            <li class="active">Projects</li>
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
            <table id="projects" class="table table-striped table-hover table-fw-widget responsive display nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr>
                    <th>Title</th>
                    <th class="hide_column">Creator</th>
                    <th class="hide_column">Categories</th>
                    <th class="hide_column">Keywords</th>
                    <th class="hide_column">Featured</th>
                    <th class="hide_column">Published</th>
                    <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($projects as $key => $project)
                    <tr>
                    <td class="title">
                        @if(!$project->trashed())
                        <a href="{{ route('projects.edit', $project) }}">{{ $project->title }}</a>
                        @else
                        {{ $project->title }}
                        @endif
                        <div class="row-actions">
                            @if(!$project->trashed())
                            <a href="{{ route('projects.edit', $project) }}">Edit</a>
                            @else
                            <a href="javascript:;" onclick="event.preventDefault();document.getElementById('restore-{{ $key + 1 }}').submit();">Restore</a>
                            @endif | 
                            @if(!$project->trashed())
                            <a href="javascript:;" class="text-danger" onclick="event.preventDefault();document.getElementById('trash-{{ $key + 1 }}').submit();">Trash</a>
                            @else
                            <a href="javascript:;" class="text-danger" onclick="event.preventDefault();if(confirm('Are you sure you want to delete this project?')){document.getElementById('delete-{{ $key + 1 }}').submit();}">Delete permanently</a>
                            @endif
                            @unless($project->trashed())
                            | <a href="{{ route('projects.show', $project) }}">View</a>
                            @endunless
                        </div>
                    <form id="trash-{{ $key + 1 }}" action="{{ route('projects.trash', $project) }}" method="POST" style="display: none;">@method("PATCH")@csrf</form>
                    <form id="restore-{{ $key + 1 }}" action="{{ route('projects.restore', $project) }}" method="POST" style="display: none;">@method("PATCH")@csrf</form>
                    <form id="delete-{{ $key + 1 }}" action="{{ route('projects.destroy', $project) }}" onsubmit="alert('here')" method="POST" style="display: none;">@method("DELETE")@csrf</form>
                    </td>
                    <td class="hide_column"><a href="{{ route('users.edit', $project->user) }}">{{ $project->user->name }}</a></td>
                    <td class="hide_column">
                        @forelse($project->categories as $key => $category)
                            @if($key < 3)
                            <span class="label label-primary" style="margin-right: 5px">{{ $category->name }}</span>
                            @else
                            ...
                            @endif
                        @empty
                        None
                        @endforelse
                    </td>
                    <td class="hide_column">{!! implode('', array_map(function($value, $index){return ($index < 3) ? '<span class="label label-primary" style="margin-right: 5px">' . $value . '</span>' : '';}, explode(',', $project->meta_keywords), array_keys(explode(',', $project->meta_keywords)))) !!}</td>
                    <td class="hide_column">
                        <a href="{{ route('projects.edit', $project) }}">
                            <div class="input-group">
                                    <div class="switch-button switch-button-yesno">
                                        <input disabled="disabled" type="checkbox" {{ !empty($project->featured) ? 'checked' : '' }}>
                                        <span><label></label></span>
                                    </div>
                            </div>
                        </a>
                    </td>
                    <td class="hide_column">
                        <a href="{{ route('projects.edit', $project) }}">
                            <div class="input-group">
                                    <div class="switch-button switch-button-yesno{{ (!empty($project->published_at) && $project->published_at->gt(now())) ? ' scheduled' : '' }}">
                                        <input disabled="disabled" type="checkbox" {{ !empty($project->published_at) ? 'checked' : '' }}>
                                        <span><label></label></span>
                                    </div>
                            </div>
                        </a>
                    </td>
                    <td class="center" title="{{ array_first(['Deleted at' => $project->deleted_at, 'Published at' => $project->published_at, 'Created at' => $project->created_at, 'Updated at' => $project->updated_at], function ($value, $key) {return !is_null($value);}) }}" data-sort="{{ array_first(['Deleted at' => $project->deleted_at, 'Published at' => $project->published_at, 'Created at' => $project->created_at, 'Updated at' => $project->updated_at], function ($value, $key) {return !is_null($value);}) }}">{{ array_first(['Deleted at' => $project->deleted_at, 'Published at' => $project->published_at, 'Created at' => $project->created_at, 'Updated at' => $project->updated_at], function ($value, $key) {return !is_null($value);})->diffForHumans() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('body.bottom')
<script src="/lib/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="/lib/datatables/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/select/1.2.5/js/dataTables.select.min.js" type="text/javascript"></script>
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

    $("#projects").dataTable({
        // columnDefs: [ {
        //     orderable: true,
        //     className: 'select-checkbox',
        //     targets:   0
        // } ],
        // select: {
        //     style:    'os',
        //     selector: 'td:first-child'
        // },
        order: [[ 6, "desc" ]],
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