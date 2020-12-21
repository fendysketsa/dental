<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css"
        integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Averia+Sans+Libre" rel="stylesheet">
    <style type="text/css">
        body {
            font-family: 'Averia Sans Libre', cursive;
        }

        h1 {
            font-size: 2rem;
        }

        a,
        a:focus,
        a:hover {
            color: #fff;
        }

        .btn-secondary,
        .btn-secondary:hover,
        .btn-secondary:focus {
            color: #333;
            text-shadow: none;
            /* Prevent inheritance from `body` */
            background-color: #fff;
            border: .05rem solid #fff;
        }

        html,
        body {
            height: 100%;
            background-color: #fcd0cf;
        }

        body {
            color: #ff9a9c;
            text-align: center;
            text-shadow: 0 .05rem .1rem rgba(0, 0, 0, .5);
        }

        .site-wrapper {
            display: table;
            width: 100%;
            height: 100%;
            min-height: 100%;
            -webkit-box-shadow: inset 0 0 5rem rgba(0, 0, 0, .5);
            box-shadow: inset 0 0 5rem rgba(0, 0, 0, .5);
        }

        .site-wrapper-inner {
            display: table-cell;
            vertical-align: top;
        }

        .cover-container {
            margin-right: auto;
            margin-left: auto;
        }

        .inner {
            padding: 2rem;
        }

        .masthead {
            margin-bottom: 2rem;
        }

        .masthead-brand {
            margin-bottom: 0;
        }

        .nav-masthead .nav-link {
            padding: .25rem 0;
            font-weight: bold;
            color: rgba(255, 255, 255, .5);
            background-color: transparent;
            border-bottom: .25rem solid transparent;
        }

        .nav-masthead .nav-link:hover,
        .nav-masthead .nav-link:focus {
            border-bottom-color: rgba(255, 255, 255, .25);
        }

        .nav-masthead .nav-link+.nav-link {
            margin-left: 1rem;
        }

        .nav-masthead .active {
            color: #fff;
            border-bottom-color: #fff;
        }

        @media (min-width: 48em) {
            .masthead-brand {
                float: left;
            }

            .nav-masthead {
                float: right;
            }
        }

        .cover {
            padding: 0 1.5rem;
        }

        .cover .btn-lg {
            padding: .75rem 1.25rem;
            font-weight: bold;
        }

        .mastfoot {
            color: rgba(255, 255, 255, .5);
        }

        @media (max-width: 40em) {

            .cover-container {
                margin-right: auto;
                margin-left: auto;
                margin-top: 100px;
            }
        }

        @media (min-width: 40em) {
            h1 {
                font-size: 4rem;
            }

            .masthead {
                position: fixed;
                top: 0;
            }

            .mastfoot {
                position: fixed;
                bottom: 0;
            }

            .site-wrapper-inner {
                vertical-align: middle;
            }

            .masthead,
            .mastfoot,
            .cover-container {
                width: 100%;
            }
        }

        @media (min-width: 62em) {

            .masthead,
            .mastfoot,
            .cover-container {
                width: 42rem;
            }
        }

        .cover img {
            width: 200px;
            height: auto;
            margin: 0 auto 20px auto;
        }
    </style>
</head>

<body>
    <div class="site-wrapper">
        <div class="site-wrapper-inner">
            <div class="cover-container">
                <div class="masthead clearfix">
                </div>

                <div class="inner cover">
                    <img src="{{ $images }}">
                    <h1 class="cover-heading">{{ $header }}</h1>
                    <p class="lead">{{ $message }}</p>
                    @if(!empty($messageFoot))
                    <p>{{ $messageFoot }}</p>
                    @endif
                </div>

                <div class="mastfoot">
                    <div class="inner">
                        <a target="_blank" href="https://www.gulawaxing.com">Medina Dental</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"
        integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"
        integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous">
    </script>

    @if(empty($messageFoot))
    <script>
        setTimeout(function() {
            window.close();
        }, 1000);
    </script>
    @endif
</body>

</html>
