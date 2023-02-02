<?php

namespace App\Controller;

use App\Model\Personaje;
use App\Helper\ViewHelper;
use App\Helper\DbHelper;

class AppController
{
    var $db;
    var $view;

    function __construct()
    {
        //Conexión a la BBDD
        $dbHelper = new DbHelper();
        $this->db = $dbHelper->db;

        //Instancio el ViewHelper
        $viewHelper = new ViewHelper();
        $this->view = $viewHelper;
    }

    public function index(){

        //Consulta a la bbdd
        $rowset = $this->db->query("SELECT * FROM personajes WHERE activo=1 AND home=1 ORDER BY nombre DESC");

        //Asigno resultados a un array de instancias del modelo
        $personajes = array();
        while ($row = $rowset->fetch(\PDO::FETCH_OBJ)){
            array_push($personajes,new Personaje($row));
        }

        //Llamo a la vista
        $this->view->vista("app", "index", $personajes);
    }

    public function acercade(){

        //Llamo a la vista
        $this->view->vista("app", "acerca-de");

    }

    public function personajes(){

        //Consulta a la bbdd
        $rowset = $this->db->query("SELECT * FROM personajes WHERE activo=1 ORDER BY nombre DESC");

        //Asigno resultados a un array de instancias del modelo
        $personajes = array();
        while ($row = $rowset->fetch(\PDO::FETCH_OBJ)){
            array_push($personajes,new Personaje($row));
        }

        //Llamo a la vista
        $this->view->vista("app", "personajes", $personajes);

    }

    public function personaje($slug){

        //Consulta a la bbdd
        $rowset = $this->db->query("SELECT * FROM personajes WHERE activo=1 AND slug='$slug' LIMIT 1");

        //Asigno resultado a una instancia del modelo
        $row = $rowset->fetch(\PDO::FETCH_OBJ);
        $personaje = new Personaje($row);

        //Llamo a la vista
        $this->view->vista("app", "personaje", $personaje);

    }
}