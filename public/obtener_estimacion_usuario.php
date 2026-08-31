<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/controllers/BusController.php');

$controlador = new BusController($conexion);

$busId = isset($_GET['bus_id']) ? intval($_GET['bus_id']) : 0;
$lat = isset($_GET['lat']) ? floatval($_GET['lat']) : 0;
$lng = isset($_GET['lng']) ? floatval($_GET['lng']) : 0;
$controlador->estimarLlegadaDesdeUsuario($busId, $lat, $lng);