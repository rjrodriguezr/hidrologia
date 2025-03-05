<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/',  function () {
    return redirect("/home");
});

//Auth::routes();
Route::auth();
Route::post('/login', [App\Http\Controllers\Auth\AuthController::class, 'login'])->name('login');
Route::get('/logout', [App\Http\Controllers\Auth\AuthController::class, 'logout'])->name('logout');


Route::get('/passwordExpiration', 'Auth\PwdExpirationController@showPasswordExpirationForm');
Route::post('/passwordExpiration', 'Auth\PwdExpirationController@postPasswordExpiration')->name('passwordExpiration');

Route::get('/home', 'HomeController@index');


//Route::group(['prefix' => 'api/v1', 'middleware' => 'auth:api'], function () {
Route::group(['prefix' => 'api/v1'], function () {
    Route::get('/users/dt', 'UserController@datatable');
    Route::get('/users', 'UserController@index');
    Route::post('/users', 'UserController@store');
    Route::put('/users/{id}', 'UserController@update');
    Route::get('/users/{id}', 'UserController@show');
    Route::delete('/users/{id}', 'UserController@destroy');
    Route::delete('/users/restore/{id}', 'UserController@restore');
    Route::options('/users', 'UserController@op');

    Route::get('/profiles/dt', 'ProfileController@datatable');
    Route::get('/profiles', 'ProfileController@index');
    Route::post('/profiles', 'ProfileController@store');
    Route::put('/profiles/{id}', 'ProfileController@update');
    Route::get('/profiles/user/{token}', 'ProfileController@showByUser');
    Route::get('/profiles/permissions', 'ProfileController@permissions');
    Route::get('/profiles/{id}', 'ProfileController@show');
    Route::delete('/profiles/{id}', 'ProfileController@destroy');
    Route::options('/profiles', 'ProfileController@op');

    Route::get('/canales', 'CanalController@index');
    Route::options('/canales', 'CanalController@op');

    Route::get('/umbrales', 'umbralController@index');
    Route::options('/umbrales', 'UmbralController@op');

    Route::get('/canal_controles/dt', 'CanalControlController@datatable');
    Route::get('/canal_controles', 'CanalControlController@index');
    Route::post('/canal_controles', 'CanalControlController@store');
    Route::put('/canal_controles/{id}', 'CanalControlController@update');
    Route::get('/canal_controles/{id}', 'CanalControlController@show');
    Route::delete('/canal_controles/{id}', 'CanalControlController@destroy');
    Route::options('/canal_controles', 'CanalControlController@op');

    Route::get('/lagunas', 'LagunaController@index');
    Route::options('/lagunas', 'LagunaController@op');

    Route::get('/laguna_controles/dt', 'LagunaControlController@datatable');
    Route::get('/laguna_controles', 'LagunaControlController@index');
    Route::post('/laguna_controles', 'LagunaControlController@store');
    Route::put('/laguna_controles/{id}', 'LagunaControlController@update');
    Route::get('/laguna_controles/{id}', 'LagunaControlController@show');
    Route::delete('/laguna_controles/{id}', 'LagunaControlController@destroy');
    Route::options('/laguna_controles', 'LagunaControlController@op');

    Route::get('/control_rimac/dt', 'ControlRimacController@datatable');
    Route::get('/control_rimac', 'ControlRimacController@index');
    Route::post('/control_rimac', 'ControlRimacController@store');
    Route::put('/control_rimac/{id}', 'ControlRimacController@update');
    Route::get('/control_rimac/{id}', 'ControlRimacController@show');
    Route::delete('/control_rimac/{id}', 'ControlRimacController@destroy');
    Route::options('/control_rimac', 'ControlRimacController@op');

    Route::get('/events/dt', 'EventsController@datatable');
    Route::get('/events', 'EventsController@index');
    Route::post('/events', 'EventsController@store');
    Route::put('/events/{id}', 'EventsController@update');
    Route::get('/events/{id}', 'EventsController@show');
    Route::delete('/events/{id}', 'EventsController@destroy');
    Route::options('/events', 'EventsController@op');
    Route::get('/ubicacion', 'UbicacionController@index');

    Route::get('/control_chinango/dt', 'ControlChinangoController@datatable');
    Route::get('/control_chinango', 'ControlChinangoController@index');
    Route::post('/control_chinango', 'ControlChinangoController@store');
    Route::put('/control_chinango/{id}', 'ControlChinangoController@update');
    Route::get('/control_chinango/{id}', 'ControlChinangoController@show');
    Route::delete('/control_chinango/{id}', 'ControlChinangoController@destroy');
    Route::options('/control_chinango', 'ControlChinangoController@op');

    Route::get('/meteorologia/dt', 'MeteorologiaController@datatable');
    Route::get('/meteorologia', 'MeteorologiaController@index');
    Route::post('/meteorologia', 'MeteorologiaController@store');
    Route::put('/meteorologia/{id}', 'MeteorologiaController@update');
    Route::get('/meteorologia/{id}', 'MeteorologiaController@show');
    Route::delete('/meteorologia/{id}', 'MeteorologiaController@destroy');
    Route::options('/meteorologia', 'MeteorologiaController@op');

    Route::get('/lastlogin/{token}', 'SignInOutLogController@lastLogin');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
