<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/controllers/BusController.php');
$controlador = new BusController($conexion);
$controlador->obtenerRutaDefinida(intval($_GET['bus_id']));