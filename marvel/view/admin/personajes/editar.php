<h3>
    <a href="<?php echo $_SESSION['home'] ?>admin" title="Inicio">Inicio</a> <span>| </span>
    <a href="<?php echo $_SESSION['home'] ?>admin/personajes" title="Personajes">Personajes</a> <span>| </span>
    <?php if ($datos->id){ ?>
        <span>Editar <?php echo $datos->nombre ?></span>
    <?php } else { ?>
        <span>Nuevo personaje</span>
    <?php } ?>
</h3>
<div class="row">
    <?php $id = ($datos->id) ? $datos->id : "nuevo" ?>
    <form class="col s12" method="POST" enctype="multipart/form-data" action="<?php echo $_SESSION['home'] ?>admin/personajes/editar/<?php echo $id ?>">
        <div class="col m12 l6">
            <div class="row">
                <div class="input-field col s12">
                    <input id="nombre" type="text" name="nombre" value="<?php echo $datos->nombre ?>">
                    <label for="nombre">Nombre</label>
                </div>

            </div>
        </div>
        <div class="col m12 l6 center-align">
            <div class="file-field input-field">
                <div class="btn">
                    <span>Imagen</span>
                    <input type="file" name="imagen">
                </div>
                <div class="file-path-wrapper">
                    <input class="file-path validate" type="text">
                </div>
            </div>
            <?php if ($datos->imagen){ ?>
                <img src="<?php echo $_SESSION['public']."img/".$datos->imagen ?>" alt="<?php echo $datos->nombre ?>">
            <?php } ?>
        </div>
        <div class="col s12">
            <div class="row">
                <div class="input-field col s12">
                    <label for="edad">Edad</label>
                    <textarea id="edad" class="materialize-textarea" name="edad"><?php echo $datos->edad ?></textarea>

                </div>
                <div class="input-field col s12">
                    <textarea id="primeraPelicula" class="materialize-textarea" name="primeraPelicula"><?php echo $datos->primeraPelicula ?></textarea>
                    <label for="primeraPelicula">Primera pelicula</label>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="input-field col s12">
                <a href="<?php echo $_SESSION['home'] ?>admin/personajes" title="Volver">
                    <button class="btn waves-effect waves-light" type="button">Volver
                        <i class="material-icons right">replay</i>
                    </button>
                </a>
                <button class="btn waves-effect waves-light" type="submit" name="guardar">Guardar
                    <i class="material-icons right">save</i>
                </button>
            </div>
        </div>
    </form>
</div>