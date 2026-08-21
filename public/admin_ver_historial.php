<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/controllers/AdminController.php');

$controlador = new AdminController($conexion);

$usuarioId = intval($_POST['usuario_id']);
$controlador->verHistorialContrasenas($usuarioId);