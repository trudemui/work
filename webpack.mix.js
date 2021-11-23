const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');
require('laravel-mix-purgecss');
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
    .js("resources/js/router.js", "public/js")
    .sass('resources/sass/app.scss', 'public/css')
    .options({
        processCssUrls: false,
        postCss: [tailwindcss('./tailwind.config.js')],
        terser: {
            terserOptions: {
                compress: {
                    drop_console: true //本番環境(npm run prod)ではconsole.logを削除
                }
            }
        }
    }).purgeCss()
    .version()
    .browserSync({
        files: [
            "resources/**/*",
            "config/**/*",
            "routes/**/*",
            "app/**/*",
            "public/**/*"
        ],
        proxy: {
            target: "http://127.0.0.1:8000",
        },
        open: false, //起動時にブラウザを開かない
        reloadOnRestart: true
    });
   