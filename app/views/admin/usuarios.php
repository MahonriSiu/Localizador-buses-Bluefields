<?php require_once __DIR__ . '/../../../config/rutas.php'; $paginaActiva = 'usuarios'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiBus - Usuarios Registrados</title>
    <link rel="stylesheet" href="<?php echo URL_BASE; ?>/asset.php?tipo=css&archivo=estilos.css">
</head>
<body>

    <?php require __DIR__ . '/../partials/encabezado_admin.php'; ?>

    <div class="contenedor">
        <div class="tarjeta">
            <div class="tabla-panel-scroll">
                <table class="tabla-panel">
                    <thead><tr><th>Nombre</th><th>Telefono</th><th>Fecha de registro</th></tr></thead>
                    <tbody id="cuerpo-tabla-usuarios"></tbody>
                </table>
            </div>
        </div>
    </div>
    
        <div class="tarjeta">
            <h2>Historial de accesos</h2>
            <p style="font-size: 13px; color: #6b6255; margin-bottom: 12px;">Cada vez que un usuario entra, no solo su primer registro.</p>
            <div class="tabla-panel-scroll">
                <table class="tabla-panel">
                    <thead><tr><th>Nombre</th><th>Telefono</th><th>Tipo</th><th>Fecha y hora</th></tr></thead>
                    <tbody id="cuerpo-tabla-historial"></tbody>
                </table>
            </div>
        </div>

    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=utilidades.js"></script>
    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=perfil.js"></script>
    <script>
        const URL_BASE = "<?php echo URL_BASE; ?>";

        async function cargarUsuarios() {
            const resultado = await llamarApi(URL_BASE + "/admin_obtener_usuarios_finales.php", {});
            const cuerpo = document.getElementById("cuerpo-tabla-usuarios");
            cuerpo.innerHTML = "";

            if (!resultado.usuarios || resultado.usuarios.length === 0) {
                cuerpo.innerHTML = "<tr><td colspan='3'>Aun no hay usuarios registrados</td></tr>";
                return;
            }

            resultado.usuarios.forEach(function (u) {
                cuerpo.innerHTML += "<tr><td>" + u.nombre + "</td><td>+505 " + u.telefono + "</td><td>" + u.fecha_registro + "</td></tr>";
            });
        }
        
        async function cargarHistorial() {
            const resultado = await llamarApi(URL_BASE + "/admin_obtener_historial_accesos.php", {});
            const cuerpo = document.getElementById("cuerpo-tabla-historial");
            cuerpo.innerHTML = "";

            if (!resultado.historial || resultado.historial.length === 0) {
                cuerpo.innerHTML = "<tr><td colspan='4'>Aun no hay historial</td></tr>";
                return;
            }

            resultado.historial.forEach(function (h) {
                const tipo = h.tipo_usuario === 'usuario_final_registro' ? "Primer registro" : "Reingreso";
                cuerpo.innerHTML += "<tr><td>" + h.nombre + "</td><td>+505 " + h.telefono + "</td><td>" + tipo + "</td><td>" + h.fecha_hora + "</td></tr>";
            });
        }

        document.addEventListener("DOMContentLoaded", cargarHistorial);

        document.addEventListener("DOMContentLoaded", cargarUsuarios);
    </script>
</body>
</html>