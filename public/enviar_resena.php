<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/controllers/ResenaController.php');

$controlador = new ResenaController($conexion);

$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
$comentario = isset($_POST['comentario']) ? $_POST['comentario'] : '';
$calificacion = isset($_POST['calificacion']) ? intval($_POST['calificacion']) : 0;

$controlador->enviar($nombre, $comentario, $calificacion);