<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/models/SolicitudReseteo.php');

header("Content-Type: application/json");

$modeloSolicitud = new SolicitudReseteo($conexion);
$correo = $_POST['correo'];
$modeloSolicitud->crear($correo);

echo json_encode(array("exito" => true, "mensaje" => "Solicitud enviada al administrador"));