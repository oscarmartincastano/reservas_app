<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
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

    Route::middleware(['auth_instalacion'])->group(function () {
        Route::get('/mis-reservas', 'UserController@mis_reservas');
        Route::post('/mis-reservas/{id}/cancel', 'UserController@cancel_reservas');
        Route::get('/perfil', 'UserController@perfil');
        Route::post('/perfil/edit', 'UserController@edit_perfil');
    });

    Route::group(['prefix' =>'admin', 'middleware' => 'auth_admin_instalacion'], function() {
        Route::get('/', 'InstalacionController@index');
    
        Route::get('/edit/{tipo}', 'InstalacionController@edit_info');
        Route::post('/edit/{tipo}', 'InstalacionController@editar_info');
    
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
            /* Route::prefix('{id}')->group(function () {
                Route::get('edit', 'InstalacionController@edit_pista_view');
                Route::post('edit/annadir', 'InstalacionController@edit_pista')->name('edit_pista');
            }); */
        });

        Route::prefix('configuracion')->group(function () {
            Route::get('/instalacion', 'InstalacionController@configuracion_instalacion');
            Route::get('/pistas-reservas', 'InstalacionController@configuracion_pistas_reservas');
            Route::post('configuracion/edit', 'InstalacionController@edit_configuracion')->name('edit_config');
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