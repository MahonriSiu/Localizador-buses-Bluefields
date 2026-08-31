<?php require_once __DIR__ . '/../../../config/rutas.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiBus - Acceso Administrador</title>
    <link rel="stylesheet" href="<?php echo URL_BASE; ?>/asset.php?tipo=css&archivo=estilos.css">
</head>
<body>

    <div class="auth-pagina">
        <div class="auth-marca">
            <img src="<?php echo URL_BASE; ?>/asset.php?tipo=img&archivo=logo.png" class="auth-marca-logo">
            <h1>MiBus</h1>
            <p>Panel de control del sistema de buses de Bluefields.</p>
            <span class="auth-marca-pulso"><span class="pulso-vivo"></span> Rastreo en tiempo real</span>
            <span class="auth-marca-rol">Administrador</span>
        </div>

        <div class="auth-formulario-lado">
            <div class="auth-formulario-caja">
                <h2>Bienvenido de vuelta</h2>
                <p class="auth-subtitulo">Ingresa con tu correo y contrasena de administrador.</p>
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
    </div>

    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=utilidades.js"></script>
    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=admin.js"></script>
</body>
</html>