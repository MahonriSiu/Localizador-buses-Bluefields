<?php require_once __DIR__ . '/../../../config/rutas.php'; $paginaActiva = 'gestion'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiBus - Gestion</title>
    <link rel="stylesheet" href="<?php echo URL_BASE; ?>/asset.php?tipo=css&archivo=estilos.css">
</head>
<body>

    <?php require __DIR__ . '/../partials/encabezado_admin.php'; ?>

    <div class="contenedor">
        <div class="tarjeta">
            <h2>Horario del sistema</h2>
            <p style="font-size: 13px; color: #6b6255; margin-bottom: 12px;">
                Se guarda automaticamente al cambiar los campos.
            </p>
            <div id="mensaje-horario"></div>
            <div class="campo-formulario">
                <label>Hora de apertura</label>
                <input type="time" id="hora-apertura" onchange="programarGuardadoHorario()">
            </div>
            <div class="campo-formulario">
                <label>Hora de cierre</label>
                <input type="time" id="hora-cierre" onchange="programarGuardadoHorario()">
            </div>
        </div>

        <div class="tarjeta">
            <h2>Crear bus</h2>
            <p style="font-size: 13px; color: #6b6255; margin-bottom: 12px;">
                Cada bus se crea con su nombre, la ruta que sigue, una descripcion opcional, y su dueno.
            </p>
            <div id="mensaje-bus"></div>
            <div class="campo-formulario">
                <label>Nombre del bus</label>
                <input type="text" id="nombre-bus" placeholder="Ej: Bus 12">
            </div>
            <div class="campo-formulario">
                <label>Origen</label>
                <input type="text" id="origen-bus" placeholder="Ej: Terminal">
            </div>
            <div class="campo-formulario">
                <label>Destino</label>
                <input type="text" id="destino-bus" placeholder="Ej: Barrio Pointeen">
            </div>
            <div class="campo-formulario">
                <label>Descripcion (opcional)</label>
                <input type="text" id="descripcion-bus" placeholder="Ej: Bus articulado, pasa cada 20 min">
            </div>
            <div class="campo-formulario">
                <label>Propietario</label>
                <select id="propietario-bus"></select>
            </div>
            <button class="boton boton-primario"
                onclick="crearBus(document.getElementById('nombre-bus').value, document.getElementById('origen-bus').value, document.getElementById('destino-bus').value, document.getElementById('descripcion-bus').value, document.getElementById('propietario-bus').value)">
                Crear bus
            </button>
        </div>

        <div class="tarjeta">
            <h2>Buses existentes</h2>
            <div class="tabla-panel-scroll">
                <table class="tabla-panel">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Ruta</th>
                            <th>Propietario</th>
                            <th>Estado</th>
                            <th>Accion</th>
                            <th>Codigo Emisor</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpo-tabla-buses"></tbody>
                </table>
            </div>
        </div>

        <div class="tarjeta">
            <h2>Agregar parada</h2>
            <div id="mensaje-paradas"></div>
            <div class="campo-formulario">
                <label>Bus</label>
                <select id="bus-para-parada" onchange="cargarParadasDelBus(this.value)"></select>
            </div>
            <div class="campo-formulario">
                <label>Nombre de la parada</label>
                <input type="text" id="nombre-parada">
            </div>
            <div class="campo-formulario">
                <label>Latitud</label>
                <input type="text" id="lat-parada" placeholder="Ej: 11.9986">
            </div>
            <div class="campo-formulario">
                <label>Longitud</label>
                <input type="text" id="lng-parada" placeholder="Ej: -83.7574">
            </div>
            <div class="campo-formulario">
                <label>Orden en la ruta</label>
                <input type="number" id="orden-parada" value="1">
            </div>
            <button class="boton boton-primario" onclick="agregarParada()">Agregar parada</button>
        </div>

        <div class="tarjeta">
            <h2>Paradas del bus seleccionado</h2>
            <div class="tabla-panel-scroll">
                <table class="tabla-panel">
                    <thead>
                        <tr><th>Nombre</th><th>Orden</th><th>Accion</th></tr>
                    </thead>
                    <tbody id="cuerpo-tabla-paradas"></tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=utilidades.js"></script>
    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=admin.js"></script>
    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=perfil.js"></script>
</body>
</html>