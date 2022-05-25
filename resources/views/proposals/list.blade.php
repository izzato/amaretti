@extends('layouts.dashboard')

@section('head.top')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.2.5/css/select.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="/lib/datatables/css/dataTables.bootstrap.min.css">
@endsection

@section('content')
<div class="am-content">
    <div class="page-head">
        <h2 style="display: inline-block">{{ request()->query('status') === 'trashed' ? 'Trashed proposals' : 'All proposals' }}</h2>

        <div class="pull-right" style="padding: 1.5em 0 0 0">
            @can('create projects')
            <div class="btn-group btn-space" style="display: inline-block !important">
                <a href="{{ route('proposals.create') }}" class="btn btn-success">Add proposal</a>
            </div>
            @endcan
        </div>

        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="active">Proposals</li>
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
            <table id="projects" class="table table-striped table-hover table-fw-widget responsive display nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr>
                    <th>Title</th>
                    <th class="hide_column">Creator</th>
                    <th class="hide_column">Published</th>
                    <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($proposals as $key => $proposal)
                    <tr>
                    <td class="title">
                        @if(!$proposal->trashed())
                        <a href="{{ route('proposals.edit', $proposal) }}">{{ $proposal->title }}</a> <a href="{{ route('proposals.show', $proposal) }}" class="view-link">&mdash; Available offline</p>
                        @else
                        {{ $proposal->title }}
                        @endif
                        <div class="row-actions">

                            <a href="{{ route('proposals.edit', $proposal) }}">Edit</a>

                            | <a href="javascript:;" class="text-danger" onclick="event.preventDefault();if(confirm('Are you sure you want to delete this project?')){document.getElementById('delete-{{ $key + 1 }}').submit();}">Delete permanently</a>


                            | <a href="{{ route('proposals.show', $proposal) }}" class="view-link">View</a>

                        </div>
                    <form id="delete-{{ $key + 1 }}" action="{{ route('proposals.destroy', $proposal) }}" method="POST" style="display: none;">@method("DELETE")@csrf</form>
                    </td>
                    <td class="hide_column"><a href="{{ route('users.edit', $proposal->user) }}">{{ $proposal->user->name }}</a></td>
                    <td class="hide_column">
                        <a href="{{ route('proposals.edit', $proposal) }}">
                            <div class="input-group">
                                    <div class="switch-button switch-button-yesno{{ (!empty($proposal->published_at) && $proposal->published_at->gt(now())) ? ' scheduled' : '' }}">
                                        <input disabled="disabled" type="checkbox" {{ !empty($proposal->published_at) ? 'checked' : '' }}>
                                        <span><label></label></span>
                                    </div>
                            </div>
                        </a>
                    </td>
                    <td class="center" title="{{ array_first(['Deleted at' => $proposal->deleted_at, 'Published at' => $proposal->published_at, 'Created at' => $proposal->created_at, 'Updated at' => $proposal->updated_at], function ($value, $key) {return !is_null($value);}) }}" data-sort="{{ array_first(['Deleted at' => $proposal->deleted_at, 'Published at' => $proposal->published_at, 'Created at' => $proposal->created_at, 'Updated at' => $proposal->updated_at], function ($value, $key) {return !is_null($value);}) }}">{{ array_first(['Deleted at' => $proposal->deleted_at, 'Published at' => $proposal->published_at, 'Created at' => $proposal->created_at, 'Updated at' => $proposal->updated_at], function ($value, $key) {return !is_null($value);})->diffForHumans() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('body.bottom')
<script src="/js/offline.js"></script>
<script src="https://cdn.datatables.net/v/bs/dt-1.10.18/datatables.min.js" type="text/javascript"></script>
<!-- <script src="/lib/datatables/js/dataTables.bootstrap.min.js" type="text/javascript"></script> -->
<script src="https://cdn.datatables.net/select/1.2.5/js/dataTables.select.min.js" type="text/javascript"></script>
<script>
    $.extend( true, $.fn.dataTable.defaults, {
      dom:
        "<'row am-datatable-header'<'col-sm-6'l><'col-sm-6'f>>" +
        "<'row am-datatable-body'<'col-sm-12'tr>>" +
        "<'row am-datatable-footer'<'col-sm-5'i><'col-sm-7'p>>"
    } );

    $("#projects").dataTable({
        order: [[ 3, "desc" ]],
        fnDrawCallback: function(oSettings) {
        if (oSettings._iDisplayLength > oSettings.fnRecordsDisplay()) {
            $(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
        } else {
            $(oSettings.nTableWrapper).find('.dataTables_paginate').show();
        }
    }
    });

    function appOnline(e) {
        var downloadLinks = document.querySelectorAll('a').forEach(function (el) {
            el.classList.remove('inactiveLink')
        });
        document.getElementById('status-indicator').innerHTML = '<span class="label label-success" style="font-size: 14px;">You are back online</span>';
    }

    function appOffline(e) {
        var downloadLinks = document.querySelectorAll('a').forEach(function (el) {
            el.classList.add('inactiveLink')
        });
        document.getElementById('status-indicator').innerHTML = '<span class="label label-warning" style="font-size: 14px;">You are offline</span>';
    }

    if ('caches' in window) {
        NodeList.prototype.forEach = Array.prototype.forEach;
        window.addEventListener('online', appOnline);
        window.addEventListener('offline', appOffline);

        if (!navigator.onLine) {
            appOffline();
        }

        var downloadLinks = document.querySelector('#projects').querySelectorAll('a').forEach(function (el) {
            if (el.getAttribute('href').indexOf('/edit') === -1) {
                return;
            }
            caches.open('pages').then(function (cache) {
                cache.match(el.getAttribute('href').substring(0, el.getAttribute('href').indexOf('/edit'))).then(function (matchedResponse) {
                    if (matchedResponse) {
                        el.parentNode.classList.add('available-offline');
                    }
                });
            });
        });
    }
</script>
<style>
#projects .title > a + a.view-link {
    display: none;
    color: green;
    font-weight: bold;
}
#projects .available-offline > a + a.view-link {
    display: inline;
}
.inactiveLink {
    color: grey;
    opacity: 0.5;
    pointer-events: none;
    cursor: default;
}
.available-offline .inactiveLink.view-link {
    color: #6bc3b0;
    opacity: 1;
    pointer-events: all;
    cursor: pointer;
}
</style>
@endsection