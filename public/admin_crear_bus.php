<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/controllers/AdminController.php');

$controlador = new AdminController($conexion);

$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
$origen = isset($_POST['origen']) ? $_POST['origen'] : '';
$destino = isset($_POST['destino']) ? $_POST['destino'] : '';
$propietarioId = isset($_POST['propietario_id']) ? intval($_POST['propietario_id']) : 0;
$controlador->crearBus($nombre, $origen, $destino, $propietarioId);