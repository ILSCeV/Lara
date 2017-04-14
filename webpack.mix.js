const { mix } = require('laravel-mix');

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

mix.sass('resources/assets/sass/app.scss', 'public/css')
	.scripts([
        // jQuery and Plugins
        'node_modules/jquery/dist/jquery.min.js',

        // Misc. js libraries
        'node_modules/isotope-layout/dist/isotope.pkgd.min.js',
        'node_modules/html5shiv/dist/html5shiv.min.js',
        'node_modules/es6-promise/dist/es6-promise.min.js',
        'node_modules/clipboard/dist/clipboard.min.js',

        // bootstrap and related libraries
        'node_modules/bootstrap/dist/js/bootstrap.min.js',
        'node_modules/bootbox/bootbox.min.js',
        'node_modules/bootstrap-select/dist/js/bootstrap-select.min.js',

        // languages
        'resources/assets/js/lang/*.js',

        // Lara scripts
        'resources/assets/js/vedst-scripts.js',

        // Compiled typescript
        'resources/assets/ts/bin/build.js',
        'resources/assets/js/surveyView-scripts.js'
	], 'public/js/app.js')
	.version()
    .sourceMaps()
