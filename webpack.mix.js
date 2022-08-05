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
   .sass('resources/assets/sass/app.scss', 'public/css');

mix.js('resources/assets/js/search.js', 'public/js');
mix.js('resources/assets/js/notification.js', 'public/js');

// Online Class Scripts
mix.js('resources/assets/js/online_class/newsfeed.js', 'public/js/onlineclass');
mix.js('resources/assets/js/online_class/quiz.js', 'public/js/onlineclass');
mix.js('resources/assets/js/online_class/assignment.js', 'public/js/onlineclass');
mix.js('resources/assets/js/online_class/searchClass.js', 'public/js/onlineclass');
mix.js('resources/assets/js/online_class/studentSearchClass.js', 'public/js/onlineclass');
