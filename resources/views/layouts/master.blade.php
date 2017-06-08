<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,900'
          rel='stylesheet'
          type='text/css'>
    <link href="{{ mix("css/app.css") }}" rel="stylesheet"/>
    <meta name="google" value="notranslate">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

    @yield('content')

    @if(usingNodeServer())
        <script src="{{ config('app.url') }}:6001/socket.io/socket.io.js"></script>
    @endif
    <script src="{{ mix("js/app.js") }}"></script>

</body>
</html>
