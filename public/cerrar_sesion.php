<?php
require_once __DIR__ . '/../config/rutas.php';
session_start();
session_unset();
session_destroy();
header("Location: " . URL_BASE . "/index.php");
exit;