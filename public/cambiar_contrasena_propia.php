<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/models/Usuario.php');

session_start();
header("Content-Type: application/json");

$usuarioId = null;
if (isset($_SESSION['propietario_id'])) {
    $usuarioId = $_SESSION['propietario_id'];
} elseif (isset($_SESSION['usuario_id']) && isset($_SESSION['auditor_autenticado'])) {
    $usuarioId = $_SESSION['usuario_id'];
}

if (!$usuarioId) {
    echo json_encode(array("exito" => false, "mensaje" => "Sesion no valida"));
    exit;
}

$modeloUsuario = new Usuario($conexion);

$contrasenaActual = $_POST['contrasena_actual'];
$contrasenaNueva = $_POST['contrasena_nueva'];

$resultado = $modeloUsuario->cambiarContrasenaPropia($usuarioId, $contrasenaActual, $contrasenaNueva);

if ($resultado === "exito") {
    echo json_encode(array("exito" => true));
} elseif ($resultado === "contrasena_actual_incorrecta") {
    echo json_encode(array("exito" => false, "mensaje" => "La contrasena actual no es correcta"));
} else {
    echo json_encode(array("exito" => false, "mensaje" => "Error al cambiar la contrasena"));
}