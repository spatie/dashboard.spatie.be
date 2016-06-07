<!DOCTYPE html>
<html>
<head>
    <title>Laravel Dashboard</title>
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,900' rel='stylesheet' type='text/css'>
    <link href="{{ elixir("css/app.css") }}" rel="stylesheet"/>
    <script type="text/javascript">
        var _config = {
            pusher: {
                id: {{ env('PUSHER_APP_ID') }},
                key: '{{ env('PUSHER_KEY') }}',
                host: '{{ env('PUSHER_HOST') }}',
                port: {{ env('PUSHER_PORT') }},
            },
        };
    </script>
</head>
<body class="dashboard">

    @yield('content')

<script src="{{ elixir("js/app.js") }}"></script>
</body>
</html>
