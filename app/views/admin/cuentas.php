<?php require_once __DIR__ . '/../../../config/rutas.php'; $paginaActiva = 'cuentas'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiBus - Cuentas</title>
    <link rel="stylesheet" href="<?php echo URL_BASE; ?>/asset.php?tipo=css&archivo=estilos.css">
</head>
<body>

    <?php require __DIR__ . '/../partials/encabezado_admin.php'; ?>

    <div class="contenedor">
        <div class="grid-estadisticas">
            <div class="tarjeta-estadistica">
                <div class="numero" id="total-cuentas">-</div>
                <div class="etiqueta">Cuentas totales del sistema</div>
            </div>
        </div>

        <div class="tarjeta">
            <h2>Solicitudes de reseteo pendientes</h2>
            <div class="tabla-panel-scroll">
                <table class="tabla-panel">
                    <thead><tr><th>Correo</th><th>Fecha</th><th>Accion</th></tr></thead>
                    <tbody id="cuerpo-tabla-solicitudes"></tbody>
                </table>
            </div>
        </div>

        <div class="tarjeta">
            <h2>Crear cuenta nueva</h2>
            <div id="mensaje-cuentas"></div>
            <div class="campo-formulario">
                <label>Nombre</label>
                <input type="text" id="nombre-cuenta">
            </div>
            <div class="campo-formulario">
                <label>Correo</label>
                <input type="email" id="correo-cuenta">
            </div>
            <div class="campo-formulario">
                <label>Rol</label>
                <select id="rol-cuenta">
                    <option value="propietario">Propietario</option>
                    <option value="auditor">Auditor</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button class="boton boton-primario"
                onclick="crearCuenta(document.getElementById('nombre-cuenta').value, document.getElementById('correo-cuenta').value, document.getElementById('rol-cuenta').value)">
                Crear cuenta
            </button>
            <p style="font-size: 12px; color: #6b7280; margin-top: 8px;">La cuenta se crea con la contrasena "admin123", el usuario debera cambiarla al entrar.</p>
        </div>

        <div class="tarjeta">
            <h2>Administradores</h2>
            <div class="tabla-panel-scroll">
                <table class="tabla-panel">
                    <thead><tr><th>Nombre</th><th>Correo</th><th>Resetear</th></tr></thead>
                    <tbody id="cuerpo-tabla-admins"></tbody>
                </table>
            </div>
        </div>

        <div class="tarjeta">
            <h2>Auditores</h2>
            <div class="tabla-panel-scroll">
                <table class="tabla-panel">
                    <thead><tr><th>Nombre</th><th>Correo</th><th>Historial</th><th>Resetear</th></tr></thead>
                    <tbody id="cuerpo-tabla-auditores"></tbody>
                </table>
            </div>
        </div>

        <div class="tarjeta">
            <h2>Propietarios</h2>
            <div class="tabla-panel-scroll">
                <table class="tabla-panel">
                    <thead><tr><th>Nombre</th><th>Correo</th><th>Historial</th><th>Resetear</th></tr></thead>
                    <tbody id="cuerpo-tabla-propietarios"></tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=utilidades.js"></script>
    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=admin.js"></script>
    <script src="<?php echo URL_BASE; ?>/asset.php?tipo=js&archivo=perfil.js"></script>
</body>
</html>