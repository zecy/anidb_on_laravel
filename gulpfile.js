var elixir = require('laravel-elixir');

require('laravel-elixir-webpack-official');
require('laravel-elixir-vue');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.webpack('app.js');
});

Elixir.webpack.mergeConfig({
    externals: {
        "vue": "Vue",
        "vue-resource": "VueResource",
        "vue-router": "VueRouter"
    }
});
