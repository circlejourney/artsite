let mix = require('laravel-mix');

mix.sass('resources/src/site.scss', 'public/src')
	.sass('resources/src/ace.scss', 'public/src')
	.sass('resources/src/bootstrap-fix.scss', 'public/src');
mix.browserSync('localhost:8000');