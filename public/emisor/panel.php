<?php
session_start();

if (!isset($_SESSION['emisor_autenticado'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiBus - Panel Emisor</title>

    <link rel="stylesheet" href="../asset.php?tipo=css&archivo=estilos.css" />

    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
            text-align: center;
        }
        #estado {
            margin-top: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="caja-login">
        <h2>MiBus - Transmitiendo</h2>
        <p>Bus en linea, enviando ubicacion</p>
        <p id="estado">Esperando GPS...</p>
    </div>

    <script src="../asset.php?tipo=js&archivo=emisor.js"></script>

</body>
</html>