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
	'public/js/jquery-2.1.3.min.js',
	
	// Misc. js libraries
	'public/js/isotope.pkgd.min.js',
	'public/js/html5shiv.js',
	'public/js/es6-promise.min.js',

	// bootstrap and related libraries
	'public/js/bootstrap.min.js',
	'public/js/bootbox.min.js',
	'public/js/bootstrap-select.min.js',

	// languages
	'public/lang/*.js',

	// Lara scripts
	'public/js/vedst-scripts.js',

	// Compiled typescript
	'public/js/bin/bundle.js',
	], 'public/js/app.js')
.scripts([
	'public/js/surveyEdit-Create-scripts.js',
	'public/js/surveyView-scripts.js',
	], 'public/js/surveys.js')
	.version()
