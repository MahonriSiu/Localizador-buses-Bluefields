<?php
session_start();

if (!isset($_SESSION['emisor_autenticado'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiBus - Panel Emisor</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }
        .caja {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            width: 300px;
        }
        #estado {
            margin-top: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="caja">
        <h2>MiBus - Transmitiendo</h2>
        <p>Bus en linea, enviando ubicacion</p>
        <p id="estado">Esperando GPS...</p>
    </div>

    <script>
        function enviarUbicacion(lat, lng) {
            const datos = new FormData();
            datos.append('lat', lat);
            datos.append('lng', lng);

            fetch('../emisor_actualizar.php', {
                method: 'POST',
                body: datos
            })
            .then(respuesta => respuesta.json())
            .then(resultado => {
                if (resultado.exito) {
                    document.getElementById('estado').innerText = 'Ubicacion enviada correctamente';
                } else {
                    document.getElementById('estado').innerText = 'Error al enviar ubicacion';
                }
            });
        }

        function obtenerYEnviarUbicacion() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(posicion) {
                    const lat = posicion.coords.latitude;
                    const lng = posicion.coords.longitude;
                    enviarUbicacion(lat, lng);
                }, function(error) {
                    document.getElementById('estado').innerText = 'Error al obtener GPS: ' + error.message;
                });
            } else {
                document.getElementById('estado').innerText = 'Geolocalizacion no soportada';
            }
        }

        obtenerYEnviarUbicacion();
        setInterval(obtenerYEnviarUbicacion, 5000);
    </script>

</body>
</html>