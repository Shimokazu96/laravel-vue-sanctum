const mix = require("laravel-mix");
const path = require("path");
const tailwindcss = require('tailwindcss');

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
mix.webpackConfig({
    resolve: {
        alias: {
            "@": path.resolve("./resources/js"),
        },
    },
});

mix.js("resources/js/app.js", "public/js")
    .js("resources/js/admin.js", "public/js")
    .js("resources/js/router/index.js", "public/js/router")
    .js("resources/js/router/admin.js", "public/js/router")
    .vue().version()
    .sass('resources/sass/app.scss', 'public/css')
    .options({
        processCssUrls: false,
        postCss: [ tailwindcss('./tailwind.config.js') ],
    });
