const { mix } = require('laravel-mix');
const  webpack  = require('webpack');
const LiveReloadPlugin = require('webpack-livereload-plugin');

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

// see https://github.com/metafizzy/isotope/issues/979#issuecomment-215771272
mix.webpackConfig({
        resolve: {
            alias: {
                'masonry': 'masonry-layout',
                'isotope': 'isotope-layout'
            }
        },
        module : {
            loaders: [
                {
                    test: /isotope\-|fizzy\-ui\-utils|desandro\-|masonry|outlayer|get\-size|doc\-ready|eventie|eventemitter/,
                    loader: 'imports?define=>false&this=>window'
                },
                //bootstrap and bootbox need jQuery to be available, so make it available with the imports loader
                {
                    test: /bootstrap.+\.(jsx|js)$/,
                    loader: 'imports-loader?jQuery=jquery,$=jquery,this=>window'
                },
                {
                    test: /bootbox.+\.(jsx|js)$/,
                    loader: 'imports-loader?jQuery=jquery,$=jquery,this=>window'
                }
            ]
        },
        plugins: [
            new webpack.ProvidePlugin({
                'jQuery': 'jquery',
                'window.jQuery': 'jquery',
                'jquery': 'jquery',
                'window.jquery': 'jquery',
                '$'     : 'jquery',
                'window.$'     : 'jquery'
            }),
            new LiveReloadPlugin()
        ]
    })
    .ts('resources/assets/ts/lara.ts', 'public/')
    .extract(['jquery', 'bootstrap', 'bootbox'])
    .sourceMaps()
    .version();
