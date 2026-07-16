<?php
session_start();

require_once(__DIR__ . "/../config/database.php");

header("Content-Type: application/json");

if (!isset($_SESSION['admin_autenticado']) || $_SESSION['admin_rol'] !== 'admin') {
    echo json_encode(array("exito" => false, "mensaje" => "No autorizado"));
    exit();
}

$rutaId = isset($_POST['ruta_id']) ? $_POST['ruta_id'] : null;

if ($rutaId === null) {
    echo json_encode(array("exito" => false, "mensaje" => "Falta el id de la ruta"));
    exit();
}

$sqlBuses = "SELECT id FROM buses WHERE ruta_id = ?";
$stmtBuses = $conexion->prepare($sqlBuses);
$stmtBuses->bind_param("i", $rutaId);
$stmtBuses->execute();
$resultadoBuses = $stmtBuses->get_result();

while ($bus = $resultadoBuses->fetch_assoc()) {
    $busId = $bus['id'];

    $sqlEmisor = "DELETE FROM emisores WHERE bus_id = ?";
    $stmtEmisor = $conexion->prepare($sqlEmisor);
    $stmtEmisor->bind_param("i", $busId);
    $stmtEmisor->execute();
}

$sqlBorrarBuses = "DELETE FROM buses WHERE ruta_id = ?";
$stmtBorrarBuses = $conexion->prepare($sqlBorrarBuses);
$stmtBorrarBuses->bind_param("i", $rutaId);
$stmtBorrarBuses->execute();

$sqlBorrarRuta = "DELETE FROM rutas WHERE id = ?";
$stmtBorrarRuta = $conexion->prepare($sqlBorrarRuta);
$stmtBorrarRuta->bind_param("i", $rutaId);
$resultado = $stmtBorrarRuta->execute();

echo json_encode(array("exito" => $resultado));

?>