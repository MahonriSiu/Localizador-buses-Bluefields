<?php require_once __DIR__ . '/../../../config/rutas.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiBus - Acceso Propietario</title>
    <link rel="stylesheet" href="<?php echo URL_BASE; ?>/asset.php?tipo=css&archivo=estilos.css">
</head>
<body>

    <div class="contenedor-angosto">
        <div class="tarjeta">
            <img src="<?php echo URL_BASE; ?>/asset.php?tipo=img&archivo=logo.png" style="width: 80px; display: block; margin: 0 auto 16px;">
            <h2>Acceso Propietario</h2>
            <div id="mensaje-login"></div>

            <div class="campo-formulario">
                <label>Correo</label>
                <input type="email" id="correo">
            </div>
            <div class="campo-formulario">
                <label>Contrasena</label>
                <input type="password" id="contrasena">
            </div>
            <button class="boton boton-primario boton-bloque" onclick="iniciarSesion()">Ingresar</button>

            <p style="margin-top: 16px; text-align: center; font-size: 13px;">
                <a href="#" onclick="mostrarFormularioReseteo(); return false;" style="color: #0f766e;">Olvidaste tu contrasena?</a>
            </p>

            <div id="formulario-reseteo" style="display: none; margin-top: 16px; border-top: 1px solid #e5e7eb; padding-top: 16px;">
                <div class="campo-formulario">
                    <label>Tu correo</label>
                    <input type="email" id="correo-reseteo">
                </div>
                <button class="boton boton-secundario boton-bloque" onclick="pedirReseteo()">Enviar solicitud al admin</button>
                <div id="mensaje-reseteo"></div>
            </div>
        </div>
    </div>

    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=utilidades.js"></script>
    <script>
        const URL_BASE = "<?php echo URL_BASE; ?>";

        async function iniciarSesion() {
            const correo = document.getElementById("correo").value;
            const contrasena = document.getElementById("contrasena").value;

            const resultado = await llamarApi(URL_BASE + "/propietario_login.php", {
                correo: correo, contrasena: contrasena
            });

            if (resultado.exito) {
                window.location.href = resultado.debe_cambiar
                    ? URL_BASE + "/cambiar_contrasena.php"
                    : URL_BASE + "/propietario/panel.php";
            } else {
                mostrarMensaje("mensaje-login", resultado.mensaje, true);
            }
        }

        function mostrarFormularioReseteo() {
            document.getElementById("formulario-reseteo").style.display = "block";
        }

        async function pedirReseteo() {
            const correo = document.getElementById("correo-reseteo").value;
            const resultado = await llamarApi(URL_BASE + "/solicitar_reseteo.php", { correo: correo });
            mostrarMensaje("mensaje-reseteo", resultado.mensaje, false);
        }
    </script>
</body>
</html>