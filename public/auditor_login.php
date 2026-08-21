<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/controllers/AuditorController.php');

$controlador = new AuditorController($conexion);

$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];
$controlador->iniciarSesion($correo, $contrasena);