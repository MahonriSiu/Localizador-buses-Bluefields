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
        .bus-icon {
            font-size: 28px;
        }
    </style>
</head>
<body>

    <div id="map"></div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        const map = L.map('map').setView([11.9938, -83.7566], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        const busIcon = L.divIcon({
            className: 'bus-icon',
            html: '<div style="background-color:#1565C0; width:20px; height:20px; border-radius:50%; border:2px solid white; box-shadow: 0 0 4px rgba(0,0,0,0.5);"></div>',
            iconSize: [20, 20]
        });

        fetch('../obtener_bus.php?id=4')
            .then(respuesta => respuesta.json())
            .then(datos => {
                if (datos.lat && datos.lng) {
                    const posicion = [parseFloat(datos.lat), parseFloat(datos.lng)];
                    L.marker(posicion, { icon: busIcon }).addTo(map);
                    map.setView(posicion, 15);
                } else {
                    console.log("No se encontro el bus");
                }
            });
    </script>

</body>
</html>