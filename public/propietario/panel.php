<?php
require_once __DIR__ . '/../../config/rutas.php';
session_start();
if (!isset($_SESSION['propietario_autenticado'])) {
    header("Location: " . URL_BASE . "/propietario/login.php");
    exit;
}
require __DIR__ . '/../../app/views/propietario/panel.php';