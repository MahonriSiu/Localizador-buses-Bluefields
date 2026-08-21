<?php
require_once __DIR__ . '/../../config/rutas.php';
session_start();
if (!isset($_SESSION['emisor_autenticado'])) {
    header("Location: " . URL_BASE . "/emisor/login.php");
    exit;
}
require __DIR__ . '/../../app/views/emisor/panel.php';