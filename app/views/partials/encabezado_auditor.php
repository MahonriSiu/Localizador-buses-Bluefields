<div class="encabezado-superior">
    <div class="encabezado-marca">
        <img src="<?php echo URL_BASE; ?>/asset.php?tipo=img&archivo=logo.png">
        <span>MiBus</span>
    </div>
    <?php require __DIR__ . '/perfil_widget.php'; ?>
</div>
<div class="nav-central">
    <a href="<?php echo URL_BASE; ?>/auditor/panel.php" class="nav-pill <?php echo $paginaActiva === 'panel' ? 'activa' : ''; ?>">
        <span class="icono">📊</span> Panel
    </a>
    <a href="<?php echo URL_BASE; ?>/auditor/mapa.php" class="nav-pill <?php echo $paginaActiva === 'mapa' ? 'activa' : ''; ?>">
        <span class="icono">🗺️</span> Mapa 24/7
    </a>
    <a href="<?php echo URL_BASE; ?>/auditor/usuarios.php" class="nav-pill <?php echo $paginaActiva === 'usuarios' ? 'activa' : ''; ?>">
        <span class="icono">📋</span> Usuarios
    </a>
    <a href="<?php echo URL_BASE; ?>/auditor/resenas.php" class="nav-pill <?php echo $paginaActiva === 'resenas' ? 'activa' : ''; ?>">
        <span class="icono">💬</span> Reseñas
    </a>
</div>