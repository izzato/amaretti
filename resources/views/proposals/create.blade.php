@extends('layouts.dashboard')

@section('head.top')
<link rel="stylesheet" type="text/css" href="/lib/datetimepicker/css/bootstrap-datetimepicker.min.css">
<link rel="stylesheet" type="text/css" href="/lib/select2/css/select2.min.css">
@endsection

@section('content')
<div class="am-content">
    <form method="POST" action="{{ empty($proposal->slug) ? route('proposals.store') : route('proposals.update', $proposal) }}" autocomplete="off" enctype="multipart/form-data" id="resource-form">
        @if(!empty($proposal->slug))
        {{ method_field('put') }}
        @endif
        <div class="page-head">
            <div class="row">
                <div class="col-md-8">
                    <h2 style="display: inline-block">{{ empty($proposal->slug) ? 'Add proposal' : 'Edit proposal' }}</h2>
                    <ol class="breadcrumb">
                        <li><a href="{{ route('home') }}">Dashboard</a></li>
                        <li>
                            @can('list proposals')
                            <a href="{{ route('proposals.list') }}">Proposals</a>
                            @else
                            Proposals
                            @endcan
                        </li>
                        <li class="active">{{ empty($proposal->slug) ? 'Add' : 'Edit' }}</li>
                    </ol>
                </div>
                <div class="col-md-4">
                    <div class="pull-right" style="padding: 1.5em 0 0 0">
                        <div class="btn-group btn-space" style="display: inline-block !important">
                            @if(!empty($proposal->slug))
                            <!-- <button type="button" class="btn btn-default">Live preview</button> -->
                            <a href="{{ route('proposals.show', $proposal) }}" class="btn btn-default">Visit page</a>
                            @endif
                            @if(!empty($proposal->slug && 1==2))
                            <button type="button" data-toggle="dropdown" class="btn btn-default dropdown-toggle" aria-expanded="false"><span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>
                            <ul role="menu" class="dropdown-menu dropdown-menu-right">
                                <li><a href="{{ route('proposals.show', $proposal) }}">Visit page</a></li>
                            </ul>
                            @endif
                        </div>
                        <div class="btn-group btn-space" style="display: inline-block !important">
                            <button type="submit" class="btn btn-success">Save</button>
                            {{-- <button type="button" data-toggle="dropdown" class="btn btn-success btn-shade2 dropdown-toggle" aria-expanded="false"><span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>
                            <ul role="menu" class="dropdown-menu dropdown-menu-right">
                                <li><a href="#" onclick="document.forms[1].action += '?return={{ route('proposals.create') }}';document.forms[1].submit()">Save and create another</a></li>
                                <li><a href="#">Save and duplicate</a></li>
                                <li class="divider"></li>
                                <li><a href="#">Separated link</a></li>
                            </ul> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="main-content">
            @include('flash::message')
            @if ($errors->any())
            <div role="alert" class="alert alert-danger alert-icon alert-dismissible alert-important">
                <div class="icon"><span class="s7-attention"></span></div>
                <div class="message">
                    <button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true" class="s7-close"></span></button>
                    <ul style="list-style-type:none">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
            <div class="row">
                <div class="col-xl-9 col-md-8">
                    <div id="left-accordian" class="panel-group accordion accordion-semi">
                        <div class="panel panel-default">
                            <div class="panel-heading default">
                                <h4 class="panel-title"><a data-toggle="collapse" href="#ac1-1" aria-expanded="true">Title <i class="icon s7-angle-down pull-right" tabindex="-1"></i><span class="icon s7-help1 pull-right" data-toggle="popover" data-trigger="hover" title="" data-content="This will be shown as the title of the proposal" data-placement="top"></span></a></h4>
                            </div>
                            <div id="ac1-1" class="panel-collapse in" aria-expanded="true">
                                <div class="panel-body">
                                    <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
                                        <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $proposal->title) }}" parsley-trigger="change" required="" autocomplete="off" data-parsley-id="1" autofocus="">

                                        @if ($errors->has('title'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('title') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading default">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#ac1-2" aria-expanded="true">PDF
                                        <i class="icon s7-angle-down pull-right" tabindex="-1"></i>
                                        <span class="icon s7-help1 pull-right" data-toggle="popover" data-trigger="hover" title="" data-content="This will be the pdf used for the proposal"
                                            data-placement="top"></span>
                                    </a>
                                </h4>
                            </div>
                            <div id="ac1-2" class="panel-collapse in" aria-expanded="true">
                                <div class="panel-body">
                                    <div class="form-group {{ $errors->has('pdf') ? ' has-error' : '' }}">
                                        @if(file_exists( public_path() . '/storage/uploads/' . $proposal->pathHash() . '.pdf'))
                                            <h4><a href="{{ asset('storage/uploads/' . $proposal->pathHash() . '.pdf') }}" target="_blank">{{ $proposal->pathHash() . '.pdf' }}</a></h4>
                                        @else
                                            <input type="file" name="pdf" id="pdf" class="form-control" parsley-trigger="change" autocomplete="off" data-parsley-id="2" required="">
                                            @if ($errors->has('pdf'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('pdf') }}</strong>
                                            </span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-xl-3 col-md-4">
                    <div id="right-accordian" class="panel-group accordion accordion-semi">
                        <div class="panel panel-default">
                            <div class="panel-heading success">
                                <h4 class="panel-title"><a data-toggle="collapse" href="#ac4-1" aria-expanded="true" data-parent="#right-accordian">Details <i class="icon s7-angle-down pull-right" tabindex="-1"></i><span class="icon s7-help1 pull-right" data-toggle="popover" data-trigger="hover" title="" data-content="Extra details about the proposal" data-placement="top"></span></a></h4>
                            </div>
                            <div id="ac4-1" class="panel-collapse in" aria-expanded="true">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group {{ $errors->has('slug') ? ' has-error' : '' }}">
                                                <label for="slug">URL slug</label>
                                                <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $proposal->slug) }}" parsley-trigger="change" autocomplete="off" data-parsley-id="3">

                                                @if ($errors->has('slug'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('slug') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group {{ $errors->has('published') ? ' has-error' : '' }} pull-right">
                                                <label for="published">Published</label>
                                                <div class="input-group" style="padding-top: .75em">
                                                    <div class="switch-button switch-button-yesno{{ (!empty($proposal->published_at) && $proposal->published_at->gt(now())) ? ' scheduled' : '' }}">
                                                        <input type="checkbox" name="published" id="published" {{ (! empty(old('published', $proposal->published_at)) ? 'checked' : '') }} onchange="if(this.checked){document.getElementById('pub_date').style.display='block'}else{document.getElementById('pub_date').style.display='none'}">
                                                        <span><label for="published"></label></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group {{ $errors->has('published_at') ? ' has-error' : '' }}" id="pub_date" style="{{ !! empty(old('published_at', $proposal->published_at)) ? 'display: none' : '' }}">
                                        <label for="published_at">Published date</label>
                                        <div class="input-group datetimepicker">
                                            <input name="published_at" id="published_at" size="16" type="text" value="{{ old('published_at', $proposal->published_at) }}" class="form-control"><span class="input-group-addon btn btn-primary"><i class="icon-th s7-date"></i></span>
                                        </div>

                                        @if ($errors->has('published_at'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('published_at') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group {{ $errors->has('user_id') ? ' has-error' : '' }}">
                                        <label for="user-id">Created by</label>
                                        <select name="user_id" id="user-id" class="tags">
                                            <option value="">Select a user...</option>
                                            @foreach($users as $key => $user)
                                            <option value="{{ $user->id }}" {{ $user->id === old('user_id', optional($proposal->user)->id) ? 'selected' : '' }}>{{ $user->name }}</option>
                                            @endforeach
                                        </select>

                                        @if ($errors->has('user_id'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('user_id') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                                <label for="password">Password</label>
                                                <input type="text" name="password" id="password" class="form-control" value="{{ old('password', $proposal->password) }}" parsley-trigger="change"
                                                    autocomplete="off" data-parsley-id="4"> @if ($errors->has('password'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @csrf
    </form>
    <footer><span class="pull-right">Version {{ config('app.version') }}</span></footer>
</div>
@endsection

@section('body.bottom')
<script src="/lib/moment.js/min/moment.min.js" type="text/javascript"></script>
<script src="/lib/datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script src="/lib/select2/js/select2.min.js" type="text/javascript"></script>
<script src="/lib/parsley/parsley.min.js" type="text/javascript"></script>
<script>
    $(".datetimepicker").datetimepicker({
    	autoclose: true,
    	componentIcon: '.s7-date',
    	navIcons:{
    		rightIcon: 's7-angle-right',
    		leftIcon: 's7-angle-left'
    	},
        pickerPosition: 'bottom-left',
        format: 'yyyy-mm-dd hh:ii:ss'
    });

    $(".tags").select2({tags: true, width: '100%'});

    $('#resource-form').parsley();

    function slugify(text)
    {
        return text.toString().toLowerCase()
            .replace(/\s+/g, '-')           // Replace spaces with -
            .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
            .replace(/\-\-+/g, '-')         // Replace multiple - with single -
            .replace(/^-+/, '')             // Trim - from start of text
            .replace(/-+$/, '');            // Trim - from end of text
    }
    var slugEl;
    document.addEventListener('DOMContentLoaded', function(){
        slugEl = document.getElementById('slug');
        hasSlug = !!slugEl.value;
        document.getElementById('title').addEventListener('keyup', function(e) {
            if (!hasSlug) {
                slugEl.value = slugify(this.value);
            }
        });
        document.getElementById('slug').addEventListener('keyup', function(e) {
            hasSlug = true;
        });
    }, false);

    $('.am-content > form').submit(function(){
        window.onbeforeunload = null;
    });

    var somethingChanged = false;
    $(document).ready(function() {
        $(document).on('change keypress', 'input, textarea, .note-editable', function() {
            window.onbeforeunload = function() {
                return "You have unsaved changes, are you sure you want to leave?";
            }
        });
    });
</script>
@endsection