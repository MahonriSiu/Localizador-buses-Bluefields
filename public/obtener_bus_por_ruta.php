<?php

require_once(__DIR__ . "/../config/database.php");
require_once(__DIR__ . "/../app/models/Bus.php");

$modeloBus = new Bus($conexion);

$rutaId = isset($_GET['ruta_id']) ? $_GET['ruta_id'] : 1;

$bus = $modeloBus->obtenerPorRuta($rutaId);

header("Content-Type: application/json");

if ($bus) {
    echo json_encode($bus);
} else {
    echo json_encode(array("error" => "No hay bus activo en esta ruta"));
}

?>