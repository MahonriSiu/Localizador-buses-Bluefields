<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/models/Propietario.php');

session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['admin_autenticado'])) {
    echo json_encode(array("exito" => false, "mensaje" => "Sesion no valida"));
    exit;
}

$modeloPropietario = new Propietario($conexion);
$propietarios = $modeloPropietario->obtenerTodos();
echo json_encode(array("propietarios" => $propietarios));