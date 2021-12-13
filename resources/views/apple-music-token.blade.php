<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <meta name="apple-music-developer-token" content="{{ config('services.apple-music.token') }}">
    <meta name="apple-music-app-name" content="My Cool Web App">
    <meta name="apple-music-app-build" content="1978.4.1">
    <script src="https://js-cdn.music.apple.com/musickit/v1/musickit.js"></script>
    <script>
        document.addEventListener('musickitloaded', function() {
            // MusicKit global is now defined
            MusicKit.configure({
                developerToken: '{{ config('services.apple-music.token') }}',
                app: {
                    name: 'Spatie Dashboard',
                    build: '1'
                }
            });

            let music = MusicKit.getInstance();

            music.authorize().then(musicUserToken => {
                document.getElementById('key').innerHTML = musicUserToken;
            });
        });
    </script>
</head>
<body>
    <div style="font-size: 30px; padding: 10px;">
        <div style="margin-bottom: 10px;">Your key is:</div>
        <code style="background: #eee; display: block; max-width: 100%; word-wrap: anywhere; padding: 10px;" id="key"></code>
    </div>
</body>
</html>
