const { mix } = require('laravel-mix');

mix.postCss('resources/css/app.css', 'public/css')

    .version()

    .options({
        processCssUrls: false,

        postCss: [require('postcss-easy-import')(), require('tailwindcss')('./tailwind.js')],
    });
