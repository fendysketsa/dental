@extends('layouts.app-login')

@section('content')
<div class="wrapper">
    <div class="inner">
        <div class="image-holder">
            <img src="{{ asset('s-login/images/left-pict.png') }}" alt="">
        </div>
        <form method="POST" class="more-top" action="{{ route('register') }}">
            <h3>Gula Salon</h3>
            <h4>Sign Up</h4>
            @error('name')
            <div class="notif-false">
                <strong>{{ $message }}</strong>
            </div>
            @enderror
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

            <div class="form-holder">
                @csrf

                <input type="text" name="name" value="{{ old('name') }}" placeholder="{{ __('Name') }}"
                    class="form-control" required autocomplete="name" autofocus>
            </div>
            <div class="form-holder">
                <input type="email" name="email" value="{{ old('email') }}" placeholder="{{ __('E-Mail Address') }}"
                    class="form-control" required autocomplete="email" autofocus>
            </div>
            <div class="form-holder">
                <input type="password" name="password" placeholder="{{ __('Password') }}" class="form-control"
                    style="font-size: 15px;" required autocomplete="new-password">
            </div>
            <div class="form-holder">
                <input type="password" name="password_confirmation" placeholder="{{ __('Confirm Password') }}" class="form-control"
                    style="font-size: 15px;" required autocomplete="new-password">
            </div>
            <div class="form-login">
                <button type="submit">{{ __('Sign Up') }}</button>
                <p>

                    @if (Route::has('password.request'))

                    <a class="txt1" href="{{ route('login') }}">{{ __('Have An Account?') }}</a>

                    @endif

                </p>
            </div>
        </form>
    </div>
</div>

@endsection
