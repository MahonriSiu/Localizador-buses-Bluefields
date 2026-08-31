<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/controllers/EventoController.php');
$controlador = new EventoController($conexion);
$controlador->crear(
    isset($_POST['nombre']) ? $_POST['nombre'] : '',
    isset($_POST['color']) ? $_POST['color'] : '#1a73e8',
    isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : '',
    isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : '',
    isset($_POST['siempre_activo']) ? intval($_POST['siempre_activo']) : 0
);