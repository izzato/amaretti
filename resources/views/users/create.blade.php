@extends('layouts.dashboard')

@section('head.top')
<link rel="stylesheet" type="text/css" href="/lib/summernote/summernote.css">
<link rel="stylesheet" type="text/css" href="/lib/datetimepicker/css/bootstrap-datetimepicker.min.css">
<link rel="stylesheet" type="text/css" href="/lib/select2/css/select2.min.css">
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
</style>
@endsection

@section('content')
<div class="am-content">
    @if(!empty($user->id))
    <form id="reset-{{ $user->id }}" action="{{ route('password.email') }}" method="POST" style="display: none;">@csrf<input type="hidden" name="email" value="{{ $user->email }}"></form>
    @endif
    <form method="POST" action="{{ empty($user->id) ? route('users.store') : route('users.update', $user) }}" autocomplete="off" enctype="multipart/form-data">
        @if(!empty($user->id))
        {{ method_field('put') }}
        @endif
        <div class="page-head">
            <h2 style="display: inline-block">{{ empty($user->id) ? 'Add user' : 'Edit user' }}</h2>

            <div class="pull-right" style="padding: 1.5em 0 0 0">
                @unless(empty($user->id))
                <div class="btn-group btn-space">
                    <button type="button" data-toggle="dropdown" class="btn btn-default dropdown-toggle" aria-expanded="false" style="display: inline-block !important">Options <span class="caret"></span></button>
                    <ul role="menu" class="dropdown-menu dropdown-menu-right">
                        <li><a href="#" onclick="event.preventDefault();if(confirm('Are you sure you want to send this user a password reset email?')){document.getElementById('reset-{{ $user->id }}').submit();}">Send password reset email</a></li>
                        {{--<li><a href="#">Log out everywhere else</a></li>
                        <li class="divider"></li>
                        <li><a href="#" class="text-danger">Remove this user</a></li>--}}
                    </ul>
                </div>
                @endunless
                <div class="btn-group btn-space" style="display: inline-block !important">
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </div>

            <ol class="breadcrumb">
                <li><a href="{{ route('home') }}">Dashboard</a></li>
                <li><a href="{{ route('users.list') }}">Users</a></li>
                <li class="active">{{ empty($user->id) ? 'Add' : 'Edit' }}</li>
            </ol>
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
                <div class="col-md-8">
                    <div id="left-accordian" class="panel-group accordion accordion-semi">
                        <div class="panel panel-default">
                            <div class="panel-heading default">
                                <h4 class="panel-title"><a data-toggle="collapse" href="#ac1-1" aria-expanded="true">User information <i class="icon s7-angle-down pull-right"></i><span class="icon s7-help1 pull-right" data-toggle="popover" data-trigger="hover" title="" data-content="This is the information that can be edited for a user" data-placement="top"></span></a></h4>
                            </div>
                            <div id="ac1-1" class="panel-collapse in" aria-expanded="true">
                                <div class="panel-body">
                                    <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                                        <label for="name">Name</label>
                                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" parsley-trigger="change" autocomplete="off" data-parsley-id="3">

                                        @if ($errors->has('name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" parsley-trigger="change" autocomplete="off" data-parsley-id="4" required>

                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                        <label for="password">Password</label>
                                        <input type="password" name="password" id="password" class="form-control" value="" parsley-trigger="change" autocomplete="off" data-parsley-id="4">

                                        @if ($errors->has('password'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                        <label for="password-confirm">Confirm password</label>
                                        <input type="password" name="password_confirmation" id="password-confirm" class="form-control" value="" parsley-trigger="change" autocomplete="off" data-parsley-id="4">

                                        @if ($errors->has('password_confirmation'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div id="right-accordian" class="panel-group accordion accordion-semi">
                        <div class="panel panel-default">
                            <div class="panel-heading success">
                                <h4 class="panel-title"><a data-toggle="collapse" href="#ac4-1" aria-expanded="true" data-parent="#right-accordian">Details <i class="icon s7-angle-down pull-right"></i><span class="icon s7-help1 pull-right" data-toggle="popover" data-trigger="hover" title="" data-content="Extra details about the user" data-placement="top"></span></a></h4>
                            </div>
                            <div id="ac4-1" class="panel-collapse in" aria-expanded="true">
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label for="profile_image">Profile image</label>
                                        <img src="{{ $user->gravitar(150) }}" class="img-responsive" style="margin: 1em 0">

                                        <p>You can change this profile image by creating an account with matching email on <a href="https://en.gravatar.com/" target="_blank">Gravatar</a>.</p>
                                    </div>
                                    <div class="form-group {{ $errors->has('role') ? ' has-error' : '' }}">
                                        <label for="role">Role</label>
                                        <select name="role" id="role" class="select-dropdown" {{ auth()->user()->hasPermissionTo('assign roles') ? '' : 'disabled' }}>
                                            <option value="">Select a role...</option>
                                            @foreach($roles as $key => $role)
                                            <option value="{{ $role->id }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                                            @endforeach
                                        </select>

                                        @if ($errors->has('role'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('role') }}</strong>
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
<script>
    $(".select-dropdown").select2({width: '100%', minimumResultsForSearch: Infinity});

    $('form').parsley();

    $('.am-content > form').submit(function(){
        window.onbeforeunload = null;
    });

    $(document).ready(function() {
        $(document).on('change keypress', 'input, textarea, .note-editable', function() {
            window.onbeforeunload = function() {
                return "You have unsaved changes, are you sure you want to leave?";
            }
        });
    });
</script>
@endsection