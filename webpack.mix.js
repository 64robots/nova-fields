let mix = require('laravel-mix')
const path = require('path')
require('./nova.mix')

mix
    .setPublicPath('dist')
    .js('resources/js/field.js', 'js')
    .vue({ version: 3 })
    .sass('resources/sass/field.scss', 'css')
    .nova('64robots/nova-fields')
    .alias({
        '@': 'vendor/laravel/nova/resources/js/',
    })
    .webpackConfig({
        resolve: {
            alias: {
                // '@nova': path.resolve(__dirname, '../../vendor/laravel/nova/resources/js/'),
                // '@ui': path.resolve(__dirname, '../nova-compact-ui/resources/js/'),
                'laravel-nova': path.resolve(__dirname, './node_modules/laravel-nova/dist/index.js'),
                // '@': path.resolve(__dirname, '../../vendor/laravel/nova/resources/js/')
            },
        },
    });
