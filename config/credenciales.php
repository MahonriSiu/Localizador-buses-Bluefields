<?php
$esLocal = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false);

if ($esLocal) {
    define('DB_HOST', 'localhost');
    define('DB_USUARIO', 'root');
    define('DB_CONTRASENA', '');
    define('DB_NOMBRE', 'localizador_buses');
} else {
    define('DB_HOST', 'sql211.infinityfree.com');
    define('DB_USUARIO', 'if0_42523829');
    define('DB_CONTRASENA', 'JustoSiu18');
    define('DB_NOMBRE', 'if0_42523829_localizador');
}
?>