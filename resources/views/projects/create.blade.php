@extends('layouts.dashboard')

@section('head.top')
<link rel="stylesheet" type="text/css" href="/lib/summernote/summernote.css">
<link rel="stylesheet" type="text/css" href="/lib/datetimepicker/css/bootstrap-datetimepicker.min.css">
<link rel="stylesheet" type="text/css" href="/lib/select2/css/select2.min.css">
<link rel="stylesheet" type="text/css" href="/lib/dropzone/dist/dropzone.css">
<style>
.datetimepicker {
    padding: 0;
}
.note-editor,
.note-toolbar {
    border: none !important;
    padding: 0 !important;
}
.panel-group.accordion .panel .panel-collapse > .panel-body {
    padding-left: 30px !important;
}
.panel-group.accordion .panel .panel-heading .panel-title a {
    padding: 20px 30px !important;
}
.main-content .panel.panel-default {
    margin-bottom: 5px !important;
}
.popover-content {
    color: black !important;
}
.panel-group.accordion .panel .panel-heading a .icon:first-child {
    -webkit-transform: rotate(-180deg) !important;
    transform: rotate(-180deg) !important;
}
.panel-group.accordion .panel .panel-heading a.collapsed .icon:first-child {
    -webkit-transform: rotate(0deg) !important;
    transform: rotate(0deg) !important;
}
.panel-group.accordion .panel .panel-heading a.collapsed .icon:last-child {
    display: none;
}
.panel-group.accordion.accordion-semi .panel-heading.default a {
    background-color: #fff !important;
    color: #000 !important;
}
.note-editor .note-editable.panel-body {
    border: 2px solid #eaeaea !important;
    padding: 10px !important;
    min-height: 400px;
    font-size: 16px;
}
/* .input-group .form-control {
    z-index: 0 !important;
} */
.note-editor a {
    background-color: #fff !important;
    color: #000 !important;
    -webkit-transition: none !important;
    transition: none !important;
    padding: 9px 20px !important;
}

#published_at:focus + span {
    color: #fff;
    background-color: #7dcaba;
    border-color: #7dcaba;
}
/* .sub-menu {
    position: fixed !important;
    z-index: 9999 !important;
} */
.asset-grid-listing {
    background: #fff;
    display: grid;
    grid-template-columns: repeat(auto-fill,minmax(150px,1fr));
    grid-row-gap: 16px;
    grid-column-gap: 8px;
    padding: 16px;
    position: relative;
}

.asset-tile.is-selected {
    background: rgba(45,57,60,.07);
    border-radius: 4px;
}

.asset-grid-listing .asset-tile {
    cursor: pointer;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-align: center;
    align-items: center;
    -ms-flex-direction: column;
    flex-direction: column;
    -ms-flex-pack: justify;
    justify-content: space-between;
    padding: 8px;
}

.asset-tile .asset-thumb img, .cp-head #typeahead, .daterange .dr-selections .dr-calendar, .mini-chart, .page-tree, .z-depth-1, trix-toolbar .dialogs .dialog {
    box-shadow: 0 0 0 0.5px rgba(49,49,93,.03), 0 2px 5px 0 rgba(49,49,93,.1), 0 1px 2px 0 rgba(0,0,0,.08);
}

.asset-tile .asset-thumb img {
    border: 5px solid #fff;
    display: block;
    max-height: 150px;
    max-width: 150px;
    height: auto;
    width: auto;
    margin: 0 auto;
    margin-bottom: 1em;
}

.asset-browser-actions > * {
    margin-left: .5em;
    margin-right: .5em;
}

.asset-browser-actions > *:first-child {
    margin-left: 0;
    margin-right: .5em;
}

.asset-browser-actions > *:last-child {
    margin-left: .5em;
    margin-right: 0;
}

.card-header {
    background-color: #fff;
}

.card-footer {
    background-color: #fafafa;
}

.card-header h4 {
    margin-top: 6px;
}

.media-browser .card-header,
.asset-browser-actions {
    display: flex;
    justify-content: space-between;
}

.pseudo-hidden {
    position: absolute;
    opacity: 0;
    z-index: -1;
}

.dropzone-container {
    display: none !important;
}
.dragover {
    display: block !important;
}

.asset-grid-listing:focus {
    outline: none;
}

.input-group .form-control,
.datetimepicker.dropdown-menu {
    z-index: 0 !important;
}
.select2-container--default .select2-search--inline .select2-search__field{
  /* width: 100% !important; */
  text-align: left !important;
}
</style>

<style>
        .media-browser .selection {
            position: absolute;
            border: 1px dotted #000;
            z-index: 9999;
            top: 0;
            left: 0;
            cursor: default;
            display: none;
        }
        .media-browser .asset-grid-listing {
            position: relative;
            width: 100%;
            height: 100%;
            /* background: #f0f0f0; */
        }
        .media-browser .selectable {
            /* position: absolute; */
            /* float: left; */
            /* width: 200px;
            height: 50px; */
            /* background-color: purple; */
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        .media-browser .selectable.selecting {
            background-color: yellow;
        }
        .media-browser .selectable.selected {
            background: rgba(45,57,60,.07);
            border-radius: 4px;
        }
    </style>
@endsection

@section('content')
<div class="am-content">
    <form method="POST" action="{{ empty($project->slug) ? route('projects.store') : route('projects.update', $project) }}" autocomplete="off" enctype="multipart/form-data" id="resource-form">
        @if(!empty($project->slug))
        {{ method_field('put') }}
        @endif
        <div class="page-head">
            <div class="row">
                <div class="col-md-8">
                    <h2 style="display: inline-block">{{ empty($project->slug) ? 'Add project' : 'Edit project' }}</h2>
                    <ol class="breadcrumb">
                        <li><a href="{{ route('home') }}">Dashboard</a></li>
                        <li>
                            @can('list projects')
                            <a href="{{ route('projects.list') }}">Projects</a>
                            @else
                            Projects
                            @endcan
                        </li>
                        <li class="active">{{ empty($project->slug) ? 'Add' : 'Edit' }}</li>
                    </ol>
                </div>
                <div class="col-md-4">
                    <div class="pull-right" style="padding: 1.5em 0 0 0">
                        <div class="btn-group btn-space" style="display: inline-block !important">
                            @if(!empty($project->slug))
                            <!-- <button type="button" class="btn btn-default">Live preview</button> -->
                            <a href="{{ route('projects.show', $project) }}" class="btn btn-default">Visit page</a>
                            @endif
                            @if(!empty($project->slug && 1==2))
                            <button type="button" data-toggle="dropdown" class="btn btn-default dropdown-toggle" aria-expanded="false"><span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>
                            <ul role="menu" class="dropdown-menu dropdown-menu-right">
                                <li><a href="{{ route('projects.show', $project) }}">Visit page</a></li>
                            </ul>
                            @endif
                        </div>
                        <div class="btn-group btn-space" style="display: inline-block !important">
                            <button type="submit" class="btn btn-success">Save</button>
                            {{-- <button type="button" data-toggle="dropdown" class="btn btn-success btn-shade2 dropdown-toggle" aria-expanded="false"><span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>
                            <ul role="menu" class="dropdown-menu dropdown-menu-right">
                                <li><a href="#" onclick="document.forms[1].action += '?return={{ route('projects.create') }}';document.forms[1].submit()">Save and create another</a></li>
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
                                <h4 class="panel-title"><a data-toggle="collapse" href="#ac1-1" aria-expanded="true">Title <i class="icon s7-angle-down pull-right" tabindex="-1"></i><span class="icon s7-help1 pull-right" data-toggle="popover" data-trigger="hover" title="" data-content="This will be shown as the title of the project throughout the site" data-placement="top"></span></a></h4>
                            </div>
                            <div id="ac1-1" class="panel-collapse in" aria-expanded="true">
                                <div class="panel-body">
                                    <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
                                        <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $project->title) }}" parsley-trigger="change" required="" autocomplete="off" data-parsley-id="1" autofocus="">

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
                                <h4 class="panel-title"><a data-toggle="collapse" href="#ac1-2" aria-expanded="true">Content <i class="icon s7-angle-down pull-right" tabindex="-1"></i><span class="icon s7-help1 pull-right" data-toggle="popover" data-trigger="hover" title="" data-content="This is what will be shown on the project page." data-placement="top"></span></a></h4>
                            </div>
                            <div id="ac1-2" class="panel-collapse in" aria-expanded="true">
                                <div class="panel-body">
                                    <div class="form-group {{ $errors->has('content') ? ' has-error' : '' }}">
                                        <textarea id="editor" name="content">{{ old('content', $project->content) }}</textarea>

                                        @if ($errors->has('content'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('content') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <dropzone-panel style="display: {{ (empty($project->slug)) ? 'none' : 'block' }}" collection="default">
                            <template slot="heading">
                                Images <i class="icon s7-angle-down pull-right" tabindex="-1"></i><span class="icon s7-help1 pull-right" data-toggle="popover" data-trigger="hover" title="" data-content="These images will be shown on the project page. Drag to reorder." data-placement="top"></span>
                            </template>
                            <div class="form-group">
                                <asset-collection :cold-assets="{{ $project->getMedia('default')->toJson() }}" slug="{{ $project->slug }}" project-id="{{ $project->id }}" ref="assets"></asset-collection>
                            </div>
                            <template slot="message">
                                <div class="dz-message">
                                    <h3>Drag and drop files here</h3><span class="note">(Maximum file size of 25MB allowed. Maximum of 20 uploads per drop.)</span>
                                </div>
                                @csrf
                                <input type="hidden" name="project_id" value="{{ $project->id }}">
                            </template>
                        </dropzone-panel>

                        <div class="panel panel-default">
                            <div role="alert" class="alert alert-info alert-icon alert-important" style="margin-bottom: 0;display: {{ (empty($project->slug)) ? 'block' : 'none' }}">
                                <div class="icon"><span class="s7-info"></span></div>
                                <div class="message">
                                    <ul style="list-style-type:none">
                                        <li>Please save the project to add images</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading default">
                                <h4 class="panel-title"><a data-toggle="collapse" href="#ac1-3" class="collapsed">Excerpt <i class="icon s7-angle-down pull-right" tabindex="-1"></i><span class="icon s7-help1 pull-right" data-toggle="popover" data-trigger="hover" title="" data-content="This is a summary of the main content. You can leave blank to use an auto generated excerpt." data-placement="top"></span></a></h4>
                            </div>
                            <div id="ac1-3" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="form-group {{ $errors->has('excerpt') ? ' has-error' : '' }}">
                                        <textarea name="excerpt" parsley-trigger="change" autocomplete="off" rows="5" class="form-control" data-parsley-id="2">{{ old('excerpt', $project->excerpt) }}</textarea>

                                        @if ($errors->has('excerpt'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('excerpt') }}</strong>
                                            </span>
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
                                <h4 class="panel-title"><a data-toggle="collapse" href="#ac4-1" aria-expanded="true" data-parent="#right-accordian">Details <i class="icon s7-angle-down pull-right" tabindex="-1"></i><span class="icon s7-help1 pull-right" data-toggle="popover" data-trigger="hover" title="" data-content="Extra details about the project" data-placement="top"></span></a></h4>
                            </div>
                            <div id="ac4-1" class="panel-collapse in" aria-expanded="true">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group {{ $errors->has('slug') ? ' has-error' : '' }}">
                                                <label for="slug">URL slug</label>
                                                <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $project->slug) }}" parsley-trigger="change" autocomplete="off" data-parsley-id="3">

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
                                                    <div class="switch-button switch-button-yesno">
                                                        <input type="checkbox" name="published" id="published" {{ (! empty(old('published', $project->published_at)) ? 'checked' : '') }} onchange="if(this.checked){document.getElementById('pub_date').style.display='block'}else{document.getElementById('pub_date').style.display='none'}">
                                                        <span><label for="published"></label></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group {{ $errors->has('published_at') ? ' has-error' : '' }}" id="pub_date" style="{{ !! empty(old('published_at', $project->published_at)) ? 'display: none' : '' }}">
                                        <label for="published_at">Published date</label>
                                        <div class="input-group datetimepicker">
                                            <input name="published_at" id="published_at" size="16" type="text" value="{{ old('published_at', $project->published_at) }}" class="form-control"><span class="input-group-addon btn btn-primary"><i class="icon-th s7-date"></i></span>
                                        </div>

                                        @if ($errors->has('published_at'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('published_at') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group {{ $errors->has('categories') ? ' has-error' : '' }}">
                                        <label for="category">Category</label>
                                        <select name="categories[]" id="category" multiple="" class="tags">
                                            <option value="" disabled>Select categories...</option>
                                            @foreach($categories as $key => $category)
                                            <option value="{{ $category->id }}" {{ ($project->hasAnyCategories($category->id) || in_array($category->id, old('categories', []))) ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>

                                        @if ($errors->has('categories'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('categories') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group {{ $errors->has('user_id') ? ' has-error' : '' }}">
                                        <label for="user-id">Created by</label>
                                        <select name="user_id" id="user-id" class="tags">
                                            <option value="">Select a user...</option>
                                            @foreach($users as $key => $user)
                                            <option value="{{ $user->id }}" {{ $user->id === old('user_id', optional($project->user)->id) ? 'selected' : '' }}>{{ $user->name }}</option>
                                            @endforeach
                                        </select>

                                        @if ($errors->has('user_id'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('user_id') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group {{ $errors->has('sort_order') ? ' has-error' : '' }}">
                                                <label for="sort_order">Sort order</label>
                                                <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', $project->sort_order) }}" parsley-trigger="change" autocomplete="off" data-parsley-id="4">

                                                @if ($errors->has('sort_order'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('sort_order') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group {{ $errors->has('featured') ? ' has-error' : '' }} pull-right">
                                                <label for="featured">Featured</label>
                                                <div class="input-group" style="padding-top: .75em">
                                                    <div class="switch-button switch-button-yesno">
                                                        <input type="checkbox" name="featured" id="featured" {{ (! empty(old('featured', $project->featured)) ? 'checked' : '') }}>
                                                        <span><label for="featured"></label></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default" style="display: {{ (empty($project->slug)) ? 'none' : 'block' }}; position: relative">
                            <div class="panel-heading success">
                                <h4 class="panel-title"><a data-toggle="collapse" href="#ac4-2" class="collapsed" data-parent="#right-accordian">Featured image <i class="icon s7-angle-down pull-right" tabindex="-1"></i><span class="icon s7-help1 pull-right" data-toggle="popover" data-trigger="hover" title="" data-content="This is the image that is shown when you share a link, primarily on social media." data-placement="top"></span></a></h4>
                            </div>
                            <div id="ac4-2" class="panel-collapse collapse">
                                <div class="panel-body text-center">
                                    <div class="form-group">
                                        <asset-collection :cold-assets="{{ $project->getMedia('featured') }}" slug="{{ $project->slug }}" project-id="{{ $project->id }}" ref="featured" collection="featured"></asset-collection>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading success">
                                <h4 class="panel-title"><a data-toggle="collapse" href="#ac4-3" class="collapsed" data-parent="#right-accordian">Search engine optimisation <i class="icon s7-angle-down pull-right" tabindex="-1"></i><span class="icon s7-help1 pull-right" data-toggle="popover" data-trigger="hover" title="" data-content="These details help to show off the page to search engines." data-placement="top"></span></a></h4>
                            </div>
                            <div id="ac4-3" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="form-group {{ $errors->has('seo_title') ? ' has-error' : '' }}">
                                        <label for="seo_title">Search engine title</label>
                                        <input type="text" name="seo_title" id="seo_title" class="form-control" value="{{ old('seo_title', $project->seo_title) }}" parsley-trigger="change" autocomplete="off" data-parsley-id="7">

                                        @if ($errors->has('seo_title'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('seo_title') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group {{ $errors->has('meta_description') ? ' has-error' : '' }}">
                                        <label for="meta_description">Meta description</label>
                                        <textarea name="meta_description" id="meta_description" class="form-control" rows="3" parsley-trigger="change" autocomplete="off" data-parsley-id="8">{{ old('meta_description', $project->meta_description) }}</textarea>

                                        @if ($errors->has('meta_description'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('meta_description') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group {{ $errors->has('meta_keywords') ? ' has-error' : '' }}">
                                        <label for="meta_keywords">Meta keywords</label>
                                        <select name="meta_keywords[]" id="meta_keywords" multiple="" class="tags form-control">
                                            @foreach ($keywords as $keyword)
                                                <option value="{{ $keyword }}" {{ strpos(old('keyword', !empty($project->meta_keywords) ? $project->meta_keywords : ''), $keyword) !== false ? 'selected' : '' }}>{{ ucfirst($keyword) }}</option>
                                            @endforeach
                                        </select>

                                        @if ($errors->has('meta_keywords'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('meta_keywords') }}</strong>
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
        @csrf
    </form>
    <!-- <div id="form-bp1" role="dialog" class="modal modal-colored-header" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-full-width">
            <div class="modal-content" style="position: relative">
                <div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close md-close"><i class="icon s7-close"></i></button>
                    <h3 class="modal-title">Media library</h3>
                </div>
                <div class="modal-body form" style="height: calc(100vh - 200px);position: relative;overflow: scroll;">
                    <div class="dropzone-container" style="display: none;">
                        <form id="dropzone" action="/assets" method="POST" class="dropzone" style="position: absolute; top: 0; left: 0; bottom: 0; right: 0; z-index: 5; background-color: #fff; margin: .5em; display: flex; flex-direction: column; justify-content: space-around;">
                            @csrf
                            <div class="dz-message">
                                <div class="icon"><span class="s7-cloud-upload"></span></div>
                                <h2>Drag and drop files here</h2><span class="note">(Maximum file size of 25MB allowed. Maximum of 20 uploads per drop.)</span>
                            </div>
                        </form>
                    </div>
                    <asset-manager ref="media" :selected-assets="{{ $project->media->toJson() }}"></asset-manager>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default md-close">Cancel</button>
                    <button type="button" data-dismiss="modal" class="btn btn-primary md-close" disabled="disabled">Select</button>
                </div>
            </div>
        </div>
    </div> -->
    <footer><span class="pull-right">Version {{ config('app.version') }}</span></footer>
</div>
@endsection

@section('body.bottom')
<script src="/lib/summernote/summernote.min.js" type="text/javascript"></script>
<script src="/lib/summernote/summernote-ext-amaretti.js" type="text/javascript"></script>
<script src="/lib/moment.js/min/moment.min.js" type="text/javascript"></script>
<script src="/lib/datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script src="/lib/select2/js/select2.min.js" type="text/javascript"></script>
<script src="/lib/parsley/parsley.min.js" type="text/javascript"></script>
<script src="/lib/dropzone/dist/dropzone.js" type="text/javascript"></script>
<script>
    Dropzone.autoDiscover = false;

    var myDropzone = new Dropzone("#dropzone", {
        maxFilesize: 64, // MB
        parallelUploads: 20,
        uploadMultiple: true,
    });
    myDropzone.on("drop", function(event) {
        window.app.$refs.assets.loading = true;
    });
    myDropzone.on("complete", function(file) {
        this.removeAllFiles();
        //window.app.$refs.media.reloadFiles() // Junky, should refactor this
        window.app.$refs.assets.loading = false;
        window.app.$refs.assets.reloadFiles() // Junky, should refactor this
    });
</script>
<script>
    //Summernote
    $('#editor').summernote({
        onImageUpload: function(image) {
            uploadImage(image[0]);
        },
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol']],
            ['insert', ['link', 'picture', 'hr']],
            ['misc', ['fullscreen', 'codeview']]
        ]
    });

    function uploadImage(image) {
        console.log('uploading');
        var formData = new FormData();
        formData.append('file', image);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/assets',
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            type: "post",
            success: function(response) {
                console.log('success');
                console.log(response);
                var image = $('<img>').attr('src', response.url);
                $('#editor').summernote("insertNode", image[0]);
            },
            error: function(response) {
                console.log('fail');
                console.log(response);
            }
        });
    }

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

    var $document = $(document);

      var collection = $(),
          $editor = $('.dropzone-container'),
          $dropzone = $('.dropzone');

      // show dropzone on dragenter when dragging a object to document
      // -but only if the editor is visible, i.e. has a positive width and height
      $document
      .on('dragenter', function (e) {
        if (!collection.length) {
          $editor.addClass('dragover');
        }
        collection = collection.add(e.target);
      })
      .on('dragleave', function (e) {
        collection = collection.not(e.target);
        // if (!collection.length) {
        //   $editor.removeClass('dragover');
        // }
      })
      .on('drop', function (e) {
        e.preventDefault();
        collection = $();
        $editor.removeClass('dragover');
      })
      .on('dragover', function(e) {
          e.preventDefault();
      });

      // change dropzone's message on hover.
      $dropzone
      .on('dragenter', function () {
        $dropzone.addClass('hover');
      })
      .on('dragleave', function () {
        $dropzone.removeClass('hover');
      })
      .on('drop', function (event) {
          $editor.removeClass('dragover');
        // var dataTransfer = event.originalEvent.dataTransfer;
        // var layoutInfo = dom.makeLayoutInfo(event.currentTarget || event.target);

        // if (dataTransfer && dataTransfer.files && dataTransfer.files.length) {
          event.preventDefault();
        //   layoutInfo.editable().focus();
        //   handler.insertImages(layoutInfo, dataTransfer.files);
        // } else {
        //   var insertNodefunc = function () {
        //     layoutInfo.holder().summernote('insertNode', this);
        //   };

        //   for (var i = 0, len = dataTransfer.types.length; i < len; i++) {
        //     var type = dataTransfer.types[i];
        //     var content = dataTransfer.getData(type);

        //     if (type.toLowerCase().indexOf('text') > -1) {
        //       layoutInfo.holder().summernote('pasteHTML', content);
        //     } else {
        //       $(content).each(insertNodefunc);
        //     }
        //   }
        // }
      })
      .on('dragover', false); // prevent default dragover event
</script>
@endsection