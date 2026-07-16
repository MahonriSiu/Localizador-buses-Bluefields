<?php

require_once(__DIR__ . "/../config/database.php");

header("Content-Type: application/json");

$sql = "SELECT rutas.*, buses.activo as bus_activo FROM rutas 
        LEFT JOIN buses ON buses.ruta_id = rutas.id";

$resultado = $conexion->query($sql);

$rutas = array();
while ($fila = $resultado->fetch_assoc()) {
    $rutas[] = $fila;
}

echo json_encode($rutas);

?>