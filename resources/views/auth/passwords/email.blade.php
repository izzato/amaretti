@extends('layouts.auth')

@section('content')
<div class="justify-content-center">
    <div class="wrapper">
        <a class="brand" href="{{ url('/') }}">
            <img src="{{ asset('images/logo-full-retina.png') }}" alt="Logo">
        </a>
        <div class="card">
            <div class="card-body">
                <h4 class="mb-4">{{ __('Reset Password') }}</h4>
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="form-group">
                        <label for="email" class="font-weight-bold">
                            {{ __('Email address') }}
                        </label>
                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
                        @if ($errors->has('email'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group lh-2 clearfix">
                        <a href="{{ route('login') }}" class="text-muted">
                            {{ __('Back to login') }}
                        </a>
                        <button type="submit" class="btn btn-outline-dark float-right font-weight-bold">
                            {{ __('Send Password Reset Link') }}
                        </button>
                    </div>
                </form>
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
            </div>
        </div>
        <div class="mt-4 text-center">
            <a href="{{ url('/') }}" class="text-muted">&larr; Back to {{ config('app.name') }}</a>
        </div>
    </div>
</div>
@endsection
