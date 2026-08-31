<?php require_once __DIR__ . '/../../../config/rutas.php'; $paginaActiva = 'resenas'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiBus - Reseñas</title>
    <link rel="stylesheet" href="<?php echo URL_BASE; ?>/asset.php?tipo=css&archivo=estilos.css">
</head>
<body>

    <?php require __DIR__ . '/../partials/encabezado_admin.php'; ?>

    <div class="contenedor">
        <div class="tarjeta">
            <h2>Reseñas y recomendaciones de los usuarios</h2>
            <div id="lista-resenas"></div>
        </div>
    </div>

    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=utilidades.js"></script>
    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=perfil.js"></script>
    <script>
        const URL_BASE = "<?php echo URL_BASE; ?>";

        function estrellasTexto(calificacion) {
            if (!calificacion) return "Sin calificacion";
            return "★".repeat(calificacion) + "☆".repeat(5 - calificacion);
        }

        async function cargarResenas() {
            const resultado = await llamarApi(URL_BASE + "/admin_obtener_resenas.php", {});
            const contenedor = document.getElementById("lista-resenas");

            if (!resultado.resenas || resultado.resenas.length === 0) {
                contenedor.innerHTML = "<div class='estado-vacio'><span class='icono-vacio'>💬</span><p>Aun no hay reseñas de usuarios.</p></div>";
                return;
            }

            contenedor.innerHTML = "";
            resultado.resenas.forEach(function (r) {
                contenedor.innerHTML += "<div class='tarjeta' style='margin-bottom:10px; border-top-color: var(--color-primario);'>" +
                    "<div style='display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;'>" +
                    "<strong>" + r.nombre + "</strong>" +
                    "<span style='color: var(--color-naranja); font-size:15px;'>" + estrellasTexto(r.calificacion) + "</span>" +
                    "</div>" +
                    "<p style='font-size:14px; color:#444;'>" + r.comentario + "</p>" +
                    "<p style='font-size:11px; color:#999; margin-top:6px;'>" + r.fecha_hora + "</p>" +
                    "</div>";
            });
        }

        document.addEventListener("DOMContentLoaded", cargarResenas);
    </script>
</body>
</html>