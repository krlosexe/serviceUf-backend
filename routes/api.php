<?php

use Illuminate\Http\Request;

use App\User;
use App\Clients;
use App\datosPersonaesModel;

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

Route::post('auth', 'Login@Auth');
Route::post('auth/recovery', 'Login@RecoveryAccount');
Route::post('change/password', 'Login@ChangePassword');
Route::post('GenerateCode', 'Login@GenerateCode');
Route::post('CreateCode', 'Login@CreateCode');
Route::post('VerifyCode', 'Login@VerifyCode');

Route::post('GenerateCodeAdviser', 'Login@GenerateCodeAdviser');
Route::post('VerifyCodeAdviser', 'Login@VerifyCodeAdviser');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('verify-token', 'Login@VerifyToken');

Route::resource('user', 'UsuariosController');


Route::post('status-user/{id}/{status}', 'UsuariosController@statusUser');
Route::get('get-asesoras', 'UsuariosController@GetAsesoras');
Route::get('get-asesoras-business-line/{id}', 'UsuariosController@GetAsesorasByBusinessLine');


Route::post('get-asesoras-business-line', 'UsuariosController@GetAsesorasByBusinessLineArray');




Route::resource('modulos', 'ModulosController');
Route::post('status-modulo/{id}/{status}', 'ModulosController@status');


Route::resource('funciones', 'FuncionesController');
Route::post('status-funciones/{id}/{status}', 'FuncionesController@status');


Route::get('list-funciones', 'FuncionesController@listFunciones');

Route::get('verify-permiso', 'Login@VerifyPermiso');

Route::resource('roles', 'RolesController');
Route::post('status-rol/{id}/{status}', 'RolesController@status');



Route::resource('clients', 'ClientsController');
Route::get('clients/aproved/{id}', 'ClientsController@AprovedServiceProvider');


Route::put('update/photo/clients/{id_client}', 'ClientsController@UpdatePhotoProfile');


Route::post('clients/forms/prp/adviser/luisa', 'ClientsController@ClientFormsPrpAdviserLuisa');


Route::get('clients/identification/{identification}', 'ClientsController@GetByIdentification');
Route::get('status-cliente/{id}/{status}', 'ClientsController@status');


Route::resource('city', 'CityController');
Route::post('status-city/{id}/{status}', 'CityController@status');



Route::get('logs/sessions', 'LogsController@session');
Route::get('logs/events/adviser', 'LogsController@EventsAdvisers');
Route::get('logs/events/clients', 'LogsController@eventsClients');




Route::post('register/referred', 'ReferredController@store');


Route::post('authApp', 'Login@AuthApp');



Route::resource('category', 'CategoryController');
Route::get('category/sub/{category}/{state?}', 'CategoryController@getSubCategory');




Route::resource('request/service', 'RequestServiceController');
Route::get('request/service/by/client/{id_client}', 'RequestServiceController@RequestByClient');
Route::get('requests/services/by/client/{id_client}', 'RequestServiceController@RequestsByClient');

Route::get('request/offerts/by/service/{id_service}', 'RequestServiceController@RequestOffertsByService');
Route::get('all/request/offerts/by/service/{id_service}', 'RequestServiceController@RequestAllOffertsByService');
Route::get('requests/offerts/by/client/{id_client}', 'RequestServiceController@RequestOffertsByClient');
Route::get('process/service/{id_service}', 'RequestServiceController@ProcessService');
Route::get('cancel/request/service/{id_service}', 'RequestServiceController@CancelService');

Route::get('cancel/request/service/pay/{id_service}/{pay}', 'RequestServiceController@CancelServicePay');
Route::get('no/assistance/client/{id_service}/{pay}', 'RequestServiceController@NoAssistence');



Route::get('refuse/request/offert/{id_offert}', 'RequestServiceController@RefuseOffert');


Route::get('client/cancel/request/service/{id_service}', 'RequestServiceController@CancelClientService');

Route::get('get/charge/client/{id_service}', 'RequestServiceController@getChargeClient');



Route::get('get/report/{id_service}', 'RequestServiceController@GetReport');

Route::post('calification/service/provider', 'CalificationController@store');
Route::get('get/rating/service/provider/{id}', 'CalificationController@GetRatingServiceProvider');

Route::post('test/notification', 'NotificationApp@Send');

Route::get('get/status/service/provider/{id_client}', 'ClientsController@GetStatusServiceProvider');
Route::post('postulated/service/provicer', 'ClientsController@PostulatedServiceProvider');
Route::get('update/status/service/provider/{id_client}/{status}', 'ClientsController@UpdateStatusServiceProvider');



Route::get('request/service/for/provider/{id_client}', 'RequestServiceController@GetRequestServicesForProvider');
Route::post('store/offert/service', 'RequestServiceController@StoreOffert');
Route::post('accept/offert', 'RequestServiceController@AcceptOffert');


Route::post('store/credit/client', 'AccountStatusController@StoreCredit');


Route::post('store/charge/client', 'ClientsController@StoreCharge');


Route::get('get/balance/client/{id_client}', 'AccountStatusController@GetBalanceClient');
Route::get('get/account/status/client/{id_client}', 'AccountStatusController@GetAccountStatusClient');


Route::get('get/categories/client/{id_client}', 'AccountStatusController@GetCategoriesClient');
Route::post('change/category/client', 'AccountStatusController@RequestUpdateCategoriesClient');
Route::get('get/request/change/category', 'AccountStatusController@GetChangeCategoriesClient');
Route::get('aproved/change/category/{id}', 'AccountStatusController@AprovedChangeCategoriesClient');



Route::resource('chat/souport', 'ChatsSouportController');
Route::get('chat/souport/by/client/{id}', 'ChatsSouportController@GetChatByClient');
Route::post('store/message/souport', 'ChatsSouportController@StoreSouport');

Route::resource('report/services', 'ReportServicesController');



Route::resource('request/offerts', 'RequestOffertsController');


Route::get('app', function () {

    $ConfigNotification = [
        "tokens" => ["eTcaZsr3SmyYBBVKV7nq6R:APA91bHAZcaELO_e3UJojU2hXcoWUhYuLcvsguJWrO0lLEuEPDLzEWbCvtAcS3l0lvHIrmGu9ieDtjN-sYidZYZ8GYx6JLfOOricWBDVTgPuyC4YxfclRYIRy8IuehJUplGXsF2T9ucr"],
        "tittle" => "ServiUf",
        "body"   => "Nuevo servicio",
        "data"   => ['type' => 'refferers'],
        "sound"  => "iphone.mp3"
    ];

    $code = SendNotifications($ConfigNotification);

    dd("YES");

});




