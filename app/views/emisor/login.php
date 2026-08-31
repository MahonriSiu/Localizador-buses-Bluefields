<?php require_once __DIR__ . '/../../../config/rutas.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiBus - Acceso Emisor</title>
    <link rel="stylesheet" href="<?php echo URL_BASE; ?>/asset.php?tipo=css&archivo=estilos.css">
</head>
<body>

    <div class="auth-pagina">
        <div class="auth-marca">
            <img src="<?php echo URL_BASE; ?>/asset.php?tipo=img&archivo=logo.png" class="auth-marca-logo">
            <h1>MiBus</h1>
            <p>Panel del chofer para transmitir la ubicacion del bus.</p>
            <span class="auth-marca-pulso"><span class="pulso-vivo pulso-naranja"></span> Envio de ubicacion</span>
            <span class="auth-marca-rol">Emisor</span>
        </div>

        <div class="auth-formulario-lado">
            <div class="auth-formulario-caja">
                <h2>Acceso del chofer</h2>
                <p class="auth-subtitulo">Ingresa el codigo de acceso asignado a tu bus.</p>
                <div id="mensaje-login"></div>

                <div class="campo-formulario">
                    <label>Codigo de acceso</label>
                    <input type="text" id="codigo" placeholder="Ej: A3F9K2">
                </div>
                <button class="boton boton-primario boton-bloque" onclick="iniciarSesionEmisor(document.getElementById('codigo').value)">
                    Ingresar
                </button>
            </div>
        </div>
    </div>

    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=utilidades.js"></script>
    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=emisor.js"></script>
</body>
</html>