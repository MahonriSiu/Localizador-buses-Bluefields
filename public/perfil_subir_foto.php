<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/controllers/PerfilController.php');
$controlador = new PerfilController($conexion);
$controlador->subirFoto();