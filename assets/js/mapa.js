let mapa;
let marcadorBus = null;
let marcadoresParadas = [];
let lineaRecorrido = null;
let decoradorFlechas = null;
let busSeleccionado = null;
let posicionUsuario = null;
let paradasNotificadas = new Set();
let marcadorUsuario = null;
let circuloPrecisionUsuario = null;
let calificacionSeleccionada = 0;
let galeriaActual = { imagenes: [], indice: 0 };
let capasEventos = {};
let eventosDisponibles = [];

const URL_BASE = "/public";

const ICONO_PARADA_SVG =
    "<svg width='30' height='30' viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'>" +
    "<circle cx='15' cy='15' r='13' fill='#ffffff' stroke='#016C80' stroke-width='2.5'/>" +
    "<rect x='9' y='9' width='12' height='9' rx='1.5' fill='#016C80'/>" +
    "<rect x='9' y='9' width='12' height='3.5' rx='1.5' fill='#F27127'/>" +
    "<circle cx='12' cy='20.5' r='1.6' fill='#0D0D0D'/>" +
    "<circle cx='18' cy='20.5' r='1.6' fill='#0D0D0D'/>" +
    "</svg>";

// pin chico, sin estrella, cuyo color se ajusta al color propio de cada evento
function iconoEventoSvg(color) {
    return "<svg width='24' height='30' viewBox='0 0 24 30' xmlns='http://www.w3.org/2000/svg'>" +
        "<path d='M12 0C5.4 0 0 5.4 0 12c0 9 12 18 12 18s12-9 12-18C24 5.4 18.6 0 12 0z' fill='" + color + "'/>" +
        "<circle cx='12' cy='12' r='5.2' fill='#ffffff'/>" +
        "</svg>";
}

function iniciarMapa() {
    const capaCalles = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", { attribution: "OpenStreetMap", maxZoom: 19 });
    const capaSatelital = L.tileLayer("https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}", { attribution: "Esri", maxZoom: 19 });

    mapa = L.map("mapa", { layers: [capaCalles] }).setView([11.9986, -83.7574], 14);

    L.control.layers({ "Calles": capaCalles, "Satelital": capaSatelital }, {}, { position: "bottomleft" }).addTo(mapa);

    setTimeout(function () { mapa.invalidateSize(); }, 200);
    window.addEventListener("resize", function () { mapa.invalidateSize(); });

    cargarBuses();
    cargarEventosDisponibles();
    seguirUbicacionUsuario();
}

async function cargarBuses() {
    const respuesta = await fetch(URL_BASE + "/obtener_buses.php");
    const buses = await respuesta.json();

    if (!buses || buses.length === 0) {
        transicionDom(function () {
            document.getElementById("mapa").style.display = "none";
            document.getElementById("sin-rutas").style.display = "flex";
        });
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
            actualizarBus(true);
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
            html: ICONO_PARADA_SVG,
            iconSize: [30, 30],
            iconAnchor: [15, 15]
        });
        const marcador = L.marker([parada.lat, parada.lng], { icon: iconoParada }).addTo(mapa).bindPopup(parada.nombre);
        marcadoresParadas.push(marcador);
    });

    window.paradasActuales = paradas;
    dibujarRecorrido(busId);
}

// linea del trayecto reciente del bus, con flechas mostrando hacia donde avanza.
// Solo se dibuja cuando hay un bus seleccionado, para no saturar el mapa.
async function dibujarRecorrido(busId) {
    if (lineaRecorrido) {
        mapa.removeLayer(lineaRecorrido);
        lineaRecorrido = null;
    }
    if (decoradorFlechas) {
        mapa.removeLayer(decoradorFlechas);
        decoradorFlechas = null;
    }

    const respuesta = await fetch(URL_BASE + "/obtener_recorrido.php?bus_id=" + busId);
    const resultado = await respuesta.json();

    if (!resultado.puntos || resultado.puntos.length < 2) return;

    const coordenadas = resultado.puntos.map(function (p) { return [p.lat, p.lng]; });
    lineaRecorrido = L.polyline(coordenadas, {
        color: "#F27127",
        weight: 4,
        opacity: 0.75,
        dashArray: resultado.ruta_aprendida ? null : "8, 8"
    }).addTo(mapa);

    if (typeof L.polylineDecorator === "function") {
        decoradorFlechas = L.polylineDecorator(lineaRecorrido, {
            patterns: [
                {
                    offset: "8%",
                    repeat: "12%",
                    symbol: L.Symbol.arrowHead({
                        pixelSize: 10,
                        polygon: false,
                        pathOptions: { stroke: true, color: "#c2530f", weight: 3 }
                    })
                }
            ]
        }).addTo(mapa);
    }
}

async function actualizarBus(centrar) {
    if (!busSeleccionado) return;

    const respuesta = await fetch(URL_BASE + "/obtener_bus.php?id=" + busSeleccionado);
    const datos = await respuesta.json();

    const contenedorMapa = document.getElementById("mapa");
    const panelReposo = document.getElementById("panel-reposo");
    const avisoBus = document.getElementById("aviso-bus");

    if (datos.en_reposo) {
        if (contenedorMapa.style.display !== "none") {
            transicionDom(function () {
                contenedorMapa.style.display = "none";
                panelReposo.style.display = "flex";
            });
        }
        return;
    }

    if (contenedorMapa.style.display === "none") {
        transicionDom(function () {
            contenedorMapa.style.display = "block";
            panelReposo.style.display = "none";
        });
    }

    const bus = datos.bus;
    window.busActual = bus;

    if (!bus || !bus.lat || !bus.lng) {
        if (avisoBus) { avisoBus.textContent = "Este bus aun no ha transmitido su ubicacion."; avisoBus.style.display = "block"; }
        return;
    }

    if (avisoBus) avisoBus.style.display = "none";

    const iconoBus = L.divIcon({ className: "icono-bus-mapa", html: "<div class='pin-bus'>🚌</div>", iconSize: [38, 38], iconAnchor: [19, 19] });

    if (marcadorBus) {
        marcadorBus.setLatLng([bus.lat, bus.lng]);
    } else {
        marcadorBus = L.marker([bus.lat, bus.lng], { icon: iconoBus }).addTo(mapa);
    }
    marcadorBus.bindPopup(bus.nombre + (bus.descripcion ? "<br>" + bus.descripcion : ""));

    if (centrar) {
        mapa.flyTo([bus.lat, bus.lng], 16, { duration: 1.1 });
    }
}

// ============ EVENTOS / CIRCUITOS (panel desplegable, activar/desactivar cada uno) ============

async function cargarEventosDisponibles() {
    const respuesta = await fetch(URL_BASE + "/obtener_eventos_activos.php");
    const resultado = await respuesta.json();

    eventosDisponibles = resultado.eventos || [];
    const contenedor = document.getElementById("lista-eventos-toggle");
    contenedor.innerHTML = "";

    if (eventosDisponibles.length === 0) {
        contenedor.innerHTML = "<p style='font-size:12px; color:#999; padding: 8px 0;'>No hay circuitos activos por ahora.</p>";
        return;
    }

    eventosDisponibles.forEach(function (evento) {
        const fila = document.createElement("label");
        fila.className = "evento-toggle-fila";
        fila.innerHTML =
            "<input type='checkbox' data-evento-id='" + evento.id + "'> " +
            "<span class='evento-color-punto' style='background:" + evento.color + ";'></span> " +
            "<span>" + evento.nombre + (evento.siempre_activo == 1 ? " (permanente)" : "") + "</span>";
        contenedor.appendChild(fila);

        const checkbox = fila.querySelector("input");
        checkbox.addEventListener("change", function () {
            if (checkbox.checked) activarCapaEvento(evento);
            else desactivarCapaEvento(evento.id);
        });

        // la Ruta Creativa (permanente) viene activada de una vez por defecto
        if (evento.siempre_activo == 1) {
            checkbox.checked = true;
            activarCapaEvento(evento);
        }
    });
}

async function activarCapaEvento(evento) {
    if (capasEventos[evento.id]) return;

    const respuesta = await fetch(URL_BASE + "/obtener_ruta_creativa.php?evento_id=" + evento.id);
    const resultado = await respuesta.json();

    if (!resultado.puntos || resultado.puntos.length === 0) return;

    const grupo = L.layerGroup();
    const iconoEvento = L.divIcon({
        className: "icono-punto-creativo",
        html: iconoEventoSvg(evento.color),
        iconSize: [24, 30],
        iconAnchor: [12, 30]
    });

    const pines = resultado.puntos.filter(function (p) { return p.visible == 1; });
    const puntosRuta = resultado.puntos.filter(function (p) { return p.visible == 0; });

    pines.forEach(function (punto) {
        L.marker([punto.lat, punto.lng], { icon: iconoEvento })
            .addTo(grupo)
            .on("click", function () { abrirModalPuntoCreativo(punto, evento); });
    });

    if (puntosRuta.length >= 2) {
        const ordenados = puntosRuta.slice().sort(function (a, b) { return a.orden - b.orden; });
        const coordenadasOsrm = ordenados.map(function (p) { return p.lng + "," + p.lat; }).join(";");
        const url = "https://router.project-osrm.org/route/v1/driving/" + coordenadasOsrm + "?overview=full&geometries=geojson";

        try {
            const respuestaOsrm = await fetch(url);
            const datosOsrm = await respuestaOsrm.json();

            if (datosOsrm.code === "Ok" && datosOsrm.routes && datosOsrm.routes[0]) {
                const coords = datosOsrm.routes[0].geometry.coordinates.map(function (c) { return [c[1], c[0]]; });
                const linea = L.polyline(coords, { color: evento.color, weight: 5, opacity: 0.85 }).addTo(grupo);

                if (typeof L.polylineDecorator === "function") {
                    L.polylineDecorator(linea, {
                        patterns: [{ offset: "6%", repeat: "10%", symbol: L.Symbol.arrowHead({ pixelSize: 9, polygon: false, pathOptions: { stroke: true, color: evento.color, weight: 2 } }) }]
                    }).addTo(grupo);
                }
            } else {
                throw new Error("sin ruta");
            }
        } catch (error) {
            const coordsRectas = ordenados.map(function (p) { return [p.lat, p.lng]; });
            L.polyline(coordsRectas, { color: evento.color, weight: 5, opacity: 0.85, dashArray: "6, 8" }).addTo(grupo);
        }
    }

    grupo.addTo(mapa);
    capasEventos[evento.id] = grupo;
}

function desactivarCapaEvento(eventoId) {
    if (capasEventos[eventoId]) {
        mapa.removeLayer(capasEventos[eventoId]);
        delete capasEventos[eventoId];
    }
}

function toggleListaEventos() {
    document.getElementById("panel-eventos").classList.toggle("visible");
}

function abrirModalPuntoCreativo(punto, evento) {
    document.getElementById("creativo-nombre").textContent = punto.nombre;
    document.getElementById("creativo-descripcion").textContent = punto.descripcion;
    document.getElementById("creativo-etiqueta-evento").textContent = "📍 " + evento.nombre;

    // la portada (una sola) se muestra primero si existe; si no, se usa la galeria
    const imagenesFinal = [];
    if (punto.imagen_portada) imagenesFinal.push({ nombre_archivo: punto.imagen_portada });
    (punto.imagenes || []).forEach(function (img) { imagenesFinal.push(img); });

    galeriaActual = { imagenes: imagenesFinal, indice: 0 };
    renderizarImagenGaleria();

    document.getElementById("modal-punto-creativo").classList.add("visible");
}

function renderizarImagenGaleria() {
    const contenedor = document.getElementById("creativo-imagen-actual");
    const contador = document.getElementById("creativo-contador-imagenes");

    if (galeriaActual.imagenes.length === 0) {
        contenedor.innerHTML = "<div class='creativo-sin-imagen'>🏛️</div>";
        contador.textContent = "";
        return;
    }

    const img = galeriaActual.imagenes[galeriaActual.indice];
    contenedor.innerHTML = "<img src='" + URL_BASE + "/asset.php?tipo=creativa&archivo=" + img.nombre_archivo + "' alt=''>";
    contador.textContent = (galeriaActual.indice + 1) + " / " + galeriaActual.imagenes.length;
}

function galeriaAnterior() {
    if (galeriaActual.imagenes.length === 0) return;
    galeriaActual.indice = (galeriaActual.indice - 1 + galeriaActual.imagenes.length) % galeriaActual.imagenes.length;
    renderizarImagenGaleria();
}

function galeriaSiguiente() {
    if (galeriaActual.imagenes.length === 0) return;
    galeriaActual.indice = (galeriaActual.indice + 1) % galeriaActual.imagenes.length;
    renderizarImagenGaleria();
}

function cerrarModalPuntoCreativo() {
    document.getElementById("modal-punto-creativo").classList.remove("visible");
}

async function mostrarInfoBus() {
    if (!window.busActual) { alert("Selecciona un bus primero"); return; }
    const b = window.busActual;

    document.getElementById("info-bus-nombre").textContent = b.nombre;
    document.getElementById("info-bus-ruta").textContent = b.origen + " - " + b.destino;
    document.getElementById("info-bus-descripcion").textContent = b.descripcion || "Sin descripcion registrada.";

    const filaDistancia = document.getElementById("info-bus-distancia");
    const filaTiempo = document.getElementById("info-bus-tiempo");

    document.getElementById("modal-info-bus").classList.add("visible");

    if (b.lat && b.lng) {
        mapa.flyTo([b.lat, b.lng], 16, { duration: 1 });
    }

    if (!b.lat || !b.lng) {
        filaDistancia.textContent = "El bus aun no ha transmitido su ubicacion";
        filaTiempo.textContent = "-";
        return;
    }

    filaDistancia.textContent = "Buscando tu ubicacion...";
    filaTiempo.textContent = "Buscando tu ubicacion...";

    const posicion = await esperarPosicionUsuario();

    if (!posicion) {
        filaDistancia.textContent = "Activa el permiso de ubicacion en tu navegador para ver la distancia";
        filaTiempo.textContent = "-";
        return;
    }

    filaDistancia.textContent = "Calculando...";
    filaTiempo.textContent = "Calculando...";

    const respuesta = await fetch(URL_BASE + "/obtener_estimacion_usuario.php?bus_id=" + b.id + "&lat=" + posicion.lat + "&lng=" + posicion.lng);
    const estimacion = await respuesta.json();

    if (estimacion.exito) {
        filaDistancia.textContent = estimacion.distancia_m < 1000
            ? estimacion.distancia_m + " metros"
            : estimacion.distancia_km + " km";
        filaTiempo.textContent = "Aproximadamente " + estimacion.minutos_estimados + " min";
    } else {
        filaDistancia.textContent = estimacion.mensaje || "No se pudo calcular";
        filaTiempo.textContent = "-";
    }
}

function esperarPosicionUsuario() {
    return new Promise(function (resolve) {
        if (posicionUsuario) { resolve(posicionUsuario); return; }

        let intentos = 0;
        const intervalo = setInterval(function () {
            intentos++;
            if (posicionUsuario) {
                clearInterval(intervalo);
                resolve(posicionUsuario);
            } else if (intentos >= 8) {
                clearInterval(intervalo);
                resolve(null);
            }
        }, 1000);
    });
}

function cerrarInfoBus() {
    document.getElementById("modal-info-bus").classList.remove("visible");
}

function seguirUbicacionUsuario() {
    if (!navigator.geolocation) return;

    navigator.geolocation.getCurrentPosition(function (posicion) {
        posicionUsuario = { lat: posicion.coords.latitude, lng: posicion.coords.longitude };
        actualizarMarcadorUsuario(posicion.coords.accuracy);
    }, function () { }, { enableHighAccuracy: true, timeout: 8000 });

    navigator.geolocation.watchPosition(function (posicion) {
        posicionUsuario = { lat: posicion.coords.latitude, lng: posicion.coords.longitude };
        actualizarMarcadorUsuario(posicion.coords.accuracy);
        revisarCercaniaParadas();
    }, function () { }, {
        enableHighAccuracy: true,
        maximumAge: 5000
    });
}

function actualizarMarcadorUsuario(precision) {
    if (!mapa || !posicionUsuario) return;
    const punto = [posicionUsuario.lat, posicionUsuario.lng];

    if (!marcadorUsuario) {
        marcadorUsuario = L.circleMarker(punto, {
            radius: 8,
            color: "#ffffff",
            weight: 3,
            fillColor: "#1a73e8",
            fillOpacity: 1
        }).addTo(mapa).bindPopup("Tu ubicacion");

        circuloPrecisionUsuario = L.circle(punto, {
            radius: precision || 30,
            color: "#1a73e8",
            weight: 1,
            fillColor: "#1a73e8",
            fillOpacity: 0.12
        }).addTo(mapa);
    } else {
        marcadorUsuario.setLatLng(punto);
        circuloPrecisionUsuario.setLatLng(punto);
        circuloPrecisionUsuario.setRadius(precision || 30);
    }
}

function centrarEnMiUbicacion() {
    if (!posicionUsuario) {
        alert("Aun no se ha detectado tu ubicacion. Activa el GPS del navegador y dale permiso a MiBus.");
        return;
    }
    mapa.flyTo([posicionUsuario.lat, posicionUsuario.lng], 16, { duration: 1 });
}

function revisarCercaniaParadas() {
    if (!posicionUsuario || !window.paradasActuales) return;
    window.paradasActuales.forEach(function (parada) {
        const distancia = calcularDistanciaMetros(posicionUsuario.lat, posicionUsuario.lng, parseFloat(parada.lat), parseFloat(parada.lng));
        if (distancia <= 15 && !paradasNotificadas.has(parada.id)) { paradasNotificadas.add(parada.id); avisarBusCercaDeParada(parada); }
        if (distancia > 15) paradasNotificadas.delete(parada.id);
    });
}

async function avisarBusCercaDeParada(parada) {
    if (!window.busActual) { mostrarNotificacionParada(parada.nombre, null, null); return; }
    const respuesta = await fetch(URL_BASE + "/obtener_estimacion.php?bus_id=" + window.busActual.id + "&parada_id=" + parada.id);
    const estimacion = await respuesta.json();
    if (estimacion.exito) mostrarNotificacionParada(parada.nombre, estimacion.minutos_estimados, estimacion.distancia_km);
    else mostrarNotificacionParada(parada.nombre, null, null);
}

function mostrarNotificacionParada(nombreParada, minutos, distanciaKm) {
    let texto = "Bus cerca de " + nombreParada;
    if (minutos !== null) texto += " - Llega en aproximadamente " + minutos + " min (" + distanciaKm + " km)";
    if (Notification.permission === "granted") new Notification(texto);
    else alert(texto);
}

function activarNotificaciones() {
    const boton = document.getElementById("boton-notificaciones");
    if (!("Notification" in window)) { alert("Este navegador no soporta notificaciones."); return; }

    if (Notification.permission === "granted") {
        boton.textContent = "Avisos activados";
        boton.classList.add("activado");
        return;
    }

    Notification.requestPermission().then(function (permiso) {
        if (permiso === "granted") { boton.textContent = "Avisos activados"; boton.classList.add("activado"); }
        else boton.textContent = "Notificaciones bloqueadas";
    });
}

function sincronizarEstadoNotificaciones() {
    const boton = document.getElementById("boton-notificaciones");
    if (!boton || !("Notification" in window)) return;

    if (Notification.permission === "granted") {
        boton.textContent = "Avisos activados";
        boton.classList.add("activado");
    } else if (Notification.permission === "denied") {
        boton.textContent = "Notificaciones bloqueadas";
    }
}

function abrirModalResena() {
    document.getElementById("modal-resena").classList.add("visible");
}

function cerrarModalResena() {
    document.getElementById("modal-resena").classList.remove("visible");
}

document.addEventListener("click", function (evento) {
    const estrella = evento.target.closest("#calificacion-estrellas span");
    if (!estrella) return;
    calificacionSeleccionada = parseInt(estrella.getAttribute("data-valor"), 10);
    document.querySelectorAll("#calificacion-estrellas span").forEach(function (s) {
        s.classList.toggle("activa", parseInt(s.getAttribute("data-valor"), 10) <= calificacionSeleccionada);
    });
});

async function enviarResena() {
    const nombre = document.getElementById("resena-nombre").value;
    const comentario = document.getElementById("resena-comentario").value;

    const resultado = await llamarApi(URL_BASE + "/enviar_resena.php", {
        nombre: nombre, comentario: comentario, calificacion: calificacionSeleccionada
    });

    if (resultado.exito) {
        mostrarMensaje("mensaje-resena", "Gracias por tu opinion", false);
        document.getElementById("resena-comentario").value = "";
        document.getElementById("resena-nombre").value = "";
        calificacionSeleccionada = 0;
        document.querySelectorAll("#calificacion-estrellas span").forEach(function (s) { s.classList.remove("activa"); });
        setTimeout(cerrarModalResena, 1200);
    } else {
        mostrarMensaje("mensaje-resena", resultado.mensaje, true);
    }
}

async function dibujarRecorrido(busId) {
    if (lineaRecorrido) { mapa.removeLayer(lineaRecorrido); lineaRecorrido = null; }
    if (decoradorFlechas) { mapa.removeLayer(decoradorFlechas); decoradorFlechas = null; }

    const respuesta = await fetch(URL_BASE + "/obtener_ruta_definida.php?bus_id=" + busId);
    const resultado = await respuesta.json();

    if (!resultado.puntos || resultado.puntos.length < 2) return;

    const color = resultado.color || "#F27127";
    const coordenadas = resultado.puntos.map(function (p) { return [p[0], p[1]]; });
    lineaRecorrido = L.polyline(coordenadas, { color: color, weight: 5, opacity: 0.85 }).addTo(mapa);

    if (typeof L.polylineDecorator === "function") {
        decoradorFlechas = L.polylineDecorator(lineaRecorrido, {
            patterns: [{ offset: "6%", repeat: "10%", symbol: L.Symbol.arrowHead({ pixelSize: 10, polygon: false, pathOptions: { stroke: true, color: color, weight: 3 } }) }]
        }).addTo(mapa);
    }
}

document.addEventListener("DOMContentLoaded", function () {
    iniciarMapa();
    sincronizarEstadoNotificaciones();
    setInterval(function () { actualizarBus(false); }, 5000);
    setInterval(function () { if (busSeleccionado) dibujarRecorrido(busSeleccionado); }, 30000);
});