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