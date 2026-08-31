<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/models/Usuario.php');

session_start();
header("Content-Type: application/json");

$usuarioId = null;
$rolSesion = null;

if (isset($_SESSION['admin_autenticado']) && isset($_SESSION['usuario_id'])) {
    $usuarioId = $_SESSION['usuario_id']; $rolSesion = 'admin';
} elseif (isset($_SESSION['auditor_autenticado']) && isset($_SESSION['usuario_id'])) {
    $usuarioId = $_SESSION['usuario_id']; $rolSesion = 'auditor';
} elseif (isset($_SESSION['propietario_autenticado']) && isset($_SESSION['usuario_id'])) {
    $usuarioId = $_SESSION['usuario_id']; $rolSesion = 'propietario';
}

if (!$usuarioId) {
    echo json_encode(array("exito" => false, "mensaje" => "Sesion no valida"));
    exit;
}

$modeloUsuario = new Usuario($conexion);
$resultado = $modeloUsuario->cambiarContrasenaPropia($usuarioId, $_POST['contrasena_actual'], $_POST['contrasena_nueva']);

if ($resultado === "exito") {
    echo json_encode(array("exito" => true, "rol" => $rolSesion));
} elseif ($resultado === "contrasena_actual_incorrecta") {
    echo json_encode(array("exito" => false, "mensaje" => "La contrasena actual no es correcta"));
} else {
    echo json_encode(array("exito" => false, "mensaje" => "Error al cambiar la contrasena"));
}