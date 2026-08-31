<?php
require_once __DIR__ . '/../../config/rutas.php';
session_start();
if (!isset($_SESSION['admin_autenticado'])) { header("Location: " . URL_BASE . "/admin/login.php"); exit; }
require __DIR__ . '/../../app/views/admin/trazar_ruta.php';