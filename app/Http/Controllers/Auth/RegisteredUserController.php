<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\Instalacion;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewUser;
use DB;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $instalacion = Instalacion::create(['nombre' => $request->name, 'direccion' => $request->direccion, 'tlfno' => $request->tlfno, 'slug' => $request->slug]);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => 'admin',
            'id_instalacion' => $instalacion->id,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    public function create_user_instalacion()
    {
        return view('auth.register_user_instalacion');
    }

    public function store_instalacion(Request $request)
    {
        $instalacion = Instalacion::where('slug', $request->slug_instalacion)->first();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Obtener el registro de la base de datos `superadmin` que coincida con el slug
        $superadmin = DB::connection('superadmin')
        ->table('superadmin')
        ->get()
        ->filter(function ($record) use ($request) {
            // Extraer el slug de la columna `url`
            $urlParts = explode('/', rtrim($record->url, '/')); // Dividir la URL por "/"
            $slug = end($urlParts); // Obtener el último segmento de la URL
            return $slug === $request->slug_instalacion; // Comparar con el slug del request
        })
        ->first();

        // Validar que la instalación exista
        if (!$superadmin) {
            abort(404, 'Instalación no encontrada');
        }

        if (isset($request->tlfno)) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'tlfno' => $request->tlfno,
                'rol' => 'user',
                'direccion' => $request->direccion,
                'aprobado' => date('Y-m-d H:i:s'),
                'id_instalacion' => $instalacion->id,
            ]);
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'rol' => 'user',
                'direccion' => $request->direccion,
                'aprobado' => date('Y-m-d H:i:s'),
                'id_instalacion' => $instalacion->id,
            ]);
        }

        config([
            'database.connections.dynamic' => [
                'driver' => env('DB_SUPERADMIN_CONNECTION', 'mysql'),
                'host' => env('DB_SUPERADMIN_HOST', '127.0.0.1'),
                'port' => env('DB_SUPERADMIN_PORT', '3306'),
                'database' => $superadmin->bd_nombre, // Nombre de la base de datos obtenido del registro
                'username' => env('DB_SUPERADMIN_USERNAME', 'forge'),
                'password' => env('DB_SUPERADMIN_PASSWORD', ''),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
                'engine' => null,
            ],
        ]);

        $existingUser = DB::connection('dynamic')
    ->table('users')
    ->where('email', $request->email)
    ->first();

        // Crear el usuario en la base de datos dinámica
        if(!$existingUser){
        DB::connection('dynamic')
        ->table('users')
        ->insert([
            'id_instalacion' => $superadmin->id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => 'user',
            'direccion' => $request->direccion,
            'tlfno' => $request->tlfno ?? null,
            'aprobado' => date('Y-m-d H:i:s'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

        event(new Registered($user));

        Auth::login($user);

        Mail::to('alfonso@tallerempresarial.es')->send(new Newuser($user));
        return redirect('/' . $request->slug_instalacion);
    }
}
