<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/controllers/AuditorController.php');

$controlador = new AuditorController($conexion);
$controlador->verPanel();