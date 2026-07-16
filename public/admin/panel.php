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
    <title>MiBus - Panel Administrador</title>

    <link rel="stylesheet" href="../css/estilos.css" />
</head>
<body>

    <div class="encabezado">
        <h2>Panel de Administrador - MiBus</h2>
        <p>Bienvenido, <?php echo $_SESSION['admin_nombre']; ?> (<?php echo $_SESSION['admin_rol']; ?>)</p>
    </div>

    <?php if ($esAuditor) { ?>
    <div class="aviso-rol">
        Modo Auditor: solo puede consultar informacion, no gestionar datos
    </div>
    <?php } ?>

    <div class="contenido">

        <div class="tarjeta">
            <h3>Vista Usuario</h3>
            <p>Ver el mapa publico en tiempo real</p>
            <a href="../usuario/index.php">Ver mapa</a>
        </div>

        <div class="tarjeta">
            <h3>Vista Emisor</h3>
            <p>Ver panel de transmision de buses</p>
            <a href="../emisor/login.php">Ver emisor</a>
        </div>

        <div class="tarjeta">
            <h3>Gestion de Rutas y Buses</h3>
            <p>Administrar rutas y buses activos</p>
            <a href="gestion.php" class="<?php echo $esAuditor ? 'deshabilitado' : ''; ?>">
                <?php echo $esAuditor ? 'No disponible para Auditor' : 'Gestionar'; ?>
            </a>
        </div>

    </div>

</body>
</html>