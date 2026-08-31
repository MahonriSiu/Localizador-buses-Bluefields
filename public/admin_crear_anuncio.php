<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/controllers/AnuncioController.php');

$controlador = new AnuncioController($conexion);
$controlador->crear(
    isset($_POST['nombre_negocio']) ? $_POST['nombre_negocio'] : '',
    isset($_POST['texto']) ? $_POST['texto'] : '',
    isset($_POST['telefono']) ? $_POST['telefono'] : ''
);