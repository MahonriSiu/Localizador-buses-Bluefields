<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/controllers/AdminController.php');
$controlador = new AdminController($conexion);
$busId = isset($_POST['id']) ? intval($_POST['id']) : 0;
$controlador->regenerarCodigoEmisor($busId);