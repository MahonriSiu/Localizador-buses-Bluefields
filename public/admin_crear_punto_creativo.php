<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/controllers/RutaCreativaController.php');

$controlador = new RutaCreativaController($conexion);

$lat = isset($_POST['lat']) ? str_replace(',', '.', trim($_POST['lat'])) : '0';
$lng = isset($_POST['lng']) ? str_replace(',', '.', trim($_POST['lng'])) : '0';

$controlador->crearPunto(
    isset($_POST['evento_id']) ? intval($_POST['evento_id']) : 0,
    isset($_POST['nombre']) ? $_POST['nombre'] : '',
    isset($_POST['descripcion']) ? $_POST['descripcion'] : '',
    floatval($lat),
    floatval($lng),
    isset($_POST['orden']) ? intval($_POST['orden']) : 0,
    isset($_POST['visible']) ? intval($_POST['visible']) : 1
);