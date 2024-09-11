<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\NewPasswordController;

use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';

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

// Route::any('{any}', function () {
//     return view('mantenimiento');
// })->where('any', '.*');

route::get('/backups54897896', function () {
    \Illuminate\Support\Facades\Artisan::call('database:backup');
});

Route::get('mantenimiento', function () {
    return view('mantenimiento');
})->name('mantenimiento');

Route::get('validar/{code}', function ($code) {
    $now = \Carbon\Carbon::now();
    $user = \App\Models\User::where('codigo_aforos', $code)->first();
    if ($user != null) { // Check user
        $reservas_activas = App\Models\Reserva::where('id_usuario', $user->id)->where('fecha', $now->format('Y-m-d'))->get();
        if ($reservas_activas != '[]') {
            foreach ($reservas_activas as $reserva) {
                $inicio = \Carbon\Carbon::createFromTimestamp($reserva->timestamp)->format('Hi');
                $fin = \Carbon\Carbon::createFromTimestamp($reserva->timestamp)->addMinutes($reserva->minutos_totales)->format('Hi');
                $actual = $now->format('Hi');
                if ($actual >= $inicio && $actual <= $fin) {
                    if ($reserva->estado == 'pasado') {
                        \App\Models\Reserva::find($reserva->id)->update(['estado' => 'canceled', 'salida', date('Y-m-d H:i:s')]);
                    } else {
                        \App\Models\Reserva::find($reserva->id)->update(['estado' => 'pasado']);
                    }
                    break;
                }
            }
        }
    }
});
Route::get('/', 'InstalacionController@home');
Route::group(['prefix' => '{slug_instalacion}', 'middleware' => 'check_instalacion'], function () {
    Route::get('/', 'UserController@index');
    Route::get('/normas', 'UserController@normas_instalacion');

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->middleware('guest')->name('login_instalacion');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('guest')->middleware('login_instalacion');

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->middleware('guest')->name('forgot_password_instalacion');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->middleware('guest')->name('forgot_password_instalacion');

    Route::get('/register', [RegisteredUserController::class, 'create_user_instalacion'])
        ->middleware('guest')->name('register_user_instalacion');

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
        Route::post('/perfil/delete', 'UserController@delete_perfil')->name('delete.perfil');
    });

    Route::group(['prefix' => 'admin', 'middleware' => 'admin_o_empleado_instalacion'], function () {
        Route::get('/', 'InstalacionController@index');
        Route::prefix('reservas')->group(function () {
            Route::get('/', 'InstalacionController@index');
            Route::get('/list', 'InstalacionController@listado_todas_reservas');
            Route::get('/list/{periodicas}', 'InstalacionController@listado_todas_reservas');
            Route::get('/periodicas', 'InstalacionController@reservas_periodicas');
            Route::get('/periodicas/add', 'InstalacionController@add_reservas_periodicas_view');
            Route::post('/periodicas/add', 'InstalacionController@add_reservas_periodicas')->name('add_reserva_periodica');
            Route::get('/periodicas/{id}/borrar', 'InstalacionController@borrar_reservas_periodicas');
            Route::get('/periodicas/{id}/editar', 'InstalacionController@editar_reservas_periodicas_view');
            Route::post('/periodicas/update', 'InstalacionController@update_reserva_periodica')->name('update_reserva_periodica');


            Route::get('/desactivaciones', 'InstalacionController@desactivaciones_periodicas');
            Route::get('/desactivaciones/add', 'InstalacionController@add_desactivaciones_periodicas_view');
            Route::post('/desactivaciones/add', 'InstalacionController@add_desactivaciones_periodicas')->name('add_desactivacion');
            Route::get('/desactivaciones/{id}/borrar', 'InstalacionController@borrar_desactivaciones_periodicas');

            Route::get('/{id}/eliminar', 'InstalacionController@eliminar_reserva')->name('reserva.remove');

            Route::get('/{id}/edit', 'InstalacionController@edit_reserva_view')->name('reserva.edit');
            Route::post('/{id}/edit', 'InstalacionController@edit_reserva')->name('reserva.edit');

            Route::get('/numero/{fecha}', 'InstalacionController@numero_reservas_dia_por_pista');
            Route::get('/{fecha}', 'InstalacionController@reservas_dia');
            Route::get('/{fecha}/{id_pista}', 'InstalacionController@reservas_dia_por_pista');
            Route::post('/validar/{id}', 'InstalacionController@validar_reserva');
            Route::get('/{id_pista}/reservar/{timestamp}', 'InstalacionController@hacer_reserva_view');
            Route::post('/{id_pista}/reservar/{timestamp}', 'InstalacionController@hacer_reserva');
            Route::post('/{id_pista}/desactivar/{timestamp}', 'InstalacionController@desactivar_tramo');
            Route::post('/{id_pista}/activar/{timestamp}', 'InstalacionController@activar_tramo');
            Route::get('/{id_pista}/desactivar-dia/{dia}', 'InstalacionController@desactivar_dia');
            Route::get('/{id_pista}/activar-dia/{dia}', 'InstalacionController@activar_dia');
        });

        Route::prefix('pistas')
            ->middleware('admin_instalacion')
            ->group(function () {
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

        Route::prefix('cobro')
            ->middleware('admin_instalacion')
            ->group(function () {
                Route::get('/', 'InstalacionController@list_cobros');
                Route::get('/add', 'InstalacionController@add_cobro_view');
                Route::post('/add', 'InstalacionController@add_cobro');
                Route::prefix('{id}')->group(function () {
                    Route::get('/', 'InstalacionController@edit_cobro_view');
                    Route::post('/', 'InstalacionController@edit_cobro');
                    Route::get('/delete', 'InstalacionController@delete_cobro');
                });
            });

        Route::prefix('configuracion')
            ->middleware('admin_instalacion')
            ->group(function () {
                Route::get('/instalacion', 'InstalacionController@configuracion_instalacion')->name('edit_config_inst');
                Route::get('/instalacion/edit/{tipo}', 'InstalacionController@edit_info');
                Route::post('/instalacion/edit/{tipo}', 'InstalacionController@editar_info');

                Route::get('/pistas-reservas', 'InstalacionController@configuracion_pistas_reservas');
                Route::post('configuracion/edit', 'InstalacionController@edit_configuracion')->name('edit_config');
            });

        Route::prefix('campos-adicionales')
            ->middleware('admin_instalacion')
            ->group(function () {
                Route::get('/', 'InstalacionController@campos_adicionales');
                Route::get('/campos-personalizados', 'InstalacionController@view_campos_personalizados');
                Route::post('/campos-personalizados', 'InstalacionController@add_campos_personalizados');
                Route::get('/campos-personalizados/{id}', 'InstalacionController@view_edit_campos_personalizados');
                Route::post('/campos-personalizados/{id}', 'InstalacionController@edit_campos_personalizados');
                Route::get('/campos-personalizados/{id}/delete', 'InstalacionController@delete_campos_personalizados');
            });
        Route::prefix('patrocinadores')->middleware('admin_instalacion')
            ->group(function () {

                route::get('/', 'SponsorController@index')->name('sponsors.index');
                route::post('/', 'SponsorController@store')->name('sponsors.store');
                route::get('/create', 'SponsorController@create')->name('sponsors.create');
                Route::get('/{id}', 'SponsorController@show')->name('sponsors.show');
                route::put('/{id}', 'SponsorController@update')->name('sponsors.update');
                route::delete('/{id}', 'SponsorController@destroy')->name('sponsors.destroy');
                route::get('/{id}/edit', 'SponsorController@edit')->name('sponsors.edit');
            });
        Route::prefix('facturas')->middleware('admin_instalacion')->group(function () {
            Route::prefix('entidades-bancarias')->group(function () {
                Route::get('/', 'BankController@index')->name('banks.index');
                Route::post('/', 'BankController@store')->name('banks.store');
                Route::get('/create', 'BankController@create')->name('banks.create');
                // Route::get('/{id}', 'BankController@show')->name('banks.show');
                Route::put('/{id}', 'BankController@update')->name('banks.update');
                Route::delete('/{id}', 'BankController@destroy')->name('banks.destroy');
                Route::get('/{id}/edit', 'BankController@edit')->name('banks.edit');
            });

            Route::prefix('tipos-servicio')->group(function () {
                Route::get('/', 'ServiceTypeController@index')->name('serviceTypes.index');
                Route::post('/', 'ServiceTypeController@store')->name('serviceTypes.store');
                Route::get('/create', 'ServiceTypeController@create')->name('serviceTypes.create');
                // Route::get('/{id}', 'ServiceTypeController@show')->name('serviceTypes.show');
                Route::put('/{id}', 'ServiceTypeController@update')->name('serviceTypes.update');
                Route::delete('/{id}', 'ServiceTypeController@destroy')->name('serviceTypes.destroy');
                Route::get('/{id}/edit', 'ServiceTypeController@edit')->name('serviceTypes.edit');
            });

            Route::prefix('proveedores')->group(function () {
                Route::get('/', 'SupplierController@index')->name('suppliers.index');
                Route::post('/', 'SupplierController@store')->name('suppliers.store');
                Route::get('/create', 'SupplierController@create')->name('suppliers.create');
                Route::get('/{id}', 'SupplierController@show')->name('suppliers.show');
                Route::put('/{id}', 'SupplierController@update')->name('suppliers.update');
                Route::delete('/{id}', 'SupplierController@destroy')->name('suppliers.destroy');
                Route::get('/{id}/edit', 'SupplierController@edit')->name('suppliers.edit');
            });

            Route::get('/', 'InvoiceController@index')->name('invoices.index');
            Route::post('/', 'InvoiceController@store')->name('invoices.store');
            Route::get('/create', 'InvoiceController@create')->name('invoices.create');
            Route::get('/{id}', 'InvoiceController@show')->name('invoices.show');
            Route::put('/{id}', 'InvoiceController@update')->name('invoices.update');
            Route::delete('/{id}', 'InvoiceController@destroy')->name('invoices.destroy');
            Route::get('/{id}/edit', 'InvoiceController@edit')->name('invoices.edit');
        });
    });

    Route::group(['prefix' => '{deporte}'], function () {
        Route::get('/', 'UserController@pistas');
        Route::group(['prefix' => '{id_pista}'], function () {
            Route::get('/', 'UserController@pistas');
            Route::group(['middleware' => 'auth_instalacion'], function () {
                Route::get('/{timestamp}', 'UserController@reserva');
                Route::post('/{timestamp}/reserva', 'UserController@reservar');
            });
        });
    });
});
