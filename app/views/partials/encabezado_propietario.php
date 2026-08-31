<div class="encabezado-superior">
    <div class="encabezado-marca">
        <img src="<?php echo URL_BASE; ?>/asset.php?tipo=img&archivo=logo.png">
        <span>MiBus</span>
    </div>
    <?php require __DIR__ . '/perfil_widget.php'; ?>
</div>
<div class="nav-central">
    <a href="<?php echo URL_BASE; ?>/propietario/panel.php" class="nav-pill <?php echo $paginaActiva === 'panel' ? 'activa' : ''; ?>">
        <span class="icono">🚌</span> Mis buses
    </a>
    <a href="<?php echo URL_BASE; ?>/propietario/mapa.php" class="nav-pill <?php echo $paginaActiva === 'mapa' ? 'activa' : ''; ?>">
        <span class="icono">🗺️</span> Mapa en vivo
    </a>
</div>