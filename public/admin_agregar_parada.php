<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/models/Parada.php');

session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['admin_autenticado'])) {
    echo json_encode(array("exito" => false, "mensaje" => "Sesion no valida"));
    exit;
}

$modeloParada = new Parada($conexion);

$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
$lat = isset($_POST['lat']) ? str_replace(',', '.', trim($_POST['lat'])) : '0';
$lng = isset($_POST['lng']) ? str_replace(',', '.', trim($_POST['lng'])) : '0';

if (trim($nombre) === '') {
    echo json_encode(array("exito" => false, "mensaje" => "La parada necesita un nombre"));
    exit;
}

$id = $modeloParada->crear($nombre, floatval($lat), floatval($lng));
echo json_encode(array("exito" => true, "id" => $id));