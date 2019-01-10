<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <script src="//d2wy8f7a9ursnm.cloudfront.net/v5.0/bugsnag.min.js"></script>
    <script>window.bugsnagClient = bugsnag('cb74377a340ff0a2f3a5ab504521f95f')</script>
    <link href="{{ mix("css/app.css") }}" rel="stylesheet"/>
    <script src="{{ mix("js/app.js") }}" defer></script>
    <meta name="google" value="notranslate">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    @yield('content')
</body>
</html>
