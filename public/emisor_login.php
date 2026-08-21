<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/controllers/EmisorController.php');

$controlador = new EmisorController($conexion);

$codigo = $_POST['codigo'];
$controlador->iniciarSesion($codigo);