let mix = require('laravel-mix')

mix.js('resources/js/field.js', 'dist/js')
   .sass('resources/sass/field.scss', 'dist/css')
    .webpackConfig(
        {
            resolve: {
                symlinks: false
            }
        }
    )


// Voyager
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

mix.options({ processCssUrls: false }).sass('resources/assets/sass/app.scss', 'publishes/assets/css', { implementation: require('node-sass') })
.js('resources/assets/js/app.js', 'publishes/assets/js')
.copy('node_modules/tinymce/skins', 'publishes/assets/js/skins')
.copy('resources/assets/js/skins', 'publishes/assets/js/skins')
.copy('node_modules/tinymce/themes/modern', 'publishes/assets/js/themes/modern')
.copy('node_modules/ace-builds/src-noconflict', 'publishes/assets/js/ace/libs');