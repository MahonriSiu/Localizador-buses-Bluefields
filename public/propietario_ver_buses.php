<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/controllers/PropietarioController.php');

$controlador = new PropietarioController($conexion);
$controlador->verMisBuses();