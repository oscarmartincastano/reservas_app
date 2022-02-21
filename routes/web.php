<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['prefix' =>'{slug_instalacion}', 'middleware' => 'check_instalacion'], function() {
    Route::get('/', 'UserController@index');
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->middleware('guest')->name('login_instalacion');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('guest')->middleware('login_instalacion');

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->middleware('guest')->name('forgot_password_instalacion');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->middleware('guest');

    Route::middleware(['auth_instalacion'])->group(function () {
        Route::get('/mis-reservas', 'UserController@mis_reservas');
        Route::post('/mis-reservas/{id}/cancel', 'UserController@cancel_reservas');
        Route::get('/perfil', 'UserController@perfil');
        Route::post('/perfil/edit', 'UserController@edit_perfil');
    });

    Route::group(['prefix' =>'admin', 'middleware' => 'auth_admin_instalacion'], function() {
        Route::get('/', 'InstalacionController@index');
        Route::prefix('reservas')->group(function () {
            Route::get('/', 'InstalacionController@index');
            Route::get('/{fecha}', 'InstalacionController@reservas_dia');
            Route::post('/validar/{id}', 'InstalacionController@validar_reserva');
            Route::get('/{id_pista}/reservar/{timestamp}', 'InstalacionController@hacer_reserva_view');
            Route::post('/{id_pista}/reservar/{timestamp}', 'InstalacionController@hacer_reserva');
            Route::post('/{id_pista}/desactivar/{timestamp}', 'InstalacionController@desactivar_tramo');
            Route::post('/{id_pista}/activar/{timestamp}', 'InstalacionController@activar_tramo');
            Route::get('/{id_pista}/desactivar-dia/{dia}', 'InstalacionController@desactivar_dia');
            Route::get('/{id_pista}/activar-dia/{dia}', 'InstalacionController@activar_dia');
        });
    
        Route::prefix('pistas')->group(function () {
            Route::get('/', 'InstalacionController@pistas');
            Route::get('add', 'InstalacionController@add_pista_view');
            Route::post('add/annadir', 'InstalacionController@add_pista')->name('add_pista');
            Route::prefix('{id}')->group(function () {
                Route::get('edit', 'InstalacionController@edit_pista_view');
                Route::post('edit/annadir', 'InstalacionController@edit_pista')->name('edit_pista');
            });
        });
    
        Route::prefix('users')->group(function () {
            Route::get('/', 'InstalacionController@users');
            Route::get('add', 'InstalacionController@add_user_view');
            Route::post('add/annadir', 'InstalacionController@add_user')->name('add_user');
            Route::prefix('{id}')->group(function () {
                Route::get('/', 'InstalacionController@edit_user_view');
                Route::post('/', 'InstalacionController@editar_user');
                
                Route::get('/desactivar', 'InstalacionController@desactivar_user');
            });
        });

        Route::prefix('configuracion')->group(function () {
            Route::get('/instalacion', 'InstalacionController@configuracion_instalacion')->name('edit_config_inst');
            Route::get('/instalacion/edit/{tipo}', 'InstalacionController@edit_info');
            Route::post('/instalacion/edit/{tipo}', 'InstalacionController@editar_info');

            Route::get('/pistas-reservas', 'InstalacionController@configuracion_pistas_reservas');
            Route::post('configuracion/edit', 'InstalacionController@edit_configuracion')->name('edit_config');

        });

        Route::prefix('campos-adicionales')->group(function () {
            Route::get('/', 'InstalacionController@campos_adicionales');
            Route::get('/campos-personalizados', 'InstalacionController@view_campos_personalizados');
            Route::post('/campos-personalizados', 'InstalacionController@add_campos_personalizados');
            Route::get('/campos-personalizados/{id}', 'InstalacionController@view_edit_campos_personalizados');
            Route::post('/campos-personalizados/{id}', 'InstalacionController@edit_campos_personalizados');
            Route::get('/campos-personalizados/{id}/delete', 'InstalacionController@delete_campos_personalizados');
        });
    });

    Route::group(['prefix' =>'{deporte}'], function() {
        Route::get('/', 'UserController@pistas');
        Route::group(['prefix' =>'{id_pista}'], function() {
            Route::get('/', 'UserController@pistas');
            Route::group(['middleware' => 'auth_instalacion'], function() {
                Route::get('/{timestamp}', 'UserController@reserva');
                Route::post('/{timestamp}/reserva', 'UserController@reservar');
            });
        });
    });
});


/* Route::get('/peticion', function($requestHttpMethod, $fechaHoraUTC, $requestUri, $param, $bodyParam, $idInstalacion) {
    $secretKey = "secret";
    $requestContentBase64String = "";
    $nonce = com_create_guid();
    $uriEncode = urlencode($requestUri);
    $now = new \DateTime();
    $timestamp = $now->getTimestamp();

    $APPId = "appkey";

    $firma = $APPId . $requestHttpMethod . $uriEncode . $timestamp . $nonce;

    $hmac = hash_hmac('sha256', $firma, $secretKey);

}); */