const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.extend('i18n', new class {
        webpackRules() {
            return [
                {
                    resourceQuery: /blockType=i18n/,
                    type:          'javascript/auto',
                    loader:        '@kazupon/vue-i18n-loader',
                },
            ];
        }
    }(),
);

mix.js('resources/js/app.js', 'public/dist/js')
	.vue()
    .i18n('resources/lang')
    .sass('resources/scss/app.scss', 'public/dist/css');
