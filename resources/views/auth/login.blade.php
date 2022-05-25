@extends('layouts.auth')

@section('content')
<div class="justify-content-center">
    <div class="wrapper">
        <a class="brand" href="{{ url('/') }}">
            <img src="{{ asset('images/logo-full-retina.png') }}" alt="Logo">
        </a>
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('login') }}">
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

                    <div class="form-group">
                        <label for="password" class="font-weight-bold">
                            {{ __('Password') }}
                        </label>
                        <a href="{{ route('password.request') }}" class="float-right text-muted" tabindex="-1">
                            {{ __('Lost your password?') }}
                        </a>
                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                        @if ($errors->has('password'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group lh-2 clearfix">
                        <label>
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('Remember me') }}
                        </label>

                        <button type="submit" class="btn btn-outline-dark float-right font-weight-bold">
                            {{ __('Login') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="mt-4 text-center">
            <a href="{{ url('/') }}" class="text-muted">&larr; Back to {{ config('app.name') }}</a>
        </div>
    </div>
</div>
@endsection
