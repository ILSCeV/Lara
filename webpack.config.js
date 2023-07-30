const path = require("path");
const webpack = require("webpack");
const LiveReloadPlugin = require("webpack-livereload-plugin");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const { CleanWebpackPlugin } = require("clean-webpack-plugin");
const WebpackAssetsManifest = require("webpack-assets-manifest");
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");
const TerserPlugin = require("terser-webpack-plugin");
const devMode = process.env.NODE_ENV !== "production";
//const devMode = false;

module.exports = {
  entry: {
    survey: "./resources/assets/ts/surveys/surveyEntry.ts",
    lara: "./resources/assets/ts/lara.ts",
    darkmode: "./resources/assets/ts/DarkMode.ts",
    legacy: "./resources/assets/ts/legacy/ieDependencys.ts",
    monthview: "./resources/assets/ts/monthview/monthview.ts",
    autocomplete: "./resources/assets/ts/shifts/autocomplete.ts",
    statistics: "./resources/assets/ts/statistics/StatisticsView.ts",
    templates: "./resources/assets/ts/template/templateEntry.ts",
  },
  module: {
    rules: [
      {
        test: /\.tsx?$/,
        use: [
          {
            loader: "ts-loader",
            options: {
              transpileOnly: true,
              experimentalWatchApi: true,
            },
          },
        ],
        exclude: "/node_modules/",
      },
      {
        test: /\.(sa|sc|c)ss$/,
        use: [
          {
            loader: MiniCssExtractPlugin.loader,
          },

          { loader: "css-loader" },
          {
            loader: "postcss-loader",
            options: {
              postcssOptions: {
                plugins: function () {
                  // post css plugins, can be exported to postcss.config.js
                  return [require("precss"), require("autoprefixer")];
                },
              },
              sourceMap: devMode,
            },
          },
          {
            loader: "sass-loader",
            options: {
              sourceMap: devMode,
              sassOptions: {
                outputStyle: "expanded",
              },
            },
          },
        ],
      },
      {
        test: /\.(woff(2)?|ttf|eot|svg)(\?v=\d+\.\d+\.\d+)?$/,
        type: "asset/resource",
        generator: {
          filename: 'fonts/[hash][ext][query]'

        }
      },
    ],
  },
  resolve: {
    extensions: [".tsx", ".ts", ".js", ".css", ".scss"],
  },
  output: {
    filename: devMode ? "[name].js" : "[name].[fullhash].js",
    path: path.resolve(__dirname, "public/"),
  },
  devtool: devMode ? "eval-source-map" : false,
  plugins: [
    new CleanWebpackPlugin({
      cleanStaleWebpackAssets: false,
      cleanOnceBeforeBuildPatterns: [
        "**/*.js",
        "**/*.css",
        "**/*LICENSE.txt",
        "**/assets-manifest.json",
        "**/fonts/*",
      ],
    }),
    new LiveReloadPlugin(),

    new MiniCssExtractPlugin({
      // Options similar to the same options in webpackOptions.output
      // both options are optional
      filename: devMode ? "[name].css" : "[name].[fullhash].css",
      chunkFilename: devMode ? "[id].css" : "[id].[fullhash].css",
    }),

    new webpack.ProvidePlugin({
      jQuery: "jquery",
      "window.jQuery": "jquery",
      jquery: "jquery",
      "window.jquery": "jquery",
      $: "jquery",
      "window.$": "jquery",
      moment: "moment",
    }),
    //new PhpManifestPlugin(),
    new WebpackAssetsManifest(),
    new webpack.SourceMapDevToolPlugin({}),
  ],
  optimization: {
    minimize: !devMode,
    minimizer: [
      new CssMinimizerPlugin({
        parallel: true,
      }),
      new TerserPlugin(),
    ],
  },
};
