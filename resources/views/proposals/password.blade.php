@extends('layouts.auth')

@section('content')
<div style="position: absolute; right: 0">
    <a href="{{ url('/login') }}" style="margin-right: 40px; color: white;">Admin login</a>
</div>
<div class="justify-content-center">
    <div class="wrapper">
        <a class="brand" href="{{ url('/') }}">
            <img src="{{ asset('images/logo-full-retina.png') }}" alt="Logo">
        </a>
        <div class="card password" id="password-form">
            <div class="card-body">
                <form method="POST" action="{{ route('proposals.password', $proposal) }}">
                    @csrf

                    <label for="password" class="font-weight-bold">
                        {{ __('Password') }}
                    </label>
                    <div class="form-group" style="display: flex; margin-top:5px">
                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required style="flex-grow: 3; margin-right: 5px" autofocus>
                        <button type="submit" class="btn btn-outline-dark">Go</button>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-danger">
                            {{ session('status') }}
                        </div>
                    @endif
                </form>
            </div>
        </div>
        <div class="mt-4 text-center">
            <a href="{{ url('/') }}" class="text-muted">&larr; Back to {{ config('app.name') }}</a>
        </div>
    </div>
</div>
@endsection
