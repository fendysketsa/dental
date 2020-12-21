@extends('layouts.app')

@section('content')
<div class="wrapper">
    <div class="inner">
        <div class="image-holder">
            <img src="{{ asset('s-login/images/left-pict.png') }}" alt="">
        </div>
            <h3>{{ __('Verify Your Email Address') }}</h3>
            @if (session('resent'))
            <div class="alert alert-success" role="alert">
                {{ __('A fresh verification link has been sent to your email address.') }}
            </div>
            @endif

            <div class="form-login">
                <p>
                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }}, <a
                        href="{{ route('verification.resend') }}">{{ __('click here to request another') }}</a>.
                </p>
            </div>
        </form>
    </div>
</div>
@endsection
