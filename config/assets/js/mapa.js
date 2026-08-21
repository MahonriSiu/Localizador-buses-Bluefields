let mapa;
let marcadorBus = null;
let marcadoresParadas = [];
let busSeleccionado = null;
let posicionUsuario = null;
let paradasNotificadas = new Set();

const URL_BASE = "/public";

function iniciarMapa() {
    mapa = L.map("mapa").setView([11.9986, -83.7574], 14);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "OpenStreetMap",
        maxZoom: 19
    }).addTo(mapa);

    setTimeout(function () {
        mapa.invalidateSize();
    }, 200);

    window.addEventListener("resize", function () {
        mapa.invalidateSize();
    });

    cargarBuses();
    seguirUbicacionUsuario();
}

async function cargarBuses() {
    const respuesta = await fetch(URL_BASE + "/obtener_buses.php");
    const buses = await respuesta.json();

    if (!buses || buses.length === 0) {
        document.getElementById("mapa").style.display = "none";
        document.getElementById("sin-rutas").style.display = "flex";
        return;
    }

    const selector = document.getElementById("selector-ruta");
    buses.forEach(function (bus) {
        const opcion = document.createElement("option");
        opcion.value = bus.id;
        opcion.textContent = bus.nombre + " (" + bus.origen + " - " + bus.destino + ")";
        selector.appendChild(opcion);
    });

    selector.addEventListener("change", function () {
        busSeleccionado = selector.value;
        if (busSeleccionado) {
            cargarParadas(busSeleccionado);
            actualizarBus();
        }
    });
}

async function cargarParadas(busId) {
    marcadoresParadas.forEach(function (m) { mapa.removeLayer(m); });
    marcadoresParadas = [];

    const respuesta = await fetch(URL_BASE + "/obtener_paradas.php?bus_id=" + busId);
    const paradas = await respuesta.json();

    paradas.forEach(function (parada) {
        const iconoParada = L.divIcon({
            className: "icono-parada-mapa",
            html: "<div class='pin-parada'>🚏</div>",
            iconSize: [30, 30],
            iconAnchor: [15, 15]
        });

        const marcador = L.marker([parada.lat, parada.lng], { icon: iconoParada })
            .addTo(mapa)
            .bindPopup(parada.nombre);
        marcadoresParadas.push(marcador);
    });

    window.paradasActuales = paradas;
}

async function actualizarBus() {
    if (!busSeleccionado) return;

    const respuesta = await fetch(URL_BASE + "/obtener_bus.php?id=" + busSeleccionado);
    const datos = await respuesta.json();

    const contenedorMapa = document.getElementById("mapa");
    const panelReposo = document.getElementById("panel-reposo");

    if (datos.en_reposo) {
        contenedorMapa.style.display = "none";
        panelReposo.style.display = "flex";
        return;
    }

    contenedorMapa.style.display = "block";
    panelReposo.style.display = "none";

    const bus = datos.bus;
    if (!bus || !bus.lat || !bus.lng) return;

    window.busActual = bus;

    const iconoBus = L.divIcon({
        className: "icono-bus-mapa",
        html: "<div class='pin-bus'>🚌</div>",
        iconSize: [38, 38],
        iconAnchor: [19, 19]
    });

    if (marcadorBus) {
        marcadorBus.setLatLng([bus.lat, bus.lng]);
    } else {
        marcadorBus = L.marker([bus.lat, bus.lng], { icon: iconoBus }).addTo(mapa);
    }
}

function seguirUbicacionUsuario() {
    if (!navigator.geolocation) return;

    navigator.geolocation.watchPosition(function (posicion) {
        posicionUsuario = {
            lat: posicion.coords.latitude,
            lng: posicion.coords.longitude
        };
        revisarCercaniaParadas();
    });
}

function revisarCercaniaParadas() {
    if (!posicionUsuario || !window.paradasActuales) return;

    window.paradasActuales.forEach(function (parada) {
        const distancia = calcularDistanciaMetros(
            posicionUsuario.lat, posicionUsuario.lng,
            parseFloat(parada.lat), parseFloat(parada.lng)
        );

        if (distancia <= 15 && !paradasNotificadas.has(parada.id)) {
            paradasNotificadas.add(parada.id);
            avisarBusCercaDeParada(parada);
        }

        if (distancia > 15) {
            paradasNotificadas.delete(parada.id);
        }
    });
}

async function avisarBusCercaDeParada(parada) {
    if (!window.busActual) {
        mostrarNotificacionParada(parada.nombre, null, null);
        return;
    }

    const respuesta = await fetch(URL_BASE + "/obtener_estimacion.php?bus_id=" + window.busActual.id + "&parada_id=" + parada.id);
    const estimacion = await respuesta.json();

    if (estimacion.exito) {
        mostrarNotificacionParada(parada.nombre, estimacion.minutos_estimados, estimacion.distancia_km);
    } else {
        mostrarNotificacionParada(parada.nombre, null, null);
    }
}

function mostrarNotificacionParada(nombreParada, minutos, distanciaKm) {
    let texto = "Bus cerca de " + nombreParada;
    if (minutos !== null) {
        texto += " - Llega en aproximadamente " + minutos + " min (" + distanciaKm + " km)";
    }

    if (Notification.permission === "granted") {
        new Notification(texto);
    } else {
        alert(texto);
    }
}

function activarNotificaciones() {
    const boton = document.getElementById("boton-notificaciones");

    if (!("Notification" in window)) {
        alert("Este navegador no soporta notificaciones. Los avisos se mostraran como alertas normales.");
        return;
    }

    Notification.requestPermission().then(function (permiso) {
        if (permiso === "granted") {
            boton.textContent = "Avisos activados";
            boton.classList.add("activado");
        } else {
            boton.textContent = "Notificaciones bloqueadas";
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {
    iniciarMapa();
    setInterval(actualizarBus, 5000);
});