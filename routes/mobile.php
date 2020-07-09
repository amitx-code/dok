<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Config;

Route::prefix('admin')->middleware('auth:mobile')->group(function () {

	/**
     * ############### Партнеры (сервис партнеров) ###############
    */
    //Route::get('partnerer/help', 'PartnererController@getHelp')->name('mobile');
    Route::get('partnerer/list', 'PartnererController@getList')->name('mobile'); // показ всех партнеров
    //Route::get('partnerer/list/{category_id}-{territory_id}', 'PartnererController@getList')->name('mobile'); // переключение категорий
    //Route::get('partnerer/list/{category_id}', 'PartnererController@getList')->name('mobile'); // переключение категорий
    //Route::get('partnerer/list/card/{id}-{territory_id}', 'PartnererController@getCard')->name('mobile');
    //Route::get('partnerer/list/card/{id}', 'PartnererController@getCard')->name('mobile');
    //Route::get('partnerer/search', 'PartnererController@getSearch')->name('mobile'); // поиск старт

    /**
     * ############### purchaser - сервис загруженных вручную документов, чеков или квитанций ###############
     */
    //Route::get('purchaser/help', 'PurchaserController@getHelp')->name('mobile');
    Route::get('purchaser/list', 'PurchaserController@getList')->name('mobile'); // показ всех загруженных документов
    Route::get('purchaser/list/{category_id?}', 'PurchaserController@getList')->name('mobile'); // переключение категорий
    Route::post('purchaser/list', 'PurchaserController@postSearch')->name('mobile'); // поиск результаты
    Route::get('purchaser/list/card/{id}', 'PurchaserController@getCard')->name('mobile');
    //Route::get('purchaser/download/{id}/{action}', 'PurchaserController@getDownload')->name('mobile');
    Route::get('purchaser/search', 'PurchaserController@getSearch')->name('mobile'); // поиск старт
    Route::get('purchaser/add', 'PurchaserController@getAdd')->name('mobile'); // добавление и загрузка нового документа
    Route::post('purchaser/add', 'PurchaserController@getAdd')->name('mobile'); // TODO: ДОДЕЛАТЬ

});





/**
 * ############### АВТОРИЗАЦИЯ-РЕГИСТРАЦИЯ ###############
 */
Route::get('auth/login', 'Auth\LoginController@login')->name('mobile');





/**
 * ############### добавление важны постоянных переменных во все шаблоны ###############
 */
view()->composer('mobile.*', function($view) {
    $view->with('user', Auth::user());
    $view->with('request', Request());
    $view->with('currency', config('currency'));
});