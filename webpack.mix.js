const { mix } = require('laravel-mix');

mix
    .js('resources/assets/js/app.js', 'public/js')
    .postCss('resources/assets/css/app.css', 'public/css')

    .version()

    .options({
        processCssUrls: false,

        postCss: [require('postcss-easy-import')],
    });
