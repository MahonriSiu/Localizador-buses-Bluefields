<?php require_once __DIR__ . '/../../../config/rutas.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiBus - Acceso</title>
    <link rel="stylesheet" href="<?php echo URL_BASE; ?>/asset.php?tipo=css&archivo=estilos.css">
</head>
<body>

    <div class="auth-pagina">
        <div class="auth-marca auth-marca-decorado">
            <div class="decoracion-cielo">
                <div class="nube nube1"></div>
                <div class="nube nube2"></div>
                <div class="nube nube3"></div>
                <div class="hoja hoja1"></div>
                <div class="hoja hoja2"></div>
                <div class="colina"></div>
            </div>

            <div class="auth-marca-contenido">
                <img src="<?php echo URL_BASE; ?>/asset.php?tipo=img&archivo=logo.png" class="auth-marca-logo">
                <h1>MiBus</h1>
                <p>Mira por donde viene tu bus antes de salir de casa.</p>
                <span class="auth-marca-pulso"><span class="pulso-vivo"></span> Rastreo en tiempo real</span>
            </div>
        </div>

        <div class="auth-formulario-lado">
            <div class="auth-formulario-caja">
                <h2>Bienvenido de vuelta</h2>
                <p class="auth-subtitulo">Ingresa con tu numero de telefono.</p>
                <div id="mensaje-registro"></div>

                <div id="bloque-inicio">
                    <div class="campo-formulario">
                        <label>Numero de telefono</label>
                        <div class="grupo-telefono">
                            <span class="prefijo-telefono">🇳🇮 +505</span>
                            <input type="tel" id="telefono-login" placeholder="0000-0000" maxlength="9">
                        </div>
                    </div>
                    <button class="boton boton-primario boton-bloque" onclick="iniciarSesion()">Ingresar</button>

                    <p style="margin-top: 16px; text-align: center; font-size: 13px;">
                        No tienes cuenta? <a href="#" onclick="mostrarRegistro(); return false;" style="color: var(--color-primario); font-weight: 600;">Registrate</a>
                    </p>
                </div>

                <div id="bloque-registro" style="display: none;">
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
                        Ya tienes cuenta? <a href="#" onclick="mostrarLogin(); return false;" style="color: var(--color-primario); font-weight: 600;">Inicia sesion</a>
                    </p>
                </div>
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
                sessionStorage.setItem("mibus_es_nuevo", "1");
                window.location.href = URL_BASE + "/usuario/index.php?nuevo=1";
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

        function mostrarRegistro() {
            document.getElementById("bloque-inicio").style.display = "none";
            document.getElementById("bloque-registro").style.display = "block";
        }

        function mostrarLogin() {
            document.getElementById("bloque-registro").style.display = "none";
            document.getElementById("bloque-inicio").style.display = "block";
        }
    </script>
</body>
</html>