let mix = require('laravel-mix')

mix.setPublicPath('dist')
   .js('resources/js/field.js', 'dist/js')
   .sass('resources/sass/field.scss', 'dist/css');
