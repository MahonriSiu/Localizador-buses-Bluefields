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

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        .encabezado {
            background-color: #1565C0;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .contenido {
            padding: 20px;
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        .tarjeta {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            width: 250px;
            text-align: center;
        }
        .tarjeta a {
            display: block;
            margin-top: 10px;
            padding: 10px;
            background-color: #1565C0;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .deshabilitado {
            background-color: #cccccc !important;
            pointer-events: none;
        }
        .aviso-rol {
            background-color: #FFF3CD;
            color: #856404;
            padding: 10px;
            text-align: center;
        }
    </style>
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
            <a href="#" class="<?php echo $esAuditor ? 'deshabilitado' : ''; ?>">
                <?php echo $esAuditor ? 'No disponible para Auditor' : 'Proximamente'; ?>
            </a>
        </div>

    </div>

</body>
</html>