<?php require_once __DIR__ . '/../../../config/rutas.php'; $paginaActiva = 'eventos'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiBus - Eventos y Circuitos</title>
    <link rel="stylesheet" href="<?php echo URL_BASE; ?>/asset.php?tipo=css&archivo=estilos.css">
</head>
<body>

    <?php require __DIR__ . '/../partials/encabezado_admin.php'; ?>

    <div class="contenedor">
        <div class="tarjeta">
            <h2 id="titulo-form-evento">Crear evento o circuito</h2>
            <div id="mensaje-evento"></div>
            <input type="hidden" id="id-evento-editando" value="">
            <div class="campo-formulario">
                <label>Nombre del evento</label>
                <input type="text" id="nombre-evento" placeholder="Ej: Marcha del 19 de julio">
            </div>
            <div class="campo-formulario">
                <label>Color en el mapa</label>
                <input type="color" id="color-evento" value="#1a73e8" style="height: 42px; padding: 4px;">
            </div>
            <div class="campo-formulario">
                <label><input type="checkbox" id="siempre-activo-evento"> Este evento es permanente</label>
            </div>
            <div id="bloque-fechas-evento">
                <div class="campo-formulario">
                    <label>Desde</label>
                    <input type="date" id="fecha-inicio-evento">
                </div>
                <div class="campo-formulario">
                    <label>Hasta</label>
                    <input type="date" id="fecha-fin-evento">
                </div>
            </div>
            <button class="boton boton-primario" id="boton-guardar-evento" onclick="guardarEvento()">Crear evento</button>
            <button class="boton boton-secundario" id="boton-cancelar-edicion" onclick="cancelarEdicionEvento()" style="display:none;">Cancelar edicion</button>
        </div>

        <div class="tarjeta">
            <h2>Eventos existentes</h2>
            <div class="tabla-panel-scroll">
                <table class="tabla-panel">
                    <thead><tr><th>Nombre</th><th>Vigencia</th><th>Pines</th><th>Puntos de ruta</th><th>Estado</th><th>Accion</th></tr></thead>
                    <tbody id="cuerpo-tabla-eventos"></tbody>
                </table>
            </div>
        </div>

        <div class="tarjeta">
            <h2>📍 Pines (lugares visibles, sin conexion entre ellos)</h2>
            <p style="font-size: 13px; color: #6b6255; margin-bottom: 12px;">
                Cada pin es un lugar independiente con nombre, descripcion e imagenes. Los pines nunca se conectan entre si con una linea.
            </p>
            <div class="campo-formulario">
                <label>Evento</label>
                <select id="evento-para-pines" onchange="cargarPinesYPuntos()"></select>
            </div>

            <div id="mensaje-pin"></div>
            <input type="hidden" id="id-pin-editando" value="">
            <div class="campo-formulario">
                <label>Nombre del lugar</label>
                <input type="text" id="nombre-pin" placeholder="Ej: Parque Reyes">
            </div>
            <div class="campo-formulario">
                <label>Descripcion</label>
                <textarea id="descripcion-pin" rows="3" style="width:100%; padding:10px 12px; border:1px solid #d9cdb8; border-radius:6px; font-family: var(--fuente-cuerpo); font-size:16px; resize: vertical;"></textarea>
            </div>
            <div class="campo-formulario">
                <label>Latitud</label>
                <input type="text" id="lat-pin" placeholder="Ej: 11.9986">
            </div>
            <div class="campo-formulario">
                <label>Longitud</label>
                <input type="text" id="lng-pin" placeholder="Ej: -83.7574">
            </div>
            <button class="boton boton-primario" id="boton-guardar-pin" onclick="guardarPin()">Agregar pin</button>
            <button class="boton boton-secundario" id="boton-cancelar-pin" onclick="cancelarEdicionPin()" style="display:none;">Cancelar edicion</button>

            <div class="tabla-panel-scroll" style="margin-top: 16px;">
                <table class="tabla-panel">
                    <thead><tr><th>Nombre</th><th>Imagenes</th><th>Accion</th></tr></thead>
                    <tbody id="cuerpo-tabla-pines"></tbody>
                </table>
            </div>
        </div>

        <div class="tarjeta">
            <h2>🧭 Puntos de ruta (invisibles, forman la linea del camino)</h2>
            <p style="font-size: 13px; color: #6b6255; margin-bottom: 12px;">
                Estos puntos no se ven como pin en el mapa publico: solo se usan para trazar el camino con flechas. No llevan imagenes ni descripcion visible.
            </p>
            <div id="mensaje-punto-ruta"></div>
            <input type="hidden" id="id-punto-ruta-editando" value="">
            <div class="campo-formulario">
                <label>Latitud</label>
                <input type="text" id="lat-punto-ruta" placeholder="Ej: 11.9986">
            </div>
            <div class="campo-formulario">
                <label>Longitud</label>
                <input type="text" id="lng-punto-ruta" placeholder="Ej: -83.7574">
            </div>
            <div class="campo-formulario">
                <label>Orden en el camino (1, 2, 3...)</label>
                <input type="number" id="orden-punto-ruta" value="1">
            </div>
            <button class="boton boton-primario" id="boton-guardar-punto-ruta" onclick="guardarPuntoRuta()">Agregar punto de ruta</button>
            <button class="boton boton-secundario" id="boton-cancelar-punto-ruta" onclick="cancelarEdicionPuntoRuta()" style="display:none;">Cancelar edicion</button>

            <div class="tabla-panel-scroll" style="margin-top: 16px;">
                <table class="tabla-panel">
                    <thead><tr><th>Orden</th><th>Coordenadas</th><th>Accion</th></tr></thead>
                    <tbody id="cuerpo-tabla-puntos-ruta"></tbody>
                </table>
            </div>
        </div>

        <div class="tarjeta">
            <h2>Imagenes de un pin</h2>
            <p style="font-size: 13px; color: #6b6255; margin-bottom: 12px;">
                Solo los pines (lugares visibles) pueden llevar imagenes.
            </p>
            <div id="mensaje-imagen"></div>
            <div class="campo-formulario">
                <label>Pin</label>
                <select id="punto-para-imagen"></select>
            </div>
            <div class="campo-formulario">
                <label>Imagen de portada</label>
                <input type="file" id="archivo-portada-punto" accept="image/jpeg,image/png">
                <button class="boton boton-secundario" style="margin-top: 8px;" onclick="subirPortadaPunto()">Subir portada</button>
            </div>
            <div class="campo-formulario" style="margin-top: 16px;">
                <label>Imagen adicional para la galeria</label>
                <input type="file" id="archivo-imagen-punto" accept="image/jpeg,image/png">
                <button class="boton boton-secundario" style="margin-top: 8px;" onclick="subirImagenPunto()">Subir a galeria</button>
            </div>
            <div id="galeria-punto-seleccionado" style="margin-top: 16px; display: flex; flex-wrap: wrap; gap: 10px;"></div>
        </div>
    </div>

    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=utilidades.js"></script>
    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=perfil.js"></script>
    <script>
        const URL_BASE = "<?php echo URL_BASE; ?>";

        document.getElementById("siempre-activo-evento").addEventListener("change", function () {
            document.getElementById("bloque-fechas-evento").style.display = this.checked ? "none" : "block";
        });

        async function guardarEvento() {
            const id = document.getElementById("id-evento-editando").value;
            const nombre = document.getElementById("nombre-evento").value;
            const color = document.getElementById("color-evento").value;
            const siempreActivo = document.getElementById("siempre-activo-evento").checked ? 1 : 0;
            const fechaInicio = document.getElementById("fecha-inicio-evento").value;
            const fechaFin = document.getElementById("fecha-fin-evento").value;

            const endpoint = id ? "/admin_actualizar_evento.php" : "/admin_crear_evento.php";
            const datos = { nombre, color, siempre_activo: siempreActivo, fecha_inicio: fechaInicio, fecha_fin: fechaFin };
            if (id) datos.id = id;

            const resultado = await llamarApi(URL_BASE + endpoint, datos);

            if (resultado.exito) {
                mostrarMensaje("mensaje-evento", id ? "Evento actualizado" : "Evento creado", false);
                cancelarEdicionEvento();
                cargarEventos();
            } else {
                mostrarMensaje("mensaje-evento", resultado.mensaje, true);
            }
        }

        function editarEvento(evento) {
            document.getElementById("id-evento-editando").value = evento.id;
            document.getElementById("nombre-evento").value = evento.nombre;
            document.getElementById("color-evento").value = evento.color;
            document.getElementById("siempre-activo-evento").checked = evento.siempre_activo == 1;
            document.getElementById("bloque-fechas-evento").style.display = evento.siempre_activo == 1 ? "none" : "block";
            document.getElementById("fecha-inicio-evento").value = evento.fecha_inicio || "";
            document.getElementById("fecha-fin-evento").value = evento.fecha_fin || "";
            document.getElementById("titulo-form-evento").textContent = "Editando: " + evento.nombre;
            document.getElementById("boton-guardar-evento").textContent = "Guardar cambios";
            document.getElementById("boton-cancelar-edicion").style.display = "inline-block";
            window.scrollTo({ top: 0, behavior: "smooth" });
        }

        function cancelarEdicionEvento() {
            document.getElementById("id-evento-editando").value = "";
            document.getElementById("nombre-evento").value = "";
            document.getElementById("color-evento").value = "#1a73e8";
            document.getElementById("siempre-activo-evento").checked = false;
            document.getElementById("bloque-fechas-evento").style.display = "block";
            document.getElementById("fecha-inicio-evento").value = "";
            document.getElementById("fecha-fin-evento").value = "";
            document.getElementById("titulo-form-evento").textContent = "Crear evento o circuito";
            document.getElementById("boton-guardar-evento").textContent = "Crear evento";
            document.getElementById("boton-cancelar-edicion").style.display = "none";
        }

        let eventosCache = [];
        let pinesCache = [];
        let puntosRutaCache = [];

        async function cargarEventos() {
            const resultado = await llamarApi(URL_BASE + "/admin_obtener_eventos.php", {});
            eventosCache = resultado.eventos || [];
            const cuerpo = document.getElementById("cuerpo-tabla-eventos");
            const selectorPines = document.getElementById("evento-para-pines");
            cuerpo.innerHTML = "";
            selectorPines.innerHTML = "";

            if (eventosCache.length === 0) {
                cuerpo.innerHTML = "<tr><td colspan='6'>Aun no hay eventos creados</td></tr>";
                return;
            }

            for (const e of eventosCache) {
                const puntosEvento = await obtenerPuntosDeEvento(e.id);
                const totalPines = puntosEvento.filter(function (p) { return p.visible == 1; }).length;
                const totalRuta = puntosEvento.filter(function (p) { return p.visible == 0; }).length;
                const vigencia = e.siempre_activo == 1 ? "Permanente" : (escaparHtml(e.fecha_inicio) + " a " + escaparHtml(e.fecha_fin));
                const etiqueta = e.habilitado == 1 ? "<span class='etiqueta-activo'>Habilitado</span>" : "<span class='etiqueta-inactivo'>Deshabilitado</span>";

                cuerpo.innerHTML += "<tr>" +
                    "<td><span style='display:inline-block;width:10px;height:10px;border-radius:50%;background:" + escaparHtml(e.color) + ";margin-right:6px;'></span>" + escaparHtml(e.nombre) + "</td>" +
                    "<td>" + vigencia + "</td><td>" + totalPines + "</td><td>" + totalRuta + "</td><td>" + etiqueta + "</td>" +
                    "<td><button class='boton boton-secundario' onclick='editarEventoPorId(" + e.id + ")'>Editar</button> " +
                    "<button class='boton boton-secundario' onclick='toggleEvento(" + e.id + ", " + (e.habilitado == 1 ? 0 : 1) + ")'>Cambiar estado</button> " +
                    "<button class='boton boton-peligro' onclick='eliminarEvento(" + e.id + ")'>Eliminar</button></td></tr>";

                const opcion = document.createElement("option");
                opcion.value = e.id;
                opcion.textContent = e.nombre;
                selectorPines.appendChild(opcion);
            }

            if (eventosCache.length > 0) cargarPinesYPuntos();
        }
        function editarEventoPorId(id) {
            const evento = eventosCache.find(function (e) { return e.id == id; });
            if (evento) editarEvento(evento);
        }

        async function toggleEvento(id, nuevoEstado) {
            await llamarApi(URL_BASE + "/admin_toggle_evento.php", { id: id, habilitado: nuevoEstado });
            cargarEventos();
        }

        async function eliminarEvento(id) {
            if (!confirm("Eliminar este evento, sus pines, puntos de ruta e imagenes?")) return;
            await llamarApi(URL_BASE + "/admin_eliminar_evento.php", { id: id });
            cargarEventos();
        }

        async function obtenerPuntosDeEvento(eventoId) {
            const respuesta = await fetch(URL_BASE + "/admin_obtener_puntos_creativos.php?evento_id=" + eventoId);
            const resultado = await respuesta.json();
            return resultado.puntos || [];
        }

        async function cargarPinesYPuntos() {
            const eventoId = document.getElementById("evento-para-pines").value;
            if (!eventoId) return;

            const puntos = await obtenerPuntosDeEvento(eventoId);
            pinesCache = puntos.filter(function (p) { return p.visible == 1; });
            puntosRutaCache = puntos.filter(function (p) { return p.visible == 0; });

            renderizarTablaPines();
            renderizarTablaPuntosRuta();
        }

        function renderizarTablaPines() {
            const cuerpo = document.getElementById("cuerpo-tabla-pines");
            const selectorImagen = document.getElementById("punto-para-imagen");
            cuerpo.innerHTML = "";
            selectorImagen.innerHTML = "";

            if (pinesCache.length === 0) {
                cuerpo.innerHTML = "<tr><td colspan='3'>Este evento aun no tiene pines</td></tr>";
                return;
            }

            pinesCache.forEach(function (p) {
                cuerpo.innerHTML += "<tr><td>" + p.nombre + "</td><td>" + p.total_imagenes + "</td>" +
                    "<td><button class='boton boton-secundario' onclick='editarPin(" + p.id + ")'>Editar</button> " +
                    "<button class='boton boton-peligro' onclick='eliminarPin(" + p.id + ")'>Eliminar</button></td></tr>";

                const opcion = document.createElement("option");
                opcion.value = p.id;
                opcion.textContent = p.nombre;
                selectorImagen.appendChild(opcion);
            });

            if (pinesCache.length > 0) verGaleriaPunto(pinesCache[0].id);
        }

        function renderizarTablaPuntosRuta() {
            const cuerpo = document.getElementById("cuerpo-tabla-puntos-ruta");
            cuerpo.innerHTML = "";

            if (puntosRutaCache.length === 0) {
                cuerpo.innerHTML = "<tr><td colspan='3'>Este evento aun no tiene puntos de ruta</td></tr>";
                return;
            }

            puntosRutaCache
                .sort(function (a, b) { return a.orden - b.orden; })
                .forEach(function (p) {
                    cuerpo.innerHTML += "<tr><td>" + p.orden + "</td><td>" + parseFloat(p.lat).toFixed(5) + ", " + parseFloat(p.lng).toFixed(5) + "</td>" +
                        "<td><button class='boton boton-secundario' onclick='editarPuntoRuta(" + p.id + ")'>Editar</button> " +
                        "<button class='boton boton-peligro' onclick='eliminarPuntoRuta(" + p.id + ")'>Eliminar</button></td></tr>";
                });
        }

        async function guardarPin() {
            const eventoId = document.getElementById("evento-para-pines").value;
            if (!eventoId) { mostrarMensaje("mensaje-pin", "Crea un evento primero", true); return; }

            const id = document.getElementById("id-pin-editando").value;
            const nombre = document.getElementById("nombre-pin").value;
            const descripcion = document.getElementById("descripcion-pin").value;
            const lat = document.getElementById("lat-pin").value;
            const lng = document.getElementById("lng-pin").value;

            const endpoint = id ? "/admin_actualizar_punto_creativo.php" : "/admin_crear_punto_creativo.php";
            const datos = { evento_id: eventoId, nombre, descripcion, lat, lng, orden: 0, visible: 1 };
            if (id) datos.id = id;

            const resultado = await llamarApi(URL_BASE + endpoint, datos);

            if (resultado.exito) {
                mostrarMensaje("mensaje-pin", id ? "Pin actualizado" : "Pin agregado", false);
                cancelarEdicionPin();
                cargarPinesYPuntos();
                cargarEventos();
            } else {
                mostrarMensaje("mensaje-pin", resultado.mensaje, true);
            }
        }

        function editarPin(id) {
            const pin = pinesCache.find(function (p) { return p.id == id; });
            if (!pin) return;

            document.getElementById("id-pin-editando").value = pin.id;
            document.getElementById("nombre-pin").value = pin.nombre;
            document.getElementById("descripcion-pin").value = pin.descripcion;
            document.getElementById("lat-pin").value = pin.lat;
            document.getElementById("lng-pin").value = pin.lng;
            document.getElementById("boton-guardar-pin").textContent = "Guardar cambios";
            document.getElementById("boton-cancelar-pin").style.display = "inline-block";
        }

        function cancelarEdicionPin() {
            document.getElementById("id-pin-editando").value = "";
            document.getElementById("nombre-pin").value = "";
            document.getElementById("descripcion-pin").value = "";
            document.getElementById("lat-pin").value = "";
            document.getElementById("lng-pin").value = "";
            document.getElementById("boton-guardar-pin").textContent = "Agregar pin";
            document.getElementById("boton-cancelar-pin").style.display = "none";
        }

        async function eliminarPin(id) {
            if (!confirm("Eliminar este pin y sus imagenes? Esto no afecta a los puntos de ruta.")) return;
            await llamarApi(URL_BASE + "/admin_eliminar_punto_creativo.php", { id: id });
            cargarPinesYPuntos();
            cargarEventos();
        }

        async function guardarPuntoRuta() {
            const eventoId = document.getElementById("evento-para-pines").value;
            if (!eventoId) { mostrarMensaje("mensaje-punto-ruta", "Crea un evento primero", true); return; }

            const id = document.getElementById("id-punto-ruta-editando").value;
            const lat = document.getElementById("lat-punto-ruta").value;
            const lng = document.getElementById("lng-punto-ruta").value;
            const orden = document.getElementById("orden-punto-ruta").value;

            const endpoint = id ? "/admin_actualizar_punto_creativo.php" : "/admin_crear_punto_creativo.php";
            const datos = { evento_id: eventoId, nombre: "", descripcion: "", lat, lng, orden, visible: 0 };
            if (id) datos.id = id;

            const resultado = await llamarApi(URL_BASE + endpoint, datos);

            if (resultado.exito) {
                mostrarMensaje("mensaje-punto-ruta", id ? "Punto de ruta actualizado" : "Punto de ruta agregado", false);
                cancelarEdicionPuntoRuta();
                cargarPinesYPuntos();
                cargarEventos();
            } else {
                mostrarMensaje("mensaje-punto-ruta", resultado.mensaje, true);
            }
        }

        function editarPuntoRuta(id) {
            const punto = puntosRutaCache.find(function (p) { return p.id == id; });
            if (!punto) return;

            document.getElementById("id-punto-ruta-editando").value = punto.id;
            document.getElementById("lat-punto-ruta").value = punto.lat;
            document.getElementById("lng-punto-ruta").value = punto.lng;
            document.getElementById("orden-punto-ruta").value = punto.orden;
            document.getElementById("boton-guardar-punto-ruta").textContent = "Guardar cambios";
            document.getElementById("boton-cancelar-punto-ruta").style.display = "inline-block";
        }

        function cancelarEdicionPuntoRuta() {
            document.getElementById("id-punto-ruta-editando").value = "";
            document.getElementById("lat-punto-ruta").value = "";
            document.getElementById("lng-punto-ruta").value = "";
            document.getElementById("orden-punto-ruta").value = puntosRutaCache.length + 1;
            document.getElementById("boton-guardar-punto-ruta").textContent = "Agregar punto de ruta";
            document.getElementById("boton-cancelar-punto-ruta").style.display = "none";
        }

        async function eliminarPuntoRuta(id) {
            if (!confirm("Eliminar este punto de ruta? Esto no afecta a los pines.")) return;
            await llamarApi(URL_BASE + "/admin_eliminar_punto_creativo.php", { id: id });
            cargarPinesYPuntos();
            cargarEventos();
        }

        async function subirPortadaPunto() {
            const puntoId = document.getElementById("punto-para-imagen").value;
            const archivo = document.getElementById("archivo-portada-punto").files[0];
            if (!puntoId || !archivo) { mostrarMensaje("mensaje-imagen", "Selecciona pin e imagen", true); return; }

            const formData = new FormData();
            formData.append("punto_id", puntoId);
            formData.append("portada", archivo);

            const respuesta = await fetch(URL_BASE + "/admin_subir_portada_punto.php", { method: "POST", body: formData });
            const resultado = await respuesta.json();

            if (resultado.exito) {
                mostrarMensaje("mensaje-imagen", "Portada actualizada", false);
                document.getElementById("archivo-portada-punto").value = "";
                verGaleriaPunto(puntoId);
            } else {
                mostrarMensaje("mensaje-imagen", resultado.mensaje, true);
            }
        }

        async function subirImagenPunto() {
            const puntoId = document.getElementById("punto-para-imagen").value;
            const archivo = document.getElementById("archivo-imagen-punto").files[0];
            if (!puntoId || !archivo) { mostrarMensaje("mensaje-imagen", "Selecciona pin e imagen", true); return; }

            const formData = new FormData();
            formData.append("punto_id", puntoId);
            formData.append("imagen", archivo);

            const respuesta = await fetch(URL_BASE + "/admin_subir_imagen_punto.php", { method: "POST", body: formData });
            const resultado = await respuesta.json();

            if (resultado.exito) {
                mostrarMensaje("mensaje-imagen", "Imagen agregada", false);
                document.getElementById("archivo-imagen-punto").value = "";
                cargarPinesYPuntos();
                verGaleriaPunto(puntoId);
            } else {
                mostrarMensaje("mensaje-imagen", resultado.mensaje, true);
            }
        }

        async function verGaleriaPunto(puntoId) {
            document.getElementById("punto-para-imagen").value = puntoId;
            const respuesta = await fetch(URL_BASE + "/admin_obtener_imagenes_punto.php?punto_id=" + puntoId);
            const resultado = await respuesta.json();

            const galeria = document.getElementById("galeria-punto-seleccionado");
            galeria.innerHTML = "";

            (resultado.imagenes || []).forEach(function (img) {
                galeria.innerHTML += "<div style='position:relative;'>" +
                    "<img src='" + URL_BASE + "/asset.php?tipo=creativa&archivo=" + img.nombre_archivo + "' style='width:100px; height:100px; object-fit:cover; border-radius:8px;'>" +
                    "<button onclick='eliminarImagenPunto(" + img.id + ", " + puntoId + ")' style='position:absolute; top:-6px; right:-6px; width:22px; height:22px; border-radius:50%; background:#0D0D0D; color:#fff; border:none; font-size:11px; cursor:pointer;'>✕</button>" +
                    "</div>";
            });
        }

        async function eliminarImagenPunto(imagenId, puntoId) {
            await llamarApi(URL_BASE + "/admin_eliminar_imagen_punto.php", { id: imagenId });
            cargarPinesYPuntos();
            verGaleriaPunto(puntoId);
        }

        document.addEventListener("DOMContentLoaded", cargarEventos);
    </script>
</body>
</html>