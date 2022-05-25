@extends('layouts.auth')

@section('content')
<div style="position: absolute; right: 0">
    <a href="{{ url('/login') }}" style="margin-right: 40px; color: white;">Admin login</a>
</div>
<div class="justify-content-center">
    <div class="wrapper">
        <a class="brand" href="{{ url('/') }}">
            <img src="{{ asset('images/logo-full-retina.png') }}" alt="Amaretti logo">
        </a>
        <div class="card" id="notify-form" style="display: {{ session('status') ? 'none' : 'block' }};">
            <div class="card-body">
                <form action="#" method="POST" name="mc-embedded-subscribe-form" target="_blank" novalidate>
                    <label for="email" class="font-weight-bold">
                        Notify me when this site launches
                    </label>
                    <div class="form-group" style="display: flex; margin-top:5px">
                        <input id="email" type="email" class="form-control" name="EMAIL" required autofocus placeholder="email address" style="flex-grow: 3; margin-right: 5px">
                        <button type="submit" class="btn btn-outline-dark">Notify</button>
                    </div>
                    {{-- real people should not fill this in and expect good things - do not remove this or risk form bot signups --}}
                    <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_5baf51bb21416980f392964db_30ae541f9f" tabindex="-1" value=""></div>
                </form>
            </div>
        </div>
        <div class="card password" style="display: {{ session('status') ? 'block' : 'none' }};" id="password-form">
            <div class="card-body">
                <form method="POST" action="/">
                    @csrf

                    <label for="password" class="font-weight-bold">
                        {{ __('Password') }}
                    </label>
                    <div class="form-group" style="display: flex; margin-top:5px">
                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required style="flex-grow: 3; margin-right: 5px">
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
        @if (!session('status'))
            <div class="mt-4 text-center">
                <a href="#" class="text-muted" onclick="event.preventDefault();document.querySelector('#password-form').style.display = 'block';document.querySelector('#notify-form').style.display = 'none'">Have a password?</a>
            </div>
        @endif
    </div>
</div>
@endsection
