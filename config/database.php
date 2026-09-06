<?php

date_default_timezone_set('America/Managua');

ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.use_strict_mode', 1);
session_set_cookie_params(['path' => '/', 'httponly' => true, 'samesite' => 'Lax']);

if (!file_exists(__DIR__ . '/credenciales.php')) {
    http_response_code(500);
    header("Content-Type: application/json");
    echo json_encode(array(
        "exito" => false,
        "mensaje" => "Falta config/credenciales.php. Copia config/credenciales.example.php, renombralo y pon los datos reales."
    ));
    exit;
}

require_once(__DIR__ . '/credenciales.php');

$conexion = new mysqli(DB_HOST, DB_USUARIO, DB_CONTRASENA, DB_NOMBRE);

if ($conexion->connect_error) {
    http_response_code(500);
    header("Content-Type: application/json");
    echo json_encode(array("exito" => false, "mensaje" => "No se pudo conectar a la base de datos"));
    exit;
}

$conexion->set_charset("utf8mb4");
$conexion->query("SET time_zone = '-06:00'");

?>