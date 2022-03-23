<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\NewPasswordController;
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

    Route::get('/register', [RegisteredUserController::class, 'create_user_instalacion'])
                ->middleware('guest');

    Route::post('/register', [RegisteredUserController::class, 'store_instalacion'])
                ->middleware('guest');

   Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
                ->middleware('guest')
                ->name('password.reset_user_instalacion');

    Route::post('/reset-password', [NewPasswordController::class, 'store'])
                ->middleware('guest')
                ->name('password.update');

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
            Route::get('/list', 'InstalacionController@listado_todas_reservas');
            Route::get('/periodicas', 'InstalacionController@reservas_periodicas');
            Route::get('/periodicas/add', 'InstalacionController@add_reservas_periodicas_view');
            Route::post('/periodicas/add', 'InstalacionController@add_reservas_periodicas')->name('add_reserva_periodica');
            Route::get('/periodicas/{id}/borrar', 'InstalacionController@borrar_reservas_periodicas');

            Route::get('/desactivaciones', 'InstalacionController@desactivaciones_periodicas');
            Route::get('/desactivaciones/add', 'InstalacionController@add_desactivaciones_periodicas_view');
            Route::post('/desactivaciones/add', 'InstalacionController@add_desactivaciones_periodicas')->name('add_desactivacion');
            Route::get('/desactivaciones/{id}/borrar', 'InstalacionController@borrar_desactivaciones_periodicas');

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
            Route::get('/novalid', 'InstalacionController@users_no_valid');
            Route::get('add', 'InstalacionController@add_user_view');
            Route::post('add/annadir', 'InstalacionController@add_user')->name('add_user');
            Route::prefix('{id}')->group(function () {
                Route::get('/', 'InstalacionController@edit_user_view');
                Route::post('/', 'InstalacionController@editar_user');
                Route::get('/cambiar-foto', 'InstalacionController@cambiar_foto_user');
                Route::get('/validar', 'InstalacionController@validar_user');
                Route::get('/borrar-permanente', 'InstalacionController@borrar_permanente_user');
                Route::get('/ver', 'InstalacionController@ver_user');
                Route::get('/cobro/add', 'InstalacionController@user_add_cobro_view');
                Route::post('/cobro/add', 'InstalacionController@user_add_cobro');
                Route::get('/desactivar', 'InstalacionController@desactivar_user');
                Route::post('/update-maximas-reservas', 'InstalacionController@update_max_reservas_user');
            });
        });

        Route::prefix('cobro')->group(function () {
            Route::get('/', 'InstalacionController@list_cobros');
            Route::get('/add', 'InstalacionController@add_cobro_view');
            Route::post('/add', 'InstalacionController@add_cobro');
            Route::prefix('{id}')->group(function () {
                Route::get('/', 'InstalacionController@edit_cobro_view');
                Route::post('/', 'InstalacionController@edit_cobro');
                Route::get('/delete', 'InstalacionController@delete_cobro');
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