const mix = require('laravel-mix');

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

mix.js('resources/js/app.js', 'public/js')
  .sass('resources/sass/app.scss', 'public/css')
  .webpackConfig({
    module: {
      rules: [
        {
          enforce: "pre",
          test: /\.(js|vue)$/,
          exclude: /node_modules/,
          loader: "eslint-loader",
          options: {
            formatter: require('eslint-friendly-formatter')
          },
        }
      ]
    },

    resolve: {
      alias: {
        '@': path.resolve(
          __dirname,
          'resources/js'
        ),
        '@resources': path.resolve(
          __dirname,
          'resources'
        ),
        '@components': path.resolve(
          __dirname,
          'resources/js/components'
        ),
        '@modules': path.resolve(
          __dirname,
          'resources/js/store'
        ),
        '@pages': path.resolve(
          __dirname,
          'resources/js/pages'
        ),
        '@layout': path.resolve(
          __dirname,
          'resources/js/layout'
        ),

      }
    }
  });
