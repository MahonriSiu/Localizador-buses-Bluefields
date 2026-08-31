<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/controllers/RutaCreativaController.php');
$controlador = new RutaCreativaController($conexion);
$controlador->obtenerImagenesAdmin(isset($_GET['punto_id']) ? intval($_GET['punto_id']) : 0);