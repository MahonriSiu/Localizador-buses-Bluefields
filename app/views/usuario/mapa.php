<?php require_once __DIR__ . '/../../../config/rutas.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiBus - Bluefields</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="stylesheet" href="<?php echo URL_BASE; ?>/asset.php?tipo=css&archivo=estilos.css">
</head>
<body>

    <div class="encabezado-usuario">
        <img src="<?php echo URL_BASE; ?>/asset.php?tipo=img&archivo=logo.png" class="logo-encabezado">
        <div>
            <h1>MiBus</h1>
            <div class="subtitulo">Ubicacion de buses en tiempo real - Bluefields</div>
        </div>
    </div>

    <div class="panel-flotante">
        <select id="selector-ruta" class="selector-ruta">
            <option value="">Selecciona tu ruta</option>
        </select>
        <button id="boton-notificaciones" class="boton-notificacion" onclick="activarNotificaciones()">
            Activar avisos de cercania
        </button>
    </div>

    <div id="mapa"></div>

    <div id="panel-reposo" class="panel-reposo" style="display: none;">
        <img src="<?php echo URL_BASE; ?>/asset.php?tipo=img&archivo=logo.png" style="width: 100px; opacity: 0.6; margin-bottom: 16px;">
        <h2>El sistema esta en reposo</h2>
        <p>El servicio de rastreo esta disponible dentro del horario establecido.</p>
    </div>

    <div id="sin-rutas" class="panel-reposo" style="display: none;">
        <h2>No hay rutas activas en este momento</h2>
        <p>Vuelve a intentarlo mas tarde.</p>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=utilidades.js"></script>
    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=mapa.js"></script>
</body>
</html>