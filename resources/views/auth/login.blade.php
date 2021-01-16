@extends('layouts.app-login')

@section('content')
<div class="wrapper">
    <div class="inner">
        <div class="image-holder">
            <img src="{{ asset('s-login/images/dent-behel-1.png') }}" alt="">
            {{-- <img src="https://hdmall.id/system/image_attachments/images/000/024/960/medium/Kawat_Gigi_Metal__Cetak_Gigi_dan_Pembersihan_Karang_Gigi_1x_di_AM_Dental_Care_%281%29.jpg" alt=""> --}}
        </div>
        <form method="POST" action="{{ route('login') }}">
            <h3>Medina Dental</h3>
            <h4>Sign In</h4>
            @error('email')
            <div class="notif-false">
                <strong>{{ $message }}</strong>
            </div>
            @enderror

            @if (session('info'))
            <div class="notif-false">
                <strong>{{ session('info') }}</strong>
            </div>
            @endif

            <div class="form-holder">
                @csrf

                <input type="email" id="i-email" name="email" value="{{ old('email') }}"
                    placeholder="{{ __('E-Mail Address') }}" class="form-control" required autocomplete="email"
                    autofocus>
            </div>
            <div class="form-holder">
                <input type="password" id="i-pass" name="password" placeholder="{{ __('Password') }}"
                    class="form-control" style="font-size: 15px;" required autocomplete="current-password">
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    {{ __('Remember Me') }}
                    <span class="checkmark"></span>
                </label>
            </div>
            <div class="form-login">
                <button type="submit">{{ __('Sign In') }}</button>
                <p>@if (Route::has('password.request'))

                    <a role="button" class="txt1"
                        href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
                    @endif</p>
            </div>
        </form>
    </div>
</div>
@endsection
