<?php

date_default_timezone_set('America/Managua');

ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.use_strict_mode', 1);
session_set_cookie_params(['path' => '/', 'httponly' => true, 'samesite' => 'Lax']);

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
    http_response_code(500);
    header("Content-Type: application/json");
    echo json_encode(array("exito" => false, "mensaje" => "No se pudo conectar a la base de datos"));
    exit;
}

$conexion->set_charset("utf8mb4");

$conexion->query("SET time_zone = '-06:00'");

?>