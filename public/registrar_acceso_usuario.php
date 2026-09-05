<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/controllers/BusController.php');

$controlador = new BusController($conexion);
$controlador->registrarAccesoSiCorresponde();