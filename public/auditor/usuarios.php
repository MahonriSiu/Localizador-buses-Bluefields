<?php
require_once __DIR__ . '/../../config/rutas.php';
session_start();
if (!isset($_SESSION['auditor_autenticado'])) { header("Location: " . URL_BASE . "/auditor/login.php"); exit; }
require __DIR__ . '/../../app/views/auditor/usuarios.php';