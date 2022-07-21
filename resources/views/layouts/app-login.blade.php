<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name_','C-MORE') }}</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('s-login/images/favicon.login.png') }}" />

    <link rel="stylesheet" href="{{ asset('s-login/css/style.css') }}">
</head>

<body>
    <div id="overlayer"></div>
    <span class="loader">
        <span class="loader-inner"></span>
    </span>
    <ul class="bubbles">
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
    </ul>
    @yield('content')
</body>

<script src="{{ asset('s-login/js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('s-login/js/main.js') }}"></script>
<script src="{{ asset('s-login/js/jquery.min.js') }}"></script>
<script src="{{ asset('s-home/dist/js/sweetalert.min.js') }}"></script>
@include('sweet::alert')
<script id="rendered-js">
    $(window).load(function () {
            $(".loader").delay(2000).fadeOut("slow");
            $("#overlayer").delay(2000).fadeOut("slow");
        });
        //# sourceURL=pen.js
</script>

</html>
