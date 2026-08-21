<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/controllers/AdminController.php');

$controlador = new AdminController($conexion);

$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];
$controlador->iniciarSesion($correo, $contrasena);