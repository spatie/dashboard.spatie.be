var elixir = require('laravel-elixir');

require('laravel-elixir-vueify');

elixir(function(mix) {
    mix.browserify('app.js')
    mix.sass('app.scss');
    mix.version(['js/app.js', 'css/app.css']);
});
