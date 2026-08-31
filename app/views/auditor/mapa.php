<?php require_once __DIR__ . '/../../../config/rutas.php'; $paginaActiva = 'mapa'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiBus - Mapa Auditor</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="stylesheet" href="<?php echo URL_BASE; ?>/asset.php?tipo=css&archivo=estilos.css">
</head>
<body class="pagina-mapa-interno">

    <?php require __DIR__ . '/../partials/encabezado_auditor.php'; ?>

    <div class="mapa-interno-contenedor">
        <div id="mapa"></div>
        <div class="mapa-interno-leyenda" id="leyenda-mapa">
            <span class="pulso-vivo"></span> Cargando buses...
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=utilidades.js"></script>
    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=perfil.js"></script>
    <script>
        const URL_BASE = "<?php echo URL_BASE; ?>";
        let mapa;
        let marcadores = {};

        function iniciarMapa() {
            mapa = L.map("mapa").setView([11.9986, -83.7574], 14);
            L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                attribution: "OpenStreetMap", maxZoom: 19
            }).addTo(mapa);
            setTimeout(function () { mapa.invalidateSize(); }, 200);
            actualizarBuses();
        }

        async function actualizarBuses() {
            const resultado = await llamarApi(URL_BASE + "/auditor_obtener_buses.php", {});
            if (!resultado.buses) return;

            let transmitiendo = 0;
            const iconoBus = L.divIcon({
                className: "icono-bus-mapa",
                html: "<div class='pin-bus'>🚌</div>",
                iconSize: [38, 38],
                iconAnchor: [19, 19]
            });

            resultado.buses.forEach(function (bus) {
                if (bus.activo == 1) transmitiendo++;
                if (!bus.lat || !bus.lng) return;

                const popup = "<b>" + bus.nombre + "</b><br>" +
                    bus.origen + " - " + bus.destino +
                    (bus.descripcion ? "<br>" + bus.descripcion : "") +
                    (bus.activo == 1 ? "<br><span style='color:#146c43;font-weight:600;'>Transmitiendo</span>" : "<br><span style='color:#6b6255;'>Sin transmitir</span>");

                if (marcadores[bus.id]) {
                    marcadores[bus.id].setLatLng([bus.lat, bus.lng]);
                    marcadores[bus.id].setPopupContent(popup);
                } else {
                    marcadores[bus.id] = L.marker([bus.lat, bus.lng], { icon: iconoBus })
                        .addTo(mapa)
                        .bindPopup(popup);
                }
            });

            document.getElementById("leyenda-mapa").innerHTML =
                "<span class='pulso-vivo'></span> " + resultado.buses.length + " buses - " + transmitiendo + " transmitiendo ahora";
        }

        document.addEventListener("DOMContentLoaded", function () {
            iniciarMapa();
            setInterval(actualizarBuses, 5000);
        });
    </script>
</body>
</html>