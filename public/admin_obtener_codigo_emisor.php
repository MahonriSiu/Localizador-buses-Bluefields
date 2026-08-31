<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/controllers/AdminController.php');
$controlador = new AdminController($conexion);
$busId = isset($_GET['bus_id']) ? intval($_GET['bus_id']) : 0;
$controlador->obtenerCodigoEmisor($busId);