const { mix } = require('laravel-mix');

mix
    .js('resources/assets/js/app.js', 'public/js')
    .sass('resources/assets/css/app.css', 'public/css')

    .options({
        postCss: [
            require('postcss-apply')(),
        ],
        processCssUrls: false,
    })

	.version()

    .webpackConfig({
        module: {
            rules: [
                // With the `import-glob-loader` we can use globs in our import
                // statements in css.
                {
                    test: /\.css/,
                    loader: 'import-glob-loader',
                    enforce: 'pre',
                },
            ],
        },
    });
