<?php

$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
$archivo = isset($_GET['archivo']) ? $_GET['archivo'] : '';

$carpetasPermitidas = array('css', 'js', 'img');

if (!in_array($tipo, $carpetasPermitidas)) {
    http_response_code(404);
    exit();
}

$archivo = basename($archivo);

$ruta = __DIR__ . "/../assets/" . $tipo . "/" . $archivo;

if (!file_exists($ruta)) {
    http_response_code(404);
    exit();
}

$extension = pathinfo($archivo, PATHINFO_EXTENSION);

$tiposMime = array(
    'css' => 'text/css',
    'js' => 'application/javascript',
    'png' => 'image/png',
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'svg' => 'image/svg+xml'
);

if (isset($tiposMime[$extension])) {
    header("Content-Type: " . $tiposMime[$extension]);
}

readfile($ruta);

?>