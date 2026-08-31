<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/controllers/EmisorController.php');

$controlador = new EmisorController($conexion);

$codigo = isset($_GET['codigo']) ? $_GET['codigo'] : (isset($_POST['codigo']) ? $_POST['codigo'] : '');
$lat = isset($_GET['lat']) ? floatval($_GET['lat']) : (isset($_POST['lat']) ? floatval($_POST['lat']) : 0);
$lng = isset($_GET['lng']) ? floatval($_GET['lng']) : (isset($_POST['lng']) ? floatval($_POST['lng']) : 0);

$controlador->actualizarPosicionPorCodigo($codigo, $lat, $lng);