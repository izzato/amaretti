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
                <form method="POST" action="{{ route('password.request') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="form-group">
                        <label for="email" class="font-weight-bold">
                            {{ __('Email address') }}
                        </label>
                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email or old('email') }}" required autofocus>
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
                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                        @if ($errors->has('password'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="password-confirm" class="font-weight-bold">
                            {{ __('Confirm password') }}
                        </label>
                        <input id="password-confirm" type="password" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" name="password_confirmation" required>
                        @if ($errors->has('password_confirmation'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group lh-2 clearfix">
                        <button type="submit" class="btn btn-outline-dark float-right font-weight-bold">
                            {{ __('Reset Password') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
