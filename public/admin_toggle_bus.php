<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/controllers/AdminController.php');

$controlador = new AdminController($conexion);

$id = intval($_POST['id']);
$habilitado = intval($_POST['habilitado']);
$controlador->toggleBus($id, $habilitado);