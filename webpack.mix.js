const { mix } = require('laravel-mix');
const { NormalModuleReplacementPlugin } = require('webpack');

mix
    .js('resources/assets/js/app.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css')

    .version()

    .options({
        processCssUrls: false,
    });
