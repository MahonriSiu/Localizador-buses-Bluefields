<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/controllers/RutaCreativaController.php');
$controlador = new RutaCreativaController($conexion);
$controlador->obtenerPorEventoPublico(isset($_GET['evento_id']) ? intval($_GET['evento_id']) : 0);