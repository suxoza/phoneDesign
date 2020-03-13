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
const tailwindcss = require('tailwindcss')
const purgecss = require('@fullhuman/postcss-purgecss')({

  content: [
    './resources/**/*.blade.php',
  ],
  defaultExtractor: content => content.match(/[\w-/:]*[\w-/:]/g) || []
})

mix.js('resources/js/app.js', 'public/js')
   .postCss('resources/css/style.css', 'public/css', [
		require('tailwindcss'),
	    require('autoprefixer'),
	    ...process.env.NODE_ENV === 'production'
	      ? [purgecss]
	      : []
   ])


mix.browserSync('phonedesign.loc')