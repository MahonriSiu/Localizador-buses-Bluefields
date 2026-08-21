<?php require_once __DIR__ . '/../../config/rutas.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiBus - Cambiar contrasena</title>
    <link rel="stylesheet" href="<?php echo URL_BASE; ?>/asset.php?tipo=css&archivo=estilos.css">
</head>
<body>

    <div class="contenedor-angosto">
        <div class="tarjeta">
            <img src="<?php echo URL_BASE; ?>/asset.php?tipo=img&archivo=logo.png" style="width: 80px; display: block; margin: 0 auto 16px;">
            <h2>Cambia tu contrasena</h2>
            <p style="font-size: 13px; color: #6b7280; margin-bottom: 16px;">
                Es tu primer ingreso o el admin reseteo tu cuenta. Pon una contrasena nueva para continuar.
            </p>
            <div id="mensaje-cambio"></div>

            <div class="campo-formulario">
                <label>Contrasena actual</label>
                <input type="password" id="contrasena-actual">
            </div>
            <div class="campo-formulario">
                <label>Contrasena nueva</label>
                <input type="password" id="contrasena-nueva">
            </div>
            <button class="boton boton-primario boton-bloque" onclick="cambiarContrasena()">Guardar y continuar</button>
        </div>
    </div>

    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=utilidades.js"></script>
    <script>
        const URL_BASE = "<?php echo URL_BASE; ?>";

        async function cambiarContrasena() {
            const actual = document.getElementById("contrasena-actual").value;
            const nueva = document.getElementById("contrasena-nueva").value;

            const resultado = await llamarApi(URL_BASE + "/cambiar_contrasena_propia.php", {
                contrasena_actual: actual, contrasena_nueva: nueva
            });

            if (resultado.exito) {
                const destino = document.referrer.includes("propietario")
                    ? URL_BASE + "/propietario/panel.php"
                    : URL_BASE + "/auditor/panel.php";
                window.location.href = destino;
            } else {
                mostrarMensaje("mensaje-cambio", resultado.mensaje, true);
            }
        }
    </script>
</body>
</html>