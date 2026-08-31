<?php require_once __DIR__ . '/../../../config/rutas.php'; $paginaActiva = 'panel'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiBus - Panel Auditor</title>
    <link rel="stylesheet" href="<?php echo URL_BASE; ?>/asset.php?tipo=css&archivo=estilos.css">
</head>
<body>

    <?php require __DIR__ . '/../partials/encabezado_auditor.php'; ?>

    <div class="contenedor">
        <div class="grid-estadisticas">
            <div class="tarjeta-estadistica">
                <div class="numero" id="total-registrados">-</div>
                <div class="etiqueta">Usuarios registrados</div>
            </div>
            <div class="tarjeta-estadistica">
                <div class="numero" id="usos-hoy">-</div>
                <div class="etiqueta">Usos del sistema hoy</div>
            </div>
        </div>

        <div class="tarjeta">
            <h2>Todos los buses</h2>
            <div class="tabla-panel-scroll">
                <table class="tabla-panel">
                    <thead><tr><th>Bus</th><th>Ruta</th><th>Estado</th></tr></thead>
                    <tbody id="cuerpo-tabla-buses"></tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=utilidades.js"></script>
    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=perfil.js"></script>
    <script>
        const URL_BASE = "<?php echo URL_BASE; ?>";

        async function cargarPanel() {
            const resultado = await llamarApi(URL_BASE + "/auditor_panel_datos.php", {});
            document.getElementById("total-registrados").textContent = resultado.total_registrados;
            document.getElementById("usos-hoy").textContent = resultado.usos_hoy;

            const cuerpo = document.getElementById("cuerpo-tabla-buses");
            cuerpo.innerHTML = "";
            resultado.buses.forEach(function (bus) {
                const etiqueta = bus.activo == 1
                    ? "<span class='etiqueta-activo'>Activo</span>"
                    : "<span class='etiqueta-inactivo'>Inactivo</span>";
                cuerpo.innerHTML += "<tr><td>" + bus.nombre + "</td><td>" + bus.origen + " - " + bus.destino + "</td><td>" + etiqueta + "</td></tr>";
            });
        }

        document.addEventListener("DOMContentLoaded", cargarPanel);
    </script>
</body>
</html>