<h3>
    <a href="<?php echo $_SESSION['home'] ?>" title="Inicio">Inicio</a> <span>| Personajes</span>
</h3>
<div class="row">
    <?php foreach ($datos as $row){ ?>
        <article class="col m12 l6">
            <div class="card horizontal small">
                <div class="card-image">
                    <img src="<?php echo $_SESSION['public']."img/".$row->imagen ?>" alt="<?php echo $row->nombre ?>">
                </div>
                <div class="card-stacked">
                    <div class="card-content">
                        <h4><?php echo $row->nombre ?></h4>
                        <p><?php echo $row->edad ?></p>
                    </div>
                    <div class="card-action">
                        <a href="<?php echo $_SESSION['home']."personaje/".$row->slug ?>">Más información</a>
                    </div>
                </div>
            </div>
        </article>
    <?php } ?>
</div>