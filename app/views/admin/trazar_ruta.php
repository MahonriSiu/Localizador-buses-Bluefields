<?php require_once __DIR__ . '/../../../config/rutas.php'; $paginaActiva = 'trazar-ruta'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiBus - Marcar Puntos de Eventos</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="stylesheet" href="<?php echo URL_BASE; ?>/asset.php?tipo=css&archivo=estilos.css">
</head>
<body class="pagina-mapa-interno">

    <?php require __DIR__ . '/../partials/encabezado_admin.php'; ?>

    <div class="mapa-interno-contenedor">
        <div id="mapa"></div>

        <div class="trazador-panel">
            <div class="trazador-fila">
                <label class="trazador-etiqueta">Evento</label>
                <select id="evento-para-marcar" onchange="cambiarEventoSeleccionado()"></select>
            </div>

            <div class="trazador-fila">
                <label class="trazador-etiqueta">Tipo de punto al tocar el mapa</label>
                <select id="tipo-punto-nuevo">
                    <option value="visible">Visible (lugar con nombre e info)</option>
                    <option value="invisible">Invisible (solo marca el camino)</option>
                </select>
            </div>

            <div id="coordenadas-clic" class="trazador-coords" style="display:none;"></div>

            <div id="mensaje-marcar"></div>
        </div>
    </div>

    <div class="modal-overlay" id="modal-punto-mapa">
        <div class="modal-caja">
            <h2 id="titulo-modal-punto">Nuevo punto</h2>
            <input type="hidden" id="modal-punto-id" value="">
            <input type="hidden" id="modal-punto-lat" value="">
            <input type="hidden" id="modal-punto-lng" value="">

            <div class="campo-formulario" id="bloque-nombre-modal">
                <label>Nombre del lugar</label>
                <input type="text" id="modal-punto-nombre" placeholder="Ej: Parque Reyes">
            </div>
            <div class="campo-formulario" id="bloque-descripcion-modal">
                <label>Descripcion</label>
                <textarea id="modal-punto-descripcion" rows="4" style="width:100%; padding:10px 12px; border:1px solid #d9cdb8; border-radius:6px; font-family: var(--fuente-cuerpo); font-size:16px; resize: vertical;"></textarea>
            </div>
            <div class="campo-formulario">
                <label>Orden en el recorrido</label>
                <input type="number" id="modal-punto-orden" value="1">
            </div>
            <div class="campo-formulario">
                <label><input type="checkbox" id="modal-punto-visible" checked onchange="alternarCamposVisibilidadModal()"> Punto visible (con nombre e info para el usuario)</label>
            </div>

            <button class="boton boton-primario boton-bloque" onclick="guardarPuntoDesdeMapa()">Guardar punto</button>
            <button class="boton boton-secundario boton-bloque" style="margin-top: 8px;" onclick="cerrarModalPuntoMapa()">Cancelar</button>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=utilidades.js"></script>
    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=perfil.js"></script>
    <script>
        const URL_BASE = "<?php echo URL_BASE; ?>";
        let mapa;
        let marcadoresEnMapa = [];
        let lineaConexion = null;
        let decoradorFlechasEvento = null;

        function iniciarMapa() {
            mapa = L.map("mapa").setView([11.9986, -83.7574], 14);
            L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", { attribution: "OpenStreetMap", maxZoom: 19 }).addTo(mapa);
            setTimeout(function () { mapa.invalidateSize(); }, 200);

            mapa.on("click", function (e) {
                abrirModalPuntoNuevo(e.latlng.lat, e.latlng.lng);
            });

            mapa.on("mousemove", function (e) {
                const caja = document.getElementById("coordenadas-clic");
                caja.style.display = "block";
                caja.textContent = "Lat: " + e.latlng.lat.toFixed(6) + " | Lng: " + e.latlng.lng.toFixed(6);
            });

            cargarEventosSelector();
        }

        async function cargarEventosSelector() {
            const resultado = await llamarApi(URL_BASE + "/admin_obtener_eventos.php", {});
            const selector = document.getElementById("evento-para-marcar");
            selector.innerHTML = "";

            (resultado.eventos || []).forEach(function (evento) {
                const opcion = document.createElement("option");
                opcion.value = evento.id;
                opcion.textContent = evento.nombre;
                opcion.dataset.color = evento.color;
                selector.appendChild(opcion);
            });

            if (resultado.eventos && resultado.eventos.length > 0) cargarPuntosDelEventoEnMapa();
        }

        function cambiarEventoSeleccionado() {
            cargarPuntosDelEventoEnMapa();
        }

        function colorEventoActual() {
            const selector = document.getElementById("evento-para-marcar");
            const opcion = selector.options[selector.selectedIndex];
            return opcion ? opcion.dataset.color : "#1a73e8";
        }

        function iconoPuntoVisible(color) {
            return L.divIcon({
                className: "icono-punto-creativo",
                html: "<svg width='24' height='30' viewBox='0 0 24 30' xmlns='http://www.w3.org/2000/svg'>" +
                    "<path d='M12 0C5.4 0 0 5.4 0 12c0 9 12 18 12 18s12-9 12-18C24 5.4 18.6 0 12 0z' fill='" + color + "'/>" +
                    "<circle cx='12' cy='12' r='5.2' fill='#ffffff'/></svg>",
                iconSize: [24, 30],
                iconAnchor: [12, 30]
            });
        }

        function iconoPuntoInvisibleAdmin(color) {
            return L.divIcon({
                className: "icono-punto-invisible-admin",
                html: "<div style='width:12px;height:12px;border-radius:50%;background:" + color + ";opacity:0.55;border:2px dashed #fff;'></div>",
                iconSize: [12, 12],
                iconAnchor: [6, 6]
            });
        }

        async function cargarPuntosDelEventoEnMapa() {
            marcadoresEnMapa.forEach(function (m) { mapa.removeLayer(m); });
            marcadoresEnMapa = [];
            if (lineaConexion) { mapa.removeLayer(lineaConexion); lineaConexion = null; }
            if (decoradorFlechasEvento) { mapa.removeLayer(decoradorFlechasEvento); decoradorFlechasEvento = null; }

            const eventoId = document.getElementById("evento-para-marcar").value;
            if (!eventoId) return;

            const respuesta = await fetch(URL_BASE + "/admin_obtener_puntos_creativos.php?evento_id=" + eventoId);
            const resultado = await respuesta.json();
            const color = colorEventoActual();

            (resultado.puntos || []).forEach(function (p) {
                const icono = p.visible == 1 ? iconoPuntoVisible(color) : iconoPuntoInvisibleAdmin(color);
                const marcador = L.marker([p.lat, p.lng], { icon: icono })
                    .addTo(mapa)
                    .bindPopup((p.visible == 1 ? "📍 " + p.nombre : "🧭 punto de ruta (orden " + p.orden + ")") + "<br><button onclick='editarPuntoDesdeMapa(" + p.id + ")' style=\"margin-top:4px;\">Editar</button>");
                marcadoresEnMapa.push(marcador);
            });

            const puntosRuta = (resultado.puntos || []).filter(function (p) { return p.visible == 0; });

            if (puntosRuta.length >= 2) {
                const coords = puntosRuta
                    .sort(function (a, b) { return a.orden - b.orden; })
                    .map(function (p) { return [p.lat, p.lng]; });

                lineaConexion = L.polyline(coords, { color: color, weight: 4, opacity: 0.7, dashArray: "6,6" }).addTo(mapa);

                if (typeof L.polylineDecorator === "function") {
                    decoradorFlechasEvento = L.polylineDecorator(lineaConexion, {
                        patterns: [{ offset: "6%", repeat: "12%", symbol: L.Symbol.arrowHead({ pixelSize: 9, polygon: false, pathOptions: { stroke: true, color: color, weight: 2 } }) }]
                    }).addTo(mapa);
                }
            }

            if (marcadoresEnMapa.length > 0) {
                const grupo = L.featureGroup(marcadoresEnMapa);
                mapa.fitBounds(grupo.getBounds().pad(0.2));
            }
        }
        function abrirModalPuntoNuevo(lat, lng) {
            document.getElementById("modal-punto-id").value = "";
            document.getElementById("modal-punto-lat").value = lat;
            document.getElementById("modal-punto-lng").value = lng;
            document.getElementById("modal-punto-nombre").value = "";
            document.getElementById("modal-punto-descripcion").value = "";
            document.getElementById("modal-punto-orden").value = marcadoresEnMapa.length + 1;

            const tipoElegido = document.getElementById("tipo-punto-nuevo").value;
            document.getElementById("modal-punto-visible").checked = tipoElegido === "visible";

            document.getElementById("titulo-modal-punto").textContent = "Nuevo punto (" + lat.toFixed(5) + ", " + lng.toFixed(5) + ")";
            alternarCamposVisibilidadModal();
            document.getElementById("modal-punto-mapa").classList.add("visible");
        }

        async function editarPuntoDesdeMapa(id) {
            const respuesta = await fetch(URL_BASE + "/admin_obtener_punto_creativo.php?id=" + id);
            const resultado = await respuesta.json();
            const punto = resultado.punto;
            if (!punto) return;

            document.getElementById("modal-punto-id").value = punto.id;
            document.getElementById("modal-punto-lat").value = punto.lat;
            document.getElementById("modal-punto-lng").value = punto.lng;
            document.getElementById("modal-punto-nombre").value = punto.nombre;
            document.getElementById("modal-punto-descripcion").value = punto.descripcion;
            document.getElementById("modal-punto-orden").value = punto.orden;
            document.getElementById("modal-punto-visible").checked = punto.visible == 1;

            document.getElementById("titulo-modal-punto").textContent = "Editar: " + punto.nombre;
            alternarCamposVisibilidadModal();
            document.getElementById("modal-punto-mapa").classList.add("visible");
        }

        function alternarCamposVisibilidadModal() {
            const visible = document.getElementById("modal-punto-visible").checked;
            document.getElementById("bloque-nombre-modal").style.display = visible ? "block" : "none";
            document.getElementById("bloque-descripcion-modal").style.display = visible ? "block" : "none";
        }

        function cerrarModalPuntoMapa() {
            document.getElementById("modal-punto-mapa").classList.remove("visible");
        }

        async function guardarPuntoDesdeMapa() {
            const eventoId = document.getElementById("evento-para-marcar").value;
            if (!eventoId) { mostrarMensaje("mensaje-marcar", "Selecciona un evento primero", true); return; }

            const id = document.getElementById("modal-punto-id").value;
            const lat = document.getElementById("modal-punto-lat").value;
            const lng = document.getElementById("modal-punto-lng").value;
            const nombre = document.getElementById("modal-punto-nombre").value;
            const descripcion = document.getElementById("modal-punto-descripcion").value;
            const orden = document.getElementById("modal-punto-orden").value;
            const visible = document.getElementById("modal-punto-visible").checked ? 1 : 0;

            const endpoint = id ? "/admin_actualizar_punto_creativo.php" : "/admin_crear_punto_creativo.php";
            const datos = { evento_id: eventoId, nombre, descripcion, lat, lng, orden, visible };
            if (id) datos.id = id;

            const resultado = await llamarApi(URL_BASE + endpoint, datos);

            if (resultado.exito) {
                mostrarMensaje("mensaje-marcar", "Punto guardado", false);
                cerrarModalPuntoMapa();
                cargarPuntosDelEventoEnMapa();
            } else {
                mostrarMensaje("mensaje-marcar", resultado.mensaje, true);
            }
        }

        document.addEventListener("DOMContentLoaded", iniciarMapa);
    </script>
</body>
</html>