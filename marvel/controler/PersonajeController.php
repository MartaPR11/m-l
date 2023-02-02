<?php
namespace App\Controller;

use App\Helper\ViewHelper;
use App\Helper\DbHelper;
use App\Model\Personaje;


class PersonajeController
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

    //Listado de personajes
    public function index(){

        //Permisos
        $this->view->permisos("personajes");

        //Recojo las personajes de la base de datos
      $rowset = $this->db->query("SELECT * FROM personajes ORDER BY edad DESC");

        //Asigno resultados a un array de instancias del modelo
        $personajes = array();
        while ($row = $rowset->fetch(\PDO::FETCH_OBJ)){
            array_push($personajes,new Personaje($row));
        }

        $this->view->vista("admin","personajes/index", $personajes);

    }

    //Para activar o desactivar
    public function activar($id){

        //Permisos
        $this->view->permisos("personajes");

        //Obtengo la noticia
        $rowset = $this->db->query("SELECT * FROM personajes WHERE id='$id' LIMIT 1");
        $row = $rowset->fetch(\PDO::FETCH_OBJ);
        $personaje = new Personaje($row);

        if ($personaje->activo == 1){

            //Desactivo la noticia
            $consulta = $this->db->exec("UPDATE personajes SET activo=0 WHERE id='$id'");

            //Mensaje y redirección
            ($consulta > 0) ? //Compruebo consulta para ver que no ha habido errores
                $this->view->redireccionConMensaje("admin/personajes","green","El personajes <strong>$personaje->nombre</strong> se ha desactivado correctamente.") :
                $this->view->redireccionConMensaje("admin/personajes","red","Hubo un error al guardar en la base de datos.");
        }

        else{

            //Activo la noticia
            $consulta = $this->db->exec("UPDATE personajes SET activo=1 WHERE id='$id'");

            //Mensaje y redirección
            ($consulta > 0) ? //Compruebo consulta para ver que no ha habido errores
                $this->view->redireccionConMensaje("admin/personajes","green","El personajes <strong>$personaje->nombre</strong> se ha activado correctamente.") :
                $this->view->redireccionConMensaje("admin/personajes","red","Hubo un error al guardar en la base de datos.");
        }

    }

    //Para mostrar o no en la home
    public function home($id){

        //Permisos
        $this->view->permisos("personajes");

        //Obtengo la noticia
        $rowset = $this->db->query("SELECT * FROM personajes WHERE id='$id' LIMIT 1");
        $row = $rowset->fetch(\PDO::FETCH_OBJ);
        $personaje = new Personaje($row);

        if ($personaje->home == 1){

            //Quito el personajes de la home
            $consulta = $this->db->exec("UPDATE personajes SET home=0 WHERE id='$id'");

            //Mensaje y redirección
            ($consulta > 0) ? //Compruebo consulta para ver que no ha habido errores
                $this->view->redireccionConMensaje("admin/personajes","green","El personajes <strong>$personaje->nombre</strong> ya no se muestra en la home.") :
                $this->view->redireccionConMensaje("admin/personajes","red","Hubo un error al guardar en la base de datos.");
        }

        else{

            //Muestro el personajes en la home
            $consulta = $this->db->exec("UPDATE personajes SET home=1 WHERE id='$id'");

            //Mensaje y redirección
            ($consulta > 0) ? //Compruebo consulta para ver que no ha habido errores
                $this->view->redireccionConMensaje("admin/personajes","green","El personajes <strong>$personaje->nombre</strong> ahora se muestra en la home.") :
                $this->view->redireccionConMensaje("admin/personajes","red","Hubo un error al guardar en la base de datos.");
        }

    }

    public function borrar($id){

        //Permisos
        $this->view->permisos("personajes");

        //Obtengo al personaje
        $rowset = $this->db->query("SELECT * FROM personajes WHERE id='$id' LIMIT 1");
        $row = $rowset->fetch(\PDO::FETCH_OBJ);
        $personaje = new Personaje($row);

        //Borro al personaje
        $consulta = $this->db->exec("DELETE FROM personajes WHERE id='$id'");

        //Borro la imagen asociada
        $archivo = $_SESSION['public']."img/".$personaje->imagen;
        $texto_imagen = "";
        if (is_file($archivo)){
            unlink($archivo);
            $texto_imagen = " y se ha borrado la imagen asociada";
        }

        //Mensaje y redirección
        ($consulta > 0) ? //Compruebo consulta para ver que no ha habido errores
            $this->view->redireccionConMensaje("admin/personajes","green","El personajes se ha borrado correctamente$texto_imagen.") :
            $this->view->redireccionConMensaje("admin/personajes","red","Hubo un error al guardar en la base de datos.");

    }

    public function crear(){

        //Permisos
        $this->view->permisos("personajes");

        //Creo un nuevo usuario vacío
        $personaje = new Personaje();

        //Llamo a la ventana de edición
        $this->view->vista("admin","personajes/editar", $personaje);

    }

    public function editar($id){

        //Permisos
        $this->view->permisos("personajes");

        //Si ha pulsado el botón de guardar
        if (isset($_POST["guardar"])){

            //Recupero los datos del formulario
            $nombre = filter_input(INPUT_POST, "nombre", FILTER_SANITIZE_STRING);
            $edad = filter_input(INPUT_POST, "edad", FILTER_SANITIZE_STRING);
            $primeraPelicula = filter_input(INPUT_POST, "primeraPelicula", FILTER_SANITIZE_STRING);

            //Genero slug (url amigable)
            $slug = $this->view->getSlug($nombre);

            //Imagen
            $imagen_recibida = $_FILES['imagen'];
            $imagen = ($_FILES['imagen']['name']) ? $_FILES['imagen']['name'] : "";
            $imagen_subida = ($_FILES['imagen']['name']) ? '/var/www/html'.$_SESSION['public']."img/".$_FILES['imagen']['name'] : "";
            $texto_img = ""; //Para el mensaje

            if ($id == "nuevo"){

                //Creo el personajes
                $consulta = $this->db->exec("INSERT INTO personajes 
                    (nombre, edad, primeraPelicula,slug,imagen) VALUES 
                    ('$nombre','$edad','$primeraPelicula','$slug','$imagen')");

                //Subo la imagen
                if ($imagen){
                    if (is_uploaded_file($imagen_recibida['tmp_name']) && move_uploaded_file($imagen_recibida['tmp_name'], $imagen_subida)){
                        $texto_img = " La imagen se ha subido correctamente.";
                    }
                    else{
                        $texto_img = " Hubo un problema al subir la imagen.";
                    }
                }

                //Mensaje y redirección
                ($consulta > 0) ?
                    $this->view->redireccionConMensaje("admin/personajes","green","El personajes <strong>$nombre</strong> se creado correctamente.".$texto_img) :
                    $this->view->redireccionConMensaje("admin/personajes","red","Hubo un error al guardar en la base de datos.");
            }
            else{

                //Actualizo el personajes
                $this->db->exec("UPDATE personajes SET 
                    nombre='$nombre', edad='$edad', primeraPelicula='$primeraPelicula', slug='$slug' WHERE id='$id'");


                //Subo y actualizo la imagen
                if ($imagen){
                    if (is_uploaded_file($imagen_recibida['tmp_name']) && move_uploaded_file($imagen_recibida['tmp_name'], $imagen_subida)){
                        $texto_img = " La imagen se ha subido correctamente.";
                        $this->db->exec("UPDATE personajes SET imagen='$imagen' WHERE id='$id'");
                    }
                    else{
                        $texto_img = " Hubo un problema al subir la imagen.";
                    }
                }

                //Mensaje y redirección
                $this->view->redireccionConMensaje("admin/personajes","green","El personajes <strong>$nombre</strong> se guardado correctamente.".$texto_img);

            }
        }

        //Si no, obtengo noticia y muestro la ventana de edición
        else{

            //Obtengo la noticia
            $rowset = $this->db->query("SELECT * FROM personajes WHERE id='$id' LIMIT 1");
            $row = $rowset->fetch(\PDO::FETCH_OBJ);
            $personaje = new Personaje($row);

            //Llamo a la ventana de edición
            $this->view->vista("admin","personajes/editar", $personaje);
        }

    }

}