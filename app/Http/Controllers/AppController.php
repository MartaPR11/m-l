<?php

namespace App\Http\Controllers;
use App\Models\Personaje;

class AppController extends Controller
{
    public function index()
    {
        //Obtengo las personajes a mostrar en la home
        $rowset = Personaje::where('activo', 1)->where('home', 1)->orderBy('nombre', 'DESC')->get();

        return view('app.index',[
            'rowset' => $rowset,
        ]);
    }

    public function personajes()
    {
        //Obtengo las personajes a mostrar en el listado de personajes
        $rowset = Personaje::where('activo', 1)->orderBy('nombre', 'DESC')->get();

        return view('app.personajes',[
            'rowset' => $rowset,
        ]);
    }

    public function personaje($slug)
    {
        //Obtengo la personaje o muestro error
        $row = Personaje::where('activo', 1)->where('slug', $slug)->firstOrFail();

        return view('app.personaje',[
            'row' => $row,
        ]);
    }

    public function acercade()
    {
        return view('app.acerca-de');
    }

    //Métodos para la API (podrían ir en otro controlador)

    public function mostrar(){

        //Obtengo las noticias a mostrar en el listado de noticias
        $rowset = Personaje::where('activo', 1)->orderBy('nombre', 'DESC')->get();

        //Opción rápida (datos completos)
        //$personajes = $rowset;

        //Opción personalizada
        foreach ($rowset as $row){
            $personajes[] = [
                'nombre' => $row->nombre,
                'edad' => $row->edad,
                'primeraPelicula' => $row->primeraPelicula,
                'descripcion' => $row->descripcion,
                'enlace' => url("personaje/".$row->slug),
                'imagen' => asset("http://3.11.140.242/marvel2-laravel/public/img/".$row->imagen)
            ];
        }

        //Devuelvo JSON
        return response()->json(
            $personajes, //Array de objetos
            200, //Tipo de respuesta
            [], //Headers
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE //Opciones de escape
        );

    }
    //Conectar la API
    public function leer(){

        //Url de destino
        $url = 'http://13.39.88.144/fut-laravel/public/index.php/mostrar';

        //Parseo datos a un array
        $rowset = json_decode(file_get_contents($url), true);

        //LLamo a la vista
        return view('api.leer',[
            'rowset' => $rowset,
        ]);

    }
}
