<?php require_once __DIR__ . '/../../../config/rutas.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiBus - Bluefields</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="stylesheet" href="<?php echo URL_BASE; ?>/asset.php?tipo=css&archivo=estilos.css">
</head>
<body>

    <div class="encabezado-usuario">
        <img src="<?php echo URL_BASE; ?>/asset.php?tipo=img&archivo=logo.png" class="logo-encabezado">
        <div>
            <h1>MiBus</h1>
            <div class="subtitulo">Ubicacion de buses en tiempo real - Bluefields</div>
        </div>
    </div>

    <button class="fab-resena" id="fab-resena" onclick="abrirModalResena()">💬</button>
    <button class="fab-eventos" id="fab-eventos" onclick="toggleListaEventos()">📅</button>

    <div class="panel-eventos" id="panel-eventos">
        <div class="panel-eventos-titulo">Circuitos y eventos activos</div>
        <div id="lista-eventos-toggle"></div>
    </div>

    <div class="panel-flotante">
        <select id="selector-ruta" class="selector-ruta">
            <option value="">Selecciona tu ruta</option>
        </select>
        <button id="boton-notificaciones" class="boton-notificacion" onclick="activarNotificaciones()">
            Activar avisos
        </button>
        <button id="boton-info-bus" class="boton-notificacion" onclick="mostrarInfoBus()">
            Info del bus
        </button>
        <button id="boton-mi-ubicacion" class="boton-notificacion" onclick="centrarEnMiUbicacion()">
            📍 Mi ubicacion
        </button>
    </div>

    <div id="mapa"></div>
    <div id="aviso-bus" style="display: none; position: fixed; top: 90px; left: 16px; right: 16px; z-index: 999; background: #fff; border-radius: 10px; padding: 12px 16px; text-align: center; font-size: 13px; color: #6b6255; box-shadow: 0 2px 10px rgba(0,0,0,0.15);"></div>

    <div id="panel-reposo" class="panel-reposo" style="display: none;">
        <img src="<?php echo URL_BASE; ?>/asset.php?tipo=img&archivo=logo.png" style="width: 100px; opacity: 0.6; margin-bottom: 16px;">
        <h2>El sistema esta en reposo</h2>
        <p>El servicio de rastreo esta disponible dentro del horario establecido.</p>
    </div>

    <div id="sin-rutas" class="panel-reposo" style="display: none;">
        <h2>No hay rutas activas en este momento</h2>
        <p>Vuelve a intentarlo mas tarde.</p>
    </div>

    <div class="modal-overlay" id="modal-info-bus">
        <div class="modal-caja">
            <h2>Informacion del bus</h2>
            <div class="info-bus-fila">
                <strong>Nombre</strong>
                <span id="info-bus-nombre">-</span>
            </div>
            <div class="info-bus-fila">
                <strong>Ruta</strong>
                <span id="info-bus-ruta">-</span>
            </div>
            <div class="info-bus-fila">
                <strong>Descripcion</strong>
                <span id="info-bus-descripcion">-</span>
            </div>
            <div class="info-bus-fila">
                <strong>Distancia hasta ti</strong>
                <span id="info-bus-distancia">-</span>
            </div>
            <div class="info-bus-fila">
                <strong>Tiempo estimado</strong>
                <span id="info-bus-tiempo">-</span>
            </div>
            <button class="boton boton-secundario boton-bloque" onclick="cerrarInfoBus()">Cerrar</button>
        </div>
    </div>

    <div class="modal-overlay" id="modal-punto-creativo">
        <div class="modal-caja modal-creativo">
            <div class="creativo-galeria">
                <button class="creativo-flecha izq" onclick="galeriaAnterior()">‹</button>
                <div id="creativo-imagen-actual" class="creativo-imagen-actual"></div>
                <button class="creativo-flecha der" onclick="galeriaSiguiente()">›</button>
                <span class="creativo-contador" id="creativo-contador-imagenes"></span>
            </div>
            <div style="padding: 16px;">
                <span class="creativo-etiqueta" id="creativo-etiqueta-evento">🏛️ Circuito</span>
                <h2 id="creativo-nombre" style="margin-top: 6px;">-</h2>
                <p id="creativo-descripcion" style="font-size: 14px; color: #444; margin-top: 8px; line-height: 1.5;">-</p>
                <button class="boton boton-secundario boton-bloque" style="margin-top: 14px;" onclick="cerrarModalPuntoCreativo()">Cerrar</button>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="modal-resena">
        <div class="modal-caja">
            <h2>Tu opinion nos ayuda</h2>
            <p style="font-size: 13px; color: #6b6255; margin-bottom: 14px;">Dejanos una resena o recomendacion sobre el servicio.</p>
            <div id="mensaje-resena"></div>

            <div class="calificacion-estrellas" id="calificacion-estrellas">
                <span data-valor="1">★</span>
                <span data-valor="2">★</span>
                <span data-valor="3">★</span>
                <span data-valor="4">★</span>
                <span data-valor="5">★</span>
            </div>

            <div class="campo-formulario">
                <label>Tu nombre (opcional)</label>
                <input type="text" id="resena-nombre">
            </div>
            <div class="campo-formulario">
                <label>Comentario</label>
                <textarea id="resena-comentario" rows="4" style="width:100%; padding:10px 12px; border:1px solid #d9cdb8; border-radius:6px; font-family: var(--fuente-cuerpo); font-size:16px; resize: vertical;"></textarea>
            </div>
            <button class="boton boton-primario boton-bloque" onclick="enviarResena()">Enviar</button>
            <button class="boton boton-secundario boton-bloque" style="margin-top: 10px;" onclick="cerrarModalResena()">Cerrar</button>
        </div>
    </div>

    <div class="modal-overlay modal-anuncio-overlay" id="modal-anuncio">
        <div class="modal-anuncio-caja">
            <button class="anuncio-sonido" id="anuncio-sonido">🔇</button>
            <button class="anuncio-cerrar" id="anuncio-cerrar">✕</button>
            <div class="anuncio-media-grande" id="anuncio-media-grande"></div>
            <div class="anuncio-contenido-grande">
                <span class="anuncio-etiqueta-grande">Anuncio local</span>
                <strong id="anuncio-titulo-grande"></strong>
                <span id="anuncio-texto-grande"></span>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-polylinedecorator@1.6.0/dist/leaflet.polylineDecorator.js"></script>
    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=utilidades.js"></script>
    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=mapa.js"></script>
    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=tutorial.js"></script>
    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=anuncios.js"></script>
    <script src="https://unpkg.com/leaflet-polylinedecorator@1.6.0/dist/leaflet.polylineDecorator.js"></script>
</body>
</html>