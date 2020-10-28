<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                margin-top: 100px;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .subtitle {
                font-size: 60px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            .face-img {
                border: 1px #000 solid;
                margin: 10px;
                width: 64px;
            }

            .badge {
                padding: 10px;
                border-radius: 25%;
            }

            .online {
                background-color: darkgreen;
                color: white;
            }

            .offline {
                background-color: darkred;
                color: white;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md">
                    {{ $date }}<br/>{{ (int)optional($counter)->views }}
                    <small>views</small>
                </div>

                <div class="subtitle m-b-md">
                    Detections
                </div>

                <div class="links">
                    @foreach($faces as $face)
                        <img src="{{ $face->image }}"
                             title="{{ $face->created_at->timezone('MSK')->format('d.m.Y H:i') }} MSK"
                             class="face-img">
                    @endforeach
                </div>

                <div class="subtitle m-b-md">
                    Status
                </div>
                <div class="m-b-md">
                    {!! $camera->isOnline() ? '<span class="badge online">Online</span>' : '<span class="badge offline">Offline</span>' !!}
                    |
                    Frames: {{ (int)optional($counter)->frames }} |
                    Faces: {{ (int)optional($counter)->faces }}
                </div>
            </div>
        </div>
    </body>
</html>
