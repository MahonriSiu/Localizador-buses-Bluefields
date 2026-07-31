<?php

$esLocal = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false);

if ($esLocal) {
    $host = "localhost";
    $usuario = "root";
    $contrasena = "";
    $basedatos = "localizador_buses";
} else {
    $host = "sql211.infinityfree.com";
    $usuario = "if0_42523829";
    $contrasena = "JustoSiu18";
    $basedatos = "if0_42523829_localizador";
}

$conexion = new mysqli($host, $usuario, $contrasena, $basedatos);

if ($conexion->connect_error) {
    die("Error de conexion: " . $conexion->connect_error);
}

?>