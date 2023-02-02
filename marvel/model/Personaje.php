<?php

namespace App\Model;

class Personaje
{
    //Variables o atributos
    var $id;
    var $nombre;
    var $edad;
    var $primeraPelicula;
    var $activo;
    var $home;
    var $imagen;
    var $slug;

    function __construct($data=null){

        $this->id = ($data) ? $data->id : null;
        $this->nombre = ($data) ? $data->nombre : null;
        $this->edad = ($data) ? $data->edad : null;
        $this->primeraPelicula = ($data) ? $data->primeraPelicula : null;
        $this->activo = ($data) ? $data->activo : null;
        $this->home = ($data) ? $data->home : null;
        $this->imagen = ($data) ? $data->imagen : null;
        $this->slug = ($data) ? $data->slug : null;

    }

}