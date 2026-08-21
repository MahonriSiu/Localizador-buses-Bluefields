<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/controllers/BusController.php');

$controlador = new BusController($conexion);

$busId = isset($_GET['bus_id']) ? intval($_GET['bus_id']) : 0;
$controlador->obtenerParadas($busId);