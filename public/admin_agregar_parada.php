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

$busId = intval($_POST['bus_id']);
$nombre = $_POST['nombre'];
$lat = floatval($_POST['lat']);
$lng = floatval($_POST['lng']);
$orden = intval($_POST['orden']);

$id = $modeloParada->crear($busId, $nombre, $lat, $lng, $orden);
echo json_encode(array("exito" => true, "id" => $id));