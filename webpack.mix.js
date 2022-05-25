let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/js/app.js', 'public/js')
    .copy('resources/assets/html/assets/js/main.js', 'public/js/dashboard.js')
    .copy('resources/assets/html/assets/img/', 'public/images')
    .copy('resources/assets/html/assets/lib/', 'public/lib')
    .less('resources/assets/less/style.less', 'public/css/dashboard.css');
