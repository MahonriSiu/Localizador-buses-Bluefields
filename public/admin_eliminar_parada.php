<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/models/Parada.php');

session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['admin_autenticado'])) {
    echo json_encode(array("exito" => false, "mensaje" => "Sesion no valida"));
    exit;
}

$modeloParada = new Parada($conexion);

$id = intval($_POST['id']);
$modeloParada->eliminar($id);
echo json_encode(array("exito" => true));