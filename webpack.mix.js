const { mix } = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css')

    .version()

    .options({
        processCssUrls: false,

        postCss: [require('postcss-easy-import')(), require('tailwindcss')('./tailwind.js')],
    });
