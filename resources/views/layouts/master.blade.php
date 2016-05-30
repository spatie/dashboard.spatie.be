<!DOCTYPE html>
<html>
<head>
    <title>Laravel Dashboard</title>
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,900' rel='stylesheet'
          type='text/css'>
    <link href="{{ elixir("css/app.css") }}" rel="stylesheet"/>
</head>
<body class="dashboard">

    @yield('content')

<script src="{{ elixir("js/app.js") }}"></script>
</body>
</html>