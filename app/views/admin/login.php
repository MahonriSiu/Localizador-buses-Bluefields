<?php require_once __DIR__ . '/../../../config/rutas.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiBus - Acceso Admin</title>
    <link rel="stylesheet" href="<?php echo URL_BASE; ?>/asset.php?tipo=css&archivo=estilos.css">
</head>
<body>

    <div class="contenedor-angosto">
        <div class="tarjeta">
            <img src="<?php echo URL_BASE; ?>/asset.php?tipo=img&archivo=logo.png" style="width: 80px; display: block; margin: 0 auto 16px;">
            <h2>Acceso Administrador</h2>
            <div id="mensaje-login"></div>

            <div class="campo-formulario">
                <label>Correo</label>
                <input type="email" id="correo">
            </div>
            <div class="campo-formulario">
                <label>Contrasena</label>
                <input type="password" id="contrasena">
            </div>
            <button class="boton boton-primario boton-bloque"
                onclick="iniciarSesionAdmin(document.getElementById('correo').value, document.getElementById('contrasena').value)">
                Ingresar
            </button>
        </div>
    </div>

    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=utilidades.js"></script>
    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=admin.js"></script>
</body>
</html>