<?php
session_start();

require_once(__DIR__ . "/../config/database.php");

header("Content-Type: application/json");

if (!isset($_SESSION['admin_autenticado']) || $_SESSION['admin_rol'] !== 'admin') {
    echo json_encode(array("exito" => false, "mensaje" => "No autorizado"));
    exit();
}

$rutaId = isset($_POST['ruta_id']) ? $_POST['ruta_id'] : null;
$accion = isset($_POST['accion']) ? $_POST['accion'] : '';

if ($rutaId === null || $accion === '') {
    echo json_encode(array("exito" => false, "mensaje" => "Datos incompletos"));
    exit();
}

if ($accion === 'inhabilitar') {
    $sql = "UPDATE buses SET activo = 0 WHERE ruta_id = ?";
} else if ($accion === 'habilitar') {
    $sql = "UPDATE buses SET activo = 1 WHERE ruta_id = ?";
} else {
    echo json_encode(array("exito" => false, "mensaje" => "Accion invalida"));
    exit();
}

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $rutaId);
$resultado = $stmt->execute();

echo json_encode(array("exito" => $resultado));

?>