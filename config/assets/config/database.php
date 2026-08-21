<?php

// configuracion de sesion segura, antes de que arranque cualquier sesion en el sistema
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.use_strict_mode', 1);

$esLocal = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false);

if ($esLocal) {
    $host = "localhost";
    $usuario = "root";
    $contrasena = "";
    $basedatos = "localizador_buses";
} else {
    $host = "sql211.infinityfree.com";
    $usuario = "if0_42523829";
    $contrasena = "JustoSiu18";
    $basedatos = "if0_42523829_localizador";
    ini_set('session.cookie_secure', 1);
}

// solo atrapa errores reales de base de datos o del sistema, ya no avisos menores de PHP
// eso fue lo que rompio el selector de bus la vez pasada
set_exception_handler(function ($excepcion) use ($esLocal) {
    http_response_code(500);
    if (!headers_sent()) {
        header("Content-Type: application/json");
    }
    $respuesta = array(
        "exito" => false,
        "mensaje" => "Ocurrio un error en el servidor. Intenta de nuevo."
    );
    if ($esLocal) {
        $respuesta["detalle_tecnico"] = $excepcion->getMessage();
    }
    echo json_encode($respuesta);
    exit;
});

$conexion = new mysqli($host, $usuario, $contrasena, $basedatos);

if ($conexion->connect_error) {
    http_response_code(500);
    header("Content-Type: application/json");
    echo json_encode(array("exito" => false, "mensaje" => "No se pudo conectar a la base de datos"));
    exit;
}

$conexion->set_charset("utf8mb4");

?>