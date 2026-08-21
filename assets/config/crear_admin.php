<?php
require_once(__DIR__ . '/database.php');

$nombreAdmin = "MahonriSiu";
$correoAdmin = "Mahonrisiu@gmail.com";
$contrasenaAdmin = "JustoSiu18";

$hash = password_hash($contrasenaAdmin, PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios (nombre, correo, contrasena, rol) VALUES (?, ?, ?, 'admin')";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sss", $nombreAdmin, $correoAdmin, $hash);

if ($stmt->execute()) {
    echo "Admin creado correctamente.";
} else {
    echo "Error al crear admin: " . $conexion->error;
}
?>