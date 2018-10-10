<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <link href="{{ mix("css/app.css") }}" rel="stylesheet"/>
    <script src="{{ mix("js/app.js") }}" defer></script>
    <meta name="google" value="notranslate">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

    @yield('content')

    @if(usingNodeServer())
        <script src="{{ config('app.url') }}:6001/socket.io/socket.io.js"></script>
    @endif

</body>
</html>
