<?php

$host = "sql211.infinityfree.com";
$usuario = "if0_42523829";
$contrasena = "JustoSiu18";
$basedatos = "if0_42523829_localizador";

$conexion = new mysqli($host, $usuario, $contrasena, $basedatos);

if ($conexion->connect_error) {
    die("Error de conexion: " . $conexion->connect_error);
}

?>