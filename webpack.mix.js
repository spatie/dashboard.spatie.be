const { mix } = require('laravel-mix');
const { NormalModuleReplacementPlugin } = require('webpack');

mix
    .js('resources/assets/js/app.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css')

    .version()

    .webpackConfig({
        plugins: [
            // For some reason, laravel-echo is trying to require jquery. To
            // ignore this requirement, we'll replace jquery with a noop
            // function.
            new NormalModuleReplacementPlugin(
                /jquery/,
                'node-noop'
            ),
        ],

        module: {
            rules: [
                {
                    test: /\.svg/,
                    loaders: [
                        {
                            loader: 'svg-url-loader',
                        },
                    ],
                },
            ],
        },

        stats: {
            // The "pretty" errors sometimes lack information. To display full
            // stack traces, run `DEBUG=1 yarn run dev`.
            errors: process.env.DEBUG,
        },
    });
