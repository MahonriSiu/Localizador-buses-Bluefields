<?php
require_once __DIR__ . '/../config/rutas.php';
session_start();

$tieneSesion = isset($_SESSION['usuario_final_id'])
    || isset($_SESSION['admin_autenticado'])
    || isset($_SESSION['auditor_autenticado']);

if (!$tieneSesion) {
    header("Location: " . URL_BASE . "/usuario/registro.php");
    exit;
}

require __DIR__ . '/../app/views/usuario/mapa.php';