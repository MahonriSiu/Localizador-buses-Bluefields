<?php require_once __DIR__ . '/../../../config/rutas.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiBus - Panel Propietario</title>
    <link rel="stylesheet" href="<?php echo URL_BASE; ?>/asset.php?tipo=css&archivo=estilos.css">
</head>
<body>

    <div class="encabezado">
        <h1>Mis Buses</h1>
    </div>

    <div class="contenedor">
        <div class="tarjeta">
            <table class="tabla-panel">
                <thead>
                    <tr><th>Bus</th><th>Ruta</th><th>Estado</th><th>Ultima actualizacion</th></tr>
                </thead>
                <tbody id="cuerpo-tabla-buses"></tbody>
            </table>
        </div>
    </div>

    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=utilidades.js"></script>
    <script>
        const URL_BASE = "<?php echo URL_BASE; ?>";

        async function cargarMisBuses() {
            const resultado = await llamarApi(URL_BASE + "/propietario_ver_buses.php", {});
            const cuerpo = document.getElementById("cuerpo-tabla-buses");
            cuerpo.innerHTML = "";

            resultado.buses.forEach(function (bus) {
                const etiqueta = bus.activo == 1
                    ? "<span class='etiqueta-activo'>Transmitiendo</span>"
                    : "<span class='etiqueta-inactivo'>Sin transmitir</span>";

                cuerpo.innerHTML += "<tr>" +
                    "<td>" + bus.nombre + "</td>" +
                    "<td>" + bus.origen + " - " + bus.destino + "</td>" +
                    "<td>" + etiqueta + "</td>" +
                    "<td>" + (bus.timestamp_actualizacion || "Sin datos") + "</td>" +
                    "</tr>";
            });
        }

        document.addEventListener("DOMContentLoaded", cargarMisBuses);
    </script>
</body>
</html>