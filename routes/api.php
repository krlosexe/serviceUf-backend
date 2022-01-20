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

Route::get('request/offerts/by/service/{id_service}', 'RequestServiceController@RequestOffertsByService');


Route::post('test/notification', 'NotificationApp@Send');