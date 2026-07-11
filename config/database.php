<?php

$host = "localhost";
$usuario = "root";
$contrasena = "";
$basedatos = "localizador_buses";

$conexion = new mysqli($host, $usuario, $contrasena, $basedatos);

if ($conexion->connect_error) {
    die("Error de conexion: " . $conexion->connect_error);
}

?>