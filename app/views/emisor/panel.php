<?php require_once __DIR__ . '/../../../config/rutas.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiBus - Panel Emisor</title>
    <link rel="stylesheet" href="<?php echo URL_BASE; ?>/asset.php?tipo=css&archivo=estilos.css">
</head>
<body>

    <div class="encabezado">
        <h1>Panel del Emisor</h1>
    </div>

    <div class="contenedor">
        <div class="tarjeta" style="text-align: center;">
            <h2>Envio de ubicacion</h2>
            <p style="text-align: center; font-size: 12px; color: #9ca3af; margin-bottom: 16px;">
                Manten esta pantalla encendida y Chrome abierto mientras conduces.
            </p>
            <p id="estado-envio" style="margin: 16px 0; font-size: 16px; color: #6b7280;">
                Envio detenido
            </p>
            <p id="ultima-actualizacion" style="font-size: 13px; color: #9ca3af; margin-bottom: 20px;"></p>
            <div id="mensaje-panel"></div>

            <button class="boton boton-primario" onclick="iniciarEnvioPosicion()">Iniciar envio</button>
            <button class="boton boton-peligro" onclick="detenerEnvioPosicion()">Detener envio</button>
        </div>
    </div>

    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=utilidades.js"></script>
    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=emisor.js"></script>
</body>
</html>