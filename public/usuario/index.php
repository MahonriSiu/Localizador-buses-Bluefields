<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiBus - Localizador de Buses Bluefields</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="../asset.php?tipo=css&archivo=estilos.css" />
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
    <script src="../asset.php?tipo=js&archivo=utilidades.js"></script>
    <script src="../asset.php?tipo=js&archivo=mapa.js"></script>

</body>
</html>