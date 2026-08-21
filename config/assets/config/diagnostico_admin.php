<?php
$host = "sql211.infinityfree.com";
$usuario = "if0_42523829";
$contrasena = "JustoSiu18";
$basedatos = "if0_42523829_localizador";

$conexion = @new mysqli($host, $usuario, $contrasena, $basedatos);

if ($conexion->connect_error) {
    die("ERROR DE CONEXION: " . $conexion->connect_error);
}

$nombreAdmin = "MahonriSiu";
$correoAdmin = "Mahonrisiu@gmail.com";
$contrasenaAdmin = "JustoSiu18";

$hash = password_hash($contrasenaAdmin, PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios (nombre, correo, contrasena, rol) VALUES (?, ?, ?, 'admin')";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    die("ERROR AL PREPARAR: " . $conexion->error);
}

$stmt->bind_param("sss", $nombreAdmin, $correoAdmin, $hash);

if ($stmt->execute()) {
    echo "Admin creado correctamente.";
} else {
    echo "ERROR AL EJECUTAR: " . $stmt->error;
}