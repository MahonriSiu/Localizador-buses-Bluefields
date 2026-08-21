<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/controllers/BusController.php');

$controlador = new BusController($conexion);

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$controlador->obtenerEstadoBus($id);