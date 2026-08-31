<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/controllers/RutaCreativaController.php');
$controlador = new RutaCreativaController($conexion);
$controlador->subirPortada(isset($_POST['punto_id']) ? intval($_POST['punto_id']) : 0);