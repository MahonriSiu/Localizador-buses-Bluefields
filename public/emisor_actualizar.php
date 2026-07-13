<?php

require_once(__DIR__ . "/../config/database.php");
require_once(__DIR__ . "/../app/controllers/EmisorController.php");

$controlador = new EmisorController($conexion);

$lat = isset($_POST['lat']) ? $_POST['lat'] : null;
$lng = isset($_POST['lng']) ? $_POST['lng'] : null;

$controlador->recibirUbicacion($lat, $lng);

?>