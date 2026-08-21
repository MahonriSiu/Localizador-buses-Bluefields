<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$esLocal = false;
$host = "sql211.infinityfree.com";
$usuario = "if0_42523829";
$contrasena = "JustoSiu18";
$basedatos = "if0_42523829_localizador";

$conexion = new mysqli($host, $usuario, $contrasena, $basedatos);
if ($conexion->connect_error) {
    die("Error de conexion: " . $conexion->connect_error);
}
$conexion->set_charset("utf8mb4");

try {
    require_once(__DIR__ . '/../app/controllers/AdminController.php');
    $controlador = new AdminController($conexion);
    $controlador->iniciarSesion('Mahonrisiu@gmail.com', 'JustoSiu18');
} catch (Throwable $e) {
    echo "<br><br>ERROR REAL:<br>";
    echo "Mensaje: " . $e->getMessage() . "<br>";
    echo "Archivo: " . $e->getFile() . "<br>";
    echo "Linea: " . $e->getLine() . "<br>";
}