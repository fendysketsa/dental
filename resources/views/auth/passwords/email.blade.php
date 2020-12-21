@extends('layouts.app-login')

@section('content')
<div class="wrapper">
    <div class="inner">
        <div class="image-holder">
            <img src="{{ asset('s-login/images/left-pict.png') }}" alt="">
        </div>
        <form method="POST" action="{{ route('password.email') }}">
            <h3>Medina Dental</h3>
            <h4>Reset Your Password</h4>
            @error('email')
            <div class="notif-false">
                <strong>{{ $message }}</strong>
            </div>
            @enderror

            @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
            @endif

            <div class="form-holder">
                @csrf

                <input type="text" name="email" value="{{ old('email') }}" placeholder="{{ __('E-Mail Address') }}"
                    class="form-control" required autocomplete="email" autofocus>
            </div>
            <div class="form-login center-pos">
                <button class="form-login-button" type="submit">{{ __('Send Link') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
