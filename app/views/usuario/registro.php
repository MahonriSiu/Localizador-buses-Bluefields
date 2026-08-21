<?php require_once __DIR__ . '/../../../config/rutas.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiBus - Registro</title>
    <link rel="stylesheet" href="<?php echo URL_BASE; ?>/asset.php?tipo=css&archivo=estilos.css">
</head>
<body>

    <div class="contenedor-angosto">
        <div class="tarjeta">
            <img src="<?php echo URL_BASE; ?>/asset.php?tipo=img&archivo=logo.png" style="width: 80px; display: block; margin: 0 auto 16px;">
            <h2>Registrate para usar MiBus</h2>
            <p style="font-size: 13px; color: #6b7280; margin-bottom: 16px;">
                El registro es obligatorio para ver la ubicacion de los buses.
            </p>
            <div id="mensaje-registro"></div>

            <div class="campo-formulario">
                <label>Nombre</label>
                <input type="text" id="nombre">
            </div>

            <div class="campo-formulario">
                <label>Numero de telefono</label>
                <div class="grupo-telefono">
                    <span class="prefijo-telefono">🇳🇮 +505</span>
                    <input type="tel" id="telefono" placeholder="0000-0000" maxlength="9">
                </div>
            </div>

            <button class="boton boton-primario boton-bloque" onclick="registrarse()">Registrarme</button>

            <p style="margin-top: 16px; text-align: center; font-size: 13px;">
                Ya tienes cuenta? <a href="#" onclick="mostrarLogin(); return false;" style="color: #0f766e; font-weight: 600;">Inicia sesion</a>
            </p>

            <div id="bloque-login" style="display: none; margin-top: 16px; border-top: 1px solid #e5e7eb; padding-top: 16px;">
                <div class="campo-formulario">
                    <label>Numero de telefono</label>
                    <div class="grupo-telefono">
                        <span class="prefijo-telefono">🇳🇮 +505</span>
                        <input type="tel" id="telefono-login" placeholder="0000-0000" maxlength="9">
                    </div>
                </div>
                <button class="boton boton-secundario boton-bloque" onclick="iniciarSesion()">Ingresar</button>
            </div>
        </div>
    </div>

    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=utilidades.js"></script>
    <script>
        const URL_BASE = "<?php echo URL_BASE; ?>";

        function formatearTelefono(input) {
            input.addEventListener("input", function () {
                let digitos = input.value.replace(/[^0-9]/g, "").substring(0, 8);
                if (digitos.length > 4) {
                    input.value = digitos.substring(0, 4) + "-" + digitos.substring(4);
                } else {
                    input.value = digitos;
                }
            });
        }

        formatearTelefono(document.getElementById("telefono"));
        formatearTelefono(document.getElementById("telefono-login"));

        async function registrarse() {
            const nombre = document.getElementById("nombre").value;
            const telefono = document.getElementById("telefono").value;

            const resultado = await llamarApi(URL_BASE + "/usuario_login.php", {
                accion: "registro", nombre: nombre, telefono: telefono
            });

            if (resultado.exito) {
                window.location.href = URL_BASE + "/usuario/index.php";
            } else {
                mostrarMensaje("mensaje-registro", resultado.mensaje, true);
            }
        }

        async function iniciarSesion() {
            const telefono = document.getElementById("telefono-login").value;

            const resultado = await llamarApi(URL_BASE + "/usuario_login.php", {
                accion: "login", telefono: telefono
            });

            if (resultado.exito) {
                window.location.href = URL_BASE + "/usuario/index.php";
            } else {
                mostrarMensaje("mensaje-registro", resultado.mensaje, true);
            }
        }

        function mostrarLogin() {
            document.getElementById("bloque-login").style.display = "block";
        }
    </script>
</body>
</html>