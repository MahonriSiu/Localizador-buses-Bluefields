<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/controllers/RutaCreativaController.php');
$controlador = new RutaCreativaController($conexion);
$controlador->obtenerPuntoAdmin(intval($_GET['id']));