<?php require_once __DIR__ . '/../../../config/rutas.php'; $paginaActiva = 'panel'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiBus - Panel Admin</title>
    <link rel="stylesheet" href="<?php echo URL_BASE; ?>/asset.php?tipo=css&archivo=estilos.css">
</head>
<body>

    <?php require __DIR__ . '/../partials/encabezado_admin.php'; ?>

    <div class="contenedor">
        <div class="grid-estadisticas">
            <div class="tarjeta-estadistica">
                <div class="numero" id="total-buses">-</div>
                <div class="etiqueta">Buses totales</div>
            </div>
            <div class="tarjeta-estadistica">
                <div class="numero" id="buses-habilitados">-</div>
                <div class="etiqueta">Buses habilitados</div>
            </div>
            <div class="tarjeta-estadistica">
                <div class="numero" id="buses-transmitiendo">-</div>
                <div class="etiqueta">Transmitiendo ahora</div>
            </div>
        </div>

        <div class="tarjeta">
            <h2>Accesos rapidos</h2>
            <div class="grid-accesos">
                <a href="<?php echo URL_BASE; ?>/admin/gestion.php" class="acceso-tarjeta">
                    <span class="icono-grande">🚌</span>
                    <span class="titulo">Gestion de buses</span>
                </a>
                <a href="<?php echo URL_BASE; ?>/admin/cuentas.php" class="acceso-tarjeta">
                    <span class="icono-grande">👤</span>
                    <span class="titulo">Cuentas</span>
                </a>
                <a href="<?php echo URL_BASE; ?>/admin/usuarios.php" class="acceso-tarjeta">
                    <span class="icono-grande">📋</span>
                    <span class="titulo">Usuarios registrados</span>
                </a>
                <a href="<?php echo URL_BASE; ?>/admin/ruta-creativa.php" class="acceso-tarjeta">
                    <span class="icono-grande">📅</span>
                    <span class="titulo">Eventos y Circuitos</span>
                </a>
                <a href="<?php echo URL_BASE; ?>/admin/anuncios.php" class="acceso-tarjeta">
                    <span class="icono-grande">📣</span>
                    <span class="titulo">Anuncios</span>
                </a>
                <a href="<?php echo URL_BASE; ?>/admin/trazar-ruta.php" class="acceso-tarjeta">
                    <span class="icono-grande">📍</span>
                    <span class="titulo">Marcar puntos de eventos</span>
                </a>
                <a href="<?php echo URL_BASE; ?>/admin/resenas.php" class="acceso-tarjeta">
                    <span class="icono-grande">💬</span>
                    <span class="titulo">Reseñas</span>
                </a>
                <a href="<?php echo URL_BASE; ?>/admin/mapa.php" class="acceso-tarjeta">
                    <span class="icono-grande">🗺️</span>
                    <span class="titulo">Mapa 24/7</span>
                </a>
                <a href="<?php echo URL_BASE; ?>/index.php" class="acceso-tarjeta" target="_blank">
                    <span class="icono-grande">🌐</span>
                    <span class="titulo">Mapa publico</span>
                </a>
            </div>

    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=utilidades.js"></script>
    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=admin.js"></script>
    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=perfil.js"></script>
</body>
</html>