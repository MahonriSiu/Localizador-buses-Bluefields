<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/controllers/EventoController.php');
$controlador = new EventoController($conexion);
$controlador->toggle(intval($_POST['id']), intval($_POST['habilitado']));