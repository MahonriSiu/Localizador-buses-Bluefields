<?php require_once __DIR__ . '/../../../config/rutas.php'; $paginaActiva = 'anuncios'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiBus - Anuncios Locales</title>
    <link rel="stylesheet" href="<?php echo URL_BASE; ?>/asset.php?tipo=css&archivo=estilos.css">
</head>
<body>

    <?php require __DIR__ . '/../partials/encabezado_admin.php'; ?>

    <div class="contenedor">
        <div class="tarjeta">
            <h2>Agregar anuncio local</h2>
            <p style="font-size: 13px; color: #6b6255; margin-bottom: 12px;">
                Todos los campos son opcionales: puedes anunciar cualquier cosa (pulperia, matricula, evento) sin exigir nombre ni texto. Deja en blanco lo que no aplique.
            </p>
            <div id="mensaje-anuncio"></div>
            <div class="campo-formulario">
                <label>Nombre del negocio o promocion (opcional)</label>
                <input type="text" id="nombre-negocio" placeholder="Ej: Pulperia El Buen Precio, o dejalo vacio">
            </div>
            <div class="campo-formulario">
                <label>Texto del anuncio (opcional)</label>
                <input type="text" id="texto-anuncio" placeholder="Ej: 10% de descuento, o dejalo vacio">
            </div>
            <div class="campo-formulario">
                <label>Telefono de contacto (opcional)</label>
                <input type="text" id="telefono-anuncio" placeholder="8888-8888">
            </div>
            <div class="campo-formulario">
                <label>Imagen (JPG/PNG, max 8MB) o video (MP4, max 40MB) — opcional</label>
                <input type="file" id="media-anuncio" accept="image/jpeg,image/png,video/mp4">
            </div>
            <div class="campo-formulario">
                <label>Audio (MP3/WAV/M4A, max 8MB) — opcional, se puede usar solo o junto con la imagen</label>
                <input type="file" id="audio-anuncio" accept="audio/mpeg,audio/wav,audio/mp4,audio/x-wav">
            </div>
            <button class="boton boton-primario" onclick="crearAnuncio()">Crear anuncio</button>
        </div>

        <div class="tarjeta">
            <h2>Anuncios existentes</h2>
            <div class="tabla-panel-scroll">
                <table class="tabla-panel">
                    <thead><tr><th>Negocio</th><th>Texto</th><th>Media</th><th>Audio</th><th>Estado</th><th>Accion</th></tr></thead>
                    <tbody id="cuerpo-tabla-anuncios"></tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=utilidades.js"></script>
    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=perfil.js"></script>
    <script>
        const URL_BASE = "<?php echo URL_BASE; ?>";

        async function crearAnuncio() {
            const nombre = document.getElementById("nombre-negocio").value;
            const texto = document.getElementById("texto-anuncio").value;
            const telefono = document.getElementById("telefono-anuncio").value;
            const archivo = document.getElementById("media-anuncio").files[0];
            const audio = document.getElementById("audio-anuncio").files[0];

            const formData = new FormData();
            formData.append("nombre_negocio", nombre);
            formData.append("texto", texto);
            formData.append("telefono", telefono);
            if (archivo) formData.append("media", archivo);
            if (audio) formData.append("audio", audio);

            try {
                const respuesta = await fetch(URL_BASE + "/admin_crear_anuncio.php", { method: "POST", body: formData });
                const resultado = await respuesta.json();

                if (resultado.exito) {
                    mostrarMensaje("mensaje-anuncio", "Anuncio creado", false);
                    document.getElementById("nombre-negocio").value = "";
                    document.getElementById("texto-anuncio").value = "";
                    document.getElementById("telefono-anuncio").value = "";
                    document.getElementById("media-anuncio").value = "";
                    document.getElementById("audio-anuncio").value = "";
                    cargarAnuncios();
                } else {
                    mostrarMensaje("mensaje-anuncio", resultado.mensaje, true);
                }
            } catch (error) {
                mostrarMensaje("mensaje-anuncio", "No se pudo subir el anuncio", true);
            }
        }

        async function cargarAnuncios() {
            const resultado = await llamarApi(URL_BASE + "/admin_obtener_anuncios.php", {});
            const cuerpo = document.getElementById("cuerpo-tabla-anuncios");
            cuerpo.innerHTML = "";

            if (!resultado.anuncios || resultado.anuncios.length === 0) {
                cuerpo.innerHTML = "<tr><td colspan='6'>Aun no hay anuncios creados</td></tr>";
                return;
            }

            resultado.anuncios.forEach(function (a) {
                const etiqueta = a.activo == 1 ? "<span class='etiqueta-activo'>Activo</span>" : "<span class='etiqueta-inactivo'>Inactivo</span>";
                const media = a.tipo_media !== 'ninguno' ? a.tipo_media : "—";
                const audio = a.url_audio ? "Si" : "—";
                cuerpo.innerHTML += "<tr><td>" + (a.nombre_negocio || "(sin nombre)") + "</td><td>" + (a.texto || "(sin texto)") + "</td><td>" + media + "</td><td>" + audio + "</td><td>" + etiqueta + "</td>" +
                    "<td><button class='boton boton-secundario' onclick='toggleAnuncio(" + a.id + ", " + (a.activo == 1 ? 0 : 1) + ")'>Cambiar estado</button> " +
                    "<button class='boton boton-peligro' onclick='eliminarAnuncio(" + a.id + ")'>Eliminar</button></td></tr>";
            });
        }

        async function toggleAnuncio(id, nuevoEstado) {
            await llamarApi(URL_BASE + "/admin_toggle_anuncio.php", { id: id, activo: nuevoEstado });
            cargarAnuncios();
        }

        async function eliminarAnuncio(id) {
            if (!confirm("Eliminar este anuncio?")) return;
            await llamarApi(URL_BASE + "/admin_eliminar_anuncio.php", { id: id });
            cargarAnuncios();
        }

        document.addEventListener("DOMContentLoaded", cargarAnuncios);
    </script>
</body>
</html>