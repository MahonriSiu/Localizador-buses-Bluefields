<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/controllers/AnuncioController.php');

$controlador = new AnuncioController($conexion);
$controlador->obtenerTodos();