<?php
session_start();

if (!isset($_SESSION['emisor_autenticado'])) {
    header("Location: /Localizador-buses-Bluefields/public/emisor/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiBus - Panel Emisor</title>

    <link rel="stylesheet" href="/Localizador-buses-Bluefields/public/asset.php?tipo=css&archivo=estilos.css" />
</head>
<body class="pagina-login">

    <div class="caja-login">
        <h2>MiBus - Transmitiendo</h2>
        <p>Bus en linea, enviando ubicacion</p>
        <p id="estado" class="texto-estado">Esperando GPS...</p>
    </div>

    <script src="/Localizador-buses-Bluefields/public/asset.php?tipo=js&archivo=emisor.js"></script>

</body>
</html>