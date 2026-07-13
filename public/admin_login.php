<?php

require_once(__DIR__ . "/../config/database.php");
require_once(__DIR__ . "/../app/controllers/AdminController.php");

$controlador = new AdminController($conexion);

$correo = isset($_POST['correo']) ? $_POST['correo'] : '';
$contrasena = isset($_POST['contrasena']) ? $_POST['contrasena'] : '';

$controlador->iniciarSesion($correo, $contrasena);

?>