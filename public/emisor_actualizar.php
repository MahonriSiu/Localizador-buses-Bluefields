<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/controllers/EmisorController.php');

header("Content-Type: application/json");

$controlador = new EmisorController($conexion);

$lat = isset($_POST['lat']) ? floatval($_POST['lat']) : 0;
$lng = isset($_POST['lng']) ? floatval($_POST['lng']) : 0;
$controlador->actualizarPosicion($lat, $lng);