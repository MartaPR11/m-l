<?php

namespace App\Model;

class Usuario {

    //Variables o atributos
    var $id;
    var $usuario;
    var $paw;
    var $activo;
    var $usuarios;
    var $personajes;

    function __construct($data=null){

        $this->id = ($data) ? $data->id : null;
        $this->usuario = ($data) ? $data->usuario : null;
        $this->paw = ($data) ? $data->paw : null;
        $this->activo = ($data) ? $data->activo : null;
        $this->usuarios = ($data) ? $data->usuarios : null;
        $this->personajes = ($data) ? $data->personajes : null;

    }

}