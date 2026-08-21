<?php
require_once __DIR__ . '/../config/rutas.php';
session_start();
if (!isset($_SESSION['propietario_id']) && !isset($_SESSION['usuario_id'])) {
    header("Location: " . URL_BASE . "/index.php");
    exit;
}
require __DIR__ . '/../app/views/cambiar_contrasena.php';