<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/controllers/BusController.php');

header("Content-Type: application/json");

$controlador = new BusController($conexion);

$accion = isset($_POST['accion']) ? $_POST['accion'] : '';

if ($accion === 'registro') {
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $controlador->registrarUsuarioFinal($nombre, $telefono);
} elseif ($accion === 'login') {
    $telefono = $_POST['telefono'];
    $controlador->iniciarSesionUsuarioFinal($telefono);
} else {
    echo json_encode(array("exito" => false, "mensaje" => "Accion no reconocida"));
}