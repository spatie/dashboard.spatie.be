var elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');

elixir(mix => {
    mix.sass('app.scss')
       .webpack('app.js')
       .version(['js/app.js', 'css/app.css']);
});
