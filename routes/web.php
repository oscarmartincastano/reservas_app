<?php

use Illuminate\Support\Facades\Route;

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
Route::group(['middleware' => 'auth'], function() {
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
        Route::get('add', 'InstalacionController@add_pista_view');
        Route::post('add/annadir', 'InstalacionController@add_pista')->name('add_pista');
        Route::prefix('{id}')->group(function () {
            Route::get('edit', 'InstalacionController@edit_pista_view');
            Route::post('edit/annadir', 'InstalacionController@edit_pista')->name('edit_pista');
        });
    });

    Route::get('configuracion', 'InstalacionController@configuracion');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
