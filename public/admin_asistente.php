<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/controllers/AsistenteController.php');
$controlador = new AsistenteController($conexion);
$controlador->responder(isset($_POST['mensaje']) ? $_POST['mensaje'] : '');