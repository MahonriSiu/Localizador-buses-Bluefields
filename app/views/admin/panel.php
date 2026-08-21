<?php require_once __DIR__ . '/../../../config/rutas.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiBus - Panel Admin</title>
    <link rel="stylesheet" href="<?php echo URL_BASE; ?>/asset.php?tipo=css&archivo=estilos.css">
</head>
<body>

    <div class="encabezado">
        <h1>Panel del Administrador</h1>
        <div>
            <a href="<?php echo URL_BASE; ?>/admin/gestion.php" style="color: #fff; margin-right: 16px;">Gestion</a>
            <a href="<?php echo URL_BASE; ?>/admin/cuentas.php" style="color: #fff;">Cuentas</a>
        </div>
    </div>

    <div class="contenedor">
        <div class="grid-estadisticas">
            <div class="tarjeta-estadistica">
                <div class="numero" id="total-buses">-</div>
                <div class="etiqueta">Buses totales</div>
            </div>
            <div class="tarjeta-estadistica">
                <div class="numero" id="buses-habilitados">-</div>
                <div class="etiqueta">Buses habilitados</div>
            </div>
            <div class="tarjeta-estadistica">
                <div class="numero" id="buses-transmitiendo">-</div>
                <div class="etiqueta">Transmitiendo ahora</div>
            </div>
        </div>

        <div class="tarjeta">
            <h2>Accesos rapidos</h2>
            <a href="<?php echo URL_BASE; ?>/admin/gestion.php" class="boton boton-primario" style="margin-right: 10px;">Gestionar rutas y buses</a>
            <a href="<?php echo URL_BASE; ?>/admin/cuentas.php" class="boton boton-secundario" style="margin-right: 10px;">Gestionar cuentas</a>
            <a href="<?php echo URL_BASE; ?>/index.php" class="boton boton-secundario" target="_blank">Ver mapa publico</a>
        </div>
    </div>

    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=utilidades.js"></script>
    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=admin.js"></script>
</body>
</html>