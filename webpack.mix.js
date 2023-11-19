let mix = require('laravel-mix');

mix.sass('resources/src/site.scss', 'public/src');
mix.browserSync('localhost:8000');