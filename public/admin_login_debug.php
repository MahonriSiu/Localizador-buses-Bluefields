<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/controllers/AdminController.php');

$controlador = new AdminController($conexion);

$correo = isset($_POST['correo']) ? $_POST['correo'] : 'Mahonrisiu@gmail.com';
$contrasena = isset($_POST['contrasena']) ? $_POST['contrasena'] : 'JustoSiu18';
$controlador->iniciarSesion($correo, $contrasena);