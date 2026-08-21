<?php

$tiposPermitidos = array(
    "css" => "text/css",
    "js" => "application/javascript",
    "img" => "image/png"
);

$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
$archivo = isset($_GET['archivo']) ? basename($_GET['archivo']) : '';

if (!array_key_exists($tipo, $tiposPermitidos) || $archivo === '') {
    http_response_code(404);
    die("Recurso no encontrado");
}

$ruta = __DIR__ . "/../assets/" . $tipo . "/" . $archivo;

if (!file_exists($ruta)) {
    http_response_code(404);
    die("Archivo no encontrado");
}

header("Content-Type: " . $tiposPermitidos[$tipo]);
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
readfile($ruta);