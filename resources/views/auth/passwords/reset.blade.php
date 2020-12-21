@extends('layouts.app-login')

@section('content')
<div class="wrapper">
    <div class="inner">
        <div class="image-holder">
            <img src="{{ asset('s-login/images/left-pict.png') }}" alt="">
        </div>
        <form method="POST" action="{{ route('password.update') }}">
            <h3>Medina Dental</h3>
            @error('email')
            <div class="notif-false">
                <strong>{{ $message }}</strong>
            </div>
            @enderror
            @error('password')
            <div class="notif-false">
                <strong>{{ $message }}</strong>
            </div>
            @enderror
            @if (session('status'))
            <div class="notif-false">
                {{ session('status') }}
            </div>
            @endif
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="form-holder">
                <input type="text" name="email" value="{{ $email ?? old('email') }}" placeholder="{{ __('E-Mail Address') }}"
                    class="form-control" required autocomplete="email" autofocus>

            </div>
            <div class="form-holder">
                <input type="password" name="password" placeholder="{{ __('Password') }}" class="form-control"
                    style="font-size: 15px;" required autocomplete="current-password">
            </div>

            <div class="form-holder">
                <input type="password" name="password_confirmation" placeholder="{{ __('Confirm Password') }}" class="form-control"
                    style="font-size: 15px;" required autocomplete="current-password">
            </div>

            <div class="form-login">
                <button type="submit">{{ __('Reset Password') }}</button>

            </div>
        </form>
    </div>
</div>
@endsection
