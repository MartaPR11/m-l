<h3>
    <a href="<?php echo $_SESSION['home'] ?>" title="Inicio">Inicio</a> <span>| </span>
    <a href="<?php echo $_SESSION['home'] ?>personajes" title="Personajes">Personajes</a> <span>| </span>
    <span><?php echo $datos->nombre ?></span>
</h3>
<div class="row">
    <article class="col s12">
        <div class="card horizontal large noticia">
            <div class="card-image">
                <img src="<?php echo $_SESSION['public']."img/".$datos->imagen ?>" alt="<?php echo $datos->nombre ?>">
            </div>
            <div class="card-stacked">
                <div class="card-content">
                    <h4><?php echo $datos->nombre ?></h4>
                    <p><?php echo $datos->edad ?></p>
                    <p><?php echo $datos->primeraPelicula ?></p>
                    <br>
                </div>
            </div>
        </div>
    </article>
</div>