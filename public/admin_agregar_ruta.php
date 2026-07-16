<?php
session_start();

require_once(__DIR__ . "/../config/database.php");

header("Content-Type: application/json");

if (!isset($_SESSION['admin_autenticado']) || $_SESSION['admin_rol'] !== 'admin') {
    echo json_encode(array("exito" => false, "mensaje" => "No autorizado"));
    exit();
}

$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
$origen = isset($_POST['origen']) ? $_POST['origen'] : '';
$destino = isset($_POST['destino']) ? $_POST['destino'] : '';

if ($nombre === '' || $origen === '' || $destino === '') {
    echo json_encode(array("exito" => false, "mensaje" => "Todos los campos son obligatorios"));
    exit();
}

$sql = "INSERT INTO rutas (nombre, origen, destino) VALUES (?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sss", $nombre, $origen, $destino);
$resultado = $stmt->execute();

if (!$resultado) {
    echo json_encode(array("exito" => false, "mensaje" => "Error al crear la ruta"));
    exit();
}

$rutaId = $conexion->insert_id;

$sqlBus = "INSERT INTO buses (ruta_id, lat, lng, activo) VALUES (?, 0, 0, 0)";
$stmtBus = $conexion->prepare($sqlBus);
$stmtBus->bind_param("i", $rutaId);
$stmtBus->execute();
$busId = $conexion->insert_id;

$codigoAcceso = "BUS" . str_pad($busId, 3, "0", STR_PAD_LEFT) . "-" . strtoupper(substr(md5(uniqid()), 0, 6));

$sqlEmisor = "INSERT INTO emisores (codigo_acceso, bus_id) VALUES (?, ?)";
$stmtEmisor = $conexion->prepare($sqlEmisor);
$stmtEmisor->bind_param("si", $codigoAcceso, $busId);
$stmtEmisor->execute();

echo json_encode(array(
    "exito" => true,
    "ruta_id" => $rutaId,
    "bus_id" => $busId,
    "codigo_acceso" => $codigoAcceso
));

?>