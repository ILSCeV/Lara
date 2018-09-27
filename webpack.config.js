const path = require('path');
const webpack = require('webpack');
const glob = require("glob");
const LiveReloadPlugin = require('webpack-livereload-plugin');
let WebpackChunkHash = require('webpack-chunk-hash');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CssEntryPlugin = require("css-entry-webpack-plugin");
const ManifestPlugin = require('webpack-manifest-plugin');
const devMode = process.env.NODE_ENV !== 'production';

module.exports = {
  entry: {
    styles: glob.sync('./resources/assets/sass/lara.scss'),
    lara: './resources/assets/ts/lara.ts',
  },
  module: {
    rules: [
      {
        test: /isotope\-|fizzy\-ui\-utils|desandro\-|masonry|outlayer|get\-size|doc\-ready|eventie|eventemitter/,
        loader: 'imports-loader?define=>false&this=>window'
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
        test: /\.tsx?$/,
        use: 'ts-loader',
        exclude: /node_modules/
      },
      {
        test: /\.(sa|sc|c)ss$/,
        use: [
          {loader: devMode ? 'style-loader' : MiniCssExtractPlugin.loader,
            options: {
              sourceMap: true
            }
          },
          'css-loader',
          {loader:'postcss-loader',
            options: {
              plugins: function () { // post css plugins, can be exported to postcss.config.js
                return [
                  require('precss'),
                  require('autoprefixer')
                ];
              }
            }
          },
          'sass-loader',
        ],
      },
      {
        test: /\.(woff(2)?|ttf|eot|svg)(\?v=\d+\.\d+\.\d+)?$/,
        use: [{
          loader: 'file-loader',
          options: {
            name: '[name].[ext]',
            outputPath: '../fonts/'
          }
        }]
      },
    ]
  },
  resolve: {
    extensions: ['.tsx', '.ts', '.js']
  },
  output: {
    //filename: '[name].js',
    path: path.resolve(__dirname, 'public/'),
    publicPath: '/public/'
  },
  plugins: [
    new LiveReloadPlugin(),
    new WebpackChunkHash({algorithm: 'sha512'}), // 'md5' is default value,
    new MiniCssExtractPlugin({
      // Options similar to the same options in webpackOptions.output
      // both options are optional
      filename: devMode ? '[name].css' : '[name].[hash].css',
      chunkFilename: devMode ? '[id].css' : '[id].[hash].css',
    }),
    new ManifestPlugin()
  ]
};
