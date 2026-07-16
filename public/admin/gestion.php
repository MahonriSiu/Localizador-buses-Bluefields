<?php
session_start();

if (!isset($_SESSION['admin_autenticado'])) {
    header("Location: login.php");
    exit();
}

$esAuditor = ($_SESSION['admin_rol'] === 'auditor');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiBus - Gestion de Rutas</title>

    <link rel="stylesheet" href="../css/estilos.css" />
</head>
<body>

    <div class="encabezado">
        <h2>Gestion de Rutas - MiBus</h2>
    </div>

    <div class="panel-gestion">
        <a href="panel.php" class="volver">Volver al panel</a>

        <?php if ($esAuditor) { ?>
            <div class="aviso-rol">
                Modo Auditor: solo puede consultar, no puede agregar ni modificar rutas
            </div>
        <?php } else { ?>
            <h3>Agregar nueva ruta</h3>
            <input type="text" id="nombre" placeholder="Nombre de la ruta">
            <input type="text" id="origen" placeholder="Origen">
            <input type="text" id="destino" placeholder="Destino">
            <button onclick="agregarRuta()">Agregar Ruta</button>
            <p id="mensaje"></p>
            <div id="codigoGenerado" style="display:none; background-color:#FFF3CD; padding:10px; border-radius:5px; margin-top:10px;">
                <strong>Codigo de acceso para el emisor:</strong>
                <p id="textoCodigo"></p>
            </div>
        <?php } ?>

        <h3>Rutas existentes</h3>
        <table id="tablaRutas">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Origen</th>
                <th>Destino</th>
                <th>Estado</th>
                <th>Accion</th>
            </tr>
        </table>
    </div>

    <script>
        const esAuditor = <?php echo $esAuditor ? 'true' : 'false'; ?>;

        function cargarRutas() {
            fetch('../admin_obtener_rutas_completas.php')
                .then(respuesta => respuesta.json())
                .then(rutas => {
                    const tabla = document.getElementById('tablaRutas');
                    tabla.innerHTML = '<tr><th>ID</th><th>Nombre</th><th>Origen</th><th>Destino</th><th>Estado</th><th>Accion</th></tr>';
                    rutas.forEach(ruta => {
                        const activo = ruta.bus_activo == 1;
                        const estado = activo ? 'Activa' : 'Inactiva';
                        const fila = document.createElement('tr');

                        let botones = '-';
                        if (!esAuditor) {
                            const accion = activo ? 'inhabilitar' : 'habilitar';
                            const texto = activo ? 'Inhabilitar' : 'Habilitar';
                            botones = '<button onclick="cambiarEstado(' + ruta.id + ', \'' + accion + '\')">' + texto + '</button> ';
                            botones += '<button onclick="eliminarRuta(' + ruta.id + ')">Eliminar</button>';
                        }

                        fila.innerHTML = '<td>' + ruta.id + '</td><td>' + ruta.nombre + '</td><td>' + ruta.origen + '</td><td>' + ruta.destino + '</td><td>' + estado + '</td><td>' + botones + '</td>';
                        tabla.appendChild(fila);
                    });
                });
        }

        function cambiarEstado(rutaId, accion) {
            const datos = new FormData();
            datos.append('ruta_id', rutaId);
            datos.append('accion', accion);

            fetch('../admin_toggle_ruta.php', {
                method: 'POST',
                body: datos
            })
            .then(respuesta => respuesta.json())
            .then(resultado => {
                if (resultado.exito) {
                    cargarRutas();
                } else {
                    alert('Error al cambiar el estado');
                }
            });
        }

        function eliminarRuta(rutaId) {
            if (!confirm('Seguro que deseas eliminar esta ruta y su bus asociado?')) {
                return;
            }

            const datos = new FormData();
            datos.append('ruta_id', rutaId);

            fetch('../admin_eliminar_ruta.php', {
                method: 'POST',
                body: datos
            })
            .then(respuesta => respuesta.json())
            .then(resultado => {
                if (resultado.exito) {
                    cargarRutas();
                } else {
                    alert('Error al eliminar la ruta');
                }
            });
        }

        function agregarRuta() {
            const nombre = document.getElementById('nombre').value;
            const origen = document.getElementById('origen').value;
            const destino = document.getElementById('destino').value;

            const datos = new FormData();
            datos.append('nombre', nombre);
            datos.append('origen', origen);
            datos.append('destino', destino);

            fetch('../admin_agregar_ruta.php', {
                method: 'POST',
                body: datos
            })
            .then(respuesta => respuesta.json())
            .then(resultado => {
                if (resultado.exito) {
                    document.getElementById('mensaje').innerText = 'Ruta agregada correctamente';
                    document.getElementById('nombre').value = '';
                    document.getElementById('origen').value = '';
                    document.getElementById('destino').value = '';

                    document.getElementById('codigoGenerado').style.display = 'block';
                    document.getElementById('textoCodigo').innerText = resultado.codigo_acceso;

                    cargarRutas();
                } else {
                    document.getElementById('mensaje').innerText = resultado.mensaje;
                }
            });
        }

        cargarRutas();
    </script>

</body>
</html>