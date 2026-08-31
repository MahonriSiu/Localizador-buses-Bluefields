<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/controllers/PerfilController.php');
$controlador = new PerfilController($conexion);
$controlador->cambiarContrasena($_POST['contrasena_actual'], $_POST['contrasena_nueva']);