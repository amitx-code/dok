<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Config;

/*############### ВНЕШНИЙ САЙТ ##################################################*/
Route::get('/', 'Controller@getIndexPage');

Route::get('partners', 'Controller@getIndexPartnersList');
Route::get('partners/{category_id}-{territory_id}', 'Controller@getIndexPartnersList');
Route::get('partners/{category_id}', 'Controller@getIndexPartnersList');
Route::get('partners/card/{id}-{territory_id}', 'Controller@getIndexPartnerCard');
Route::get('partners/card/{id}', 'Controller@getIndexPartnerCard');
Route::get('search', 'Controller@getIndexSearch'); // поиск старт

Route::get('categories', 'Controller@getIndexCategoriesList');

Route::get('about', 'Controller@getIndexAbout');
Route::get('docs', 'Controller@getIndexDocs');
Route::get('saving', 'Controller@getIndexSaving');
Route::get('earning', 'Controller@getIndexeEarning');
Route::get('cooperation', 'Controller@getIndexCooperation');
Route::get('faq', 'Controller@getIndexFaq');
Route::get('job', 'Controller@getIndexJob');
Route::get('contacts', 'Controller@getIndexContacts');
Route::get('oferta', 'Controller@getIndexOferta');
Route::get('usage', 'Controller@getIndexUsage');

Route::get('news', 'Controller@getNewsList');
Route::get('news/{y}/{m}/{d}/{file}', 'Controller@getNews');

/*############### ВНЕШНИЙ САЙТ ОПЛАТА ##################################################*/
Route::any('payment/result', 'BillingController@postRobokassaResult');

/*############### АДМИНКА ##################################################*/
Route::prefix('admin')->middleware('auth:web')->group(function () {

	/*### Прочие сервисы и возможности ###*/
	Route::get('/', 'AdminController@getIndex');
	Route::get('/test', 'AdminController@getTest');

	Route::get('support/service', 'AdminController@getSupportService');
	Route::get('support/faq', 'AdminController@getSupportFaq');
	Route::get('support/news', 'AdminController@getSupportNews');
	Route::get('support/contacts', 'AdminController@getSupportContacts');
	Route::get('support/learning', 'AdminController@getSupportLearning');
	Route::get('support/docs', 'AdminController@getSupportDocs');

	Route::get('news', 'AdminController@getNewsList');
	Route::get('news/{y}/{m}/{d}/{file}', 'AdminController@getNews');

	/*### Модуль афиллатов и партнёров ###*/
	Route::get('afillater/partnership/{level}/{tariff?}', 'AfillateController@getUsers')->name('afillate_users');
	Route::get('afillater/partnership', 'AdminController@getPartnership');

	/*### Личные данные ###*/
	Route::get('settings/help', 'AdminController@getHelp');
	Route::get('settings/personal', 'AdminController@getSettings');
	Route::get('settings/personal/{new?}', 'AdminController@getSettings');
	Route::post('settings/personal', 'AdminController@postSettings');
	Route::get('settings/tsp', 'AdminController@getTsp');
	Route::post('settings/tsp', 'AdminController@postTsp');
	Route::get('settings/tsp/download/{file}', 'AdminController@getTspFile');
	Route::post('settings/post-password', 'AdminController@postChangePassword');
	Route::post('settings/post-address', 'AdminController@postChangeAddress');
	Route::post('settings/post-avatar', 'AdminController@postChangeAvatar');
	Route::post('settings/post-afillate', 'AdminController@postChangeAfillate');

	/*### Шаблоны (сервис формирования шаблонов-документов) ###*/
	Route::get('templater/help', 'TemplaterController@getHelp');
	Route::get('templater/settings', 'TemplaterController@getSettings');
	Route::get('templater/add', 'TemplaterController@getAdd'); // показ всех шаблонов
	Route::get('templater/add/{category_id?}', 'TemplaterController@getAdd'); // переключение категорий
	Route::get('templater/add/{id}/start', 'TemplaterController@getAddStart'); // старт выбора шаблона
    Route::get('templater/add/{id}/startvis', 'TemplaterController@getAddStartvis'); // старт выбора шаблона
	Route::get('templater/add/{id}/example', 'TemplaterController@getAddExample'); // другой документ
	Route::get('templater/add/{id}/example/{category_id?}', 'TemplaterController@getAddExample'); // категории
	Route::post('templater/add/{id}/generate', 'TemplaterController@postAddGenerate'); // финальная генерация
	Route::get('templater/list', 'TemplaterController@getList'); // показ всех документов из архива
	Route::get('templater/list/{category_id?}', 'TemplaterController@getList'); // переключение категорий
	Route::get('templater/list/card/{id}', 'TemplaterController@getCard');

	/*### Документы (сервис документооборота) ###*/
	Route::get('documenter/help', 'DocumenterController@getHelp');
	Route::get('documenter/settings', 'DocumenterController@getSettings');
	Route::get('documenter/list', 'DocumenterController@getList'); // показ всех документов из архива
	Route::get('documenter/list/{category_id?}', 'DocumenterController@getList'); // переключение категорий
	Route::get('documenter/list/card/{id}', 'DocumenterController@getCard');
	Route::get('documenter/download/{id}/{action}', 'DocumenterController@getDownload');

	/*### Администрирование ###*/
	Route::get('configer/help', 'ConfigerController@getHelp');
	Route::get('configer/users', 'ConfigerController@getUsers');
	Route::get('configer/roles', 'ConfigerController@getRoles');
	Route::get('configer/token', 'ConfigerController@getToken');
	Route::post('configer/token', 'ConfigerController@postToken');
	Route::get('configer/convertapi', 'ConfigerController@getConvertapi');

	/*### Биллинг и оплата ###*/
	Route::get('billing/pay', 'BillingController@getPayment');
	Route::post('billing/pay/robokassa', 'BillingController@postPaymentRobokassa');
	Route::get('billing/order', 'BillingController@getOrder');
	Route::post('billing/order', 'BillingController@postOrder');
	Route::get('billing/history', 'BillingController@getFinancial');
	Route::get('billing/history/{pay_result}', 'BillingController@getFinancial');
	Route::get('billing/saving', 'BillingController@getSaving');
	Route::get('billing/earning', 'BillingController@getEarning');
	Route::get('billing/cards', 'BillingController@getCards');
	Route::post('billing/post-giftcard', 'BillingController@postGiftcards');

	/*### Приобретение услуг ###*/
	Route::get('services/list', 'ServicesController@getList');
	Route::get('services/{service_id}', 'ServicesController@getService');
	Route::post('services/order', 'ServicesController@postOrder');

	/*### Рейтинг и начисления ###*/
	Route::get('rating/history', 'RatingController@getHistory');

	Route::get('reports', 'AdminController@getReports');

    /**
     * ############### Партнеры (сервис партнеров) ###############
     */
	Route::get('partnerer/help', 'PartnererController@getHelp')->name('website');
	Route::get('partnerer/list', 'PartnererController@getList')->name('website'); // показ всех партнеров
	Route::get('partnerer/list/{category_id}-{territory_id}', 'PartnererController@getList')->name('website'); // переключение категорий
	Route::get('partnerer/list/{category_id}', 'PartnererController@getList')->name('website'); // переключение категорий
	Route::get('partnerer/list/card/{id}-{territory_id}', 'PartnererController@getCard')->name('website');
	Route::get('partnerer/list/card/{id}', 'PartnererController@getCard')->name('website');
	Route::get('partnerer/search', 'PartnererController@getSearch')->name('website'); // поиск старт

    /**
     * ############### purchaser - сервис загруженных вручную документов, чеков или квитанций ###############
     */
    Route::get('purchaser/help', 'PurchaserController@getHelp')->name('website');
	Route::get('purchaser/list', 'PurchaserController@getList')->name('website'); // показ всех загруженных документов
	Route::get('purchaser/list/{category_id?}', 'PurchaserController@getList')->name('website'); // переключение категорий
    Route::post('purchaser/list', 'PurchaserController@postSearch')->name('website'); // поиск результаты
	Route::get('purchaser/list/card/{id}', 'PurchaserController@getCard')->name('website');
	Route::get('purchaser/download/{id}/{action}', 'PurchaserController@getDownload')->name('website');
	Route::get('purchaser/search', 'PurchaserController@getSearch')->name('website'); // поиск старт
	Route::get('purchaser/add', 'PurchaserController@getAdd')->name('website'); // добавление и загрузка нового документа
	Route::post('purchaser/add', 'PurchaserController@getAdd')->name('website'); // TODO: ДОДЕЛАТЬ

});





/**
 * ############### АВТОРИЗАЦИЯ-РЕГИСТРАЦИЯ ###############
 */
Route::redirect('admin/login', '/login');
Route::get('login', 'Auth\LoginController@getLogin');
Route::post('auth/login', 'Auth\LoginController@login')->name('website');

Route::redirect('admin/logout', '/logout');
Route::redirect('auth/logout', '/logout');
Route::get('logout', 'Auth\LoginController@logout')->name('website');

Route::redirect('admin/reg', '/reg');
Route::get('reg', 'Auth\RegisterController@getRegistration');
Route::post('auth/register', 'Auth\RegisterController@register')->name('website');

Route::redirect('admin/reminder', '/reminder');
Route::get('reminder', 'Auth\ReminderController@getReminder');
Route::post('auth/reminder', 'Auth\ReminderController@register')->name('website');

Route::get('confirm/{confirmToken}', 'Auth\RegisterController@verification');
Route::get('confirm', 'Auth\RegisterController@verification');

Route::post('ulogin', 'Auth\UloginController@login')->name('website');





/**
 * ############### подключение композеров ###############
 */
view()->composer([
	'admin.menu', 
	'admin.template',
], '\App\Http\Composers\CommonComposer');

view()->composer([
	'admin.afillater.afilliate_matrix',
], \App\Http\Composers\AfillaterComposer::class);





/**
 * ############### добавление важны постоянных переменных во все шаблоны ###############
 */
view()->composer('admin.*', function($view) {
    $view->with('user', Auth::user());
    $view->with('request', Request());
    $view->with('currency', config('currency'));
});