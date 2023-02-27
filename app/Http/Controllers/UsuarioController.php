<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Http\Requests\UsuarioRequest;
use App\Helpers\Vistas;
use App\Helpers\Funciones;

class UsuarioController extends Controller
{
    public function __construct()
    {
        /**
         * Asigno el middleware auth al controlador,
         * de modo que sea necesario estar al menos autenticado
         */
        $this->middleware('auth');
    }

    /**
     * Mostrar un listado de elementos
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //Página a mostrar
        $pagina = ($request->pagina) ? $request->pagina : 1;

        //Obtengo todas las noticias ordenadas por fecha más reciente
        $rowset = Usuario::orderBy("email","DESC")->paginate(2,['*'],'pagina',$pagina);

        return view('admin.usuarios.index',[
            'rowset' => $rowset,
        ]);
    }

    /**
     * Mostrar el formulario para crear un nuevo elemento
     *
     * @return \Illuminate\Http\Response
     */
    public function crear()
    {
        //Creo un nuevo usuario vacío
        $row = new Usuario();

        return view('admin.usuarios.editar',[
            'row' => $row,
        ]);
    }

    /**
     * Guardar un nuevo elemento en la bbdd
     *
     * @param  \App\Http\Requests\UsuarioRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function guardar(UsuarioRequest $request)
    {
        Usuario::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'usuarios' => ($request->usuarios) ? 1 : 0,
            'personajes' => ($request->personajes) ? 1 : 0,
        ]);

        return redirect('admin/usuarios')->with('success', 'Usuario <strong>'.$request->nombre.'</strong> creado');
    }

    /**
     * Mostrar el formulario para editar un elemento
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editar($id)
    {
        //Obtengo el usuario o muestro error
        $row = Usuario::where('id', $id)->firstOrFail();

        return view('admin.usuarios.editar',[
            'row' => $row,
        ]);
    }

    /**
     * Actualizar un elemento en la bbdd
     *
     * @param  \App\Http\Requests\UsuarioRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function actualizar(UsuarioRequest $request, $id)
    {
        $row = Usuario::findOrFail($id);

        Usuario::where('id', $row->id)->update([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => ($request->cambiar_clave) ? Hash::make($request->password) : $row->password,
            'usuarios' => ($request->usuarios) ? 1 : 0,
            'personajes' => ($request->personajes) ? 1 : 0,
        ]);

        return redirect('admin/usuarios')->with('success', 'Usuario <strong>'.$request->nombre.'</strong> guardado');
    }

    /**
     * Activar o desactivar elemento.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activar($id)
    {
        $row = Usuario::findOrFail($id);
        $valor = ($row->activo) ? 0 : 1;
        $texto = ($row->activo) ? "desactivado" : "activado";

        Usuario::where('id', $row->id)->update(['activo' => $valor]);

        return redirect('admin/usuarios')->with('success', 'Usuario <strong>'.$row->nombre.'</strong> '.$texto.'.');
    }

    /**
     * Borrar elemento.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function borrar($id)
    {
        $row = Usuario::findOrFail($id);

        Usuario::destroy($row->id);

        return redirect('admin/usuarios')->with('success', 'Usuario <strong>'.$row->nombre.'</strong> borrado');
    }
}
