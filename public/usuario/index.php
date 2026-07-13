<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiBus - Localizador de Buses Bluefields</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        #map {
            height: 100vh;
            width: 100%;
        }
        #selector {
            position: absolute;
            top: 10px;
            left: 50px;
            z-index: 1000;
            background-color: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0,0,0,0.3);
        }
        select {
            padding: 5px;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <div id="selector">
        <label for="rutaSeleccionada">Ruta: </label>
        <select id="rutaSeleccionada" onchange="cambiarRuta()">
            <option value="">Cargando rutas...</option>
        </select>
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
                    } else {
                        console.log("No se encontro bus para esta ruta");
                    }
                });
        }

        cargarRutas();
        actualizarPosicionBus();

        setInterval(actualizarPosicionBus, 5000);
    </script>

</body>
</html>