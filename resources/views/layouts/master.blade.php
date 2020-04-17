<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <link href="{{ mix("css/app.css") }}" rel="stylesheet"/>
    <meta name="google" value="notranslate">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <livewire:styles>
</head>
<body>
    @yield('content')
    <livewire:scripts />
</body>
</html>
