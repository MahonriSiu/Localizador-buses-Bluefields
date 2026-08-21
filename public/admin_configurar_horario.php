<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../app/controllers/AdminController.php');

$controlador = new AdminController($conexion);

$horaApertura = $_POST['hora_apertura'];
$horaCierre = $_POST['hora_cierre'];
$controlador->configurarHorario($horaApertura, $horaCierre);