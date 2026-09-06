<?php

$esLocal = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false);

if ($esLocal) {
    define('DB_HOST', 'localhost');
    define('DB_USUARIO', 'root');
    define('DB_CONTRASENA', '');
    define('DB_NOMBRE', 'nombre_base_local');
} else {
    define('DB_HOST', 'host_de_produccion');
    define('DB_USUARIO', 'usuario_de_produccion');
    define('DB_CONTRASENA', 'contrasena_de_produccion');
    define('DB_NOMBRE', 'nombre_base_produccion');
}
?>