<div class="encabezado-superior">
    <div class="encabezado-marca">
        <img src="<?php echo URL_BASE; ?>/asset.php?tipo=img&archivo=logo.png">
        <span>MiBus</span>
    </div>
    <?php require __DIR__ . '/perfil_widget.php'; ?>
</div>
<div class="nav-central">
    <a href="<?php echo URL_BASE; ?>/admin/panel.php" class="nav-pill <?php echo $paginaActiva === 'panel' ? 'activa' : ''; ?>">
        <span class="icono">📊</span> Panel
    </a>
    <a href="<?php echo URL_BASE; ?>/admin/gestion.php" class="nav-pill <?php echo $paginaActiva === 'gestion' ? 'activa' : ''; ?>">
        <span class="icono">🚌</span> Gestion
    </a>
    <a href="<?php echo URL_BASE; ?>/admin/cuentas.php" class="nav-pill <?php echo $paginaActiva === 'cuentas' ? 'activa' : ''; ?>">
        <span class="icono">👤</span> Cuentas
    </a>
    <a href="<?php echo URL_BASE; ?>/admin/usuarios.php" class="nav-pill <?php echo $paginaActiva === 'usuarios' ? 'activa' : ''; ?>">
        <span class="icono">📋</span> Usuarios
    </a>
    <a href="<?php echo URL_BASE; ?>/admin/mapa.php" class="nav-pill <?php echo $paginaActiva === 'mapa' ? 'activa' : ''; ?>">
        <span class="icono">🗺️</span> Mapa 24/7
    </a>
    <a href="<?php echo URL_BASE; ?>/index.php" class="nav-pill" target="_blank">
        <span class="icono">🌐</span> Mapa publico
    </a>
    <a href="<?php echo URL_BASE; ?>/admin/resenas.php" class="nav-pill <?php echo $paginaActiva === 'resenas' ? 'activa' : ''; ?>">
        <span class="icono">💬</span> Reseñas
    </a>
    <a href="<?php echo URL_BASE; ?>/admin/anuncios.php" class="nav-pill <?php echo $paginaActiva === 'anuncios' ? 'activa' : ''; ?>">
        <span class="icono">📣</span> Anuncios
    </a>
    <a href="<?php echo URL_BASE; ?>/admin/ruta-creativa.php" class="nav-pill <?php echo $paginaActiva === 'eventos' ? 'activa' : ''; ?>">
        <span class="icono">📅</span> Eventos
    </a>
    <a href="<?php echo URL_BASE; ?>/admin/trazar-ruta.php" class="nav-pill <?php echo $paginaActiva === 'trazar-ruta' ? 'activa' : ''; ?>">
        <span class="icono">📍</span> Marcar Eventos
    </a>
</div>