const rutaApi = "/Localizador-buses-Bluefields/public";

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
    fetch(rutaApi + '/obtener_rutas.php')
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
    fetch(rutaApi + '/obtener_bus_por_ruta.php?ruta_id=' + rutaActual)
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
                document.getElementById('info-distancia').innerText = 'No hay bus activo en esta ruta';
            }
        });
}

obtenerPosicionUsuario();
cargarRutas();
actualizarPosicionBus();

setInterval(actualizarPosicionBus, 5000);