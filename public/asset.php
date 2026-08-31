<?php

$archivo = isset($_GET['archivo']) ? basename($_GET['archivo']) : '';
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';

if ($archivo === '') {
    http_response_code(404);
    die("Recurso no encontrado");
}

$mapaCarpetas = array(
    "css" => "css",
    "js" => "js",
    "img" => "img",
    "perfil" => "img/perfiles",
    "anuncio" => "img/anuncios",
    "anuncio-audio" => "audio/anuncios",
    "creativa" => "img/ruta-creativa"
);

if (!array_key_exists($tipo, $mapaCarpetas)) {
    http_response_code(404);
    die("Recurso no encontrado");
}

$ruta = __DIR__ . "/../assets/" . $mapaCarpetas[$tipo] . "/" . $archivo;

if (!file_exists($ruta)) {
    http_response_code(404);
    die("Archivo no encontrado");
}

$extension = strtolower(pathinfo($ruta, PATHINFO_EXTENSION));
$tiposMime = array(
    "css" => "text/css",
    "js" => "application/javascript",
    "png" => "image/png",
    "jpg" => "image/jpeg",
    "jpeg" => "image/jpeg",
    "mp4" => "video/mp4",
    "mp3" => "audio/mpeg",
    "m4a" => "audio/mp4",
    "wav" => "audio/wav"
);
$mime = isset($tiposMime[$extension]) ? $tiposMime[$extension] : "application/octet-stream";

$tamano = filesize($ruta);
header("Content-Type: " . $mime);
header("Cache-Control: public, max-age=30, must-revalidate");
header("Accept-Ranges: bytes");

if (isset($_SERVER['HTTP_RANGE'])) {
    $rango = $_SERVER['HTTP_RANGE'];
    list(, $rango) = explode('=', $rango, 2);

    if (strpos($rango, ',') !== false) {
        header('HTTP/1.1 416 Range Not Satisfiable');
        header("Content-Range: bytes */" . $tamano);
        exit;
    }

    list($inicio, $fin) = explode('-', $rango, 2);
    $inicio = ($inicio === '') ? 0 : intval($inicio);
    $fin = ($fin === '') ? $tamano - 1 : intval($fin);
    $fin = min($fin, $tamano - 1);

    if ($inicio > $fin) {
        header('HTTP/1.1 416 Range Not Satisfiable');
        header("Content-Range: bytes */" . $tamano);
        exit;
    }

    header('HTTP/1.1 206 Partial Content');
    header("Content-Range: bytes $inicio-$fin/$tamano");
    header("Content-Length: " . ($fin - $inicio + 1));

    $handle = fopen($ruta, 'rb');
    fseek($handle, $inicio);
    $restante = $fin - $inicio + 1;

    while ($restante > 0 && !feof($handle)) {
        $trozo = min(8192, $restante);
        echo fread($handle, $trozo);
        $restante -= $trozo;
        flush();
    }
    fclose($handle);
} else {
    header("Content-Length: " . $tamano);
    readfile($ruta);
}