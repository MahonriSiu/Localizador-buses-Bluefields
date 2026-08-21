<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/controllers/BusController.php');

$controlador = new BusController($conexion);

$busId = intval($_GET['bus_id']);
$paradaId = intval($_GET['parada_id']);
$controlador->estimarLlegada($busId, $paradaId);