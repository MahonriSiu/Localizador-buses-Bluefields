<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiBus - Localizador de Buses Bluefields</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="../css/estilos.css" />
</head>
<body>

    <div id="selector">
        <label for="rutaSeleccionada">Ruta: </label>
        <select id="rutaSeleccionada" onchange="cambiarRuta()">
            <option value="">Cargando rutas...</option>
        </select>
    </div>

    <div id="info-distancia">
        Calculando distancia...
    </div>

    <div id="map"></div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        const map = L.map('map').setView([12.028487, -83.770011], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'OpenStreetMap'
        }).addTo(map);

        const busIcon = L.divIcon({
            className: 'bus-icon',
            html: '<div style="background-color:#1565C0; width:20px; height:20px; border-radius:50%; border:2px solid white; box-shadow: 0 0 4px rgba(0,0,0,0.5);"></div>',
            iconSize: [20, 20]
        });

        let marcadorBus = null;
        let rutaActual = 1;
        let primeraVezCargando = true;
        let posicionUsuario = null;

        function calcularDistanciaKm(lat1, lng1, lat2, lng2) {
            const radioTierra = 6371;
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLng = (lng2 - lng1) * Math.PI / 180;
            const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                      Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                      Math.sin(dLng/2) * Math.sin(dLng/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            return radioTierra * c;
        }

        function obtenerPosicionUsuario() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(posicion) {
                    posicionUsuario = {
                        lat: posicion.coords.latitude,
                        lng: posicion.coords.longitude
                    };
                });
            }
        }

        function cargarRutas() {
            fetch('../obtener_rutas.php')
                .then(respuesta => respuesta.json())
                .then(rutas => {
                    const select = document.getElementById('rutaSeleccionada');
                    select.innerHTML = '';
                    rutas.forEach(ruta => {
                        const opcion = document.createElement('option');
                        opcion.value = ruta.id;
                        opcion.textContent = ruta.nombre;
                        select.appendChild(opcion);
                    });
                });
        }

        function cambiarRuta() {
            rutaActual = document.getElementById('rutaSeleccionada').value;
            primeraVezCargando = true;
            actualizarPosicionBus();
        }

        function actualizarPosicionBus() {
            fetch('../obtener_bus_por_ruta.php?ruta_id=' + rutaActual)
                .then(respuesta => respuesta.json())
                .then(datos => {
                    if (datos.lat && datos.lng) {
                        const posicion = [parseFloat(datos.lat), parseFloat(datos.lng)];

                        if (marcadorBus !== null) {
                            map.removeLayer(marcadorBus);
                        }

                        marcadorBus = L.marker(posicion, { icon: busIcon }).addTo(map);

                        if (primeraVezCargando) {
                            map.setView(posicion, 15);
                            primeraVezCargando = false;
                        }

                        if (posicionUsuario !== null) {
                            const distancia = calcularDistanciaKm(
                                posicionUsuario.lat, posicionUsuario.lng,
                                posicion[0], posicion[1]
                            );
                            document.getElementById('info-distancia').innerText =
                                'Distancia al bus: ' + distancia.toFixed(1) + ' km';
                        } else {
                            document.getElementById('info-distancia').innerText =
                                'Activa tu ubicacion para ver distancia';
                        }
                    } else {
                        console.log("No se encontro bus para esta ruta");
                    }
                });
        }

        obtenerPosicionUsuario();
        cargarRutas();
        actualizarPosicionBus();

        setInterval(actualizarPosicionBus, 5000);
    </script>

</body>
</html>