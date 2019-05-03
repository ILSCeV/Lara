let mix = require('laravel-mix');
let webpack = require('webpack');
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
    module: {
      rules: [
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
        },
        {
          test: /\.(scss)$/,
          use: [{
            loader: 'style-loader', // inject CSS to page
          }, {
            loader: 'css-loader', // translates CSS into CommonJS modules
          }, {
            loader: 'postcss-loader', // Run post css actions
            options: {
              plugins: function () { // post css plugins, can be exported to postcss.config.js
                return [
                  require('precss'),
                  require('autoprefixer')
                ];
              }
            }
          }, {
            loader: 'sass-loader' // compiles Sass to CSS
          }]
        }
      ]
    },
    plugins: [
      new webpack.ProvidePlugin({
        'jQuery': 'jquery',
        'window.jQuery': 'jquery',
        'jquery': 'jquery',
        'window.jquery': 'jquery',
        '$': 'jquery',
        'window.$': 'jquery',
        'Popper': 'popper.js',
        'popper': 'popper.js',
        'popper.js': 'popper.js'
      }),
      new LiveReloadPlugin()
    ]
  }
)
  .sass('resources/assets/sass/lara.scss', 'public/')
  .sass('resources/assets/sass/surveys.scss', 'public/')
  .autoload({
    "jquery": ['$', 'window.jQuery', "jQuery", "window.$", "jquery", "window.jquery"],
    'node_modules/popper.js/dist/umd/popper.js': ['Popper']
  })
  .scripts([
    'node_modules/cookieconsent/build/cookieconsent.min.js'
  ], 'public/static.js')
  .ts('resources/assets/ts/lara.ts', 'public/')
  .extract(['jquery', 'bootstrap', 'bootbox', 'bootstrap-select', 'popper.js'])
  .styles(['node_modules/cookieconsent/build/cookieconsent.min.css'], 'public/static.css')
  .sourceMaps()
  .version();
