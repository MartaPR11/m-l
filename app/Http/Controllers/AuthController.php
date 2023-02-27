<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use DB;
use Carbon\Carbon;
use Mail;


class AuthController extends Controller
{
    public function acceder()
    {
        return view('auth.acceso');
    }

    public function autenticar(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);
        $credentials['activo'] = 1;

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('admin')->withSuccess('Bienvenido al panel de Administración');
        }

        return back()->withErrors([
            'email' => 'El email no está registrado.',
        ]);
    }

    public function registro()
    {
        return view('auth.registro');
    }

    public function registrarse(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'email' => 'required|email|unique:usuarios',
            'password' => 'required|confirmed|min:6',
        ]);

        $data = $request->all();

        $usuario = Usuario::create([
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);

        Auth::login($usuario);

        return redirect("admin")->withSuccess('Te has registrado correctamente. Bienvenido');
    }

    public function salir(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('admin');
    }

    //Muestro el formulario para introducir el email
    public function email()
    {
        return view('auth.email');
    }

    //Genero y envío el enlace para restaurar la clave
    public function enlace(Request $request)
    {
        //Validación de email
        $request->validate([
            'email' => 'required|email|exists:usuarios',
        ]);

        //Generación de token y almacenado en la tabla password_resets
        $token = Str::random(64);
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        //Envío de email al usuario
        Mail::send('email.email', ['token' => $token], function($message) use($request){
            $message->to($request->email);
            $message->subject('Cambiar contraseña en CMS Laravel');
        });

        //Retorno
        return redirect('acceder')->with('success','Te hemos enviado un email a <strong>'.$request->email.'</strong> con un enlace para realizar el cambio de contraseña.');

    }

    //Muestro el formulario para cambiar la clave
    public function clave($token)
    {
        return view('auth.clave', ['token' => $token]);
    }

    //cambio la clave
    public function cambiar(Request $request)
    {
        //Valido datos
        $request->validate([
            'email' => 'required|email|exists:usuarios',
            'password' => 'required|min:8|max:16|confirmed',
            'password_confirmation' => 'required'
        ]);

        //Compruebo token válido
        $comprobarToken = DB::table('password_resets')->where(['email' => $request->email, 'token' => $request->token])->first();
        if(!$comprobarToken){
            return back()->withInput()->with('danger','El enlace no es válido');
        }

        //Actualizo password
        Usuario::where('email', $request->email)->update(['password' => Hash::make($request->password)]);

        //Borro token para que no se pueda volver a usar
        DB::table('password_resets')->where(['email'=> $request->email])->delete();

        //Retorno
        return redirect('acceder')->with('success','La contraseña se ha cambiado correctamente.');
    }
}
