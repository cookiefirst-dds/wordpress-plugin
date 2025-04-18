const { mix } = require("laravel-mix");
const StyleLintPlugin = require("stylelint-webpack-plugin");

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

mix
  .js("assets/js/admin.js", "admin/js")
  .js("assets/js/public.js", "public/js")
  .sass("assets/sass/admin.scss", "admin/css")
  .sass("assets/sass/public.scss", "public/css")
  .sourceMaps()
  .options({
    processCssUrls: false
  })
  .webpackConfig({
    plugins: [
      new StyleLintPlugin({
        lintDirtyModulesOnly: true,
        failOnError: false,
        emitErrors: false,
        quiet: mix.config.inProduction
      })
    ],
    resolve: {
      alias: {
        jquery: "jquery/src/jquery"
      }
    }
  });

mix.browserSync({
  open: true,
  proxy: false,
  files: ["dist/css/*.css", "dist/js/*.js"],
  server: {
    baseDir: "dist"
  }
});
