let mix = require('laravel-mix')

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

mix.webpackConfig({
  module: {
    rules: [
      {
        test: /\.hbs$/,
        loader: "handlebars-template-loader"
      },
      {
        test: require.resolve('zepto'),
        use: 'imports-loader?this=>window'
      }
    ],
  },
});




// копирование базовых картинок и шрифтов
mix.copyDirectory('resources/assets/img', 'public/img');
mix.copyDirectory('resources/assets/fonts', 'public/fonts');



// финальная сборка-микс
mix





	//################## ФАЙЛЫ БУТСТРАП ##############################################
	.sass('resources/assets/sass/admin_bootstrap.scss', 		'public/css') 
	.combine([
		'resources/assets/js/bootstrap-3.3.7.min.js',
		'resources/assets/js/bootstrap-tooltip.js',
		'resources/assets/js/bootstrap-datetimepicker.min.js',
		'resources/assets/js/bootstrap-datetimepicker.ru.js',
	], 'public/js/admin_bootstrap.js')







	// админки сборный js и css 
	.js('resources/assets/js/admin_app.js', 'public/js')
	.sass('resources/assets/sass/admin_app.scss', 'public/css') 

// админки шрифты 
	.sass('resources/assets/sass/admin_fonts.scss', 'public/css') 






	//################## ФАЙЛЫ jquery ##############################################
	.sass('resources/assets/sass/admin_jquery.scss', 'public/css') 
	.combine([
		'resources/assets/js/jquery-3.3.1.min.js',
//		'resources/assets/js/jquery-migrate-1.4.1.js', // включать только для отладки
//		'resources/assets/js/jquery-migrate-3.0.0.js', // включать только для отладки
		'resources/assets/js/jquery-ui-1.12.1.min.js',
		'resources/assets/js/jquery-form-3.51.0.js',
	], 'public/js/admin_jquery.js')






	// админки css только для страницы логина
	.sass('resources/assets/sass/admin_login.scss', 'public/css') 

