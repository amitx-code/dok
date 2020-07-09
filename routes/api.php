<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::middleware('auth:api')->group(function () {

	/*### сервис формирования шаблонов-документов ###*/
	Route::post('templater/add', 'TemplaterController@getAdd'); // показ всех шаблонов
	Route::post('templater/add/{id}/start', 'TemplaterController@getAddStart'); // старт выбора шаблона
	Route::post('templater/add/{id}/generate', 'TemplaterController@postAddGenerate'); // финальная генерация

});



