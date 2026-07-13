<?php

require_once(__DIR__ . "/../config/database.php");
require_once(__DIR__ . "/../app/models/Ruta.php");

$modeloRuta = new Ruta($conexion);
$rutas = $modeloRuta->obtenerTodas();

header("Content-Type: application/json");
echo json_encode($rutas);

?>